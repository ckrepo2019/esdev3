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
            /* font-family: Verdana, Geneva, Tahoma, sans-serif !important; */
            font-family: 'Times New Roman', Times, serif;
            
        }
		
		 .check_mark {
               /* font-family: ZapfDingbats, sans-serif; */
            }
        @page { size: 11.7in 8.3in; margin: 10px 20px 0px 20px;  }
        
        .firstpage {
            page-break-after: always;
        }
        .secondpage {
            /* page-break-before: avoid; */
            /* width:100%;
            height:100%; */
            /* -webkit-transform: rotate(90deg) scale(1.30,.76); 
            -moz-transform:rotate(90deg) scale(.8,.50); */
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
                left: 1;
                transform-origin: 10 10;
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    28.57cm;
                height:   20.1cm;

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
                height: 150px;
                width: 315px;
                position: absolute;
                top: 71.5%;
                left: 60.7%;
            }
            .info table tr > td {
               transform: scale(1, 1.5)
            }
            .inapplicable {
                font-size: 13px;
                position: absolute;
                top: 64.3%;
                left: 50%;
            }
    </style>
</head>
<body>
    <div class="firstpage" style="">
        <div style="">
            <table width="100%" class="" style="table-layout: fixed; padding: 15px 14px 0px 14px;">
                <tr>
                    <td width="50%" class="p-0" style="margin-right: 12px!important;">
                        <div style="background-color: rgb(208, 206, 206); padding: 5px 5px;">
                            <table width="100%" style="table-layout: fixed; font-size: 10px;">
                                <tr>
                                    <td width="8%" class="text-left" style="">NAME:</td>
                                    <td width="60%" class="text-left" style=""><b>{{$student->student}}</b></td>
                                    <td width="6%" class="text-left" style="">LRN:</td>
                                    <td width="26%" class="text-left" style=""><b>{{$student->lrn}}</b></td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout: fixed; font-size: 10px;">
                                <tr>
                                    <td width="13%" class="text-left" style="">BIRTHDATE:</td>
                                    <td width="34%" class="text-left" style=""><b>{{$student->dob}}</b></td>
                                    <td width="6%" class="text-left" style="">AGE:</td>
                                    <td width="15%" class="text-left" style=""><b>{{$student->age}}</b></td>
                                    <td width="6%" class="text-left" style="">SEX:</td>
                                    <td width="26%" class="text-left" style=""><b>{{$student->gender}}</b></td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout: fixed; font-size: 10px;">
                                <tr>
                                    <td width="11.5%" class="text-left" style="">ADDRESS:</td>
                                    <td width="88.5%" class="text-left" style=""><b>{{$student->street}}, {{$student->barangay}}, {{$student->province}} {{$student->city}}</b></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td width="50%" class="p-0" style="padding-left: 12px!important;">
                        <div style="background-color: rgb(208, 206, 206); padding: 5px 5px;">
                            <table width="100%" style="table-layout: fixed; font-size: 10px;">
                                <tr>
                                    <td width="28%" class="text-left" style="">ACADEMIC SCHOOL YEAR:</td>
                                    <td width="47%" class="text-left" style=""><b>{{$schoolyear->sydesc}}</b></td>
                                    <td width="8" class="text-left" style="">TRACK:</td>
                                    <td width="17%" class="text-left" style="text-transform: uppercase;"><b>{{$strandInfo->trackname}}</b></td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout: fixed; font-size: 10px;">
                                <tr>
                                    <td width="13%" class="text-left" style="">STRAND:</td>
                                    <td width="87%" class="text-left" style=""><b>{{$strandInfo->strandname}}</b></td>
                                </tr>
                            </table>
                            <table width="100%" style="table-layout: fixed; font-size: 10px;">
                                <tr>
                                    <td>
                                        &nbsp;
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px; padding: 15px 10px 0px 10px;">
                <tr>
                    <td width="100%" class="text-center" style="">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</td>
                </tr>
            </table>
            <table width="100%" class="" style="table-layout: fixed; font-size: 10px; padding: 15px 10px 0px 10px;">
                <tr>
                    <td width="18%" class="text-center" style=""></td>
                    <td width="23%" class="text-center" style="background-color: rgb(208, 206, 206);">First Semester</td>
                    <td width="20%" class="text-center" style="font-size: 12px !important;"><b><u>SENIOR HIGH SCHOOL</u></b></td>
                    <td width="30.5%" class="text-center" style="background-color:rgb(208, 206, 206);">Second Semester</td>
                    <td width="8.5%" class="text-center" style=""></td>
                </tr>
            </table>
            <table width="100%" class="" style="table-layout: fixed; padding: 15px 14px 0px 14px;">
                <tr>
                    <td width="50%" class="p-0" style="vertical-align: top; padding-right: 12px!important;">
                        @php
                            $x = 1;
                        @endphp
                        <table width="100%" class="table table-bordered table-sm grades mb-0">
                            <tr>
                                <td width="40%" class="text-center" style="font-size: 10px!important; vertical-align: middle;">Subjects</td>
                                @if($x == 1)
                                    <td width="14%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>FIRST</div>
                                        <div style="margin-top: 8px;">QUARTER</div>
                                    </td>
                                    <td width="14%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>SECOND</div>
                                        <div style="margin-top: 8px;">QUARTER</div>
                                    </td>
                                @elseif($x == 2)
                                    <td width="14%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>THIRD</div>
                                        <div style="margin-top: 8px;">QUARTER</div>
                                    </td>
                                    <td width="14%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>FURTH</div>
                                        <div style="margin-top: 8px;">QUARTER</div>
                                    </td>
                                @endif
                                    <td width="15%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>FINAL</div>
                                        <div style="margin-top: 8px;">GRADE</div>
                                    </td>
                                <td width="17%" class="text-center align-middle" style="font-size: 8px!important;">REMARKS</td>
                            </tr>
                            <tr>
                                <td class="" style="text-align: left;font-size: 12px!important; background-color:rgb(208, 206, 206);" colspan="5"><b>CORE SUBJECTS</b></td>
                            </tr>
                            @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                                <tr>
                                    <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 7px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="" style="text-align: left;font-size: 12px!important; background-color:rgb(208, 206, 206);" colspan="5"><b>APPLIED SUBJECTS</b></td>
                            </tr>
                            @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                                <tr>
                                    <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 7px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="" style="text-align: left;font-size: 12px!important; background-color:rgb(208, 206, 206);" colspan="5"><b>SPECIALIZED SUBJECTS</b></td>
                            </tr>
                            @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                                <tr>
                                    <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 7px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                </tr>
                            @endforeach
                        
                            <tr>
                                @php
                                    $genave = collect($finalgrade)->where('semid',$x)->first();
                                @endphp
                                <td class="text-right" colspan="3" style="font-size: 12px!important;"><b>General Average</b></td>
                                <td class="text-center" style="font-weight: bold; font-size: 12px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                <td class="text-center" style="font-weight: bold; font-size: 12px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" class="p-0" style="vertical-align: top; padding-left: 12px!important;">
                        @php
                            $x = 2;
                        @endphp
                        <table width="100%" class="table table-bordered table-sm grades mb-0">
                            <tr>
                                <td width="40%" class="text-center" style="font-size: 10px!important; vertical-align: middle;">Subjects</td>
                                @if($x == 1)
                                    <td width="14%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>FIRST</div>
                                        <div style="margin-top: 8px;">QUARTER</div>
                                    </td>
                                    <td width="14%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>SECOND</div>
                                        <div style="margin-top: 8px;">QUARTER</div>
                                    </td>
                                @elseif($x == 2)
                                    <td width="14%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>THIRD</div>
                                        <div style="margin-top: 8px;">QUARTER</div>
                                    </td>
                                    <td width="14%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>FURTH</div>
                                        <div style="margin-top: 8px;">QUARTER</div>
                                    </td>
                                @endif
                                    <td width="15%" class="text-center align-middle" style="font-size: 8px!important;">
                                        <div>FINAL</div>
                                        <div style="margin-top: 8px;">GRADE</div>
                                    </td>
                                <td width="17%" class="text-center align-middle" style="font-size: 8px!important;">REMARKS</td>
                            </tr>
                            <tr>
                                <td class="" style="text-align: left;font-size: 12px!important; background-color:rgb(208, 206, 206);" colspan="5"><b>CORE SUBJECTS</b></td>
                            </tr>
                            @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                                <tr>
                                    <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 7px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="" style="text-align: left;font-size: 12px!important; background-color:rgb(208, 206, 206);" colspan="5"><b>APPLIED SUBJECTS</b></td>
                            </tr>
                            @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                                <tr>
                                    <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 7px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="" style="text-align: left;font-size: 12px!important; background-color:rgb(208, 206, 206);" colspan="5"><b>SPECIALIZED SUBJECTS</b></td>
                            </tr>
                            @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                                <tr>
                                    <td style="font-size: 8px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    @if($x == 1)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    @elseif($x == 2)
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    @endif
                                    <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                    <td class="text-center align-middle" style="font-size: 7px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                                </tr>
                            @endforeach
                        
                            <tr>
                                @php
                                    $genave = collect($finalgrade)->where('semid',$x)->first();
                                @endphp
                                <td class="text-right" colspan="3" style="font-size: 12px!important;"><b>General Average</b></td>
                                <td class="text-center" style="font-weight: bold; font-size: 12px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                <td class="text-center" style="font-weight: bold; font-size: 12px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; margin-top: 2px; margin-left: 15px; margin-right: 15px; font-size: 10px; padding: 0px 0px 0px 0px !important; background-color:rgb(208, 206, 206);">
                <tr>
                    <td width="10%" class="text-left" style=""><b>LEGEND: </b></td>
                    <td width="14%" class="text-left" style="">Outstanding (90-100)</td>
                    <td width="14%" class="text-left" style="">Very Satisfactory (85-89)</td>
                    <td width="28 %" class="text-left" style="">Satisfactory (80-84)</td>
                    <td width="18%" class="text-left" style="">Fairly Satisfaction (75-79)</td>
                    <td width="16%" class="text-left" style="">Did Not Expectation (Below 75)</td>
                </tr>
            </table>
            <table width="100%" class="" style="table-layout: fixed; padding: 0px 14px 0px 14px;">
                <tr>
                    <td width="50%" class="p-0" style="vertical-align: top; padding-right: 12px!important;">
                        <table width="100%" class="mb-0" style="">
                            <tr>
                                <td class="text-center" style="font-size: 12px"><b>REPORT ON OBSERVED VALUES</b></td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" class="p-0" style="vertical-align: top; padding-left: 12px!important;">
                        <table width="100%" class="mb-0" style="">
                            <tr>
                                <td class="text-center" style="font-size: 12px"><b>REPORT ON ATTENDANCE</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" class="" style="table-layout: fixed; padding: 0px 14px 0px 14px;">
                <tr>
                    <td width="50%" class="p-0" style="vertical-align: top; padding-right: 12px!important;">
                        <table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 9px;;">
                            <tr>
                                {{-- <td rowspan="2" class="align-middle text-left"><b>Core Values</b></td> --}}
                                <td rowspan="2" class="align-middle text-center" style="font-size: 8.5px">Student's Level of Commitment to DCC Core Values (DCCI)</td>
                                <td colspan="4" class="align-middle text-center"><b>Quarter</b></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="14%"><center>1ST</center></td>
                                <td class="text-center" width="14%"><center>2ND</center></td>
                                <td class="text-center" width="14%"><center>3RD</center></td>
                                <td class="text-center" width="14%"><center>4TH</center></td>
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
                            @else
                            <tr>
                                <div class="inapplicable text-center">
                                    <div><b>INAPPLICABLE FOR S.Y. 2021-2022</b></div>
                                </div>
                                <td class="p-0" width="44%" class="align-middle" style="font-size: 10px; padding-left: 2px!important;">1. Abides by the school rules and regulations</td>
                                <td colspan="4" rowspan="8" class="inap text-center align-middle"></td>
                                
                            </tr>
                            <tr>
                                <td class="p-0" width="44%" class="align-middle" style="font-size: 10px; padding-left: 2px!important;">2. Treats everyone with utmost respect and courtesy</td>
                            </tr>
                            <tr>
                                <td class="p-0" width="44%" class="align-middle" style="font-size: 10px; padding-left: 2px!important;">3. Accomplishes tasks more than what is expected</td>
                            </tr>
                            <tr>
                                <td class="p-0" width="44%" class="align-middle" style="font-size: 10px; padding-left: 2px!important;">4. Exudes independence in learning to learn</td>
                            </tr>
                            <tr>
                                <td class="p-0" width="44%" class="align-middle" style="font-size: 10px; padding-left: 2px!important;">5. Offers kindness and consideration to others</td>
                            </tr>
                            <tr>
                                <td class="p-0" width="44%" class="align-middle" style="font-size: 10px; padding-left: 2px!important;">6. Displays a sense of connectedness with others</td>
                            </tr>
                            <tr>
                                <td class="p-0" width="44%" class="align-middle" style="font-size: 10px; padding-left: 2px!important;">7. Exudes honesty, candidness, and forthrightness in giving information</td>
                            </tr>
                            <tr>
                                <td class="p-0" width="44%" class="align-middle" style="font-size: 10px; padding-left: 2px!important;">8. Maintains the school culture both on and off campus</td>
                            </tr>
                        @endif
                            {{-- ========================================================== --}}
                        </table>
                        <table width="100%" class="mb-0" style="background-color:rgb(208, 206, 206);">
                            <tr>
                                <td colspan="2" class="text-left" style="font-size: 9px">LEGEND</td>
                            </tr>
                            <tr>
                                <td width="52%" class="text-left" style="font-size: 9px"><b>5</b> - Students always demonstrates this quality</td>
                                <td width="48%" class="text-left" style="font-size: 9px"><b>3</b> - Students usually demonstrates this quality</td>
                            </tr>
                            <tr>
                                <td width="52%" class="text-left" style="font-size: 9px"><b>4</b> - Students almost always demonstrates this quality</td>
                                <td width="48%" class="text-left" style="font-size: 9px"><b>2</b> - Students sometimes demonstrates this quality</td>
                            </tr>
                            <tr>
                                <td width="52%" class="text-left" style="font-size: 9px"></td>
                                <td width="48%" class="text-left" style="font-size: 9px"><b>1</b> - Students seldom demonstrates this quality</td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" class="p-0" style="vertical-align: top; padding-left: 12px!important;">
                        <table width="100%" class="" style="table-layout: fixed; font-size: 10px; ">
                            <tr>
                                <td width="100%" class="p-0">
                                    @php
                                        $width = count($attendance_setup) != 0? 61 / count($attendance_setup) : 0;
                                    @endphp
                                    <table class="table table-bordered table-sm grades mb-0" width="100%">
                                        <tr>
                                            <td width="17%" style="text-align: center; vertical-align: middle!important;"></td>
                                            @foreach ($attendance_setup as $item)
                                                <td class="text-center align-middle;" style="vertical-align: middle; font-size: 8px!important;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                                            @endforeach
                                            <td class="text-center" width="7%" style="vertical-align: middle; font-size: 9px!important;"><span>TOTAL</span></td>
                                        </tr>
                                        <tr class="table-bordered">
                                            <td class="text-center p-0" style="font-size:  12px!important; vertical-align: middle;">No. of School Days</td>
                                            @foreach ($attendance_setup as $item)
                                                <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                                            @endforeach
                                            <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                                        </tr>
                                        <tr class="table-bordered">
                                            <td class="text-center p-0" style="font-size: 12px!important; vertical-align: middle;">No. of days present</td>
                                            @foreach ($attendance_setup as $item)
                                                <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                                            @endforeach
                                            <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                                        </tr>
                                        <tr class="table-bordered">
                                            <td class="text-center p-0" style="font-size: 12px!important; vertical-align: middle;">No. of days Tardy</td>
                                            @foreach ($attendance_setup as $item)
                                                <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                                            @endforeach
                                            <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                                        </tr>
                                    </table>
                                    <div style="background-color:rgb(208, 206, 206); margin-top: 10px;">
                                        <table width="100%" class="mb-0" style="font-size: 12px;">
                                            <tr>
                                                <td width="26%" class="text-left p-0" style="">The student is eligible for:</td>
                                                <td width="27%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                                <td width="12%" class="text-left p-0" style=""></td>
                                                <td width="5%" class="text-left p-0" style="">Date:</td>
                                                <td width="15%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                                <td width="15%" class="text-left p-0" style=""></td>
                                            </tr>
                                        </table>
                                        <table width="100%" class="mb-0" style="font-size: 12px;">
                                            <tr>
                                                <td width="18%" class="text-left p-0" style="">Lack subject/s in:</td>
                                                <td width="32%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                                                <td width="50%" class="text-left p-0" style=""></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <table width="100%" class="mb-0" style="font-size: 12px;">
                                            <tr>
                                                <td width="100%" class="text-left p-0" style="padding-left: 75px!important;">Approved by:</td>
                                            </tr>
                                        </table>
                                        <table width="100%" class="mb-0" style="font-size: 11px; margin-top: 30px;">
                                            <tr>
                                                <td width="21%" class="text-left p-0" style=""></td>
                                                <td width="24%" class="text-center p-0" style=""><b>GIL BRIELA D. AZURO</b></td>
                                                <td width="5%" class="text-left p-0" style=""></td>
                                                <td width="10%" class="text-left p-0" style=""></td>
                                                <td width="30%" class="text-center p-0" style=""><b>KRISTEL ANN P. PAL, MAEd</b></td>
                                                <td width="10%" class="text-left p-0" style=""></td>
                                            </tr>
                                            <tr>
                                                <td width="21%" class="text-left p-0" style=""></td>
                                                <td width="24%" class="text-center p-0" style="">Adviser</td>
                                                <td width="5%" class="text-left p-0" style=""></td>
                                                <td width="10%" class="text-left p-0" style=""></td>
                                                <td width="30%" class="text-center p-0" style="">Director, Basic Education</td>
                                                <td width="10%" class="text-left p-0" style=""></td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="secondpage">
        <div id="watermark1" style="padding-top: 100px;">
            @if($student->acadprogid == 5)
                <img src="{{base_path()}}/public/assets/images/dcc/shs.png" height="100%" width="100%" />
            @elseif($student->acadprogid == 4)
                <img src="{{base_path()}}/public/assets/images/dcc/jhs.png" height="100%" width="100%" />
            @elseif($student->acadprogid == 3)
                <img src="{{base_path()}}/public/assets/images/dcc/elem.png" height="100%" width="100%" />
            @else
                <img src="{{base_path()}}/public/assets/images/dcc/mwsp.png" height="100%" width="100%" />
            @endif
        </div>
        <div id="secpage" style="height: 97.4%;">
           <div class="info">
                <table class="lrn" style="width: 100%; table-layout: fixed; font-size: 12px; padding: 0px 10px 0px 10px;">
                    <tr>
                        <td class="text-center" style="padding-top: 7px;">{{$student->lrn}}</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="padding-top: 29px;">{{$student->student}}</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="padding-top: 38px;">{{str_replace('GRADE', '', $student->levelname)}} - {{$student->sectionname}}</td>
                    </tr>
                </table>
           </div>
        </div>
    </div>
</body>
</html>