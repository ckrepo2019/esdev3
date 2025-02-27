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
            font-family:Verdana, Geneva, Tahoma, sans-serif;
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
            height: 25px;
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
            transform-origin: 6 9;
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
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 297mm 210mm; margin: 0px 0px;} 
        
    </style>
</head>
<body style="">
    <table class="table table-sm mb-0" style="table-layout: fixed; margin: 1.2cm 1.5cm 0cm 1.8cm;">
        <tr>
            <td width="50%">
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; padding-top: .8cm!important;">
                    <tr>
                        <td class="text-left p-0" style="font-size: 14px;">TO THE PARENTS:</td>
                    </tr>
                    <tr>
                        <td class="p-0" style="font-size: 14px; text-align: justify!important; padding-top: 14px!important;"><span style="padding-left: 1.3cm!important;">This Report Card is issued four times a year to give you an update of your son's/daughters performance in school. You are requested to confer with the subject teacher/s about his/her rating/s.</span></td>
                    </tr>
                    <tr>
                        <td class="p-0" style="font-size: 14px; text-align: justify!important; padding-top: 5px!important;"><span style="padding-left: 1.3cm!important;">Thank you!</span></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 10px;">
                    <tr>
                        <td width="85%" class="text-center"><b>SIGNATURE OF PARENT / GUARDIAN</b></td>
                        <td width="15%" class="text-center"><b>DATE</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 5px;">
                    <tr>
                        <td width="27%" class="text-left p-0">First Grading</td>
                        <td width="54%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="4%" class="text-left p-0" style=""></td>
                        <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 5px;">
                    <tr>
                        <td width="27%" class="text-left p-0">Second Grading</td>
                        <td width="54%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="4%" class="text-left p-0" style=""></td>
                        <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 5px;">
                    <tr>
                        <td width="27%" class="text-left p-0">Third Grading</td>
                        <td width="54%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="4%" class="text-left p-0" style=""></td>
                        <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 5px;">
                    <tr>
                        <td width="27%" class="text-left p-0">Fourth Grading</td>
                        <td width="54%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="4%" class="text-left p-0" style=""></td>
                        <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 30px;">
                    <tr>
                        <td width="19%" class="text-left p-0">Promoted to</td>
                        <td width="81%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 5px;">
                    <tr>
                        <td width="27%" class="text-left p-0">Advance unit/s in</td>
                        <td width="73%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 5px;">
                    <tr>
                        <td width="29%" class="text-left p-0">Deficiency unit/s in</td>
                        <td width="71%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 5px;">
                    <tr>
                        <td width="8%" class="text-left p-0">Date</td>
                        <td width="40%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                        <td width="52%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; font-size: 14px; padding-top: 20px;">
                    <tr>
                        <td width="57%" class="text-center p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="43%" class="text-left p-0" style=""></td>
                    </tr>
                    <tr>
                        <td width="57%" class="text-center p-0" style="">Adviser</td>
                        <td width="43%" class="text-left p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; padding-top: 30px;">
                    <tr>
                        <td width="100%" class="text-center p-0" style="font-size: 19px;"><b>PRIMA A. PANCHACALA, MA</b></td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-center p-0" style="font-size: 14px;">Principal</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-right: 1.3cm!important; padding-top: 30px;">
                    <tr>
                        <td width="100%" class="text-center p-0" style="font-size: 19px;"><b>CANCELLATION OF TRANSFER</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="font-size: 14px; table-layout: fixed; padding-right: 1.3cm!important; padding-top: 10px;">
                    <tr>
                        <td width="20%" class="text-left p-0" style="">Admitted to: </td>
                        <td width="25%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="15%" class="text-center p-0" style="">Section: </td>
                        <td width="40%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="font-size: 14px; table-layout: fixed; padding-right: 1.3cm!important; padding-top: 5px;">
                    <tr>
                        <td width="8%" class="text-left p-0" style="">Date: </td>
                        <td width="37%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="55%" class="text-center p-0" style=""></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="font-size: 14px; table-layout: fixed; padding-right: 1.3cm!important; padding-top: 35px;">
                    <tr>
                        <td width="15%" class="text-left p-0" style=""></td>
                        <td width="70%" class="text-center p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="15%" class="text-center p-0" style=""></td>
                    </tr>
                    <tr>
                        <td width="15%" class="text-left p-0" style=""></td>
                        <td width="70%" class="text-center p-0" style="">Principal</td>
                        <td width="15%" class="text-center p-0" style=""></td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="">
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td class="text-right p-0" style="font-size: 11.5px;">DepEd Form 138-A</td>
                    </tr>
                    <tr>
                        <td class="text-right p-0">(Revised SY 2021)&nbsp;</td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 30px!important;">
                    <tr>
                        <td width="30%" class="text-right">
                            <img style="padding-top: 5px;" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="110px">
                        </td>
                        <td width="70%" style="vertical-align: middle;">
                            <div class="text-left p-0" style="font-family: Rockwell Extra Bold Regularockwell Extra Bold,Rockwell Bold,monospace">
                                <img style="padding-top: 5px;" src="{{base_path()}}/public/assets/images/sic/sic.png" alt="school" width="300px">
                            </div>
                            <div class="text-left p-0" style="font-size: 16px; padding-top: 6px!important"><b>INTEGRATED BASIC EDUCATION</b></div>
                            <div class="text-left p-0" style="font-size: 16px;"><b>JUNIOR HIGH SCHOOL</b></div>
                            <div class="text-left p-0" style="font-size: 16px;"><i>Malaybalay City</i></div>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                    <tr>
                        <td style="padding-left: 100px!important;font-size: 15px;"><b>DepEd Recogition No. 289 - 1955 (Day)</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 50px;">
                    <tr>
                        <td class="text-center" style="font-size: 15px;">
                            <img style="padding-top: 5px;" src="{{base_path()}}/public/assets/images/sic/reportcard.png" alt="school" width="190px">
                        </td>
                    </tr>
                </table>
                <div style="background-color: #fff; margin-left: 1.1cm!imporant; border-radius: 5px;">
                    <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 180px; padding-left: 20px!important; padding-top: 20px!important; padding-right: 20px!important;">
                        <tr>
                            <td width="13%" class="text-left p-0" style="font-size: 15px;"><b>Name:</b></td>
                            <td width="87%" class="text-left p-0" style="font-size: 15px; border-bottom: 1px solid #000;"><b></b></td>
                        </tr>
                    </table>
                    <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 20px!important; padding-top: 15px!important; padding-right: 20px!important;">
                        <tr>
                            <td width="11%" class="text-left p-0" style="font-size: 15px;"><b>LRN:</b></td>
                            <td width="41%" class="text-left p-0" style="font-size: 15px; border-bottom: 1px solid #000;"><b></b></td>
                            <td width="9%" class="text-center p-0" style="font-size: 15px;"><b>Sex:</b></td>
                            <td width="14%" class="text-left p-0" style="font-size: 15px; border-bottom: 1px solid #000;"><b></b></td>
                            <td width="11%" class="text-center p-0" style="font-size: 15px;"><b>Age:</b></td>
                            <td width="14%" class="text-left p-0" style="font-size: 15px; border-bottom: 1px solid #000;"><b></b></td>
                        </tr>
                    </table>
                    <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 20px!important; padding-top: 15px!important; padding-right: 20px!important;">
                        <tr>
                            <td width="31%" class="text-left p-0" style="font-size: 15px;"><b>Grade & Section:</b></td>
                            <td width="69%" class="text-left p-0" style="font-size: 15px; border-bottom: 1px solid #000;"><b></b></td>
                        </tr>
                    </table>
                    <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 20px!important; padding-top: 15px!important; padding-right: 20px!important;">
                        <tr>
                            <td width="24%" class="text-left p-0" style="font-size: 15px;"><b>School Year:</b></td>
                            <td width="76%" class="text-left p-0" style="font-size: 15px; border-bottom: 1px solid #000;"><b></b></td>
                        </tr>
                    </table>
                    <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 20px!important; padding-top: 20px!important; padding-right: 20px!important;">
                        <tr>
                            <td width="100%" class="text-left p-0" style="font-size: 15px;"><b><i>Curriculum: K to 12 Basic Educational Curriculum</i></b></td>
                        </tr>
                    </table>
                    <br>
                </div>
            </td>
        </tr>
    </table>
    <br><br>
    <table class="table table-sm mb-0" style="table-layout: fixed; margin: .5cm 1.2cm 0cm .8cm; ">
        <tr>
            <td width="50%" style="padding-right: 1.3cm!important;">
                <table class="table table-sm table-bordered grades" width="100%" style="padding-top: 10px;">
                    <thead>
                        <tr style="">
                            <td rowspan="2"  class="align-middle text-center" width="44%" style="font-size: 18px!important;"><b>SUBJECTS</b></td>
                            <td colspan="4"  class="text-center align-middle" style="font-size: 13px!important;"><b>GRADING PERIOD</b></td>
                            <td rowspan="2" class="text-center align-middle" width="8%" ><b>Final <br> Rating</b></td>
                            <td rowspan="2"  class="text-center align-middle" width="18%" ><b>Remarks</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="7.5%"><b>1</b></td>
                            <td class="text-center align-middle" width="7.5%"><b>2</b></td>
                            <td class="text-center align-middle" width="7.5%"><b>3</b></td>
                            <td class="text-center align-middle" width="7.5%"><b>4</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 14px!important;" class="p-1">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-left p-1" style="font-size: 14px!important;"><b>GENERAL AVERAGE</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="vertical-align: middle; font-size: 14px!important;">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                            <td class="text-center align-middle" style="font-size: 14px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm grades" width="100%" style="padding-top: 10px;">
                    <tr>
                        <td width="10%"></td>
                        <td width="34%" style="padding-left: 20px; font-size: 11px!important; border-bottom: .5px solid #000;"><b><i>Descriptors</i></b></td>
                        <td width="23%" class="text-center" style="border-bottom: .5px solid #000;"><b>Grading Scale</b></td>
                        <td width="17%" class="text-center" style="border-bottom: .5px solid #000;"><b>Remarks</b></td>
                        <td width="16%"><b></b></td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="34%" style=""><i>Outstanding</i></td>
                        <td width="23%" class="text-center"><i>90%-100%</i></td>
                        <td width="17%" class="text-center"><i>Passed</i></td>
                        <td width="16%"><b></b></td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="34%" style=""><i>Very Outstanding</i></td>
                        <td width="23%" class="text-center"><i>85%-89%</i></td>
                        <td width="17%" class="text-center"><i>Passed</i></td>
                        <td width="16%"><b></b></td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="34%" style=""><i>Satisfactory</i></td>
                        <td width="23%" class="text-center"><i>80%-84%</i></td>
                        <td width="17%" class="text-center"><i>Passed</i></td>
                        <td width="16%"><b></b></td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="34%" style=""><i>Fairy Satisfactory</i></td>
                        <td width="23%" class="text-center"><i>75%-79%</i></td>
                        <td width="17%" class="text-center"><i>Passed</i></td>
                        <td width="16%"><b></b></td>
                    </tr>
                    <tr>
                        <td width="10%"></td>
                        <td width="34%" style=""><i>Did not meet expectation</i></td>
                        <td width="23%" class="text-center"><i>Below 75%</i></td>
                        <td width="17%" class="text-center"><i>Failed</i></td>
                        <td width="16%"><b></b></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-top: 8px;">
                    <tr>
                        <td width="100%" class="p-0">
                            @php
                                $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                            @endphp
                            <table class="table table-bordered table-sm grades mb-0" width="100%">
                                <tr>
                                    <td width="17%" style="text-align: center; vertical-align: middle!important;">Attendance</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="aside text-center align-middle;" style="vertical-align: middle; font-size: 10px!important;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                                    @endforeach
                                    <td class="text-center" width="13%" style="vertical-align: middle; font-size: 9px!important;"><span>TOTAL</span></td>
                                </tr>
                                <tr class="table-bordered">
                                    <td style="font-size: 10px!important;">Days of School</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                    @endforeach
                                    <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td style="font-size: 10px!important;">Days Present</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                    @endforeach
                                    <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                                </tr>
                                <tr class="table-bordered">
                                    <td style="font-size: 10px!important;">Days Tardy</td>
                                    @foreach ($attendance_setup as $item)
                                        <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                    @endforeach
                                    <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="background-color: aqua;">
                <table class="table table-sm grades" width="100%" style="padding-top: 10px;">
                    <tr>
                        <td class="text-center p-0" style="font-size: 16px!important;"><b>RATING ON STUDENT'S MANIFESTATION</b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 16px!important;"><b>OF THE SIX (6) CORE VALUES</b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 13px!important; padding-top: 10px!important;">Legend: E-Excellent; VG-Very Good; G-Good; F-Fair, NI- Needs Improvement</td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered grades" width="100%" style="padding-top: 10px;">
                    <thead>
                        <tr style="">
                            <td rowspan="2"  class="align-middle text-center" width="44%" style="font-size: 16px!important;"><b>SUBJECTS</b></td>
                            <td colspan="4"  class="text-center align-middle" style="font-size: 14px!important;"><b>GRADING PERIOD</b></td>
                            <td class="text-center align-middle" width="8%" ><b>FINAL</b></td>
                            <td rowspan="2"  class="text-center align-middle" width="18%" ><b>REMARKS</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="7.5%"><b>1</b></td>
                            <td class="text-center align-middle" width="7.5%"><b>2</b></td>
                            <td class="text-center align-middle" width="7.5%"><b>3</b></td>
                            <td class="text-center align-middle" width="7.5%"><b>4</b></td>
                            <td class="text-center align-middle"><b>RATING</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-left"><b>GENERAL AVERAGE</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                            <td class="text-center align-middle" >{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>