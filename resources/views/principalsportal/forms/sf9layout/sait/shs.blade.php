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
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
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
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
            
        }
		 .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }
        @page { size: 11in 8.5in; margin: .20in .25in;}
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="vertical-align: top; padding-right: .25in!important;">
            <table class="table mb-0 mt-0" style="width: 100%; table-layout: fixed;">
                <tr>
                    <td width="25%" class="p-0 text-left" style="font-size: 11px;">SF9-SHS</td>
                    <td width="50%" class="p-0"></td>
                    <td width="25%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" class="table mt-0 mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="32%" class="p-0" style="text-align: right; padding-right: 20px!important;">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                    </td>
                    <td width="36%" class="p-0" style="text-align: center; font-size: 13px; vertical-align: middle; padding-top: 5px 0px!important;">
                        <div><b>Republic of the Philippines</b></div>
                        <div style="font-size: 12px!important;"><b>Department of Education</b></div>
                        <div style="padding-top: 5px;"><b>Region X</b></div>
                        <div style="font-size: 12px!important;"><b>DIVISION OF VALENCIA CITY</b></div>
                        <div style=""><b>District IV</b></div>
                        {{-- <div style="width: 100%; font-size: 12px; margin-top: 7px;">Basic Education Department</div>
                        <div style="width: 100%; font-weight: bold; font-size: 12px;">{{$schoolinfo[0]->schoolname}}</div>
                        <div style="width: 100%; font-size: 12px; margin-top: 7px;">Basic Education Department</div>
                        <div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div>
                        <div style="width: 100%; font-size: 12px; margin-top: 15px;"><b>Report on Learning Progress and Achievements</b></div>
                        <div style="width: 100%; font-size: 12px;">AY: {{$schoolyear->sydesc}}</div> --}}
                    </td>
                    <td width="32%" class="text-center"></td>
                </tr>
            </table>
            <table width="100%" class="table mt-0 mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    {{-- <td class="text-center p-0" style="font-size: 19px;"><b>{{$schoolinfo[0]->schoolname}}</b></td> --}}
                    <td class="text-center p-0" style="font-size: 16px; background-color: #CCECFF;">
                        <div><b>SAN AGUSTIN INSTITUTE OF TECHNOLOGY</b></div>
                        <div style="font-size: 12px;">Valencia City, Bukidnon</div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center p-0" style="font-size: 13px;"><b>S.Y. {{$schoolyear->sydesc}}</b></td>
                </tr>
            </table>
            <table width="100%" class="table mt-0 mb-0" style="table-layout: fixed; margin-top: 10px; background-color: #DBDBDB;">
                <tr>
                    <td class="text-center p-0" style="font-size: 12px;"><b>SCHOOL FORM 9 PROGRESS REPORT CARD</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 10px;">
                <tr>
                    <td width="15%" class="p-0">Name:</td>
                    <td width="60%" class="p-0 text-center border-bottom" style=""><b>{{$student->student}}</b></td>
                    <td width="6%" class="p-0 text-center">Sex:</td>
                    <td width="19%" class="p-0 text-center border-bottom" style=""><b>{{$student->gender}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 5px;">
                <tr>
                    <td width="15%" class="p-0 text-left">Grade:</td>
                    <td width="9%" class="p-0 text-center border-bottom" style=""><b>{{str_replace('GRADE', '', $student->levelname)}}</b></td>
                    <td width="12%" class="p-0 text-center">Section:</td>
                    <td width="39%" class="p-0 text-center border-bottom" style=""><b>{{$student->sectionname}}</b></td>
                    <td width="6%" class="p-0 text-center">Age:</td>
                    <td width="19%" class="p-0 text-center border-bottom" style=""><b>{{$student->age}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 5px;">
                <tr>
                    <td width="15%" class="p-0">Track/Strand:</td>
                    <td width="85%" class="p-0 text-center border-bottom" style="font-size: 10px!important;"><b>ACADEMIC TRACK: {{$strandInfo->strandname}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 5px;">
                <tr>
                    <td width="15%" class="p-0">LRN:</td>
                    <td width="30%" class="p-0 text-left" style=""><u><b>{{$student->lrn}}</b></u></td>
                    <td width="55%"></td>
                </tr>
            </table>
            {{-- <table width="100%" style="font-size: 12px; margin-top: 5px;">
                <tr>
                    <td width="12%" class="p-0">AGE:</td>
                    <td width="41%" class="p-0 text-left border-bottom" style=""><b>{{$student->age}}</b></td>
                    <td width="3%" class="p-0"></td>
                    <td width="8%" class="p-0 text-right">SEX:&nbsp;</td>
                    <td width="36%" class="p-0 text-left border-bottom" style=""><b>{{$student->lrn}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 5px;">
                <tr>
                    <td width="12%" class="p-0">GRADE:</td>
                    <td width="41%" class="p-0 text-left border-bottom" style=""><b>{{str_replace('GRADE', '', $student->levelname)}} - {{$student->sectionname}}</b></td>
                    <td width="3%" class="p-0"></td>
                    <td width="13%" class="p-0 text-right">SECTION:</td>
                    <td width="4%" class="p-0"></td>
                    <td width="27%" class="p-0 text-left border-bottom" style=""><b>{{$student->lrn}}</b></td>
                </tr>
            </table> --}}
            
            <table width="100%" style="font-size: 14px; margin-top: 5px; font-family: 'Times New Roman', Times, serif !important;">
                <tr>
                    <td width="15%" class="p-0"><i>Dear Parent/Guardian,</i></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; font-family: 'Times New Roman', Times, serift; margin-top: 20px;">
                <tr>
                    <td class="p-0" style="text-indent: 70px;"><i>This report card shows the ability and progress your child has made in the different learning areas. 
                        The school welcomes you if you desire to know more about your child’s  progress.</i></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 30px;">
                <tr>
                    <td width="45%" class="text-center p-0 border-bottom" style=""><b>{{$adviser}}</b></td>
                    <td width="10%"></td>
                    <td width="45%" class="text-center p-0 border-bottom text-center" style=""><b>CLAIRE H. LACERNA, PhD</b></td>
                </tr>
                <tr>
                    <td class="p-0 text-center" style=""><i>Adviser</i></td>
                    <td></td>
                    <td class="p-0 text-center" style=""><i>Principal</i></td>
                </tr>
            </table>
            <table width="100%" class="table mt-0 mb-0" style="margin-top: 10px;">
                <tr>
                    <td class="text-center p-0" style="font-size: 12px;"><b><i>Certificate of Transfer</i></b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 10px; font-style: italic;">
                <tr>
                    <td width="27%" class="p-0">Admitted to Grade:</td>
                    <td width="24%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="12%" class="p-0 text-center">Section:</td>
                    <td width="37%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b>{{$student->sectionname}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; font-style: italic; margin-top: 3px;">
                <tr>
                    <td width="40%" class="p-0 text-left">Eligibility for Admission to Grade:</td>
                    <td width="60%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 3px;">
                <tr>
                    <td width="14%" class="p-0 text-left"><i>Approved:</i></td>
                    <td width="37%" class="p-0 border-bottom"></td>
                    <td width="12%" class="p-0"></td>
                    <td width="37%" class="p-0 border-bottom"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left"></td>
                    <td class="p-0 text-center"><i>School Head</i></td>
                    <td class="p-0"></td>
                    <td class="p-0 text-center"><i>Adviser</i></td>
                </tr>
            </table>
            <table width="100%" class="table mt-0 mb-0" style="margin-top: 10px; background-color: #DBDBDB;">
                <tr>
                    <td class="text-center p-0" style="font-size: 12px;"><b><i>Cancellation of Eligibility to Transfer</i></b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 5px;">
                <tr>
                    <td width="14%" class="p-0"><i>Admitted in:</i></td>
                    <td width="37%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="50%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 2px; font-style: italic;">
                <tr>
                    <td width="14%" class="p-0">Date:</td>
                    <td width="37%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                    <td width="12%" class="p-0 text-center"></td>
                    <td width="27%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b></b></td>
                    <td width="10%" class="p-0"></td>
                </tr>
                <tr>
                    <td class="p-0 text-left"></td>
                    <td class="p-0 text-center"></td>
                    <td class="p-0"></td>
                    <td class="p-0 text-center"><i>Principal</i></td>
                    <td></td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 60 / count($attendance_setup) : 0;
            @endphp
            <table width="100%" class="table table-bordered table-sm grades mb-0" style="table-layout: fixed; margin-top: 6px;">
                <tr>
                    <td class="text-center" style="background-color: #DBDBDB;"><b>REPORT ON ATTENDACE</b></td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered table-sm grades mb-0" style="table-layout: fixed; margin-top: -2px;">
                <tr>
                    <td width="30%" style="border: 1px solid #000; text-align: center; height: 20px;"></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" width="{{$width}}%"><span style="font-size: 10px!important"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</b></span></td>
                    @endforeach
                    <td class="text-center" width="8%" style="vertical-align: middle; font-size: 10px!important;"><span><b>TOTAL</b></span></td>
                </tr>
                <tr class="table-bordered">
                    <td>No. of school days</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td>No. of days present</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td>No. of days absent</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
            </table>
            <table width="100%" class="table table-sm grades mb-0" style="table-layout: fixed; margin-top: 5px; border: 1px solid #000;">
                <tr>
                    <td colspan="2" class="text-center p-0" style="border: 1px solid #000; background-color: #DBDBDB;"><b>REPORT ON ATTENDACE</b></td>
                </tr>
                <tr>
                    <td width="15%" class="text-left">1st Quarter</td>
                    <td width="85%" class="p-0 border-bottom">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-left">2nd Quarter</td>
                    <td class="p-0 border-bottom">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-left">3rd Quarter</td>
                    <td class="p-0 border-bottom">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-left">4th Quarter</td>
                    <td class="p-0 border-bottom">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="vertical-align: top;">
            
        </td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="vertical-align: top;">
        
        </td>
        <td width="50%" class="p-0" style="vertical-align: top; padding-left: .25in!important;">
            <table width="100%" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="p-0" style="vertical-align: top;">
                        <table class="table-sm table mb-0" width="100%" style="table-layout: fixed;">
                            <tr>
                                <td class="align-middle text-center"style="font-size: 12px!important;"><b>LEARNERS PROGRESS REPORT CARD</b></td>
                            </tr>
                        </table>
                        @if($student->acadprogid != 5)
                            <table class="table table-sm mt-0 table-bordered" width="100%" style="table-layout: fixed;">
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
                                {{-- <table class="table mb-0" width="100%" style="table-layout: fixed; margin-top: 10px;">
                                    <tr>
                                        <td width="72%" class="align-middle text-center p-0" style="font-size: 15px;"><b>{{$x == 1 ? 'First Semester' : 'Second Semester'}}</b></td>
                                        <td width="14%"></td>
                                        <td width="14%"></td>
                                    </tr>
                                </table> --}}
                                <table class="table table-bordered grades_2 mb-0" width="100%" style="table-layout: fixed;  margin-top: {{$x == 1 ? '0px' : '10px'}};">
                                    <tr>
                                        <td colspan="6" class="align-middle text-center p-0" style="font-size: 12px; background-color: #DBDBDB;"><b>{{$x == 1 ? 'FIRST SEMESTER' : 'SECOND SEMESTER'}}</b></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" colspan="2" class="align-middle text-center p-0" style="font-size: 12px!important;"><b>SUBJECTS</b></td>
                                        <td width="14%" colspan="2"  class="text-center align-middle p-0" style="font-size: 9px!important;"><b>QUARTER</b></td>
                                        <td width="8%" rowspan="2" class="text-center align-middle p-0" style="font-size: 9px!important;"><b>FINAL <br> GRADE</b></td>
                                        <td rowspan="2" width="10%" class="text-center align-middle p-0" style="font-size: 9px!important;"><b>ACTION <br> TAKEN</b></td>
                                    </tr>
                                    <tr style="">
                                        @if($x == 1)
                                            <td class="p-0 text-center align-middle"><b>1</b></td>
                                            <td class="p-0 text-center align-middle"><b>2</b></td>
                                        @elseif($x == 2)
                                            <td class="p-0 text-center align-middle"><b>3</b></td>
                                            <td class="p-0 text-center align-middle"><b>4</b></td>
                                        @endif
                                    </tr>
                                    {{-- <tr><td colspan="4" style=" color:black; border:solid 1px black"><b>CORE Subjects</b></td></tr> --}}
                                    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                                        <tr>
                                            <td class="p-0 text-center align-middle" width="13%">
                                                @if($item->type == 1)
                                                    <span>Core</span>
                                                @elseif($item->type == 2)
                                                    <span>Specialized</span>
                                                @else
                                                    <span>Applied</span>
                                                @endif
                                            </td>
                                            <td class="p-0" width="55%" style="padding-left: 5px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                            @if($x == 1)
                                                <td class="p-0 text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                            @elseif($x == 2)
                                                <td class="p-0 text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                            @endif
                                            <td class="text-center align-middle p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                            <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                        </tr>
                                    @endforeach
                                    {{-- <tr><td colspan="4" style=" color:black; border:solid 1px black"><b>APPLIED Subjects</b></td></tr> --}}
                                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                                        <tr>
                                            <td class="p-0 text-center align-middle">
                                                @if($item->type == 1)
                                                    <span>Core</span>
                                                @elseif($item->type == 2)
                                                    <span>Specialized</span>
                                                @else
                                                    <span>Applied</span>
                                                @endif
                                            </td>
                                            <td class="p-0" style="padding-left: 5px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                            @if($x == 1)
                                                <td class="p-0 text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                            @elseif($x == 2)
                                                <td class="p-0 text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                            @endif
                                            <td class="p-0 text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                            <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                        </tr>
                                    @endforeach
                                      {{-- <tr><td colspan="4" style=" color:black; border:solid 1px black"><b>SPECIALIZED Subjects</b></td></tr> --}}
                                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                                        <tr>
                                            <td class="p-0 text-center align-middle">
                                                @if($item->type == 1)
                                                    <span>Core</span>
                                                @elseif($item->type == 2)
                                                    <span>Specialized</span>
                                                @else
                                                    <span>Applied</span>
                                                @endif
                                            </td>
                                            <td class="p-0" style="padding-left: 5px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                            @if($x == 1)
                                                <td class="p-0 text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                            @elseif($x == 2)
                                                <td class="p-0 text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                <td class="p-0 text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                            @endif
                                            <td class="text-center align-middle  p-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                            <td class="text-center align-middle p-0" style="font-size: 10px!important;">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        @php
                                            $genave = collect($finalgrade)->where('semid',$x)->first();
                                        @endphp
                                        <td class="p-0"></td>
                                        <td class="p-0 text-right" style="font-size: 12px!important;"><b>General Average for Semester</b></td>
                                        @if($x == 1)
                                            <td class="p-0 text-center align-middle">{{isset($genave->quarter1) ? $genave->quarter1 != null ? $genave->quarter1:'' : ''}}</td>
                                            <td class="p-0 text-center align-middle">{{isset($genave->quarter2) ? $genave->quarter2 != null ? $genave->quarter2:'' : ''}}</td>
                                        @elseif($x == 2)
                                            <td class="p-0 text-center align-middle">{{isset($genave->quarter3) ? $genave->quarter3 != null ? $genave->quarter3:'' : ''}}</td>
                                            <td class="p-0 text-center align-middle">{{isset($genave->quarter4) ? $genave->quarter4 != null ? $genave->quarter4:'' : ''}}</td>
                                        @endif
                                        <td class="text-center align-middle  p-0">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                        <td class="text-center align-middle" style="font-size: 10px!important;">{{$genave->actiontaken != null ? $genave->actiontaken:''}}</td>
                                    </tr>
                                </table>
                                <table class="table table-sm mt-0 mb-0 table-bordered" width="100%" style="table-layout: fixed; margin-top: 10px; border: 2px solid #000;">
                                    <tr>
                                        <td rowspan="2" class="text-center p-0 align-middle">Learning Modality</td>
                                        <td class="p-0 text-center"><b>QUARTER @if($x==1) 1 @else 3 @endif</b></td>
                                        <td class="p-0 text-center"><b>QUARTER  @if($x==1) 2 @else 4 @endif</b></td>
                                    </tr>
                                    <tr>
                                        <td class="p-0 text-center">Online and Printed Module</td>
                                        <td class="p-0 text-center">Online and Printed Module</td>
                                    </tr>
                                </table>
                            @endfor
                        @endif
                        <table class="mb-0 mt-0 table-bordered" width="100%" style="table-layout: fixed; font-size: 12px!important; margin: 10px 0px 0px 0px;">
                            <tr>
                                <td colspan="3" class="align-middle text-center p-0" style="font-size: 11px!important; border: 2px solid #000;"><b>LEARNERS PROGRESS AND ACHIEVEMENT</b></td>
                            </tr>
                            <tr>
                                <td width="44%" class="text-center p-0" style="font-size: 11px!important;"><b>DESCRIPTORS</b></td>
                                <td width="25%" class="text-center p-0" style="font-size: 11px!important"><b>GRADING SCALE</b></td>
                                <td width="31%" class="text-center p-0" style="font-size: 11px!important"><b>REMARKS</b></td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="3">
                                    <span style="padding-left: 50px;">Outstanding</span>
                                    <span style="padding-left: 120px;">90 - 100</span>
                                    <span style="padding-left: 95px;">Passed</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="3">
                                    <span style="padding-left: 36px;">Very Satisfacory</span>
                                    <span style="padding-left: 115px;">85 - 89</span>
                                    <span style="padding-left: 98px;">Passed</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="3">
                                    <span style="padding-left: 50px;">Satisfacory</span>
                                    <span style="padding-left: 129px;">80 - 84</span>
                                    <span style="padding-left: 98px;">Passed</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="3">
                                    <span style="padding-left: 35px;">Fairly Satisfacory</span>
                                    <span style="padding-left: 112px;">75 - 79</span>
                                    <span style="padding-left: 98px;">Passed</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="3">
                                    <span style="padding-left: 12px;">Did Not Meet Expectations</span>
                                    <span style="padding-left: 80px;">Below 75</span>
                                    <span style="padding-left: 95px;">Failed</span>
                                </td>
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