<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
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
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 297mm 210mm; margin: .2in .3in;}
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" style="height; 100px; vertical-align: top;font-size: 14px;">
            {{-- attendance --}}
            <table style="width: 100%; margin-top: 70px!important;">
                <tr>
                    <td class="p-0" style="font-size: 14px;"><b>REPORT ON ATTENDANCE</b></td>
                </tr>
            </table>
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td  width="100%" class="p-0">
                        @php
                            $width = count($attendance_setup) != 0? 78 / count($attendance_setup) : 0;
                        @endphp
                        <table class="table table-bordered table-sm grades mb-0" width="100%">
                            <tr>
                                <td width="14%" style="border: 1px solid #000; text-align: center; height: 65px;"></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle aside" width="{{$width}}%"><span style="font-size: 14px!important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                                @endforeach
                                <td width="8%" class="text-center asidetotal" style="font-size: 14px!important;"><span style=""><b>Total</b></span></td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="asideno" style="font-size: 14px!important; height: 65px;"><span>&nbsp;No. of <br> school <br> &nbsp;days</span></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                @endforeach
                                <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="asideno" style="font-size: 14px!important; height: 65px;"><span>&nbsp;No. of <br> &nbsp;&nbsp;days <br> present</span></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="asideno" style="font-size: 14px!important; height: 65px;"><span>&nbsp;No. of <br> &nbsp;&nbsp;days <br> absent</span></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                            {{-- <tr class="table-bordered">
                                <td>No. of days tardy</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                            </tr> --}}
                        </table>
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <br>
            <table style="width: 100%; font-size: 15px;">
                <tr>
                    <td width="12%"class="p-0"></td>
                    <td width="88%" class="p-0 text-left">PARENT/ GUARDIAN'S SIGNATURE</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 15px;">
                <tr>
                    <td width="12%"class="p-0"></td>
                    <td width="22%" class="p-0 text-left">1<sup>st</sup> Quarter</td>
                    <td width="56%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 35px;">
                <tr>
                    <td width="12%"class="p-0"></td>
                    <td width="22%" class="p-0 text-left">2<sup>nd</sup> Quarter</td>
                    <td width="56%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 35px;">
                <tr>
                    <td width="12%"class="p-0"></td>
                    <td width="22%" class="p-0 text-left">3<sup>rd</sup> Quarter</td>
                    <td width="56%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 35px;">
                <tr>
                    <td width="12%"class="p-0"></td>
                    <td width="22%" class="p-0 text-left">4<sup>th</sup> Quarter</td>
                    <td width="56%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="10%" class="p-0"></td>
                </tr>
            </table>
        </td>
        <td width="50%" style="vertical-align: top; padding-left: .5in!important;">
            <table width="100%" class="table mb-0 mt-0" style="table-layout: fixed;">
                <tr>
                    <td width="25%" class="p-0 text-left">DepEd FORM 138</td>
                    <td width="50%" class="p-0"></td>
                    <td width="25%" class="p-0"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0 p-0" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td class="p-0" width="25%" style="text-align: right;">
                        <img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="90px">
                    </td>
                    <td width="50%" class="p-0" style="text-align: center; font-size: 12px;">
                        <div style="width: 100%;">Republic of the Philippines</div>
                        <div style="width: 100%;">Department of Education</div>
                        <div style="width: 100%;"><u>REGION XI</u></div>
                        <div style="width: 100%;">Region</div>
                        <div style="width: 100%;"><u>DAVAO ORIENTAL</u></div>
                        <div style="width: 100%;">Division</div>
                        <div style="width: 100%;"><u>LUPON WEST</u></div>
                        <div style="width: 100%;">District</div>
                    </td>
                    <td width="25%">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" width="100%" class="p-0" style="text-align: center; font-size: 12px;">
                        <div style="width: 100%; font-size: 18px;"><u>{{$schoolinfo[0]->schoolname}}</u></div>
                        <div style="width: 100%;">School</div>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 14px; margin-top: 10px;" >
                <tr>
                    <td width="100%" class="text-center p-0" style=""><b>JUNIOR HIGH SCHOOL DEPARTMENT</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 5px;" >
                <tr>
                    <td width="9%" class="p-0">Name:</td>
                    <td width="79%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->student}}</b></td>
                    <td width="12%" class="p-0 text-left" style=""></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="7%" class="p-0">Age:</td>
                    <td width="38%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->age}}</b></td>
                    <td width="8%" class="p-0">&nbsp;Sex:</td>
                    <td width="35%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->gender}}</b></td>
                    <td width="12%" class="p-0 text-left" style=""></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="9%" class="p-0">Grade:</td>
                    <td width="37%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->levelname}}</b></td>
                    <td width="12%" class="p-0">&nbsp;Section:</td>
                    <td width="30%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->sectionname}}</b></td>
                    <td width="12%" class="p-0 text-left" style=""></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="16%" class="p-0">School Year:</td>
                    <td width="30%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$schoolyear->sydesc}}</b></td>
                    <td width="8%" class="p-0">&nbsp;LRN:</td>
                    <td width="34%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->lrn}}</b></td>
                    <td width="12%" class="p-0 text-left" style=""></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 13px; margin-top: 10px;">
                <tr>
                    <td class="p-0">Dear Parent:</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="11%" class="p-0"></td>
                    <td width="89%" class="p-0">This report card shows the ability and progress your child has made in</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="89%" class="p-0">the different learning areas as well as his/her core values.</td>
                    <td width="11%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="11%" class="p-0"></td>
                    <td width="89%" class="p-0">We would appreciate very much your coming to Maryknoll School of</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="89%" class="p-0">Lupon, Inc., to confer with us regarding the progress of your child.</td>
                    <td width="11%" class="p-0"></td>
                </tr>
            </table>
            <table style="width: 100%; padding-top: 25px; text-align: center;">
                <tr>
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                    <td class="p-0" width="9%" style=""></td>
                    <td class="p-0" width="45%" style="font-size: 13px;"><u><b>{{$adviser}}</b></u></td>
                    <td class="p-0" width="1%" style=""></td>

                </tr>
                <tr>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                    <td class="p-0" width="9%" style=""></td>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;">Adviser</td>
                    <td class="p-0" width="1%" style=""></td>
                </tr>
            </table>
            <table style="width: 100%; padding-top: 5px; text-align: center;">
                <tr>
                    {{-- <td class="p-0" width="50%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td> --}}
                    <td class="text-left p-0" width="50%" style="font-size: 13px;"><u>SR. MA. DOMITILLA B. SENDINO, O.P.</u></td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                </tr>
                <tr>
                    <td class="p-0" width="50%" style="text-align: center; font-size: 13px;">Principal</td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                </tr>
            </table>
            <br>
            <table style="width: 100%;">
                <tr>
                    <td class="text-center" style="font-size: 14px;"><b>Certificate of Transfer</b></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="24%" class="p-0">Admitted to Grade:</td>
                    <td width="16%" class="p-0 text-center" style="border-bottom: 1px solid #000">&nbsp;<b></b></td>
                    <td width="10%" class="p-0">Section:</td>
                    <td width="28%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b></b></td>
                    <td width="22%" class="p-0 text-center"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="41%" class="p-0 text-left">Eligibility for Admission to Grade:</td>
                    <td width="37%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b></b></td>
                    <td width="22%" class="p-0 text-center"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="100%" class="p-0 text-left">Approved:</td>
                </tr>
            </table>
            <table style="width: 100%; padding-top: 25px; text-align: center;">
                <tr>
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                    <td class="p-0" width="9%" style=""></td>
                    <td class="p-0" width="45%" style="font-size: 13px;"><u><b>{{$adviser}}</b></u></td>
                    <td class="p-0" width="1%" style=""></td>

                </tr>
                <tr>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                    <td class="p-0" width="9%" style=""></td>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;">Adviser</td>
                    <td class="p-0" width="1%" style=""></td>
                </tr>
            </table>
            <table style="width: 100%; padding-top: 5px; text-align: center;">
                <tr>
                    {{-- <td class="p-0" width="50%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td> --}}
                    <td class="text-left p-0" width="50%" style="font-size: 13px;"><u>SR. MA. DOMITILLA B. SENDINO, O.P.</u></td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="45%" style="font-size: 13px;"></td>
                </tr>
                <tr>
                    <td class="p-0" width="50%" style="text-align: center; font-size: 13px;">Principal</td>
                    <td class="p-0" width="5%" style=""></td>
                    <td class="p-0" width="45%" style="text-align: center; font-size: 13px;"></td>
                </tr>
            </table>
            <table style="width: 100%;">
                <tr>
                    <td class="text-center" style="font-size: 14px;">Cancellation of Eligibility to Transfer</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 10px;">
                <tr>
                    <td width="15%" class="p-0 text-left">Admitted in:</td>
                    <td width="32%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="53%" class="p-0 text-center"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td width="7%" class="p-0 text-left">Date:</td>
                    <td width="40%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="53%" class="p-0 text-center"></td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 13px; margin-top: 30px;">
                <tr>
                    <td width="46%" class="p-0 text-center"></td>
                    <td width="46%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="8%" class="p-0 text-center"></td>
                </tr>
                <tr>
                    <td width="46%" class="p-0 text-center"></td>
                    <td width="46%" class="p-0 text-center" style="">Principal</td>
                    <td width="8%" class="p-0 text-center"></td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    <br>
    <table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" style="height; 100px; vertical-align: top;">
            <table class="table table-sm" style="font-size: 14px!important; margin-top: 10px;" width="100%">
                <tr>
                    <td width="100%" class="p-0">
                        @php
                        $acadtext = '';
                        if($student->acadprogid == 3){
                            $acadtext = 'GRADE SCHOOL';
                        }else if($student->acadprogid == 4){
                            $acadtext = 'JUNIOR HIGH SCHOOL';
                        }
                    @endphp
                    <table class="table table-sm table-bordered grades" width="100%" style="font-size: 14px!important; margin-top: 10px;">
                        <thead>
                            {{-- <tr>
                                <td colspan="7" class="align-middle text-center"><b>ACADEMIC ACHIEVEMENT</b></td>
                            </tr> --}}
                            <tr>
                                <td width="35%" rowspan="2"  class="align-middle text-center" style="font-size: 14px!important;"><b>Learning Areas</b></td>
                                <td colspan="4"  class="text-center align-middle" style="font-size: 14px!important;"><b>Quarter</b></td>
                                <td width="12%" rowspan="2" class="text-center align-middle" style="font-size: 14px!important;"><b>Final <br> Grade</b></td>
                                <td width="15%" rowspan="2"  class="text-center align-middle" style="font-size: 14px!important"><b>Remarks</b></span></td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle" width="10%" style="font-size: 14px!important;"><b>1</b></td>
                                <td class="text-center align-middle" width="10%" style="font-size: 14px!important;"><b>2</b></td>
                                <td class="text-center align-middle" width="10%" style="font-size: 14px!important;"><b>3</b></td>
                                <td class="text-center align-middle" width="8%" style="font-size: 14px!important;"><b>4</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studgrades as $item)
                                <tr>
                                    <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 14px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 14px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 14px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 14px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td class="text-right" colspan="4" style="font-size: 14px!important;"><b>General Average</b></td>
                                <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 14px!important;"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                                <td class="text-center align-middle" style="font-size: 14px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>
                    <br>
                    <table class="table-sm mb-0 mt-0" width="100%">
                        <tr>
                            <td width="13%" class="p-0"></td>
                            <td width="42%" class="p-0"><b>Descriptors</b></td>
                            <td width="28%" class="p-0 text-left"><b>Grading Scale</b></td>
                            <td width="17%" class="p-0 text-left"><b>Remarks</b></td>
                        </tr>
                        <tr>
                            <td width="13%" class="p-0"></td>
                            <td width="42%" class="p-0">Outstanding</td>
                            <td width="28%" class="p-0 text-left">90-100</td>
                            <td width="17%" class="p-0 text-left">&nbsp;&nbsp;Passed</td>
                        </tr>
                        <tr>
                            <td width="13%" class="p-0"></td>
                            <td width="42%" class="p-0">Very Satisfactory</td>
                            <td width="28%" class="p-0 text-left">85-89</td>
                            <td width="17%" class="p-0 text-left">&nbsp;&nbsp;Passed</td>
                        </tr>
                        <tr>
                            <td width="13%" class="p-0"></td>
                            <td width="42%" class="p-0">Satisfactory</td>
                            <td width="28%" class="p-0 text-left">80-84</td>
                            <td width="17%" class="p-0 text-left">&nbsp;&nbsp;Passed</td>
                        </tr>
                        <tr>
                            <td width="13%" class="p-0"></td>
                            <td width="42%" class="p-0">Fairly Satisfactory</td>
                            <td width="28%" class="p-0 text-left">75-79</td>
                            <td width="17%" class="p-0 text-left">&nbsp;&nbsp;Passed</td>
                        </tr>
                        <tr>
                            <td width="13%" class="p-0"></td>
                            <td width="42%" class="p-0">Did Not Meet Expectations</td>
                            <td width="28%" class="p-0 text-left">Below 75</td>
                            <td width="17%" class="p-0 text-left">&nbsp;&nbsp;Failed</td>
                        </tr>
                    </table>
                    </td>
                </tr>    
            </table>
        </td>
        <td width="50%" style="height; 100px; vertical-align: top;">
            <table class="table table-sm" style="font-size: 12px; margin-top: 10px;" width="100%">
                <tr>
                    <td width="100%" class="p-0">
                        <table class="table-sm" width="100%">
                            <tr>
                                <td colspan="6" class="align-middle text-left p-0" style="font-size: 14px!important;"><b>REPORT ON LEARNER'S OBSERVED VALUES</b></td>
                            </tr>
                        </table>
                        <table class="table-sm table table-bordered" width="100%" style="margin-top: 10px; font-size: 14px!important;">
                                {{-- <tr>
                                    <td colspan="6" class="align-middle text-center">Reports on Learner's Observed Values</td>
                                </tr> --}}
                                <tr>
                                    <td width="25%" rowspan="2" class="align-middle text-center p-0"><b>Core Values</b></td>
                                    <td width="30%" rowspan="2" class="align-middle text-center p-0"><b>Behavior <br> Statements</b></td>
                                    <td colspan="4" class="cellRight"><center><b>Quarter</b></center></td>
                                </tr>
                                <tr>
                                    <td class="" width="11.25%"><center><b>1</b></center></td>
                                    <td class="" width="11.25%"><center><b>2</b></center></td>
                                    <td class="" width="11.25%"><center><b>3</b></center></td>
                                    <td class="" width="11.25%"><center><b>4</b></center></td>
                                </tr>
                                {{-- ========================================================== --}}
                                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($groupitem as $item)
                                        @if($item->value == 0)
                                        @else
                                            <tr >
                                                @if($count == 0)
                                                        <td width="18%" class="align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                                        @php
                                                            $count = 1;
                                                        @endphp
                                                @endif
                                                <td class="align-middle">{{$item->description}}</td>
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

                        <table class="table-sm mb-0 mt-0" width="100%" style="font-size: 14px;">
                            <tr>
                                <td width="12%" class="p-0"></td>
                                <td width="15%" class="p-0 text-center"><b>Marking</b></td>
                                <td width="18%" class="p-0"></td>
                                <td width="30%" class="p-0"><b>Non-numerical Rating</b></td>
                                <td width="25%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="12%" class="p-0"></td>
                                <td width="15%" class="p-0 text-center">AO</td>
                                <td width="18%" class="p-0"></td>
                                <td width="30%" class="p-0">Always Observed</td>
                                <td width="25%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="12%" class="p-0"></td>
                                <td width="15%" class="p-0 text-center">SO</td>
                                <td width="18%" class="p-0"></td>
                                <td width="30%" class="p-0">Sometimes Observed</td>
                                <td width="25%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="12%" class="p-0"></td>
                                <td width="15%" class="p-0 text-center">RO</td>
                                <td width="18%" class="p-0"></td>
                                <td width="30%" class="p-0">Rarely Observed</td>
                                <td width="25%" class="p-0"></td>
                            </tr>
                            <tr>
                                <td width="12%" class="p-0"></td>
                                <td width="15%" class="p-0 text-center">NO</td>
                                <td width="18%" class="p-0"></td>
                                <td width="30%" class="p-0">Not Observed</td>
                                <td width="25%" class="p-0"></td>
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