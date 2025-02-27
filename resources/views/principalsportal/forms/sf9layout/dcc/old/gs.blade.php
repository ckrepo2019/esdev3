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
            font-family: Verdana, Geneva, Tahoma, sans-serif !important;
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 8.5in 11in; margin: 10px 20px 0px 20px;  }
        
        .firstpage {
            page-break-after: always;
        }
        .secondpage {
            /* page-break-before: avoid; */
            /* width:100%;
            height:100%; */
            -webkit-transform: rotate(90deg) scale(1.30,.76); 
            -moz-transform:rotate(90deg) scale(.8,.50);
        }
        /* #secpage {
            background: url('/assets/images/dcc/elem1-62.png');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        } */
        #watermark1 {
        opacity: 1;
                position: absolute;
                top: -72;
                left: 5;
                transform-origin: 10 10;
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    25.3cm;
                height:   26.9cm;

                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }

            /* .lrn {
                position: absolute;
                top: 72%;
                left: 65%;
            }
            .name {
                position: absolute;
                top: 79%;
                left: 65%;
            } */
            .info {
                height: 200px;
                width: 225px;
                position: absolute;
                top: 72%;
                left: 61%;
            }
            .info table tr > td {
               transform: scale(1, 1.5)
            }
            .inapplicable {
                color: #404040;
                font-size: 16px;
                position: absolute;
                top: 57.2%;
                left: 67.5%;
                transform: rotate(-15deg);
            }
            .inap {
                background-color: #D9D9D9;
            }
    </style>
</head>
<body>
    <div class="firstpage" style="border-left: 1px solid #000; border-right: 1px solid #000;">
        <div style="height: 97.5%; border: 1px solid #000; margin: 1px;">
            <table style="width: 100%; table-layout: fixed; font-size: 12px; padding: 15px 10px 0px 10px;">
                <tr>
                    <td width="13%" style=""><b>Name:</b></td>
                    <td width="37%" style="border-bottom: 1px solid #000;">{{$student->student}}</td>
                    <td width="10%" style=""></td>
                    <td width="14%" style=""><b>Sex:</b></td>
                    <td width="11%" style="border-bottom: 1px solid #000;">{{$student->gender}}</td>
                    <td width="10%" style=""></td>
                </tr>
                <tr>
                    <td width="13%" style=""><b>Birth Date:</b></td>
                    <td width="37%" style="border-bottom: 1px solid #000;">{{$student->dob}}</td>
                    <td width="10%" style=""></td>
                    <td width="14%" style=""><b>Curriculum:</b></td>
                    <td width="11%" style="border-bottom: 1px solid #000;">K TO 12</td>
                    <td width="10%" style=""></td>
                </tr>
                <tr>
                    <td width="13%" style=""><b>Address:</b></td>
                    <td width="37%" style="border-bottom: 1px solid #000;"></td>
                    <td width="10%" style=""></td>
                    <td width="14%" style=""><b>Academic Year:</b></td>
                    <td width="11%" style="border-bottom: 1px solid #000;">{{$schoolyear->sydesc}}</td>
                    <td width="10%" style=""></td>
                </tr>
            </table>
            <table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 12px; padding: 15px 14px 0px 14px;">
                <thead>
                    <tr>
                        <th width="13%" rowspan="3" style="font-size: 8px;">QUARTER</th>
                        <th colspan="{{collect($studgrades)->where('isCon',0)->count()+1}}" style="font-size: 12px;">LEARNING AREAS</th>
                    </tr>
                    <tr>
                        @foreach($studgrades as $eachstudsubj)
                            @if($eachstudsubj->inMAPEH == 0 && $eachstudsubj->isCon == 0)
                                <td style="font-size: 6px;" class="text-center" rowspan="2">{{$eachstudsubj->subjdesc}}</td>
                            @endif
                        @endforeach
                        @if(collect($studgrades)->where('inMAPEH','1')->count()>0)
                            <td class="text-center" colspan="{{collect($studgrades)->where('inMAPEH',1)->count()}}" style="font-size: 8px;">MAPEH</td>
                        @endif
                        <td width="17%" class="text-center" rowspan="2" style="font-size: 8px;"><b>FINAL RATING</b></td>
                    </tr>
                    <tr style="font-size: 8px;">
                        @foreach($studgrades as $eachstudsubj)
                            @if($eachstudsubj->inMAPEH == 1)
                                <td class="text-center">{{$eachstudsubj->subjdesc}}</td>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center align-middle" style="font-size: 9.5px!important; padding-top: 2px; padding-bottom: 2px;"><b>1ST</b></td>
                        @foreach (collect($studgrades)->where('isCon',0)->values() as $item)
                            <td class="text-center" style="font-size: 8px;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" style="font-size: 9.5px!important; padding-top: 2px; padding-bottom: 2px;"><b>2ND</b></td>
                        @foreach (collect($studgrades)->where('isCon',0)->values() as $item)
                            <td class="text-center" style="font-size: 8px;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" style="font-size: 9.5px!important; padding-top: 2px; padding-bottom: 2px;"><b>3RD</b></td>
                        @foreach (collect($studgrades)->where('isCon',0)->values() as $item)
                        <td class="text-center" style="font-size: 8px;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" style="font-size: 9.5px!important; padding-top: 2px; padding-bottom: 2px;"><b>4TH</b></td>
                        @foreach (collect($studgrades)->where('isCon',0)->values() as $item)
                        <td class="text-center" style="font-size: 8px;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" style="font-size: 9.5px!important; padding-top: 2px; padding-bottom: 2px;"><b>AVERAGE</b></td>
                        @foreach (collect($studgrades)->where('isCon',0)->values() as $item)
                            <td class="text-center align-middle" style="font-size: 8px;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                        @endforeach
                        <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 8px;">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" style="font-size: 9.5px!important; padding-top: 2px; padding-bottom: 2px;"><b>REMARKS</b></td>
                        @foreach (collect($studgrades)->where('isCon',0)->values() as $item)
                            <td class="text-center align-middle" style="font-size: 8px;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        @endforeach
                        <td class="text-center align-middle" style="font-size: 8px;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="font-size: 8px; padding-top: 5px; padding-bottom: 5px;"><b>LEGEND</b></td>
                        <td colspan="{{collect($studgrades)->where('isCon',0)->count()+1}}" class="text-center" style="font-size: 8px; vertical-align: top;">
                            <div class="text-left p-0" style=""><span>OUTSTANDING ( 90-100 )</span> <span style="padding-left: 80px;">SATISFACTORY ( 80-84 )</span></div>
                            <div class="text-right p-0" style=""><span style="padding-right: 30px;">DID NOT MEET EXPECTATIONS (BELOW 75)</span></div>
                            <div class="text-left p-0" style=""><span>VERY SATISFACTORY ( 85-89 )</span> <span style="padding-left: 80px;">FAIRLY SATISFACTORY ( 75-79 )</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" style="font-size: 6px; padding-top: 5px; padding-bottom: 5px;">GENERAL POINT AVERAGE <br> (GPA)</td>
                        <td colspan="{{collect($studgrades)->where('isCon',0)->count()+1}}" class="text-center" style="font-size: 8px; vertical-align: top;"></td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" class="" style="table-layout: fixed; font-size: 10px; padding: 15px 14px 0px 14px;">
                <tr>
                    <td width="7%"></td>
                    <td width="16%">The pupil is eligible for :</td>
                    <td width="20%" style="border-bottom: 1px solid #000;"></td>
                    <td width="7%"></td>
                    <td width="4%" class="text-left">Date:</td>
                    <td width="11%" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%"></td>
                    <td width="12%">Lack subject/s in:</td>
                    <td width="18%" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table width="100%" class="" style="table-layout: fixed; font-size: 10px; padding: 15px 14px 0px 14px; margin-top: 120px;">
                <tr>
                    <td width="100%" class="p-0">
                        @php
                            $width = count($attendance_setup) != 0? 67 / count($attendance_setup) : 0;
                        @endphp
                        <table class="table table-bordered table-sm grades mb-0" width="100%">
                            <tr>
                                <td width="11%" style="text-align: center; vertical-align: middle!important;"></td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle;" style="vertical-align: middle; font-size: 7px!important;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM')}}</span></td>
                                @endforeach
                                <td class="text-center" width="7%" style="vertical-align: middle; font-size: 9px!important;"><span>TOTAL</span></td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="text-center p-0" style="font-size: 8px!important; vertical-align: middle;">Days of School</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                @endforeach
                                <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="text-center p-0" style="font-size: 8px!important; vertical-align: middle;">Number of days Present</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td class="text-center p-0" style="font-size: 8px!important; vertical-align: middle;">Number of times Tardy</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                @endforeach
                                <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 10px; padding: 15px 14px 0px 14px;">
                <tr>
                    <td rowspan="2" class="align-middle text-center"><b>Student's Level of Commitment to DCC Core Values (DCCI)</b></td>
                    <td colspan="4" class="align-middle text-center"><b>Quarter</b></td>
                </tr>
                <tr>
                    <td class="text-center" width="8%"><center>1st</center></td>
                    <td class="text-center" width="8%"><center>2nd</center></td>
                    <td class="text-center" width="8%"><center>3rd</center></td>
                    <td class="text-center" width="8%"><center>4th</center></td>
                </tr>
                @if($schoolyear->sydesc != 2)
                    @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($groupitem as $item)
                            @if($item->value == 0)
                            @else
                                <tr>
                                    <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">{{$item->description}}</td>
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
                @else
                    <tr>
                        <div class="inapplicable text-center">
                            <div><b>INAPPLICABLE FOR</b></div> 
                            <div><b>S.Y. 2021-2022</b></div> 
                        </div>
                        <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">Abides by the school rules and regulations</td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">Treats everyone with utmost respect and courtesy</td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">Accomplishes tasks more than what is expected</td>
                        <td class="inap -center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">Exudes independence in learning to learn</td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">Offers kindness and consideration to others</td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">Displays a sense of connectedness with others</td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">Exudes honesty, candidness, and forthrightness in giving information</td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="68%" class="align-middle" style="font-size: 10.5px; padding-left: 2px!important;">Maintains school culture both on and off campus</td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                        <td class="inap text-center align-middle"></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="5" class="p-0">
                        <table width="100%" class="table-bordered-0">
                            <tr>
                                <td width="33%" style="font-size:.55rem !important">5 - Pupil always demonstrates this  quality.</td>
                                <td width="33%">4 - Pupil almost always demonstrates this quality.</td>
                                <td width="34%" rowspan="2">1 - Pupil seldom demonstrates this quality.</td>
                            </tr>
                            <tr>
                                <td width="33%">3 - Pupil usually demonstrates this quality.</td>
                                <td width="33%">2 - Pupil sometimes demonstrates this quality.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <h5 style="padding: 15px 14px 0px 14px;">PARENT'S / GUARDIAN'S SIGNATURE:</h5>
            <table width="100%" class="table table-sm mb-1" style="padding: 0px 14px 0px 14px;">
                <tr>
                    <td width="5%"></td>
                    <td width="13%" style="vertical-align: bottom!important; padding-bottom:0!important;"><b>1ST QUARTER</b></td>
                    <td width="20%" style="border-bottom: 1px solid #000 !important;"></td>
                    <td width="24%"></td>
                    <td width="13%" style="vertical-align: bottom!important; padding-bottom:0!important;"><b>3RD QUARTER</b></td>
                    <td width="20%" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%"></td>
                </tr>
            </table>
            <table width="100%" class="table table-sm " style="padding: 5px 14px 0px 14px;">
                <tr>
                    <td width="5%"></td>
                    <td width="13%" style="vertical-align: bottom !important; padding-bottom:0!important;"><b>2ND QUARTER</b></td>
                    <td width="20%" style="border-bottom: 1px solid #000 !important;"></td>
                    <td width="24%"></td>
                    <td width="13%" style="vertical-align: bottom !important; padding-bottom:0!important;"><b>4TH QUARTER</b></td>
                    <td width="20%" style="border-bottom: 1px solid #000 ;"></td>
                    <td width="5%"></td>
                </tr>
            </table>
            <table width="100%" class="table table-sm " style="padding: 12px 14px 0px 14px;">
                <tr>
                    <td width="10%"></td>
                    <td width="25%" class="text-center" style="border-bottom: 1px solid #000 !important;"><b>{{$adviser}}</b></td>
                    <td width="30%"></td>
                    <td width="25%" class="text-center" style="border-bottom: 1px solid #000 !important;"><b>{{$principal_info[0]->name}}</b></td>
                    <td width="10%"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center"><b>Adviser</b></td>
                    <td></td>
                    <td class="text-center"><b>{{$principal_info[0]->title}}</b></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="secondpage" style="">
        <div id="watermark1" style="padding-top: 100px;">
            @if($student->acadprogid == 4)
                <img src="{{base_path()}}/public/assets/images/dcc/jhs.png" height="100%" width="81%" />
            @elseif($student->acadprogid == 3)
                <img src="{{base_path()}}/public/assets/images/dcc/elem1-62.png" height="100%" width="81%" />
            @else
                <img src="{{base_path()}}/public/assets/images/dcc/mwsp.png" height="100%" width="81%" />
            @endif
        </div>
        <div id="secpage" style="height: 97.5%; margin: 1px;">
           <div class="info">
                <table class="lrn" style="width: 100%; table-layout: fixed; font-size: 12px; padding: 15px 10px 0px 10px;">
                    <tr>
                        <td class="text-center">{{$student->lrn}}</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="padding-top: 40px;">{{$student->student}}</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="padding-top: 53px;">{{str_replace('GRADE', '', $student->levelname)}} - {{$student->sectionname}}</td>
                    </tr>
                </table>
           </div>
        </div>
    </div>
</body>
</html>