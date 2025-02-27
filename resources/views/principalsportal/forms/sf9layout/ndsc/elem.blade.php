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
            font-family:'Times New Roman', Times, serif;
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
            transform-origin: 17 17;
            transform: rotate(-90deg);
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
        .yunit {
            position: absolute; 
            top: 23.8%;
            left: 230;
            /* -moz-transform: rotate(-90.0deg);
            -o-transform: rotate(-90.0deg);  
            -webkit-transform: rotate(-90.0deg);  Saf3.1+, Chrome */
            filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -2em;
            margin-right: -2em;
            transform: rotate(-90deg);
            
        }
		
		 .check_mark {
            font-family:'Times New Roman', Times, serif;
            }
        @page { size: 297mm 210mm; margin: 0px 10px;  }
        
    </style>
</head>
<body style="">
    <table class="table table-sm mb-0" style="table-layout: fixed; page-break-after: always;">
        <tr>
            <td width="50%" style="padding-right: 20px;">
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 15px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>REPORT ON ATTENDANCE</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm" width="100%" style="table-layout: fixed; margin-top: 5px;" >
                    @php
                         $width = count($attendance_setup) != 0? 78 / count($attendance_setup) : 0;
                    @endphp
                    <tr>
                        <th width="12%" class="align-middle text-center" style="font-size: 14px;"></th>
                        @foreach ($attendance_setup as $item)
                            <th class="text-center align-middle"  width="{{$width}}%" style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                        @endforeach
                        <th class="align-middle text-center text-center" style="text-transform: uppercase;" width="10%">Total</th>
                    </tr>
                    <tr>
                        <td class="text-left" style="font-size: 12px;">No. of School <br> Days</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days}}</td>
                        @endforeach
                        <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr >
                        <td class="text-left" style="font-size: 12px;">No. of school days Present</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->present}}</td>
                        @endforeach
                        <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                    </tr>
                    <tr>
                        <td class="text-left" style="font-size: 12px;">No. of times tardy</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->absent}}</td>
                        @endforeach
                        <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 15px 5px 0px 0px !important; ">
                    <tr>
                        <td class="text-left p-0" style="font-size: 16.2px;">
                            You are encouraged to confer with the teachers concerning your child'
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>TO THE PARENT OR GUARDIAN</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 15px 0px 0px 0px !important; ">
                    <tr>
                        <td class="text-right p-0" style="font-size: 16.2px;">
                            s academic performance/progress/problems encountered in his/her studies.
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 30px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>PARENT/GUARDIAN'S SIGNATURE</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed;font-size: 16.2px ;padding: 25px 43px 0px 43px !important; ">
                    <tr>
                        <td width="23%" class="text-left p-0" style="">1<sup>st</sup> Grading</td>
                        <td width="77%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed;font-size: 16.2px ;padding: 20px 43px 0px 43px !important; ">
                    <tr>
                        <td width="23%" class="text-left p-0" style="">2<sup>nd</sup> Grading</td>
                        <td width="77%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed;font-size: 16.2px ;padding: 20px 43px 0px 43px !important; ">
                    <tr>
                        <td width="23%" class="text-left p-0" style="">3<sup>rd</sup> Grading</td>
                        <td width="77%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed;font-size: 16.2px ;padding: 20px 43px 0px 43px !important; ">
                    <tr>
                        <td width="23%" class="text-left p-0" style="">4<sup>th</sup> Grading</td>
                        <td width="77%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 60px 43px 0px 43px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <td width="25%" class="p-0" style="">Grading System</td>
                            <td width="75%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 43px 0px 43px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <td width="40%" class="p-0" style="">Eligible for Admission to</td>
                            <td width="60%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 43px 0px 43px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <td width="37%" class="p-0" style="">Has Advanced Units in</td>
                            <td width="63%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 43px 0px 43px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <td width="22%" class="p-0" style="">Lack Units in</td>
                            <td width="78%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 43px 0px 43px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <td width="8%" class="p-0" style="">Date</td>
                            <td width="92%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="">
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 13px;">
                    <tr>
                        <td width="30%" class="text-left"><i>SF9 Progress Report Card</i></td>
                        <td width="70%" class="text-right"><i>Government Recognition No. <b>SK 405775-055 s. 2021</b></i></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 12px; margin-top: 0px;">
                    <tr>
                        <td width="100%" class="text-center p-0">
                            <div>Republic of the Philippines</div>
                            <div>DEPARTMENT OF EDUCATION</div>
                            <div>Region XII</div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important;">
                    <tr>
                        <td width="10%" class="text-left p-0" style="vertical-align: top!important;">
                            <img style="" src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="55px">
                        </td>
                        <td width="80%" class="text-center p-0" style="vertical-align: top!important;">
                            <div class="p-0" style="font-size: 19px;"><b>NOTRE DAME OF SALAMAN COLLEGE, INC.</b></div>
                            <div class="p-0">Poblacion I, Lebak, Sultan Kudarat</div>
                            <div class="p-0">Founded in 1965 by the Oblates of Mary Immaculate (OMI)</div>
                            <div class="p-0">Owned by the Archdiocese of Cotabato</div>
                            <div class="p-0" style="text-align: right!important;">Administered by the Diocesan Clergy of Cotabato (DCC)</div>
                            <div class="p-0" style="font-size: 14px;"><i>“Service for the Love of God through Mary”</i></div>
                            <div class="p-0"><b>(B.E.S.T)</b></div>
                            <div class="p-0" style="font-size: 12px;"><i>Email Add: notredame_salamancollege@yahoo.com</i></div>
                            <div class="p-0" style="font-size: 12px;"><i>Tel. No.: (064) 205-3041</i></div>
                        </td>
                        <td width="10%" class="text-right p-0" style="vertical-align: top!important;">
                            <img style="" src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="55px">
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 5px 5px 0px 5px !important; ">
                    <tr>
                        <td class="p-0" style="border-top: 5px solid #000; "></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 2px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>JUNIOR HIGH SCHOOL DEPARTMENT</b></div>
                            <div class="p-0" style="font-size: 18px;">School I.D No. <b><u>{{$student->sid}}</u></b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="9%" class="p-0" style="">Name:</td>
                        <td width="91%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="7%" class="p-0" style="">Age:</td>
                        <td width="39%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->age}}</b></td>
                        <td width="8%" class="text-center p-0" style="">&nbsp;Sex:</td>
                        <td width="46%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->gender}}</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="9%" class="p-0" style="">Grade:</td>
                        <td width="38%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{str_replace('GRADE', '', $student->levelname)}}</b></td>
                        <td width="12%" class="text-left p-0" style="">&nbsp;Section:</td>
                        <td width="41%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->sectionname}}</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="17%" class="p-0" style="">School Year:</td>
                        <td width="30%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$schoolyear->sydesc}}</b></td>
                        <td width="9%" class="text-left p-0" style="">&nbsp;LRN:</td>
                        <td width="44%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->lrn}}</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 5px 5px 0px 5px !important; ">
                    <tr>
                        <td width="100%" class="p-0" style="">Dear parent/guardian,</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 5px 5px 0px 5px !important; ">
                    <tr>
                        <td width="100%" class="p-0" style="text-indent: 36px;">This report card shows the ability and progress your child has made in the
                            different learning areas as well as his/her core values.
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 5px 5px 0px 5px !important; ">
                    <tr>
                        <td width="100%" class="p-0" style="text-indent: 36px;">The school welcomes you if you desire to know more about your child’s
                            progress.
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="50%" class="p-0" style=""></td>
                        <td width="50%" class="p-0 text-right" style=""><b><u>{{$adviser}}</u></b></td>
                    </tr>
                    <tr>
                        <td width="50%" class="p-0" style=""></td>
                        <td width="50%" class="p-0 text-right" style="padding-right: 45px!important;">Adviser</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="50%" class="p-0" style=""><b><u>CRISTY ANN S. ESCOÑA, LPT</u></b></td>
                        <td width="50%" class="p-0" style=""></td>
                    </tr>
                    <tr>
                        <td width="50%" class="p-0 text-left" style="text-indent: 65px;">Principal</td>
                        <td width="50%" class="p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 2px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>CERTIFICATE OF TRANSFER</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="25%" class="p-0" style="">Admitted to Grade:</td>
                        <td width="18%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="4%" class="p-0" style=""></td>
                        <td width="12%" class="text-left p-0" style="">&nbsp;Section:</td>
                        <td width="41%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="43%" class="p-0" style="">Eligibility for Admission to Grade:</td>
                        <td width="52%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 20px 5px 0px 5px !important; ">
                    <tr>
                        <td width="50%" class="text-center p-0" style=""><b><u>{{$adviser}}</u></b></td>
                        <td width="50%" class="text-center p-0" style=""><b><u>CRISTY ANN S. ESCOÑA, LPT</u></b></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-center p-0" style="">Adviser</td>
                        <td width="50%" class="text-center p-0" style="">Principal</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>CANCELLATION OF ELIGIBILITY TO TRANSFER</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 5px 5px 0px 5px !important; ">
                    <tr>
                        <td width="17%" class="p-0" style="">Admitted in:</td>
                        <td width="28%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="55%" class="p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="8%" class="p-0" style="">Date:</td>
                        <td width="37%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="55%" class="p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="55%" class="p-0" style=""></td>
                        <td width="45%" class="p-0" style=""><b><u>CRISTY ANN S. ESCOÑA, LPT</u></b></td>
                    </tr>
                    <tr>
                        <td width="55%" class="p-0" style=""></td>
                        <td width="45%" class="p-0 text-center" style="">Principal</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-sm mb-0" style="table-layout: fixed;">
        <tr>
            <td width="50%" style="padding-right: 20px;">
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 0px 0px 0px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>REPORT ON STUDENT'S PROGRESS IN LEARNING</b></div>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm table-bordered grades" style="table-layout: fixed; margin-top: 25px;">
                    <thead>
                        <tr>
                            <td rowspan="2"  class="align-middle text-center" width="38%" style="font-size: 16.2px!important;"><b><i>Learning Areas</i></b></td>
                            <td colspan="4"  class="text-center align-middle" style="font-size: 16.2px!important;"><b><i>Quarter</i></b></td>
                            <td rowspan="2" class="text-center align-middle p-0" width="12%"  style="font-size: 16.2px!important;">
                                <div style=""><b><i>Final <br> Grade</i></b></div>
                            </td>
                            <td rowspan="2"  class="text-center align-middle" width="14%" style="font-size: 16.2px!important;"><b><i>Remarks</i></b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="9%" style="font-size: 16.2px!important;"><b><i>1</i></b></td>
                            <td class="text-center align-middle" width="9%" style="font-size: 16.2px!important;"><b><i>2</i></b></td>
                            <td class="text-center align-middle" width="9%" style="font-size: 16.2px!important;"><b><i>3</i></b></td>
                            <td class="text-center align-middle" width="9%" style="font-size: 16.2px!important;"><b><i>4</i></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="font-style:{{$item->subjCom != null ? 'italic':'normal'}}; font-size: 14px!important;">
                                    @if($item->subjCom != null)
                                    <img style="" src="{{base_path()}}/public/assets/images/ndsc/blackdiamond.png" alt="school" width="10px"> <span style="padding-left:{{$item->subjCom != null ? '.8rem':'.25rem'}};">{{$item->subjdesc!=null ? $item->subjdesc : null}}</span>
                                    @else
                                        {{$item->subjdesc!=null ? $item->subjdesc : null}}
                                    @endif
                                </td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-left" style="font-size: 16.2px!important;"><b><i>Ranks</i></b></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 16.2px!important;">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                            <td class="text-center align-middle" style="font-size: 16.2px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 30px 100px 0px 100px !important; ">
                    <tr>
                        <td width="38%" class="p-0" style=""><b><i>General Average</i></b></td>
                        <td width="62%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 40px 0px 0px 0px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>LEVEL OF PROGRESS AND ACHIEVEMENT</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 30px 30px 0px 30px !important; ">
                    <tr>
                        <td width="48%" class="p-0" style=""><b><i>Descriptors</i></b></td>
                        <td width="34%" class="p-0" style=""><b><i>Grading Scale</i></b></td>
                        <td width="18%" class="p-0" style=""><b><i>Remarks</i></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 10px 30px 0px 30px !important; ">
                    <tr>
                        <td width="48%" class="p-0 text-left" style="">Outstanding</td>
                        <td width="34%" class="p-0 text-left" style="">90%-100%</td>
                        <td width="18%" class="p-0 text-left" style="">Passed</td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left">Very Satisfactory</td>
                        <td class="p-0 text-left">85%-89%</td>
                        <td class="p-0 text-left">Passed</td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left">Satisfactory</td>
                        <td class="p-0 text-left">80%-84%</td>
                        <td class="p-0 text-left">Passed</td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left">Fairly Satisfactory</td>
                        <td class="p-0 text-left">75%-79%</td>
                        <td class="p-0 text-left">Passed</td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left">Did Not Meet Expectations</td>
                        <td class="p-0 text-left">Below 75%</td>
                        <td class="p-0 text-left">Failed</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 0px 0px 0px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>REPORT ON LEARNING OBSERVED VALUES</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="font-size: 16.2px; table-layout: fixed; padding: 15px 0px 0px 0px !important; ">
                    <tr>
                        <td width="18%" class="text-left p-0">Directions:</td>
                        <td width="82%" class="text-left p-0">
                            <div>Write appropriate letters for each value/virtue in the appropriate
                                column as indicated in the legend</div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="font-size: 16.2px; table-layout: fixed; padding: 10px 0px 0px 0px !important; ">
                    <tr>
                        <td width="25%" class="text-right p-0"><i>AO</i></td>
                        <td width="14%" class="text-left p-0"></td>
                        <td width="61%" class="text-left p-0"><b><i>Always Observed</i></b></td>
                    </tr>
                    <tr>
                        <td class="text-right p-0"><i>SO</i></td>
                        <td class="text-left p-0"></td>
                        <td class="text-left p-0"><b><i>Sometimes Observed</i></b></td>
                    </tr>
                    <tr>
                        <td class="text-right p-0"><i>RO</i></td>
                        <td class="text-left p-0"></td>
                        <td class="text-left p-0"><b><i>Rarely Observed</i></b></td>
                    </tr>
                    <tr>
                        <td class="text-right p-0"><i>NO</i></td>
                        <td class="text-left p-0"></td>
                        <td class="text-left p-0"><b><i>Not Observed</i></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 0px 0px 0px !important; ">
                    <tr>
                        <td class="text-left p-0">
                            <div class="p-0" style="font-size: 18px;"><b><i>A. Dep.Ed's Core Values (4M's)</i></b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; padding: 10px 0px 0px 20px !important; ">
                    <tr>
                        <td rowspan="2" class="align-middle text-center" style="font-size: 13px!important;"><b><i>Core Values</i></b></td>
                        <td rowspan="2" class="align-middle text-center" style="font-size: 12px;"><b><i>Behavior Statement</i></b></td>
                        <td colspan="4" class="align-middle text-center"style="font-size: 12px;"><b><i>Quarter</i></b></td>
                    </tr>
                    <tr>
                        <td class="text-center" width="7%" style="font-size: 12px;"><center>1</center></td>
                        <td class="text-center" width="7%" style="font-size: 12px;"><center>2</center></td>
                        <td class="text-center" width="7%" style="font-size: 12px;"><center>3</center></td>
                        <td class="text-center" width="7%" style="font-size: 12px;"><center>4</center></td>
                    </tr>
                    @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($groupitem as $item)
                            @if($item->value == 0)
                            @else
                                <tr >
                                    @if($count == 0)
                                            <td width="24%" class="text-center align-middle" style="font-size: 12px;" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                            @php
                                                $count = 1;
                                            @endphp
                                    @endif
                                    <td class="p-0" width="48%" class="align-middle" style="font-size: 12px; text-align: left!important; padding-left: 2px!important;">{{$item->description}}</td>
                                    <td class="text-center align-middle">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    {{-- ========================================================== --}}
                </table>  
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 0px 0px 0px !important; ">
                    <tr>
                        <td class="text-left p-0">
                            <div class="p-0" style="font-size: 18px;"><b><i>B. School’s Core Values (B.E.S.T)</i></b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; padding: 10px 0px 0px 20px !important; ">
                    <tr>
                        <td rowspan="2" class="align-middle text-center" style="font-size: 13px!important;"><b><i>Core Values</i></b></td>
                        <td rowspan="2" class="align-middle text-center" style="font-size: 12px;"><b><i>Behavior Statement</i></b></td>
                        <td colspan="4" class="align-middle text-center"style="font-size: 12px;"><b><i>Quarter</i></b></td>
                    </tr>
                    <tr>
                        <td class="text-center" width="7%" style="font-size: 12px;"><center>1</center></td>
                        <td class="text-center" width="7%" style="font-size: 12px;"><center>2</center></td>
                        <td class="text-center" width="7%" style="font-size: 12px;"><center>3</center></td>
                        <td class="text-center" width="7%" style="font-size: 12px;"><center>4</center></td>
                    </tr>
                    @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($groupitem as $item)
                            @if($item->value == 0)
                            @else
                                <tr >
                                    @if($count == 0)
                                            <td width="27%" class="text-center align-middle" style="font-size: 12px;" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                            @php
                                                $count = 1;
                                            @endphp
                                    @endif
                                    <td class="p-0" width="45%" class="align-middle" style="font-size: 12px; text-align: left!important; padding-left: 2px!important;">{{$item->description}}</td>
                                    <td class="text-center align-middle">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    {{-- ========================================================== --}}
                </table>  
            </td>
        </tr>
    </table>
</body>
</html>