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
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

            
        /* .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
        } */
        .p-0 {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }
        .mt-0 {
            margin-top: 0px !important;
            margin-bottom: 0px !important;
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
            transform-origin: 10 10;
            transform: rotate(-90deg);
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
            /* font-family: Verdana, Geneva, Tahoma, sans-serif !important; */
            font-family: Arial, Helvetica, sans-serif;
            
        }
        
            .check_mark {
                /* font-family: ZapfDingbats, sans-serif; */
            }
        @page { size: 11in 8.5in; margin: 40px 20px;  }
        
        #watermark1 {
        opacity: 1;
                position: absolute;
                top: 20%;
                left: 30%;
                opacity: .5;
                /* transform-origin: 10 10; */
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                /* width:    50cm;
                height:   50cm; */

                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }
    </style>
</head>
<body>  
    <table width="100%" style="table-layout: fixed;">
        <tr>
        <td width="50%" class="p-0" valign="top" style="padding-right: 15px!important;">
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td class="text-left">SHJMS FORM 138</td> 
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-right">
                            <div style="padding-top: 5px;"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px" height="55px"></div>
                        </td>
                        <td width="60%" class="text-center">
                            <div>Republic of the Philippines</div>
                            <div>DepEd Region 10</div>
                            <div>Division of City Schools, Cagayan de Oro City</div>
                            <div style="padding-top: 4px; font-size: 10px; color: #FF0000;"><b>SACRED HEART OF JESUS MONTESSORI SCHOOL</b></div>
                            <div>J.R. Borja Extension, Gusa, Cagayan de Oro City</div>
                            <div style="padding-top: 5px;"><b>Senior High School Report Card</b></div>
                        </td>
                        <td width="20%"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
                    <tr>
                        <td width="20%" class="text-left">Name:</td>
                        <td width="80%" class="text-left"><u>{{$student->student}}</u></td>
                    </tr>
                    <tr>
                        <td width="20%" class="text-left">LRN:</td>
                        <td width="80%" class="text-left"><u>{{$student->lrn}}</u></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-left">Year & Section:</td>
                        <td width="52%" class=""><u>{{str_replace('GRADE', '', $student->levelname)}} - {{$student->sectionname}}</u></td>
                        <td width="6%" class="text-left">Sex:</td>
                        <td width="22%" class=""><u>{{$student->gender}}</u></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-left">School Year:</td>
                        <td width="52%" class=""><u>{{$schoolyear->sydesc}}</u></td>
                        <td width="6%" class="text-left">Age:</td>
                        <td width="22%" class=""><u>{{$student->age}}</u></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-left">Class Adviser:</td>
                        <td width="80%" class="text-left"><u>{{$adviser}}</u></td>
                    </tr>
                </table>
                


                @php
                    $x = 1;
                @endphp
                <table class="table table-bordered table-sm grades mb-0" width="100%" style="table-layout: fixed; margin-top: 10px;">
                    <tr>
                        <td width="38%" rowspan="2"  class="p-0 text-center" style="vertical-align: middle!important;"><b>FIRST SEMESTER</b></td>
                        <td colspan="2"  class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>RATINGS</b></td>
                        <td width="10%" rowspan="2" class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>Final <br> Term</b></td>
                        <td width="12%" rowspan="2"  class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>Action <br> Taken</b></td>
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="20%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Midterm</b></td>
                            <td width="20%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Final Term</b></td>
                        @elseif($x == 2)
                            <td width="20%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Midterm</b></td>
                            <td width="20%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Final Term</b></td>
                        @endif
                    </tr>
                    <tr>
                        <td class="p-0" style="text-align: left;"><b>&nbsp;&nbsp;Core Subjects</b></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                        <tr>
                            <td class="align-middle" style="font-size: 9px!important; padding-left: 20px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="p-0" style="text-align: left;"><b>&nbsp;&nbsp;Applied Subjects</b></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                        <tr>
                            <td class="align-middle" style="font-size: 9px!important; padding-left: 20px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
                    </tr> --}}
                    <tr>
                        <td class="p-0" style="text-align: left;"><b>&nbsp;&nbsp;Specialized Subjects</b></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                        <tr>
                            <td class="align-middle" style="font-size: 9px!important; padding-left: 20px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="p-0 text-left" style="font-size: 11px!important;">&nbsp;Homeroom Guidance</td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left" style="font-size: 11px!important;">&nbsp;Co-curricular</td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                    </tr>
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td class="p-0 text-left" style="font-size: 11px!important;">&nbsp;<b>General Average</b></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="text-center" style="font-weight: bold; font-size: 9px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                        <td class="text-center align-middle" style="font-size: 8px!important;"><b>{{isset($item->actiontaken) ? $item->actiontaken:''}}</b></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left" style="font-size: 11px!important;">&nbsp;<b>Grading Plan Used</b></td>
                        <td class="p-0 text-center" colspan="4"><b>AVERAGING</b></td>
                    </tr>
                </table>
                @php
                    $firstsem = collect($attendance_setup)->where('semid', $x);
                    $width = count($firstsem) != 0? 55 / count($firstsem) : 0;
                @endphp
                <table width="100%" class="table table-bordered table-sm grades mb-0" style="table-layout: fixed;">
                    <tr>
                        <td class="p-0" width="38%" style="border: 1px solid #000; text-align: center;"></td>
                        @foreach (collect($attendance_setup)->where('semid',$x) as $item)
                            <td class="text-center align-middle p-0" width="{{$width}}%"><span style="font-size: 11px!important"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</b></span></td>
                        @endforeach
                        <td class="text-center p-0" width="7%" style="vertical-align: middle; font-size: 12px!important;"><span><b>Total</b></span></td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of School Days</td>
                        @foreach (collect($attendance_setup)->where('semid',$x) as $item)
                            <td class="p-0 text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle">{{collect($attendance_setup)->where('semid', $x)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of Days Present</td>
                        @foreach (collect($attendance_setup)->where('semid',$x) as $item)
                            <td class="p-0 text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle">{{collect($attendance_setup)->where('semid', $x)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of Days Tardy</td>
                        @foreach (collect($attendance_setup)->where('semid',$x) as $item)
                            <td class="p-0 text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
                {{-- <table class="table" width="100%" style="font-size: 12px; margin-top: 30px;">
                    <tr>
                        <td width="38%" class="p-0 text-left">&nbsp;Eligible admission to:</td>
                        <td width="20%" class="p-0 text-left border-bottom">&nbsp;</td>
                        <td width="42%" class="p-0 text-left"></td>
                    </tr>
                </table>
                <table class="table" width="100%" style="font-size: 12px; margin-top: 35px;">
                    <tr>
                        <td width="30%" class="p-0 text-left"><b><u>Cathy R. Balaba</u></b></td>
                        <td width="40%" class="p-0 text-left">&nbsp;</td>
                        <td width="30%" class="p-0 text-right"><b><u>MS. MERLINA BRITOS</u></b></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left">Class Adviser</td>
                        <td class="p-0 text-left">&nbsp;</td>
                        <td class="p-0 text-right">Academic Head</td>
                    </tr>
                </table>
                <table class="table" width="100%" style="font-size: 12px; margin-top: 5px;">
                    <tr>
                        <td width="30%" class="p-0 text-left"></td>
                        <td width="40%" class="p-0 text-center"><b><u>MS. ANN B. NERI</u></b></td>
                        <td width="30%" class="p-0 text-center"></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center">School Director</td>
                        <td class="p-0 text-center"></td>
                    </tr>
                </table> --}}

            </td> 
            <td width="50%" class="" style="padding-left: 15px;" valign="top;">
                @php
                    $x = 2;
                @endphp
                <table class="table table-bordered table-sm grades mb-0" width="100%" style="table-layout: fixed; margin-top: 10px;">
                    <tr>
                        <td width="38%" rowspan="2"  class="p-0 text-center" style="vertical-align: middle!important;"><b>FIRST SEMESTER</b></td>
                        <td colspan="2"  class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>RATINGS</b></td>
                        <td width="10%" rowspan="2" class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>Final <br> Term</b></td>
                        <td width="12%" rowspan="2"  class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>Action <br> Taken</b></td>
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="20%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Midterm</b></td>
                            <td width="20%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Final Term</b></td>
                        @elseif($x == 2)
                            <td width="20%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Midterm</b></td>
                            <td width="20%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Final Term</b></td>
                        @endif
                    </tr>
                    <tr>
                        <td class="p-0" style="text-align: left;"><b>&nbsp;&nbsp;Core Subjects</b></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                        <tr>
                            <td class="align-middle" style="font-size: 9px!important; padding-left: 20px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="p-0" style="text-align: left;"><b>&nbsp;&nbsp;Applied Subjects</b></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                        <tr>
                            <td class="align-middle" style="font-size: 9px!important; padding-left: 20px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
                    </tr> --}}
                    <tr>
                        <td class="p-0" style="text-align: left;"><b>&nbsp;&nbsp;Specialized Subjects</b></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                        <tr>
                            <td class="align-middle" style="font-size: 9px!important; padding-left: 20px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="p-0 text-left" style="font-size: 11px!important;">&nbsp;Homeroom Guidance</td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left" style="font-size: 11px!important;">&nbsp;Co-curricular</td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center"></td>
                    </tr>
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td class="p-0 text-left" style="font-size: 11px!important;">&nbsp;<b>General Average</b></td>
                        <td class="p-0"></td>
                        <td class="p-0"></td>
                        <td class="text-center" style="font-weight: bold; font-size: 9px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                        <td class="text-center align-middle" style="font-size: 8px!important;"><b>{{isset($item->actiontaken) ? $item->actiontaken:''}}</b></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left" style="font-size: 11px!important;">&nbsp;<b>Grading Plan Used</b></td>
                        <td class="p-0 text-center" colspan="4"><b>AVERAGING</b></td>
                    </tr>
                </table>
                @php
                    $secondsem = collect($attendance_setup)->where('semid', $x);
                    $width = count($secondsem) != 0? 55 / count($secondsem) : 0;
                @endphp
                <table width="100%" class="table table-bordered table-sm grades mb-0" style="table-layout: fixed;">
                    <tr>
                        <td class="p-0" width="38%" style="border: 1px solid #000; text-align: center;"></td>
                        @foreach (collect($attendance_setup)->where('semid',$x) as $item)
                            <td class="text-center align-middle p-0" width="{{$width}}%"><span style="font-size: 11px!important"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</b></span></td>
                        @endforeach
                        <td class="text-center p-0" width="7%" style="vertical-align: middle; font-size: 12px!important;"><span><b>Total</b></span></td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of School Days</td>
                        @foreach (collect($attendance_setup)->where('semid',$x) as $item)
                            <td class="p-0 text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of Days Present</td>
                        @foreach (collect($attendance_setup)->where('semid',$x) as $item)
                            <td class="p-0 text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle" >{{collect($attendance_setup)->where('semid', $x)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of Days Tardy</td>
                        @foreach (collect($attendance_setup)->where('semid',$x) as $item)
                            <td class="p-0 text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle" >{{collect($attendance_setup)->where('semid', $x)->sum('absent')}}</td>
                    </tr>
                </table>
                <table class="table" width="100%" style="font-size: 12px; margin-top: 30px;">
                    <tr>
                        <td width="38%" class="p-0 text-left">&nbsp;Eligible admission to:</td>
                        <td width="20%" class="p-0 text-left border-bottom">&nbsp;</td>
                        <td width="42%" class="p-0 text-left"></td>
                    </tr>
                </table>
                <table class="table" width="100%" style="font-size: 12px; margin-top: 35px;">
                    <tr>
                        <td width="30%" class="p-0 text-left"><b><u>Cathy R. Balaba</u></b></td>
                        <td width="40%" class="p-0 text-left">&nbsp;</td>
                        <td width="30%" class="p-0 text-right"><b><u>MS. MERLINA BRITOS</u></b></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left">Class Adviser</td>
                        <td class="p-0 text-left">&nbsp;</td>
                        <td class="p-0 text-right">Academic Head</td>
                    </tr>
                </table>
                <table class="table" width="100%" style="font-size: 12px; margin-top: 5px;">
                    <tr>
                        <td width="30%" class="p-0 text-left"></td>
                        <td width="40%" class="p-0 text-center"><b><u>MS. ANN B. NERI</u></b></td>
                        <td width="30%" class="p-0 text-center"></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center">School Director</td>
                        <td class="p-0 text-center"></td>
                    </tr>
                </table>
            </td> 
        </tr>
    </table>
</body>
</html>