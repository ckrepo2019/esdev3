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
            font-family: Arial, Helvetica, sans-serif;
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
            /* font-family:'Times New Roman', Times, serif; */
            }
        @page { size: 297mm 210mm; margin: 5px 0px 0px 10px;  }
        
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
                <table class="table table-bordered table-sm" width="100%" style="table-layout: fixed; margin-top: 5px; font-size: 13px;" >
                    @php
                         $width = count($attendance_setup) != 0? 78 / count($attendance_setup) : 0;
                    @endphp
                    <tr>
                        <th width="12%" class="align-middle text-center" style="font-size: 14px;"></th>
                        @foreach ($attendance_setup as $item)
                            <th class="text-center align-middle"  width="{{$width}}%" style="text-transform: uppercase; font-size: 13px;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                        @endforeach
                        <th class="align-middle text-center text-center" style="text-transform: uppercase; font-size: 13px;" width="10%">Total</th>
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
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 25px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>TO THE PARENT OR GUARDIAN</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 15px 5px 0px 0px !important; ">
                    <tr>
                        <td class="text-left p-0" style="font-size: 18.5px; font-family: Georgia, 'Times New Roman', Times, serif;">
                            <i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You are encouraged to confer with the teachers concerning
                                your child’s academic performance/progress/problems encountered
                                in his/her studies. </i>
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
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 20px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>FIRST SEMESTER</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed;font-size: 16.2px ;padding: 20px 43px 0px 43px !important; ">
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
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 30px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>SECOND SEMESTER</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed;font-size: 16.2px ;padding: 10px 43px 0px 43px !important; ">
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
            </td>
            <td width="50%" style="">
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 13px;">
                    <tr>
                        <td width="30%" class="text-left p-0"><i>Form 138 (Report Card)</i></td>
                        <td width="70%" class="text-right p-0"><i>Provisional Permit to Operate No. <b>SHS-R12-092 s. 2016</b></i></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 12px; margin-top: 0px; font-family:'Times New Roman', Times, serif;">
                    <tr>
                        <td width="100%" class="text-center p-0">
                            <div><b>Republic of the Philippines</b></div>
                            <div><b>DEPARTMENT OF EDUCATION</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.2px; padding: 0px 5px 0px 5px !important; font-family:'Times New Roman', Times, serif;">
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
                            <div class="p-0" style="font-size: 17px;"><b><i>“Service for the Love of God through Mary”</i></b></div>
                            <div class="p-0"><b>(B.E.S.T)</b></div>
                            <div class="p-0" style="font-size: 12px;"><b>“Amare Est Servire”</b></div>
                            <div class="p-0" style="font-size: 12px;"><i>Email Add: notredame_salamancollege@yahoo.com</i></div>
                            <div class="p-0" style="font-size: 12px;"><i>Tel. No.: (064) 205-3041</i></div>
                        </td>
                        <td width="10%" class="text-right p-0" style="vertical-align: top!important;">
                            <img style="" src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="55px">
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 5px 5px 0px 5px !important; ">
                    <tr>
                        <td class="p-0" style="border-top: 5px solid #000; "></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 2px 5px 0px 5px !important; font-family:'Times New Roman', Times, serif;">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 14px;"><b>SENIOR HIGH SCHOOL DEPARTMENT</b></div>
                            <div class="p-0" style="font-size: 14px;">School I.D No. <b><u>{{$student->sid}}</u></b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="10%" class="p-0" style="">Name:</td>
                        <td width="90%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="8%" class="p-0" style="">Age:</td>
                        <td width="38%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->age}}</b></td>
                        <td width="9%" class="text-center p-0" style="">&nbsp;Sex:</td>
                        <td width="45%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->gender}}</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="10%" class="p-0" style="">Grade:</td>
                        <td width="37%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{str_replace('GRADE', '', $student->levelname)}}</b></td>
                        <td width="13%" class="text-left p-0" style="">&nbsp;Section:</td>
                        <td width="40%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->sectionname}}</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="19%" class="p-0" style="">School Year:</td>
                        <td width="28%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$schoolyear->sydesc}}</b></td>
                        <td width="20%" class="text-left p-0" style="">&nbsp;Track/Strand:</td>
                        <td width="33%" class="p-0" style="border-bottom: 1px solid #000;"><b></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="9%" class="text-left p-0" style="">&nbsp;LRN:</td>
                        <td width="38%" class="p-0" style="border-bottom: 1px solid #000;"><b>{{$student->lrn}}</b></td>
                        <td width="53%" class="p-0"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 16.8px; padding: 5px 5px 0px 5px !important; font-family: Georgia, serif;">
                    <tr>
                        <td width="100%" class="text-left p-0" style=""><i>Dear parent/guardian,</td>
                        </tr></i>
                    <tr>
                        <td width="100%" class="p-0" style="text-indent: 36px;"><i>This report card shows the ability and progress your child has made in the
                            different learning areas as well as his/her core values.</i>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" class="p-0" style="text-indent: 36px;"><i>The school welcomes you if you desire to know more about your child’s
                            progress.</i>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="50%" class="p-0" style=""></td>
                        <td width="50%" class="p-0 text-right" style=""><b><u>{{$adviser}}</u></b></td>
                    </tr>
                    <tr>
                        <td width="50%" class="p-0" style=""></td>
                        <td width="50%" class="p-0 text-right" style="padding-right: 30px!important; font-family: Georgia, serif;"><i>Teacher/Adviser</i></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="50%" class="p-0" style=""><b><u>ANTONIO C. LLANURA, Ed.D</u></b></td>
                        <td width="50%" class="p-0" style=""></td>
                    </tr>
                    <tr>
                        <td width="50%" class="p-0 text-left" style="text-indent: 55px; font-family: Georgia, serif;"><i>School Principal</i></td>
                        <td width="50%" class="p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 2px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 17px;"><b>Certificate of Transfer</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="26%" class="p-0" style="">Admitted to Grade:</td>
                        <td width="21%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="12%" class="text-left p-0" style="">&nbsp;Section:</td>
                        <td width="41%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="43%" class="p-0" style="">Eligibility for Admission to Grade:</td>
                        <td width="52%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 10px 5px 0px 5px !important; ">
                    <tr>
                        <td width="100%" class="text-left p-0" style="">Approved:</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="50%" class="text-center p-0" style=""><b><u>CRISTY ANN S. ESCOÑA, LPT</u></b></td>
                        <td width="50%" class="text-center p-0" style=""><b><u>{{$adviser}}</u></b></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-center p-0" style="font-family: Georgia, serif;"><i>School Principal</i></td>
                        <td width="50%" class="text-center p-0" style="font-family: Georgia, serif;"><i>Teacher/Adviser</i></td>

                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 5px 0px 5px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 16px;"><b>Cancellation of Eligibility to Transfer</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="17%" class="p-0" style="">Admitted in:</td>
                        <td width="28%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="55%" class="p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
                    <tr>
                        <td width="10%" class="p-0" style="">&nbsp;&nbsp;&nbsp;Date:</td>
                        <td width="39%" class="p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="51%" class="p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 15px; padding: 0px 5px 0px 5px !important; ">
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
    <table class="table table-sm mb-0" style="table-layout: fixed; font-family:Arial, Helvetica, sans-serif !important;">
        <tr>
            <td width="50%" style="padding-right: 20px;">
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 0px 0px 0px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>REPORT ON STUDENT'S PROGRESS IN LEARNING</b></div>
                        </td>
                    </tr>
                </table> 
                @if($student->acadprogid != 5)
                    <table class="table table-sm table-bordered" width="100%">
                        <tr>
                            <td width="48%" rowspan="2"  class="align-middle text-center"><b>LEARNING AREAS</b></td>
                            <td width="30%" colspan="4"  class="text-center align-middle"><b>QUARTER</b></td>
                            <td width="10%" rowspan="2"  class="text-center align-middle"><b>FINAL GRADE</b></td>
                            <td width="10%" rowspan="2"  class="text-center align-middle"><b>ACTION TAKEN</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle"><b>1</b></td>
                            <td class="text-center align-middle"><b>2</b></td>
                            <td class="text-center align-middle"><b>3</b></td>
                            <td class="text-center align-middle"><b>4</b></td>
                        </tr>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="padding-left:{{$item->mapeh == 1 || $item->inTLE == 1 ? '2rem':'.25rem'}}" ></td>
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            @php
                                $genave = collect($finalgrade)->where('semid',1)->first();
                            @endphp
                            <td colspan="5"><b>GENERAL AVERAGE</b></td>
                            <td class="text-center align-middle">{{$genave->finalrating != null ? $genave->finalrating:''}}</td>
                            <td class="text-center align-middle">{{$genave->actiontaken != null ? $genave->actiontaken:''}}</td>
                        </tr>
                    </table>
                @else
                    @for ($x=1; $x <= 2; $x++)
                        <table class="table mb-0" width="100%" style="table-layout: fixed; margin-top: 10px;">
                            <tr>
                                <td width="100%" class="align-middle text-left p-0" style="font-size: 15px;"><b><i>{{$x == 1 ? 'First Semester' : 'Second Semester'}}</i></b></td>
                            </tr>
                        </table>
                        <table class="table table-bordered grades_2 mb-0" width="100%" style="table-layout: fixed; font-size: 13px;">
                            <tr style="">
                                <td rowspan="2" width="68%" class="align-middle text-center p-0" style="font-size: 15px!important;"><b>Subjects</b></td>
                                <td width="17%" colspan="2"  class="text-center align-middle" style="font-size: 15px!important;"><b>Quarter</b></td>
                                <td rowspan="2" width="15%" class="text-center align-middle p-0" style="font-size: 13px!important;"><b>Semester <br>Final Grade</b></td>
                            </tr>
                            <tr style="">
                                @if($x == 1)
                                    <td class="text-center align-middle p-0" style="font-size: 15px!important;"><b>1</b></td>
                                    <td class="text-center align-middle p-0" style="font-size: 15px!important;"><b>2</b></td>
                                @elseif($x == 2)
                                    <td class="text-center align-middle p-0" style="font-size: 15px!important;"><b>3</b></td>
                                    <td class="text-center align-middle p-0" style="font-size: 15px!important;"><b>4</b></td>
                                @endif
                            </tr>
                            <tr><td colspan="4" class="p-0" style="color:black; border:solid 1px black; padding: 0px 0px 0px 4px !important; font-size: 15px!important;"><b>Core Subjects</b></td></tr>
                            @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                                <tr>
                                    <td class="p-0" style="padding: 0px 0px 0px 4px !important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle p-0">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle p-0">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle p-0">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle p-0">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                </tr>
                            @endforeach
                            <tr><td colspan="4" class="p-0" style=" color:black; border:solid 1px black; padding: 0px 0px 0px 4px !important; font-size: 15px!important;"><b>Applied Subjects</b></td></tr>
                            @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                                <tr>
                                    <td class="p-0" style="padding: 0px 0px 0px 4px !important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle p-0">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle p-0">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle p-0">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle p-0">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle  p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                </tr>
                            @endforeach
                              <tr><td colspan="4" class="p-0" style="color:black; border:solid 1px black; padding: 0px 0px 0px 4px !important; font-size: 15px!important;"><b>Specialized Subjects</b></td></tr>
                            @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                                <tr>
                                    <td class="p-0" style="padding: 0px 0px 0px 4px !important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle p-0">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle p-0">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle p-0">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle p-0">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle  p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                @php
                                    $genave = collect($finalgrade)->where('semid',$x)->first();
                                @endphp
                                <td colspan="3" class="text-right" style="font-size: 15px!important;"><b>General Average for Semester</b></td>
                                {{-- @if($x == 1)
                                    <td class="text-center align-middle">{{isset($genave->quarter1) ? $genave->quarter1 != null ? $genave->quarter1:'' : ''}}</td>
                                    <td class="text-center align-middle">{{isset($genave->quarter2) ? $genave->quarter2 != null ? $genave->quarter2:'' : ''}}</td>
                                @elseif($x == 2)
                                    <td class="text-center align-middle">{{isset($genave->quarter3) ? $genave->quarter3 != null ? $genave->quarter3:'' : ''}}</td>
                                    <td class="text-center align-middle">{{isset($genave->quarter4) ? $genave->quarter4 != null ? $genave->quarter4:'' : ''}}</td>
                                @endif --}}
                                <td class="text-center align-middle  p-0">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                            </tr>
                        </table>
                    @endfor
                @endif
            </td>
            <td width="50%" style="padding-left: 20px;">
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 0px 0px 0px !important; ">
                    <tr>
                        <td class="text-center p-0">
                            <div class="p-0" style="font-size: 18px;"><b>REPORT ON LEARNING OBSERVED VALUES</b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="font-size: 15px; table-layout: fixed; padding: 10px 0px 0px 0px !important; ">
                    <tr>
                        <td width="18%" class="text-left p-0">Directions:</td>
                        <td width="82%" class="text-left p-0">
                            <div>Write appropriate letters for each value/virtue in the appropriate
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;column as indicated in the legend</div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="font-size: 12px; table-layout: fixed; padding: 10px 0px 0px 0px !important; ">
                    <tr>
                        <td width="17%" class="text-right p-0">AO</td>
                        <td width="10%" class="text-left p-0"></td>
                        <td width="73%" class="text-left p-0">Always Observed</td>
                    </tr>
                    <tr>
                        <td class="text-right p-0">SO</td>
                        <td class="text-left p-0"></td>
                        <td class="text-left p-0">Sometimes Observed</td>
                    </tr>
                    <tr>
                        <td class="text-right p-0">RO</td>
                        <td class="text-left p-0"></td>
                        <td class="text-left p-0">Rarely Observed</td>
                    </tr>
                    <tr>
                        <td class="text-right p-0">NO</td>
                        <td class="text-left p-0"></td>
                        <td class="text-left p-0">Not Observed</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 0px 5px 0px !important; ">
                    <tr>
                        <td class="text-left p-0">
                            <div class="p-0" style="font-size: 13px;"><b><i>A. Dep.Ed's Core Values (4M's)</i></b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; padding: 0px 0px 0px 20px !important; ">
                    <tr>
                        <td rowspan="2" class="align-middle text-center p-0" style="font-size: 13px!important;"><i>Core Values</i></td>
                        <td rowspan="2" class="align-middle text-center p-0" style="font-size: 12px;"><i>Behavior Statement</i></td>
                        <td colspan="4" class="align-middle text-center p-0"style="font-size: 12px;"><i>Quarter</i></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" width="6%" style="font-size: 12px;"><center>1</center></td>
                        <td class="text-center p-0" width="6%" style="font-size: 12px;"><center>2</center></td>
                        <td class="text-center p-0" width="6%" style="font-size: 12px;"><center>3</center></td>
                        <td class="text-center p-0" width="6%" style="font-size: 12px;"><center>4</center></td>
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
                                            <td width="24%" class="text-center align-middle" style="font-size: 11px;" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                            @php
                                                $count = 1;
                                            @endphp
                                    @endif
                                    <td class="p-0" width="52%" class="align-middle" style="font-size: 11px; text-align: left!important; padding-left: 2px!important;">{{$item->description}}</td>
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
                <table class="table table-sm mb-0" style="table-layout: fixed; padding: 5px 0px 5px 0px !important; ">
                    <tr>
                        <td class="text-left p-0">
                            <div class="p-0" style="font-size: 13px;"><b><i>B. School’s Core Values (B.E.S.T)</i></b></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; padding: 0px 0px 0px 20px !important; ">
                    <tr>
                        <td rowspan="2" class="align-middle text-center p-0" style="font-size: 13px!important;"><i>Core Values</i></td>
                        <td rowspan="2" class="align-middle text-center p-0" style="font-size: 13px!important;"><i>Core Values</i></td>
                        <td colspan="4" class="align-middle text-center p-0"style="font-size: 12px;"><i>Quarter</i></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" width="6%" style="font-size: 12px;"><center>1</center></td>
                        <td class="text-center p-0" width="6%" style="font-size: 12px;"><center>2</center></td>
                        <td class="text-center p-0" width="6%" style="font-size: 12px;"><center>3</center></td>
                        <td class="text-center p-0" width="6%" style="font-size: 12px;"><center>4</center></td>
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
                                            <td width="27%" class="text-center align-middle" style="font-size: 11px;" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                            @php
                                                $count = 1;
                                            @endphp
                                    @endif
                                    <td class="p-0" width="52%" class="align-middle" style="font-size: 11px; text-align: left!important; padding-left: 2px!important;">{{$item->description}}</td>
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
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 13px; padding: 10px 30px 0px 0px !important; ">
                    <tr>
                        <td width="52%" class="p-0" style=""><b><u>Learner Progress and Achievement</u> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>Descriptors</u></b></td>
                        <td width="28%" class="p-0" style=""><b><u>Grading Scale</u></b></td>
                        <td width="20%" class="p-0" style=""><b><u>Remarks</u></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 13px; padding: 10px 30px 0px 30px !important; ">
                    <tr>
                        <td width="52%" class="p-0 text-left" style="">Outstanding</td>
                        <td width="28%" class="p-0 text-left" style="">90%-100%</td>
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
        </tr>
    </table>
</body>
</html>