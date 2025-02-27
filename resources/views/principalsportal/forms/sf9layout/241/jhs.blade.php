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
            transform-origin: 8 15;
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
        @page { size: 5.5in 8.5in; margin: 10px 30px; } 
        
    </style>
</head>
<body style="">
    <table class="table table-sm mb-0" style="table-layout: fixed;">
        <tr>
            <td width="100%" style="">
                <div style="background-color: #fd84a1ec; border-radius: 15px; box-shadow: 5px 5px #000000 !important; border: 1px solid rgb(250, 241, 189);">
                    <table class="table table-sm mb-0" style="table-layout: fixed; padding-bottom: 4px!important;">
                        <tr>
                            <td width="30%" class="text-right p-0" style="vertical-align: top;">
                                <img style="padding-top: 5px;" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                            </td>
                            <td width="45%" class="text-center" style="font-size: 18px; vertical-align: middle; font-family: cursive; font-style: italic; font-weight: 600;">
                                {{-- <div>{{$schoolinfo[0]->schoolname}}</div>
                                <div>{{$schoolinfo[0]->address}}</div> --}}
                                <div>Claret School of Maluso</div>
                                <div>Maluso, Basilan</div>
                            </td>
                            <td width="25%" style=""></td>
                        </tr>
                    </table>
                </div>
                <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                    <tr>
                        <td class="text-center p-0" style="font-size: 15px;"><b>SECONDARY EDUCATION REPORT CARD</b></td>
                    </tr>
                    <tr>
                        <td class="text-center p-0" style="font-size: 13px;"><b>SY: {{$schoolyear->sydesc}}</b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 5px!important;">
                    <tr>
                        <td width="11%" class="text-left p-0" style="font-size: 13px;"><b>Name:</b></td>
                        <td width="76%" class="text-left p-0" style="font-size: 14px;"><b><u>{{$student->student}}</u></b></td>
                        <td width="8%" class="text-left p-0" style="font-size: 13px;"><b>Age: </b></td>
                        <td width="5%" class="text-left p-0" style="font-size: 14px;"><b><u>{{$student->age}}</u></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 5px!important;">
                    <tr>
                        <td width="28%" class="text-left p-0" style="font-size: 13px;"><b>Grade & Section:</b></td>
                        <td width="60%" class="text-left p-0" style="font-size: 14px;"><b><u>{{str_replace('GRADE', '', $student->levelname)}} - {{$student->sectionname}}</u></b></td>
                        <td width="7%" class="text-left p-0" style="font-size: 13px;"><b>Sex: </b></td>
                        <td width="5%" class="text-left p-0" style="font-size: 14px;"><b><u>{{$student->gender[0]}}</u></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 5px!important;">
                    <tr>
                        <td width="15%" class="text-left p-0" style="font-size: 13px;"><b>LRN No:</b></td>
                        <td width="85%" class="text-left p-0" style="font-size: 14px;"><b><u>{{$student->lrn}}</u></b></td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered grades" width="100%" style="padding-top: 10px;">
                    <thead>
                        <tr style="font-size: 10px!important;">
                            <td rowspan="2"  class="align-middle text-center" width="36%" style="vertical-align: top!important;">Subjects <br> (Larangan ng <br> Pag-aaral)</td>
                            <td colspan="5"  class="text-center align-middle"><b>Ratings (Markahan)</b></td>
                            <td rowspan="2"  class="text-center align-middle" width="18%" >Action <br> Taken <br> (Pasiya)</td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="8.5%"><b>1</b></td>
                            <td class="text-center align-middle" width="8.5%"><b>2</b></td>
                            <td class="text-center align-middle" width="8.5%"><b>3</b></td>
                            <td class="text-center align-middle" width="8.5%"><b>4</b></td>
                            <td class="text-center align-middle" width="12%" style="font-size: 7px!important;"><b>Final <br> (HulingMarka)</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 11px!important;" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
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
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 10px!important;">
                    <tr>
                        <td width="25%" class="text-left p-0" style="font-size: 11px;"><b>Grading System:</b></td>
                        <td width="75%" class="text-left p-0" style="font-size: 11px;"><b>AVERAGING</b></td>
                    </tr>
                </table>
                <table style="width: 100%; margin-top: 10px;">
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
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 5px!important;">
                    <tr>
                        <td width="12%" class="text-left p-0" style="font-size: 11px;"><b>Adviser:</b></td>
                        <td width="88%" class="text-left p-0" style="font-size: 12px; color: rgb(13, 99, 219);"><b><u>{{$adviser}}</u></b></td>
                    </tr>
                </table>
                <table class="table table-sm mb-0" style="table-layout: fixed; padding-top: 2px!important;">
                    <tr>
                        <td colspan="3" class="text-left p-0" style="font-size: 9px;"><b>Learner Progress and Achievement</b></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 9px;"><b>Description</b></td>
                        <td width="30%" class="text-left p-0" style="font-size: 9px;"><b>Grading Scale</b></td>
                        <td width="9%" class="text-left p-0" style="font-size: 9px;"><b>Remarks</b></td>
                        <td width="11%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 9px;">Outstanding</td>
                        <td width="30%" class="text-left p-0" style="font-size: 9px;">90-100</td>
                        <td width="9%" class="text-left p-0" style="font-size: 9px;">Passed</td>
                        <td width="11%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 9px;">Very Satisfactory</td>
                        <td width="30%" class="text-left p-0" style="font-size: 9px;">85-89</td>
                        <td width="9%" class="text-left p-0" style="font-size: 9px;">Passed</td>
                        <td width="11%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 9px;">Satisfactory</td>
                        <td width="30%" class="text-left p-0" style="font-size: 9px;">80-84</td>
                        <td width="9%" class="text-left p-0" style="font-size: 9px;">Passed</td>
                        <td width="9%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 9px;">Fairly Satisfactory</td>
                        <td width="30%" class="text-left p-0" style="font-size: 9px;">75-79</td>
                        <td width="9%" class="text-left p-0" style="font-size: 9px;">Passed</td>
                        <td width="11%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 9px;">Did Not Meet Expectations</td>
                        <td width="30%" class="text-left p-0" style="font-size: 9px;">Below 75</td>
                        <td width="9%" class="text-left p-0" style="font-size: 9px;">Failed</td>
                        <td width="11%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="30%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="9%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="11%" class="text-left p-0" style="font-size: 9px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table table-sm mb-0"  style="table-layout: fixed; padding-top: 10px!important;">
        <tr>
            <td width="100%" style="">
                <table class="table table-sm mb-0" style="table-layout: fixed;">
                    <tr>
                        <td width="100%" class="text-center" style="font-size: 13px;"><b>REPORT ON LEARNER'S OBSERVED VALUES</b></td>
                    </tr>
                </table>
                <table class="table-sm table table-bordered mb-0 mt-0" width="100%">
                    <tr>
                        <td rowspan="2" class="align-middle text-left"><b>Core Values</b></td>
                        <td rowspan="2" class="align-middle text-center"><b>Behavior Statement</b></td>
                        <td colspan="4" class="align-middle text-center"><b>Quarter</b></td>
                    </tr>
                    <tr>
                        <td class="text-center" width="7%"><center>1st</center></td>
                        <td class="text-center" width="7%"><center>2nd</center></td>
                        <td class="text-center" width="7%"><center>3rd</center></td>
                        <td class="text-center" width="7%"><center>4th</center></td>
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
                                            <td width="28%" class="text-left align-middle" style="font-size: 10.5px;" rowspan="{{count($groupitem)}}">&nbsp;&nbsp;{{$item->group}}&nbsp;&nbsp;&nbsp;</td>
                                            @php
                                                $count = 1;
                                            @endphp
                                    @endif
                                    <td class="p-0" width="44%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">{{$item->description}}</td>
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
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 5px;">
                    <tr>
                        <td class="text-left"><b>Observed Values</b></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="">
                    <tr>
                        <td width="5%" class="p-0"></td>
                        <td width="15%" class="p-0 text-center"><b>Marking</b></td>
                        <td width="15%" class="p-0"></td>
                        <td width="30%" class="p-0"><b>Non-numerical Rating</b></td>
                        <td width="35%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="5%" class="p-0"></td>
                        <td width="15%" class="p-0 text-center"><b>AO</b></td>
                        <td width="15%" class="p-0"></td>
                        <td width="30%" class="p-0">Always Observed</td>
                        <td width="35%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="5%" class="p-0"></td>
                        <td width="15%" class="p-0 text-center"><b>SO</b></td>
                        <td width="15%" class="p-0"></td>
                        <td width="30%" class="p-0">Sometimes Observed</td>
                        <td width="35%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="5%" class="p-0"></td>
                        <td width="15%" class="p-0 text-center"><b>RO</b></td>
                        <td width="15%" class="p-0"></td>
                        <td width="30%" class="p-0">Rarely Observed</td>
                        <td width="35%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="5%" class="p-0"></td>
                        <td width="15%" class="p-0 text-center"><b>NO</b></td>
                        <td width="15%" class="p-0"></td>
                        <td width="30%" class="p-0">Not Observed</td>
                        <td width="35%" class="p-0"></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 10px;">
                    <tr>
                        <td class="text-center p-0" style=""><b>TO THE PARENT OR GUARDIAN</b></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 5px;">
                    <tr>
                        <td class="p-0 text-left"><b>Dear Parent:</b></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This report card shows the ability and progress your child has made inthe different learning areas as well as his/ her core values.</b></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-left"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The school welcomes you, should desire to know more about your Child's progress.</b></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 10px;">
                    <tr>
                        <td class="p-0 text-center" style=""><b>Parent's/ Guardian's Signature</b></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="">
                    <tr>
                        <td width="8%" class="p-0 text-left" style=""><b>1.</b></td>
                        <td width="92%" class="p-0 text-left" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 5px;">
                    <tr>
                        <td width="8%" class="p-0 text-left" style=""><b>2.</b></td>
                        <td width="92%" class="p-0 text-left" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 5px;">
                    <tr>
                        <td width="8%" class="p-0 text-left" style=""><b>3.</b></td>
                        <td width="92%" class="p-0 text-left" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 5px;">
                    <tr>
                        <td width="8%" class="p-0 text-left" style=""><b>4.</b></td>
                        <td width="92%" class="p-0 text-left" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0" style="margin-top: 10px;">
                    <tr>
                        <td class="p-0 text-center" style=""><b>Certificate to Transfer</b></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0 mt-0" style="margin-top: 3px;">
                    <tr>
                        <td width="24%" class="p-0 text-left" style="">Admitted to Grade:</td>
                        <td width="30%" class="p-0 text-left" style="border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="11%" class="p-0 text-center" style="">Section:</td>
                        <td width="35%" class="p-0 text-left" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0" style="margin-top: 3px;">
                    <tr>
                        <td width="41%" class="p-0 text-left" style="">Eligibility for Admission to Grade:</td>
                        <td width="59%" class="p-0 text-left" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0" style="margin-top: 10px;">
                    <tr>
                        <td class="p-0 text-center" style=""><b>Cancellation of Eligibility to Transfer</b></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0" style="margin-top: 2px;">
                    <tr>
                        <td width="15%" class="p-0 text-left" style="">Admitted in:</td>
                        <td width="40%" class="p-0 text-left" style="border-bottom: 1px solid #000;"></td>
                        <td width="45%" class="p-0 text-left" style=""></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0" style="">
                    <tr>
                        <td width="7%" class="p-0 text-left" style="">Date:</td>
                        <td width="48%" class="p-0 text-left" style="border-bottom: 1px solid #000;"></td>
                        <td width="45%" class="p-0 text-left" style=""></td>
                    </tr>
                </table>
                <table width="100%" class="table-sm mb-0" style="margin-top: 25px;">
                    <tr>
                        <td width="20%" class="p-0 text-left" style=""></td>
                        <td width="60%" class="p-0 text-left" style="border-bottom: 1px solid #000;">&nbsp;</td>
                        <td width="20%" class="p-0 text-left" style=""></td>
                    </tr>
                    <tr>
                        <td width="20%" class="p-0 text-left" style=""></td>
                        <td width="60%" class="p-0 text-center" style="font-size: 12px;"><b>Director/Principal</b></td>
                        <td width="20%" class="p-0 text-left" style=""></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>