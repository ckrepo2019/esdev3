<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> -->
    <style>

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
       .p-1 {
            padding-top: 8px!important;
            padding-bottom: 8px!important;
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
            font-size: 10px !important;
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
            /* line-height: 15px; */
            /* height: 35px; */
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
            /* transform-origin: 10 10; */
            /* transform: rotate(-90deg); */
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }

        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 14in 8.5in; margin: 10px 20px;  }
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="33.3333333333%" class="" valign="top">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left" style="font-size: 12px; color: #2587be;"><b>PAG-UNLAD SA TAGLAY NA MGA PAGPAPAHALAGA AT SALOOBIN</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left" style="font-size: 12px;">Panuto: Lagyan ng tatlong (3) istar (???) kung lubhang kasiya-siya ang pinamalas, dalawang (2) istar (??) kung kasiya-siya, at isang (1) istar (?) kung dapat linangin ang mag-aaral</b></td>
                </tr>
            </table>
            <table class="table table-sm table-bordered mb-0" style="table-layout: fixed; vertical-align: middle;">
                <tr>
                    <td width="60%" rowspan="2" class="text-center p-1" style="font-size: 12px;"><span style="color: #2587be;"><b>Mga Kinakailangang Namamasid sa <br> Pangangalaga at Saloobin</b></span></td>
                    <td width="10%" colspan="4" class="text-center p-1" style="font-size: 12px;"><span style="color: #2587be;"><b>MARKAHAN</b></span></td>
                </tr>
                <tr>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b style="color: #2587be;">1</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b style="color: #2587be;">2</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b style="color: #2587be;">3</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b style="color: #2587be;">4</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>1. Paggalang sa Sarili</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>2. Pagkamatapat at pagka-Makakatotohanan</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>3. Pagkamamahal at Paggalang sa iba</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>4. Pagmamahal at Paggalang sa iba</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>5. Panangutan/ Tungkulin</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>6. Pangangalaga sa Sariling Kapaligiran</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>7. Pagmamahal sa Panginoon</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>8. Pagkamatulungin</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>9. Pagmamalasakit sa Kapwa</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 11px; padding-top: 8px!important; padding-bottom: 8px!important;"><span style="color: #2587be;"><b>10. Pagmamahal sa Bayan</b></span></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
            </table>
            <table width="100%" class="table table-sm grades mb-0" style="margin-top: 80px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size:15px!important;"><b style="color: #2587be;">ATTENDANCE</b></td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 82 / count($attendance_setup) : 0;
            @endphp
            <table class="table table-bordered table-sm grades mb-0" width="100%">
                <tr>
                    <td style="padding-top: 13px!important;border: 1px solid #000; text-align: center; padding-top: 40px;">&nbsp;</td>
                    @foreach ($attendance_setup as $item)
                        <td class="aside text-center align-middle;" width="{{$width}}%" style="vertical-align: bottom;"><span style="font-size: 8px!important;"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</b></span></td>
                    @endforeach
                    <td class="text-center p-0" width="8%" style="vertical-align: bottom; font-size: 8px!important;"><span style=""><b>Total</b></span></td>
                </tr>
                <tr class="table-bordered">
                    <td width="10%" class="text-center" style="font-size: 8px!important;"><b>No. of School <br> Days</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-center" style="font-size: 8px!important;"><b>No. of School <br> Days Present</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-center" style="font-size: 8px!important;"><b>No. of Times <br> Tardy</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" class="" valign="top">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px; color: #2587be;"><b>PARENT'S / SIGNATURE</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important;">
                <tr>
                    <td width="24%" class="text-left p-0" style="font-size: 16px;">First Grading:</td>
                    <td width="61%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                    <td width="15%"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important;">
                <tr>
                    <td width="29%" class="text-left p-0" style="font-size: 16px;">Second Grading:</td>
                    <td width="56%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                    <td width="15%"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important;">
                <tr>
                    <td width="25%" class="text-left p-0" style="font-size: 16px;">Third Grading:</td>
                    <td width="60%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                    <td width="15%"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important;">
                <tr>
                    <td width="27%" class="text-left p-0" style="font-size: 16px;">Fourth Grading:</td>
                    <td width="58%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                    <td width="15%"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 40px!important; border: 1px solid #2587be; margin-right: 50px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 16px;">
                        <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px!important;">
                            <tr>
                                <td width="100%" class="text-center p-0" style="font-size: 16px; color: #2587be;"><b>Certificate of Transfer</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important;">
                            <tr>
                                <td width="39%" class="text-left p-0" style="font-size: 16px;">&nbsp;Admitted to Grade:</td>
                                <td width="48%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                                <td width="13%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px!important;">
                            <tr>
                                <td width="19%" class="text-left p-0" style="font-size: 16px;">&nbsp;Section:</td>
                                <td width="68%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                                <td width="13%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px!important;">
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 16px;">&nbsp;Eligible for Admission to Grade:</td>
                                <td width="22%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                                <td width="13%"></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px!important;">
                            <tr>
                                <td width="100%" class="text-left p-0" style="font-size: 16px;">&nbsp;Approved:</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px!important; padding-left: 5px!important;">
                            <tr>
                                <td width="42%" class="text-center p-0" style="font-size: 15px; border-bottom: 1px solid #000;">Cynthia H. Ortega, MA</td>
                                <td width="15%"></td>
                                <td width="35%" class="text-center p-0" style="font-size: 15px; border-bottom: 1px solid #000;">Reyvia L. Apduhan</td>
                                <td width="8%"></td>
                            </tr>
                            <tr>
                                <td width="42%" class="text-center p-0" style="font-size: 15px;"><i>Principal</i></td>
                                <td width="15%"></td>
                                <td width="35%" class="text-center p-0" style="font-size: 15px;"><i>Teacher</i></td>
                                <td width="8%"></td>
                            </tr>
                        </table>
                        <br>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 40px!important;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 16px; color: #2587be;"><b>Cancellation of eligible to transfer</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px!important;">
                <tr>
                    <td width="22%" class="text-left p-0" style="font-size: 16px;">&nbsp;Admitted in:</td>
                    <td width="35%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                    <td width="43%" class="text-center p-0"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px!important;">
                <tr>
                    <td width="11%" class="text-left p-0" style="font-size: 16px;">&nbsp;Date:</td>
                    <td width="46%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;"></td>
                    <td width="43%" class="text-center p-0"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 80px!important; margin-right: 50px;">
                <tr>
                    <td width="57%" class="text-left p-0" style="font-size: 16px;"></td>
                    <td width="43%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;">Cynthia H. Ortega,MA</td>
                </tr>
                <tr>
                    <td width="57%" class="text-left p-0" style="font-size: 16px;"></td>
                    <td width="43%" class="text-center p-0" style="font-size: 16px;"><i>Principal</i></td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" class="" valign="top">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left" style="font-size: 12px;">
                        <b><i>SF9</i></b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">Republic of the Philippines</td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;"><b><i>Department of Education</i></b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">Region IX, Zamboanga Peninsula</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 17px;"><b>MARIAN COLLEGE, Inc</b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;"><b>Ipil, Zamboanga Sibugay</b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 16px; padding-top: 10px!important;">Integrated Basic Education Department</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 30px;">
                <tr>
                    <td width="13%" class="text-left p-0" style="font-size: 16px;">Name:</td>
                    <td width="65%" class="text-center p-0" style="font-size: 17px; border-bottom: 1px solid #000;"><b>{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</b></td>
                    <td width="22%" class="text-left p-0" style="font-size: 16px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="11%" class="text-left p-0" style="font-size: 16px;">Age:</td>
                    <td width="16%" class="text-left p-0" style="font-size: 16px; border-bottom: 1px solid #000;">&nbsp;4.75</td>
                    <td width="26%" class="text-left p-0" style="font-size: 16px;"></td>
                    <td width="17%" class="text-left p-0" style="font-size: 16px;">Gender:</td>
                    <td width="18%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;">{{$student->gender}}</td>
                    <td width="15%" class="text-left p-0" style="font-size: 16px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="13%" class="text-left p-0" style="font-size: 16px;">Grade:</td>
                    <td width="23%" class="text-left p-0" style="font-size: 16px;"><u>{{$student->levelname}}</u></td>
                    <td width="17%" class="text-left p-0" style="font-size: 16px;"></td>
                    <td width="17%" class="text-left p-0" style="font-size: 16px;">Section:</td>
                    <td width="25%" class="text-center p-0" style="font-size: 16px;"><u>{{$student->sectionname}}</u></td>
                    <td width="8%" class="text-left p-0" style="font-size: 16px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="10%" class="text-left p-0" style="font-size: 16px;">LRN:</td>
                    <td width="37%" class="text-left p-0" style="font-size: 16px; border-bottom: 1px solid #000;">{{$student->lrn}}</td>
                    <td width="6%" class="text-left p-0" style="font-size: 16px;"></td>
                    <td width="25%" class="text-left p-0" style="font-size: 16px;">School Year:</td>
                    <td width="19%" class="text-left p-0" style="font-size: 16px; border-bottom: 1px solid #000;">{{$schoolyear->sydesc}}</td>
                    <td width="6%" class="text-left p-0" style="font-size: 16px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 40px;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 16px;"><b>Dear Parent:</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; text-align: justify!important;"><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This report card shows the ability and progress your child has made in the different learning areas as well as his/her progress in character development</i></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 11.5px; text-align: justify!important;"><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The school welcome you if desire to know more about the progress of your child.</i></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 30px;">
                <tr>
                    <td width="57%" class="text-left p-0" style="font-size: 16px;"></td>
                    <td width="32%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;">Reyvia L. Apduhan</td>
                    <td width="11%" class="text-left p-0" style="font-size: 16px;"></td>
                </tr>
                <tr>
                    <td width="57%" class="text-left p-0" style="font-size: 16px;"></td>
                    <td width="32%" class="text-center p-0" style="font-size: 16px;"><i>Teacher</i></td>
                    <td width="11%" class="text-left p-0" style="font-size: 16px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 30px;">
                <tr>
                    <td width="38%" class="text-center p-0" style="font-size: 16px; border-bottom: 1px solid #000;">Cynthia H. Ortega,MA</td>
                    <td width="62%" class="text-left p-0" style="font-size: 16px;"></td>
                </tr>
                <tr>
                    <td width="38%" class="text-center p-0" style="font-size: 16px;"><i>Principal</i></td>
                    <td width="62%" class="text-left p-0" style="font-size: 16px;"></td>
                </tr>
            </table>
            <br>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 5px;">
    <tr>
        <td width="33.3333333333%" class="" valign="top">
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="60%" rowspan="2" class="text-center p-0" style="font-size: 14.5px; vertical-align: middle;"><span style="color: #2587be;"><b>PHYSICAL HEALTH, WELL-BEING<br> AND <br>MOTOR DEVELOPMENT</span></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>4</b></td>
                </tr>
                <tr>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>10 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>20 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>30 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>40 <br>WKS</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Shows control and coordination of hand body movements while performing variety of physical activities</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Practices basic personal care routines <br> and self-help</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Demonstrates ability to protect oneself by responding appropriately to common sign at danger</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
            </table>
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="60%" rowspan="2" class="text-center p-0" style="font-size: 14.5px; vertical-align: middle;"><span style="color: #2587be;"><b>SOCIAL AND EMOTIONAL<br> DEVELOPMENT </b></span></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>4</b></td>
                </tr>
                <tr>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>10 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>20 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>30 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>40 <br>WKS</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Demontrates self-expression and awareness of self in terms of specific abilities, characteristic and preferences</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Demonstrates respect and positive interactions with children, as well as adult members of the family, school and community</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
            </table>
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="60%" rowspan="2" class="text-center p-0" style="font-size: 14.5px; vertical-align: middle;"><span style="color: #2587be;"><b>MATHEMATICS</b></span></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>4</b></td>
                </tr>
                <tr>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>10 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>20 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>30 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>40 <br>WKS</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>understands the numerals and the quantities they represent</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Demonstrates knowledge of the concepts of chance and the collection and organization of data</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Demonstrates knowledge of the concepts on space, including location, shapes, length, capacity, mass and time</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
            </table>
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="60%" rowspan="2" class="text-center p-0" style="font-size: 14.5px; vertical-align: middle;"><span style="color: #2587be;"><b>SENSORY PERCEPTUAL</b></span></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>4</b></td>
                </tr>
                <tr>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>10 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>20 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>30 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>40 <br>WKS</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Makes observations using one or more of the five senses</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Demonstrates knowledge of concepts pertaining of living and non-living things and the environment</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Able to follow logic of events (i.e. why this happen) and draws conclusion by evaluating the facts presented to the child</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
            </table>
        </td>
        <td width="33.3333333333%" class="" valign="top">
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; margin-top: 28px;">
                <tr>
                    <td width="60%" rowspan="2" class="text-center p-0" style="font-size: 14.5px; vertical-align: middle;"><span style="color: #2587be;"><b>LANGUAGE AND LITERACY</b></span></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>4</b></td>
                </tr>
                <tr>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>10 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>20 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>30 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>40 <br>WKS</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Speaks audibly in coherent sentences/ use words (oral language) to express the child's thoughts and felling effectively</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>&nbsp; <br> Shows interest in and enjoy books <br> &nbsp;</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Uses symbolic represents <br> (e.g. drawing, sounds, print)</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
            </table>
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; margin-top: 50px;">
                <tr>
                    <td width="60%" rowspan="2" class="text-center p-0" style="font-size: 14.5px; vertical-align: middle;"><span style="color: #2587be;"><b>VALUES EDUCATION</b></span></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>1</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>2</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>3</b></td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px; vertical-align: middle;"><b style="color: #2587be;"><b>4</b></td>
                </tr>
                <tr>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>10 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>20 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>30 <br>WKS</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"><b><b>40 <br>WKS</b></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>&nbsp; <br>Demonstrates honesty in words and in actions<br> &nbsp;</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Understand how to make turns and cooperate with group rules and Responsibilities as the child carry out task</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
                <tr>
                    <td width="60%" class="text-left" style="font-size: 10px;"><b>Shows concern for others and caring attitude towards God's creation and the environment</b></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                    <td width="10%" class="text-center" style="font-size: 12px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 50px; border: 1px solid #000;">
                <tr>
                    <td class="p-0">
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                            <tr>
                                <td width="100%" class="text-center" style="font-size: 14.5px;"><b style="color: #2587be;">LEGEND</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                            <tr>
                                <td width="60%" class="text-left" style="font-size: 12px;"><b style="color: #2587be;">Descriptors</b></td>
                                <td width="40%" class="text-center" style="font-size: 12px;"><b style="color: #2587be;">Grading Scale</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                            <tr>
                                <td width="10%" class="text-left" style="font-size: 12px;"><b>O</b></td>
                                <td width="50%" class="text-left" style="font-size: 12px;"><b>Outstanding</b></td>
                                <td width="40%" class="text-center" style="font-size: 12px;"><b>90 - 100</b></td>
                            </tr>
                            <tr>
                                <td width="10%" class="text-left" style="font-size: 12px;"><b>VS</b></td>
                                <td width="50%" class="text-left" style="font-size: 12px;"><b>Very Satisfactory</b></td>
                                <td width="40%" class="text-center" style="font-size: 12px;"><b>85 - 89</b></td>
                            </tr>
                            <tr>
                                <td width="10%" class="text-left" style="font-size: 12px;"><b>S</b></td>
                                <td width="50%" class="text-left" style="font-size: 12px;"><b>Satisfactory</b></td>
                                <td width="40%" class="text-center" style="font-size: 12px;"><b>80 - 84</b></td>
                            </tr>
                            <tr>
                                <td width="10%" class="text-left" style="font-size: 12px;"><b>FS</b></td>
                                <td width="50%" class="text-left" style="font-size: 12px;"><b>Fairly Satisfactory</b></td>
                                <td width="40%" class="text-center" style="font-size: 12px;"><b>75 - 79</b></td>
                            </tr>
                            <tr>
                                <td width="10%" class="text-left" style="font-size: 12px;"><b>D</b></td>
                                <td width="50%" class="text-left" style="font-size: 12px;"><b>Did Not Meet Expectations</b></td>
                                <td width="40%" class="text-center" style="font-size: 12px;"><b>Below 75</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
        </td>
        <td width="33.3333333333%" class="" valign="top">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td class="p-0">
                        <table class="table table-bordered  table-sm mb-0" style="table-layout: fixed;">
                            <tr>
                                <td width="65%" class="text-center" style="font-size: 15px;"><b style="color: #2587be;">LEARNING DOMAINS</b></td>
                                <td width="8.75%" class="text-center" style="font-size: 15px;"><b style="color: #2587be;">1</b></td>
                                <td width="8.75%" class="text-center" style="font-size: 15px;"><b style="color: #2587be;">2</b></td>
                                <td width="8.75%" class="text-center" style="font-size: 15px;"><b style="color: #2587be;">3</b></td>
                                <td width="8.75%" class="text-center" style="font-size: 15px;"><b style="color: #2587be;">4</b></td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; border-top: none!important;">
                            <tr>
                                <td colspan="5" class="text-left" style="font-size: 15px;"><b style="color: #2587be;">I. PHYSICAL HEALTH WELL-BEING <br> AND MOTOR DEVELOPMENT</b></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;a. Physical Health</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;b. Gross Motor Skills Development</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;c. Fine Motor Skills Development</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;d. Personal Care and Hygiene</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-left" style="font-size: 15px;"><b style="color: #2587be;">II. MATHEMATICS</b></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;a. Classification</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left" style="font-size: 15px;"><b>&nbsp;b. Seriation/Pattern</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;c. Number and Numeration</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;d. Operations on Whole Numbers</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-left" style="font-size: 15px;"><b style="color: #2587be;">III. LANGUAGE AND LITERACY</b></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;a. Receptive Skills</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;b. Expressive Skills</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-left" style="font-size: 15px;"><b style="color: #2587be;">IV. SENSORY PERCEPTUAL</b></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;a. Body Awareness</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;b. Visual Discrimination</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;c. Form Perception Representation</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-left" style="font-size: 15px;"><b style="color: #2587be;">V. PHYSICAL AND NATURAL <br> ENVIRONMENT</b></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;a. Concept Development</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;b. Understanding of Social Development</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-left" style="font-size: 15px;"><b style="color: #2587be;">VI. SOCIAL AND EMOTIONAL <br> DEVELOPMENT</b></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;a. Emotional Expression</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;b. Receptive to Other's Emotion</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;c. Emerging Sense of Self</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;d. Forming Attachments</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;e. Interaction with Other Children</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;f. Interaction with Adults</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                            </tr>
                            <tr>
                                <td width="65%" class="text-left p-0" style="font-size: 15px;"><b>&nbsp;g. Appreciating Diversity</b></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
                                <td width="8.75%" class="text-center p-0" style="font-size: 15px;"></td>
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