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
            margin-bottom: 0px!important;
        }
        .mt-0{
            margin-top: 0px!important;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 8pt;
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
            font-size: 8pt!important;
            background-color:#4F81BD;
            
        }
        .gray{
            color: rgb(99, 99, 99);
        }
        .indented{
            text-indent: .5in!important;
        }
		 .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }
        @page { size: 13in 8.5in; margin: .1in .3in;}
    </style>
</head>
<body>
<table width="100%">
    <tr>
        <td width="33.3%" style="vertical-align: top">
            <table width="100%">
                <tr>
                    <td width="20%"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
                    <td width="60%" class="text-center">
                        <div style="font-size: 10pt"><b>PHILIPPINE ADVENT COLLEGE</div>
                        <div class="small-font"><i>Sindangan, Zamboanga del Norte</div>
                        <div class="small-font"><i>Philippines</div>
                        <div class="small-font" style="margin-top: 7px">Tel No. (065) 224-2038  Telefax:(065) 224-2700</div>
                        <div class="small-font">E-mail address:elem.pac@eudoramail.com</div>
                    </td>
                    <td width="20%"></td>
                </tr>
            </table>
            <table width="100%" style=";margin-left: 15px">
                <tr>
                    <td width="100%" class="text-center">
                        <div style="font-size: 10pt"><b>ELEMENTARY DEPARTMENT</div>
                        <div class="small-font">Basic Education Curriculum (BEC)</div>
                    </td>
                </tr>
            </table>
            <table width="100%" class="text-center" style="margin-top: 10px;margin-left: 15px">
                <tr>
                    <td width="100%">
                     <div style="font-size: 10pt"><b>PROGRESS REPORT CARD</div>
                    <div><i>School Year: {{$schoolyear->sydesc}}</div>
                    </td>
                </tr>
            </table>
            <table width="100%" class="grades">
                <tr>
                    <td width="10%">LRN:</td>
                    <td width="90%" class="underline">{{$student->lrn}}</td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td class="underline">{{$student->student}}</td>
                </tr>
            </table>
            <table width="100%" class="mb-0 grades">
                <tr>
                    <td width="5%">Age:</td>
                    <td width="5%" class="underline">{{$student->age}}</td>
                    <td width="5%">Sex:</td>
                    <td width="10%" class="underline">{{$student->gender}}</td>
                    <td width="10%">Grade:</td>
                    <td width="20%" class="underline">{{$student->levelname}}</td>
                    <td width="10%">Section:</td>
                    <td width="35%" class="underline">{{$student->sectionname}}</td>
                </tr>
            </table>
            <table width="100%" class="mt-0">
                <tr>
                    <td width="100%" class="">
                        <div><h4 class="mt-0" style="margin-top: 10px!important">Dear Parents/Guardians</h4></div>
                        <div><p class="indented">This Report Card shows the progress of your child in different areas as well as his/her core values.</p></div>
                        <div><p class="indented">The school asdf you desire to know more about the progress of your child.</p></div>
                        <div><p class="indented">The passing mark in any given subjects is 75% or above its equivalent.</p></div>
                    </td>
                </tr>
            </table>
            <table width="100%" class="text-center" style="margin-top: 5px">
                <tr>
                    <td width="50%"><u><b>{{$adviser}}</td>
                    <td width="50%"><u><b>{{$principal_info[0]->name}}</td>
                </tr>
                <tr>
                    <td>Teacher</td>
                    <td>{{$principal_info[0]->title}}</td>
                </tr>
            </table>
            <table width="100%" class="text-center" style="border-top: 2px solid rgb(14, 170, 242);margin-top: 5px;margin-left: 15px">
                <tr>
                    <td><b>CERTIFICATE OF TRANSFER</td>
                </tr>
            </table>
            <table width="80%" class="small-font" style="margin-top: 10px"> 
                <tr>
                    <td width="30%">Admitted to grade</td>
                    <td width="40%" class="underline"></td>
                    <td width="10%">Section:</td>
                    <td width="20%" class="underline"></td>
                </tr>
            </table>
            <table width="80%"  class="small-font">
                <tr>
                    <td width="38%">Eligible for admission to</td>
                    <td width="62%" class="underline"></td>
                </tr>
                <tr>
                   <td colspan="2">Approved:</td> 
                </tr>
            </table>
            <table width="80%" class="text-center small-font">
                <tr>
                    <td width="50%" ><u><b>{{$adviser}}</td>
                    <td width="50%" ><u><b><b>{{$principal_info[0]->name}}</td>
                </tr>
                <tr>
                    <td><i>Teacher</td>
                    <td><i>{{$principal_info[0]->title}}</td>
                </tr>
            </table>
            <table width="100%" class="text-center small-font" style="margin-top: 10px;margin-left: 15px">
                <tr>
                    <td colspan="3"><i>Cancellation of Eligibility to Transfer</td>
                </tr>
                <tr>
                    <td width="10%" class="text-right">Admitted in</td>
                    <td width="20%" class="underline"></td>
                    <td width="10%" class="text-left">Date</td>
                </tr>
            </table>
            <table width="100%"class="text-center small-font" style="margin-top: 5px;margin-left: 15px">
                <tr>
                    <td><u><b>{{$principal_info[0]->name}}</td>
                </tr>
                <tr>
                    <td><i>{{$principal_info[0]->title}}</td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 74 / count($attendance_setup) : 0;
            @endphp
            <table width="100%" class="table table-bordered table-sm grades mb-0" style="table-layout: fixed">
                <tr>
                    <td colspan="{{count($attendance_setup)+2}}" class="text-center title"><b style="color:white">REPORT OF ATTENDANCE</td>
                </tr>
                <tr>
                    <td width="22%" style="border: 1px solid #000; text-align: center; height: 20px;"></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" width="{{$width}}%"><span style="font-size: 6pt!important"><i>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                    @endforeach
                    <td class="text-center" width="7%" style="vertical-align: middle;font-size: 6pt!important"><span>Total</span></td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 6pt!important">No. of School<br> Days</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style="font-size: 6pt!important">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle" style="font-size: 6pt!important">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 6pt!important">No. of School <br> Days Present</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style="font-size: 6pt!important">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle"  style="font-size: 6pt!important">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 6pt!important">No. of Times<br> Tardy</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style="font-size: 6pt!important">{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" style="font-size: 6pt!important">{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 6pt!important">No. of Times<br> Absent</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style="font-size: 6pt!important"></td>
                    @endforeach
                    <td class="text-center align-middle"  style="font-size: 6pt!important"></td>
                </tr>
            </table>
            <table width="100%" class="text-center table-bordered" style="margin-left: .4in;margin-top: 10px;margin-right: .4in ">
                <tr style="background-color: orange!important">
                    <td width="20%" class="spacing"><b>QUARTER</td>
                    <td width="30%" class="text-center spacing"><b>PARENT'S SIGNATURE</td>
                    <td width="20%" class="spacings"><b>DATE</td>
                </tr>
                <tr style="background-color: orange!important">
                    <td>First Grading</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="background-color: orange!important">
                    <td>Second Grading</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="background-color: orange!important">
                    <td>Third Grading</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="background-color: orange!important">
                    <td>Fourth Grading</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <table width="100%" class="text-center small-font" style="margin-top: 5px;margin-left: .7in">
                <tr>
                    <td><b><i>"The School that Prepares Students to Serve"</td>
                </tr>
            </table>
        </td>
        <td width="33.3%" style="vertical-align: top">
            <table width="100%">
                <tr>
                    <td>
                        <table width="100%" style="margin-top: 10px">
                            <tr>
                                <td class="title text-center"><b style="color:white">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-sm table-bordered grades mb-0" style="table-layout: fixed; margin-top: 7px;">
                            <thead>
                                <tr style="">
                                    <td rowspan="2" class="align-middle text-center" width="30%" style="font-size: 12px!important;"><b>LEARNING <br> AREAS</b></td>
                                    <td colspan="4"  class="text-center align-middle" style="font-size: 12px!important;"><b>QUARTER</b></td>
                                    
                                    <td rowspan="2" class="text-center align-middle" width="22%" style="font-size: 12px!important;"><b>FINAL <br> RATING</b></td>
                                </tr>
                                <tr>
                                    <td class="text-center align-middle" width="12%" ><b>1<sup>st</b></td>
                                    <td class="text-center align-middle" width="12%" ><b>2<sup>nd</b></td>
                                    <td class="text-center align-middle" width="12%" ><b>3<sup>rd</b></td>
                                    <td class="text-center align-middle" width="12%" ><b>4<sup>th</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studgrades as $item)
                                <tr>
                                    <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; padding: 1px 0px 1px 4px !important">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                    <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                    <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                    <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                    <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                    <td class="text-center align-middle">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="text-right"><b>GENERAL AVERAGE</b></td>
                                    <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table width="100%" style="margin-top: 10px">
                            <tr class="text-center">
                                <td width="50%"><b>DESCRIPTOR</td>
                                <td width="25%"><b>GRADE SCALE</td>
                                <td width="25%"><b>REMARKS</td>
                            </tr>
                            <tr>
                                <td>Advance</td>
                                <td style="margin-left: 15px!important">90-100</td>
                                <td style="margin-left: 25px!important">Passed</td>
                            </tr>
                            <tr>
                                <td>Proficient</td>
                                <td style="margin-left: 15px!important">85-89</td>
                                <td style="margin-left: 25px!important">Passed</td>
                            </tr>
                            <tr>
                                <td>Approaching Proficiency </td>
                                <td style="margin-left: 15px!important">80-84</td>
                                <td style="margin-left: 25px!important">Passed</td>
                            </tr>
                            <tr>
                                <td>Developing</td>
                                <td style="margin-left: 15px!important">75-79</td>
                                <td style="margin-left: 25px!important">Passed</td>
                            </tr>
                            <tr>
                                <td>Beginning</td>
                                <td style="margin-left: 15px!important">Below 75</td>
                                <td style="margin-left: 25px!important">Failed</td>
                            </tr>
                        </table>
                        <table class="table-sm table table-bordered mb-0" width="100%" style="table-layout: fixed; font-size: 8pt!important; margin-top: 15px">
                            <tr>
                                <td colspan="6" class="text-center title p-0"><b style="color:white">REPORT ON LEARNER’S OBSERVED VALUES</td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="align-middle text-center" style=""><b>Core Values</b></td>
                                <td rowspan="2"class="align-middle text-center" style=""><b>Behavior Statements</b></td>
                                <td colspan="4" class="text-center"><b>Quarter</td>
                            </tr>
                            <tr>
                                <td width="6.5%"><center><b>1</b></center></td>
                                <td width="6.5%"><center><b>2</b></center></td>
                                <td width="6.5%"><center><b>3</b></center></td>
                                <td width="6.5%"><center><b>4</b></center></td>
                            </tr>
                            {{-- ========================================================== --}}
                            @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                @php
                                    $count = 0;
                                @endphp
                                @foreach ($groupitem as $item)
                                    @if($item->value == 0)
                                    @else
                                        <tr>
                                            @if($count == 0)
                                                    <td width="19%" class="text-center align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                                    @php
                                                        $count = 1;
                                                    @endphp
                                            @endif
                                            <td width="55%" class="align-middle">{{$item->description}}</td>
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
                        </table>
                        <table width="100%" style="font-size: 8pt; margin-top: 6px">
                            <tr>
                                <td width="50%" class="text-center"><b>MARKING</td>
                                <td width="50%" class="text-center"><b>NON-NUMERICAL RATING</td>
                            </tr>
                            <tr>
                                <td class="text-center"><b>AO</td>
                                <td style="padding-left: 55px"><b>Always Observed</td>
                            </tr>
                            <tr>
                                <td class="text-center"><b>SO</td>
                                <td style="padding-left: 55px"><b>Sometimes Observed</td>
                            </tr>
                            <tr>
                                <td class="text-center"><b>RO</td>
                                <td style="padding-left: 55px"><b>Rarely Observed</td>
                            </tr>
                            <tr>
                                <td class="text-center"><B>NO</td>
                                <td style="padding-left: 55px"><b>Not Observed</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td width="33.3%">
            <table></table>
        </td>
    </tr>
</table>
</body>
</html>