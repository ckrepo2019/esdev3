<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <link href="{{asset('assets/font/ariblk.ttf')}}" rel="stylesheet" />
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
        @page { size: 14in 8.5in; margin: 50px 20px 0px 20px;  }
        
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
            <td width="50%" class="p-0" valign="top">
                
            </td>
            <td width="50%" class="p-0" valign="top">
                <div style="height: 90%; margin-left: 25px!important; border:3px solid #FF0000;">
                    <table width="100%" style="table-layout: fixed;">
                        <tr>
                            <td class="text-center" style="font-size: 22px!important; padding-top: 10px;"><b>SACRED HEART OF JESUS MONTESSORI SCHOOL</b></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="font-size: 22px!important; padding-top: 10px;">J.R Borja Extension, Gusa, Cagayan de Oro City</td>
                        </tr>
                        <tr>
                            <td class="text-center" style="font-size: 20px!important; padding-top: 10px;">
                                <div style="padding-top: 5px;"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="140px" height="130px"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="font-size: 27px!important; padding-top: 50px;"><b>CASA REPORT CARD</b></td>
                        </tr>
                    </table>
                    <table width="100%" style="table-layout: fixed; padding: 60px 70px 0px 70px; font-size: 18px;">
                        <tr>
                            <td width="27%">Name:</td>
                            <td width="73%" class="" style="border: 3px solid #000;">{{$student->student}}</td>
                        </tr>
                    </table>
                    <table width="100%" style="table-layout: fixed; padding: 20px 70px 0px 70px; font-size: 18px;">
                        <tr>
                            <td width="27%">LRN:</td>
                            <td width="73%" class="" style="border: 3px solid #000;">{{$student->lrn}}</td>
                        </tr>
                    </table>
                    <table width="100%" style="table-layout: fixed; padding: 20px 70px 0px 70px; font-size: 18px;">
                        <tr>
                            <td width="55%">Section and Grade Level:</td>
                            <td width="45%" class="" style="border: 3px solid #000;">{{$student->sectionname}} - {{str_replace('GRADE', '', $student->levelname)}}</td>
                        </tr>
                    </table>
                    <table width="100%" style="table-layout: fixed; padding: 20px 70px 0px 70px; font-size: 18px;">
                        <tr>
                            <td width="27%">School Year:</td>
                            <td width="73%" class="" style="border: 3px solid #000;">{{$schoolyear->sydesc}}</td>
                        </tr>
                    </table>
                    <table width="100%" style="table-layout: fixed; padding: 20px 70px 0px 70px; font-size: 18px;">
                        <tr>
                            <td width="27%">Adviser:</td>
                            <td width="73%" class="" style="border: 3px solid #000;">{{$student->student}}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" style="table-layout: fixed; margin-top: 40px;">
        <tr>
            <td width="50%" class="p-0" valign="top">
                <div style="height: 90%; margin-right: 25px!important;">
                    <table width="100%" class="table table-sm" style="table-layout: fixed; border: 2px solid #000;">
                        <tr>
                            <td class="p-0">
                                <table width="100%" class="table table-sm table-bordered grades mb-0" style="table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <td class="p-0 align-middle text-center" width="44%" style="font-size: 15px!important;"><b>Subjects</b></td>
                                            <td class="p-0 text-center align-middle" width="14%" style="font-size: 15px!important;">First <br> Trimester</td>
                                            <td class="p-0 text-center align-middle" width="14%" style="font-size: 15px!important;">Second <br> Trimester</td>
                                            <td class="p-0 text-center align-middle" width="14%" style="font-size: 15px!important;">Third <br> Trimester</td>
                                            <td class="p-0 text-center align-middle" width="14%" style="font-size: 15px!important;">Final <br> Trimester</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($studgrades as $item)
                                            <tr>
                                                <td class="p-0" style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 13px!important;">&nbsp;{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                <td class="p-0 text-center align-middle"></td>
                                                {{-- <td class="p-0 text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td> --}}
                                            </tr>
                                        @endforeach
                                        {{-- <tr>
                                            <td class="p-0 text-left" style="font-size: 12px!important;">&nbsp;<b>General Average</b></td>
                                            <td class="text-center p-0"><b></b></td>
                                            <td class="text-center p-0"><b></b></td>
                                            <td class="text-center p-0"><b></b></td>
                                            <td class="text-center p-0"><b></b></td>
                                            <td class="text-center p-0 {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                                            <td class="text-center align-middle p-0" ><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                                <table class="table table-bordered grades mb-0"  width="100%" style="table-layout: fixed; margin-top: 20px;">
                                    <tr>
                                        {{-- <td rowspan="2" width="18%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Core Values</b></td> --}}
                                        <td rowspan="2" class="text-center align-middle p-0" style="font-size: 15px!important;"><b>Patterns of Learning Behavior, <br> Attitude and Interaction</b></td>
                                        <td width="14%" class="text-center p-0" style="font-size: 15px!important;">First</td>
                                        <td width="14%" class="text-center p-0" style="font-size: 15px!important;">Second</td>
                                        <td width="14%" class="text-center p-0" style="font-size: 15px!important;">Third</td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle text-center p-0" style="font-size: 15px!important;">Trimester</td>
                                        <td class="align-middle text-center p-0" style="font-size: 15px!important;">Trimester</td>
                                        <td class="align-middle text-center p-0" style="font-size: 15px!important;">Trimester</td>
                                    </tr>
                                    @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                        @php
                                            $count = 0;
                                            $counter = 0;
                                        @endphp
                                        @foreach ($groupitem as $item)
                                            @php
                                                $counter++;
                                            @endphp
                                            @if($item->value == 0)
                                            @else
                                                <tr>
                                                    {{-- @if($count == 0)
                                                            <td width="14%" class="text-left p-0" style="font-size: 12px; vertical-align: top; padding-top: 5px!important; padding-bottom: 5px!important;" rowspan="{{count($groupitem)}}"><b>&nbsp;&nbsp;{{$item->group}}</b></td>
                                                            @php
                                                                $count = 1;
                                                            @endphp
                                                    @endif --}}
                                                    {{-- <td width="8%" class="text-center p-0">
                                                        {{$counter}}
                                                    </td> --}}
                                                    {{-- <td class="align-middle" style="font-size: 10px;"><b>{{$item->group}}</b></td> --}}
                                                    <td width="58%"  class="align-middle p-0" style="font-size: 13px!important;padding-left: 3px!important;">{{$item->description}}</td>
                                                    <td class="text-center p-0 align-middle" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                                        @foreach ($rv as $key=>$rvitem)
                                                            {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                        @endforeach 
                                                    </td>
                                                    <td class="text-center align-middle p-0" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                                        @foreach ($rv as $key=>$rvitem)
                                                            {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                        @endforeach 
                                                    </td>
                                                    <td class="text-center align-middle p-0" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                                        @foreach ($rv as $key=>$rvitem)
                                                            {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                                        @endforeach 
                                                    </td>
                                                    {{-- <td class="text-center align-middle p-0" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                                        @foreach ($rv as $key=>$rvitem)
                                                            {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                                        @endforeach 
                                                    </td> --}}
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; margin-top: 20px; border: 1px solid #000; font-size: 13px;">
                                    <tr>
                                        <td class="text-left p-0" style="font-size: 15px!important;">&nbsp;<b>Comments:</b></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left p-0">&nbsp;First Trimester:</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left p-0">&nbsp;Second Trimester:</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left p-0">&nbsp;Third Trimester:</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left p-0">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td width="50%" class="p-0" valign="top">
                <div style="height: 90%; margin-left: 25px!important;">
                    <table width="100%" class="table table-sm" style="table-layout: fixed; border: 2px solid #000;">
                        <tr>
                            <td class="p-0">
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; font-size: 15px; border: 1px solid #000;">
                                    <tr>
                                        <td class="text-left p-0">&nbsp;<b>Letter Grade and It's Equivalent</b></td>
                                    </tr>
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; border: 1px solid #000; font-size: 13px; margin-top: -1px;">
                                    <tr>
                                        <td width="55%" class="text-left p-0" style="padding-top: 2px!important;">&nbsp;O+ - Exceptionally Mastered Skills (97.50-100)</td>
                                        <td width="45%" class="text-left p-0" style="padding-top: 2px!important;">&nbsp;S- Satisfactory (83.50-86.45)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left p-0" style="padding-top: 4px!important;">&nbsp;O - Excellent (94.50-97.45)</td>
                                        <td class="text-left p-0" style="padding-top: 4px!important;">&nbsp;MS - Moderate Satisfactory (79.50-83.45)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left p-0" style="padding-top: 4px!important;">&nbsp;O- Outstanding (89.50-94.45)</td>
                                        <td class="text-left p-0" style="padding-top: 4px!important;">&nbsp;NI- Needs Improvement (79.45 below)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left p-0" style="padding-top: 4px!important;">&nbsp;VS - Very Satisfactory (86.50-89.45)</td>
                                        <td class="text-left p-0" style="padding-top: 4px!important;">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; border: 1px solid #000; margin-top: -1px;">
                                        @if (count($attendance_setup) > 0)
                                            @php
                                            $attendance_setup1 = collect($attendance_setup)->split(2)->first();
                                            $attendance_setup2 = collect($attendance_setup)->split(2)->last();

                                            $count_attendance_setup1 = count($attendance_setup1);
                                            $count_attendance_setup2 = count($attendance_setup2);
                                        @endphp
                                        <tr>
                                            <td width="55%" class="text-left p-0">
                                                <table class="table table-bordered mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; border: 1px solid #000;">
                                                    <tr>
                                                        <td colspan="2" class="text-left align-middle" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px;"><b>ATTENDANCE</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No of School Days</td>
                                                        <td width="19%" class="text-center align-middle" style="font-size: 12px;"><b>Present</b></td>
                                                        <td width="15%" class="text-center align-middle" style="font-size: 12px;"><b>Tardy</b></td>
                                                    </tr>
                                                    @foreach ($attendance_setup1 as $item)
                                                        <tr>
                                                            <td width="54%" class="text-left align-middle"><span style="font-size: 13px!important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM')}}</span></td>
                                                            <td width="12%" class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                                            <td class="text-center"></td>
                                                            <td class="text-center"></td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="4" class="text-center" style="font-size: 15px;"><b>&nbsp;</b></td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="45%" class="text-left p-0">
                                                <table class="table table-bordered mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; border: 1px solid #000;">
                                                    <tr>
                                                        <td colspan="2" class="text-left align-middle" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                                        <td width="19%" class="text-left align-middle" style="font-size: 12px;"><b>Present</b></td>
                                                        <td width="15%" class="text-left align-middle" style="font-size: 12px;"><b>Tardy</b></td>
                                                    </tr>
                                                    @if (count($attendance_setup) == 1)
                                                        <tr>
                                                            <td class="text-center" style="font-size: 13px;">&nbsp;</td>
                                                            <td class="text-left"></td>
                                                            <td class="text-left"></td>
                                                            <td class="text-left"></td>
                                                        </tr>
                                                    @else
                                                        @foreach ($attendance_setup2 as $item)
                                                            <tr>
                                                                <td width="54%" class="text-left align-middle"><span style="font-size: 13px!important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM')}}</span></td>
                                                                <td width="12%" class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                                                <td class="text-left"></td>
                                                                <td class="text-left"></td>
                                                            </tr>
                                                        @endforeach
                                                        @if ($count_attendance_setup1 > $count_attendance_setup2)
                                                            <tr>
                                                                <td class="text-center" style="font-size: 13px;">&nbsp;</td>
                                                                <td class="text-left"></td>
                                                                <td class="text-left"></td>
                                                                <td class="text-left"></td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                    <tr>
                                                        <td class="text-center" style="font-size: 15px;"><b>TOTAL:</b></td>
                                                        <td class="text-left"></td>
                                                        <td class="text-left"></td>
                                                        <td class="text-left"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        @else
                                            <tr>
                                                <td width="55%" class="text-left p-0">
                                                    <table class="table table-bordered mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; border: 1px solid #000;">
                                                        <tr>
                                                            <td colspan="2" class="text-left align-middle" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px;"><b>ATTENDANCE</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No of School Days</td>
                                                            <td width="19%" class="text-center align-middle" style="font-size: 12px;"><b>Present</b></td>
                                                            <td width="15%" class="text-center align-middle" style="font-size: 12px;"><b>Tardy</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="54%" class="text-left align-middle"><span style="font-size: 13px!important"></span></td>
                                                            <td width="12%" class="text-center align-middle"></td>
                                                            <td class="text-center"></td>
                                                            <td class="text-center"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="text-center" style="font-size: 15px;"><b>&nbsp;</b></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="45%" class="text-left p-0">
                                                    <table class="table table-bordered mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; border: 1px solid #000;">
                                                        <tr>
                                                            <td colspan="2" class="text-left align-middle" style="font-size: 12px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                                            <td width="19%" class="text-left align-middle" style="font-size: 12px;"><b>Present</b></td>
                                                            <td width="15%" class="text-left align-middle" style="font-size: 12px;"><b>Tardy</b></td>
                                                        </tr>
                                                        
                                                            <tr>
                                                                <td width="54%" class="text-left align-middle"><span style="font-size: 13px!important"></span></td>
                                                                <td width="12%" class="text-center align-middle"></td>
                                                                <td class="text-left"></td>
                                                                <td class="text-left"></td>
                                                            </tr>
                                                        <tr>
                                                            <td class="text-center" style="font-size: 15px;"><b>TOTAL:</b></td>
                                                            <td class="text-left"></td>
                                                            <td class="text-left"></td>
                                                            <td class="text-left"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; border-top: 1px solid #000; font-size: 13px; margin-top: -1px;">
                                    <tr>
                                        <td width="100%" class="text-center p-0" style="padding-top: 2px!important; font-size: 15px; padding: 20px 10px 10px 10px;"><b>SIGNATURE OF PARENT OR GUARDIAN</b></td>
                                    </tr>
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; padding-right: 95px; margin-top: 20px;">
                                    <tr>
                                        <td width="25%" class="text-left p-0" style="">&nbsp;First Trimester:</td>
                                        <td width="75%" class="text-left p-0 border-bottom" style="">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; padding-right: 95px; margin-top: 5px;">
                                    <tr>
                                        <td width="25%" class="text-left p-0" style="">&nbsp;Second Trimester:</td>
                                        <td width="75%" class="text-left p-0 border-bottom" style="">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; padding-right: 95px; margin-top: 5px;">
                                    <tr>
                                        <td width="25%" class="text-left p-0" style="">&nbsp;Third Trimester:</td>
                                        <td width="75%" class="text-left p-0 border-bottom" style="">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; margin-top: 10px;">
                                    <tr>
                                        <td width="100%" class="text-center p-0" style="padding-top: 2px!important; font-size: 15px; padding: 30px 10px 10px 10px;"><b>CANCELLATION OF TRANSFER ELIGIBILITY</b></td>
                                    </tr>
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; padding-right: 120px; margin-top: 20px;">
                                    <tr>
                                        <td width="30%" class="text-left p-0" style="">&nbsp;Eligible to be admitted to</td>
                                        <td width="70%" class="text-left p-0 border-bottom" style="">&nbsp;</td>
                                    </tr>
                                </table>
                                <table class="table mb-0"  width="100%" style="table-layout: fixed; font-size: 13px; margin-top: 80px;">
                                    <tr>
                                        <td width="30%" class="text-center p-0 border-bottom" style="">{{$adviser}}</td>
                                        <td width="5%" class="text-left p-0" style=""></td>
                                        <td width="30%" class="text-center p-0 border-bottom" style="">ANN B. NERI, PhD</td>
                                        <td width="5%" class="text-left p-0" style=""></td>
                                        <td width="30%" class="text-center p-0 border-bottom" style="">MERLINA B. BRITOS</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center p-0" style="">Class Adviser</td>
                                        <td class="text-left p-0" style=""></td>
                                        <td class="text-center p-0" style="">School Director</td>
                                        <td class="text-left p-0" style=""></td>
                                        <td class="text-center p-0" style="">Academic Head</td>
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