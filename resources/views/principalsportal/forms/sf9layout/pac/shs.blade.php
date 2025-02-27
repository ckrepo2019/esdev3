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
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 9pt;
        }
        p{
            margin: 0;
        }
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 8pt !important;
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
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
            
        }
        .font{
            font-size: 8pt!important;
        }
        .small-font{
            font-size: 7pt!important;
        }
        .smaller-font{
            font-size: 6pt!important;
        }
        .underline{
            border-bottom: 1px solid black;
        }
        h4{
            margin-bottom: 10px;
        }
        .spacing{
            padding: 5px;
        }
        .title{
            font-size: 11pt!important;
            
        }
        .first-page{
            font-size: 10pt!important;
        }
        .second-page{
            font-size: 9pt;
        }
        .compress-2nd{
            margin-right: .3in;
            margin-left: .3in;
        }
        .compress{
            margin-right: .6in;
            margin-left: .6in;
        }
        .compressed{
            margin-right: 1in;
            margin-left: 1in;
        }
        .times-new{
            font-family: 'Times New Roman', Times, serif;
        }
        .subject-type{
            background-color: rgb(207, 232, 240)
        }
        .linespacing{
            line-height: 14px;
        }
		 .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }
        @page { size: 13in 8.5in; margin: .in .2in;}
    </style>
</head>
<body>
    <table width="100%" style="page-break-after: always ">
        <tr>
            <td width="50%" class="first-page" style="vertical-align: top">
                <table width="100%" class="text-center times-new title" style="margin-top: 1.5in;">
                    <tr>
                        <td width="100%"><b>REPORT ON ATTENDANCE</td>
                    </tr>
                </table>
                @php
                $width = count($attendance_setup) != 0? 74 / count($attendance_setup) : 0;
            @endphp
            <table width="100%" class="table table-bordered table-sm grades mb-0 compress" style="table-layout: fixed; margin-top:.25in" >
                <tr>
                    <td width="14%" style="border: 1px solid #000; text-align: center; height: 20px;"></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle " width="{{$width}}%"><span class="smaller-font" style="">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                    @endforeach
                    <td class="text-center" width="7%" style="vertical-align: middle;"><span>Total</span></td>
                </tr>
                <tr class="table-bordered">
                    <td style=""><i>No. of School<br> Days</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style="">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle" style="">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style=""><i>No. of days <br>Present</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style="">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle"  style="">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                {{-- <tr class="table-bordered">
                    <td style=""><i>No. of Times<br> Tardy</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style="">{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" style="">{{collect($attendance_setup)->sum('absent')}}</td>
                </tr> --}}
                <tr class="table-bordered">
                    <td style=""><i>No. of days<br> Absent</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style=""></td>
                    @endforeach
                    <td class="text-center align-middle"  style=""></td>
                </tr>
            </table>
            <table width="100%" class="text-center times-new title" style="margin-top: 1.5in;">
                <tr>
                    <td width="100%"><b>PARENT/GUARDIAN’S SIGNATURE</td>
                </tr>
            </table>
            <table width="100%" class="compressed"  style="margin-top: .7in;">
                <tr>
                    <td width="25%">1<sup>st</sup> Quarter</td>
                    <td width="75%" class="underline"></td>
                </tr>
                <tr>
                    <td width="25%">2<sup>nd</sup> Quarter</td>
                    <td width="75%" class="underline"></td>
                </tr>
                <tr>
                    <td width="25%">3<sup>rd</sup> Quarter</td>
                    <td width="75%" class="underline"></td>
                </tr>
                <tr>
                    <td width="25%">4<sup>th</sup> Quarter</td>
                    <td width="75%" class="underline"></td>
                </tr>
            </table>
            </td>
            <td width="50%" class="first-page" style="vertical-align: top">
                <table width="100%" style="margin-top: .2in">
                    <tr>
                        <td width="20%" class="text-right align-top" style="">
                            <div><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></div>
                            <div class="font text-center" style="padding-left: 35px">FORM 138-E</div>
                        </td>
                        <td width="60%" class="text-center p-0" style="padding-top: 22px!important">
                            <div class="times-new title"><b>PHILIPPINE ADVENT COLLEGE ACADEMY</div>
                            <div class="font"><i>Sindangan, Zamboanga del Norte</div>
                            <div class="font"><i>Philippines</div>
                            <div style="font-size: 9pt;margin-top:7px">"The School that Prepares Students to Serve."</div>
                        </td>
                        <td width="20%"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center">
                            <div class="smaller-font"><i>Association of Christian Schools, Colleges and Universities of the Phil's. (ACSCU)</div>
                            <div class="smaller-font"><i>Fund for Assistance to Private Education (FAPE)</div>
                            <div class="smaller-font"><i>Zamboanga, Basilan, Sulu, Tawi-Tawi, Association of Private Schools (ZAMBASULTAPS)</div>
                            <div class="smaller-font"><i>Tel. No. (065) 224-2700/224-2038 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	E-mail add: philippineadventcollege@gmail.com</div>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <table width="100%" class="text-center ">
                    <tr>
                        <td width="100%" class="title times-new" style="margin-top: 20px!important"><b style="color: rgb(39, 39, 107)">SENIOR HIGH SCHOOL</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" class="text-center" style="font-size: 9pt!important;margin: 0in 2in">
                                <tr>
                                    <td width="10%"><i>Track</td>
                                    <td width="7%" class="underline"><b>{{str_replace('Track', '',$strandInfo->trackname)}}</td>
                                    <td width="10%"><i>Strand</td>
                                    <td width="10%" class="underline"><b>{{$strandInfo->strandcode}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="compress" style="margin-top: 20px">
                    <tr>
                        <td width="20%">LRN:</td>
                        <td width="40%" class="underline">{{$student->lrn}}</td>
                    </tr>
                    <tr> 
                        <td>Name:</td>
                        <td colspan="2" class="underline"><b>{{$student->student}}</td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td width="20%">Age:</td>
                        <td width="20%" class="underline">{{$student->age}}</td>
                        <td width="15%">Sex:</td>
                        <td width="45%" class="underline">{{$student->gender}}</td>
                    </tr>
                    <tr>
                        <td>Grade</td>
                        <td class="underline">{{str_replace('GRADE', '', $student->levelname)}}</td>
                        <td>Section:</td>
                        <td class="underline">{{$student->sectionname}}</td>
                    </tr>
                    <tr>
                        <td>School Year:</td>
                        <td class="underline">{{$schoolyear->sydesc}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <table width="100%" class="compress" style="margin-top: 20px">
                    <tr>
                        <td width="100%" style="padding-left: 35px"><i>Dear Parent,</td>
                    </tr>
                    <tr>
                        <td width="100%" style="padding-left: 60px"><i>This report card shows the ability and progress yourchild has made </td>
                    </tr>
                    <tr>
                        <td width="100%" style="padding-left: 60px"><i>in the different learning areas as well as his/her core values. </td>
                    </tr>
                    <tr>
                        <td width="100%" style="padding-left: 60px"><i>The school welcomes you should you desire to know more about your</td>
                    </tr>
                    <tr>
                        <td width="100%" style="padding-left: 60px">child’s progress.</td>
                    </tr>
                </table>
                <table width="100%" class="compress text-center" style="margin-top: 20px">
                    <tr>
                        <td width="50%"></td>
                        <td width="50%"><u><b>{{$adviser}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><i>Teacher</td>
                    </tr>
                </table>
                <table width="100%" class="compress text-center">
                    <tr>
                        <td width="50%"><u><b>{{$principal_info[0]->name}}</td>
                        <td width="50%"></td>
                    </tr>
                    <tr>
                        <td><i>{{$principal_info[0]->title}}</td>
                        <td></td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td width="100%" style="border-top: 1px solid blue; border-bottom: 1px solid blue"></td>
                    </tr>
                </table>
                <table width="100%" class="text-center" style="margin-top: 5px">
                    <tr>
                        <td class="title"><b>CERTIFICATE OF TRANSFER</td>
                    </tr>
                </table>
                <table width="100%" class="compress" style="margin-top: 20px">
                    <tr>
                        <td width="27%">Admitted to Grade:</td>
                        <td width="23%" class="underline"></td>
                        <td width="13%">Section:</td>
                        <td width="37%" class="underline"></td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td width="43%">Eligibility for Admission to Grade:</td>
                        <td width="57%" class="underline"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="margin-top: .25in!important">Approved:</td>
                    </tr>
                </table>
                <table width="100%" class="compress text-center" style="margin-top: .25in!important">
                    <tr>
                        <td width="33%" class="underline"><b>{{$principal_info[0]->name}}</td>
                        <td width="33%"></td>
                        <td width="33%" class="underline"><b>{{$adviser}}</td>
                    </tr>
                    <tr>
                        <td><i>{{$principal_info[0]->title}}</td>
                        <td></td>
                        <td><i>Teacher</td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td colspan="3" class="text-center"><b>Cancellation of Eligibility to Transfer</td>
                    </tr>
                    <tr>
                        <td width="33.3%">Admitted in:</td>
                        <td width="33.3%" class="underline"></td>
                        <td width="33.3%"></td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td class="underline"></td>
                        <td></td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td width="50%"></td>
                        <td width="50%" class="text-center"><u><b>{{$principal_info[0]->name}}s</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center">{{$principal_info[0]->title}}s</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="50%" style="vertical-align: top">
                <table width="100%" style="margin-top: .5in">
                    <tr>
                        <td class="title times-new text-center" style=""><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</td>
                    </tr>
                </table>
                @php
                $x = $semid;
                @endphp
                @for ($x=1; $x <= 2; $x++)
                <table class="text-left p-0" style="margin-top: {{$x == 2 ? '25px' : '20px'}}">
                    <tr>
                        <td class="text-left title" style=" padding-top:{{$x == 2 ? '0px' : '10px'}}!important; padding-bottom: 5px!important"><b>{{$x == 1 ? 'FIRST SEMESTER' : 'SECOND SEMESTER'}}</td>
                    </tr>
                </table>
                <table width="100%" class="table-bordered p-0 compress-2nd grades" style="padding-bottom: 0px!important; margin-bottom: 0px!important"> 
                    <tr>
                        <td class="align-top text-center" width="25%" rowspan="2" style="padding-top: 0px!important;"><b>Subjects</td>
                        <td class="text-center" colspan="2" style="padding: 5px!important"><b>QUARTER</td>
                        <td class="text-center" width="12%" rowspan="2" class="p-0" style="padding: 0px!important"><b>SEMESTER <br> FINAL GRADE</td>
                        {{-- <td class="text-center" rowspan="2" width="10%" style="padding: 0px!important"><b>REMARKS</td> --}}
                    </tr>
                    @if($x == 1)
                    <tr>
                        <td class="text-center p-0" width="4%"><b>1</td>
                        <td class="text-center p-0" width="4%"><b>2</td>
                    </tr>
                    @elseif($x==2)
                    <tr>
                        <td class="text-center p-0" width="4%"><b>3</td>
                        <td class="text-center p-0" width="4%"><b>4</td>
                    </tr>
                    @endif
                    <tr  class="subject-type">
                        <td ><b>Core Subjects</td>
                       <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                    
                    <tr>
                        <td class="p-0" style="padding-left: 5px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                        <td class="p-0 text-center align-middle" style="">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                        <td class="p-0 text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                        <td class="p-0 text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                        <td class="p-0 text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                        {{-- <td class="text-center align-middle p-0" style="">{{$item->actiontaken != null ? $item->actiontaken:''}}</td> --}}
                    </tr>
                    @endforeach
                    <tr  class="subject-type">
                        <td ><b>Applied Subjects</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                    
                    <tr>
                        <td class="p-0" style="padding-left: 5px!important">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                        <td class="p-0 text-center align-middle" style="">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                        <td class="p-0 text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                        <td class="p-0 text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                        <td class="p-0 text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                        {{-- <td class="text-center align-middle p-0" style="">{{$item->actiontaken != null ? $item->actiontaken:''}}</td> --}}
                    </tr>
                    @endforeach
                    <tr  class="subject-type">
                        <td ><b>Specialized Subjects</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                    <tr>
                        <td class="p-0" style="padding-left: 5px!important">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                        <td class="p-0 text-center align-middle" style="">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                        <td class="p-0 text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                        <td class="p-0 text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                        <td class="p-0 text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                        {{-- <td class="text-center align-middle p-0" style="">{{$item->actiontaken != null ? $item->actiontaken:''}}</td> --}}
                    </tr>
                    @endforeach
                    <tr  class="subject-type">
                        <td ><b>Other Subjects</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @if (collect($studgrades)->where('semid', $x)->count() < 12)
                    @for ($i = 0; $i < 12 - collect($studgrades)->where('semid', $x)->count(); $i++)
                    <tr>
                        <td class="p-0" style=""><span style="color: white">Scale</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endfor
                    @endif
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td colspan="3" class="p-0 text-right" style="border: 0;font-size: 10px!important; padding-right: 5px!important"><b>General Average for the Semester</b></td>
                        <td class="text-center align-middle  p-0" style="border-top:#FFF"><b>{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</b></td>
                        {{-- <td class="text-center"><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td> --}}
                    </tr>

                </table>
                @endfor
            </td>
            
            <td width="50%" style="vertical-align: top;">
                <table width="100%" class="" style="margin-top: .5in">
                <tr>
                        <td class="title times-new text-center"><b>REPORT ON LEARNER'S OBSERVED VALUES</td>
                    </tr>
                </table>
                <table class="table-sm table table-bordered compress-2nd"  width="100%" style="margin-top: 50px">
                    <thead>
                        <tr>
                            <th rowspan="2" width="23%" class="align-middle"><center >Core Values</center></th>
                            <th rowspan="2" width="45%" class="align-middle"><center>Behavior Statements</center></th>
                            <th colspan="4" class="cellRight"><center>Quarter</center></th>
                        </tr>
                        <tr>
                            <th width="8%"><center>1</center></th>
                            <th width="8%"><center>2</center></th>
                            <th width="8%"><center>3</center></th>
                            <th width="8%"><center>4</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                            @php
                                $count = 0;
                            @endphp
                        
                            @foreach ($groupitem as $item)
                                @if($item->value == 0)
                                        <tr>
                                            <th colspan="6">{{$item->description}}</th>
                                        </tr>
                                @else
                                        <tr>
                                            @if($count == 0)
                                                    <td class="align-middle text-center" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                                    @php
                                                        $count = 1;
                                                    @endphp
                                            @endif
                                            <td class="align-middle linespacing "><i>{{$item->description}}</td>
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
                        
                    </tbody>
                </table>
                <table width="100%" class="compress-2nd">
                    <tr>
                        <td width="100%" class="first-page"><b>Observed Values</td>
                    </tr>
                </table>
                <table width="70%" class="second-page" style="margin-top: 15px">
                    <tr>
                        <td width="40%" class="text-center"><b>Marking</td>
                        <td width="60%" class="text-center"><b>Non-numerical Rating</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i>AO</td>
                        <td  style="padding-left: 1in"><i>Always Observed</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i>SO</td>
                        <td  style="padding-left: 1in"><i>Sometimes Observed</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i>RO</td>
                        <td  style="padding-left: 1in"><i>Rarely Observed</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i>NO</td>
                        <td  style="padding-left: 1in"><i>Never Observed</td>
                    </tr>
                </table>
                <table width="100%" class="compress-2nd" style="margin-top: 20px">
                    <tr>
                        <td width="100%" class="second-page"><b>Learner Progress and Achievement</td>
                    </tr>
                </table>
                <table width="100%" class="compress-2nd" style="margin-top: 20px">
                    <tr>
                        <td width="40%"><b>Descriptors</td>
                        <td class="text-center" width="30%"><b>Grading Scale</td>
                        <td class="text-center" width="30%"><b>Remarks</td>
                    </tr>
                    <tr>
                        <td><i>Outstanding</td>
                        <td class="text-center" ><i>90-100</td>
                        <td class="text-center" ><i>Passed</td>
                    </tr>
                    <tr>
                        <td><i>Very Satisfactory</td>
                        <td class="text-center" ><i>85-89</td>
                        <td class="text-center" ><i>Passed</td>
                    </tr>
                    <tr>
                        <td><i>Satisfactory</td>
                        <td class="text-center" ><i>80-84</td>
                        <td class="text-center" ><i>Passed</td>
                    </tr>
                    <tr>
                        <td><i>Fairly Satisfactory</td>
                        <td class="text-center"><i>75-79</td>
                        <td class="text-center"><i>Passed</td>
                    </tr>
                    <tr>
                        <td><i>Did Not Meet Expectations</td>
                        <td class="text-center"><i>Below 75</td>
                        <td class="text-center"><i>Failed</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>