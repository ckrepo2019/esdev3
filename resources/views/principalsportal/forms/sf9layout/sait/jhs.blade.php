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
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
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
                    <td width="25%" class="p-0 text-center" style="font-size: 11px;">DepEd FORM 138-E</td>
                    <td width="50%" class="p-0"></td>
                    <td width="25%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" class="table mt-0 mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="30%" class="p-0" style="text-align: center;">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                    </td>
                    <td width="40%" class="p-0" style="text-align: center; font-size: 13px; vertical-align: middle; padding-top: 5px 0px!important;">
                        <div><b>Republic of the Philippines</b></div>
                        <div style="font-size: 12px!important;"><b>DEPARTMENT OF EDUCATION</b></div>
                        <div style="padding-top: 5px;">Region X</div>
                        <div style="font-size: 12px!important;"><b>DIVISION OF VALENCIA CITY</b></div>
                        {{-- <div style="width: 100%; font-size: 12px; margin-top: 7px;">Basic Education Department</div>
                        <div style="width: 100%; font-weight: bold; font-size: 12px;">{{$schoolinfo[0]->schoolname}}</div>
                        <div style="width: 100%; font-size: 12px; margin-top: 7px;">Basic Education Department</div>
                        <div style="width: 100%; font-size: 12px;">{{$schoolinfo[0]->address}}</div>
                        <div style="width: 100%; font-size: 12px; margin-top: 15px;"><b>Report on Learning Progress and Achievements</b></div>
                        <div style="width: 100%; font-size: 12px;">AY: {{$schoolyear->sydesc}}</div> --}}
                    </td>
                    <td width="30%" class="text-center"></td>
                </tr>
            </table>
            <table width="100%" class="table mt-0 mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    {{-- <td class="text-center p-0" style="font-size: 19px;"><b>{{$schoolinfo[0]->schoolname}}</b></td> --}}
                    <td class="text-center p-0" style="font-size: 20px; background-color: #0099FF; color: #fff;">
                        <div>SAN AGUSTIN INSTITUTE OF TECHNOLOGY</div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center p-0" style="font-size: 12px;"><b>GRADE SCHOOL REPORT CARD</b></td>
                </tr>
                <tr>
                    <td class="text-center p-0" style="font-size: 12px;"><b>S.Y. {{$schoolyear->sydesc}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px;" >
                <tr>
                    <td width="12%" class="p-0">NAME:</td>
                    <td width="41%" class="p-0 text-center border-bottom" style=""><b>{{$student->student}}</b></td>
                    <td width="3%" class="p-0"></td>
                    <td width="8%" class="p-0">LRN #:</td>
                    <td width="36%" class="p-0 text-center border-bottom" style=""><b>{{$student->lrn}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 5px;">
                <tr>
                    <td width="12%" class="p-0">AGE:</td>
                    <td width="41%" class="p-0 text-center border-bottom" style=""><b>{{$student->age}}</b></td>
                    <td width="3%" class="p-0"></td>
                    <td width="8%" class="p-0 text-right">SEX:&nbsp;</td>
                    <td width="36%" class="p-0 text-center border-bottom" style=""><b>{{$student->gender}}</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 5px;">
                <tr>
                    <td width="12%" class="p-0">GRADE:</td>
                    <td width="41%" class="p-0 text-center border-bottom" style=""><b>{{str_replace('GRADE', '', $student->levelname)}}</b></td>
                    <td width="3%" class="p-0"></td>
                    <td width="13%" class="p-0 text-right">SECTION:</td>
                    <td width="4%" class="p-0"></td>
                    <td width="27%" class="p-0 text-center border-bottom" style=""><b>{{$student->sectionname}}</b></td>
                </tr>
            </table>
            
            <table width="100%" style="font-size: 15x; margin-top: 10px; font-family: 'Times New Roman', Times, serif !important;">
                <tr>
                    <td width="15%" class="p-0"><i>Dear Parent:</i></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 14px; font-family: 'Times New Roman', Times, serift;">
                <tr>
                    <td class="p-0" style="text-indent: 70px;">This report card shows the ability and the progress  your child has made</td>
                </tr>
            </table>
            <table width="100%" style="font-size: 14px; font-family: 'Times New Roman', Times, serift;">
                <tr>
                    <td class="p-0" style="">in different learning areas as well as his/her core values.</td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 30px;">
                <tr>
                    <td width="55%" class="p-0" style=""></td>
                    <td width="45%" class="p-0 border-bottom text-center" style=""><b>{{$adviser}}</b></td>
                </tr>
                <tr>
                    <td width="55%" class="p-0" style=""></td>
                    <td width="45%" class="p-0 text-center" style="">Teacher</td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 15px">
                <tr>
                    <td width="35%" class="p-0 border-bottom text-center" style=""><b>{{$principal_info[0]->name}}</b></td>
                    <td width="65%" class="p-0" style=""></td>
                </tr>
                <tr>
                    <td width="35%" class="p-0 text-center" style="">School Head</td>
                    <td width="65%" class="p-0" style=""></td>
                </tr>
            </table>
            <table width="100%" class="table table-sm table-bordered grades mb-0" style="table-layout: fixed; margin-top: 10px;">
                <thead>
                    <tr>
                        <td colspan="7" class="align-middle text-center" style="font-size: 12px!important; background-color: #EBF1DE;"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td>
                    </tr>
                    <tr style="background-color: #FDE9D9;">
                        <td class="align-middle text-center" width="32%" style="font-size: 12px!important;"><b>LEARNING <br> AREAS</b></td>
                        {{-- <td colspan="4"  class="text-center align-middle" style="font-size: 12px!important;"><b>QUARTER</b></td> --}}
                        <td class="text-center align-middle" width="10%" style="font-size: 12px!important;"><b>1</b></td>
                        <td class="text-center align-middle" width="10%" style="font-size: 12px!important;"><b>2</b></td>
                        <td class="text-center align-middle" width="10%" style="font-size: 12px!important;"><b>3</b></td>
                        <td class="text-center align-middle" width="10%" style="font-size: 12px!important;"><b>4</b></td>
                        <td class="text-center align-middle" width="13%" style="font-size: 12px!important;"><b>FINAL <br> RATING</b></td>
                        <td class="text-center align-middle" width="15%" style="font-size: 12px!important;"><b>REMARKS</b></span></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studgrades as $item)
                        <tr>
                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 11px!important; padding: 1px 0px 1px 4px !important">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            <td class="text-center align-middle">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                            <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td colspan="4" class="text-center" style="font-size: 12.5px!important;"><b>GENERAL AVERAGE</b></td>
                        <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                        <td class="text-center align-middle" ><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                    </tr>
                </tbody>
            </table>
            <table class="mb-0 mt-0" width="100%" style="table-layout: fixed; font-size: 12px!important; margin: 0px 0px 0px 0px;">
                <tr>
                    <td width="43%" class="text-center" style="font-size: 12px!important"><b>Descriptors</b></td>
                    <td width="20%" class="text-center" style="font-size: 12px!important"><b>Grading Scale</b></td>
                    <td width="31%" class="text-center" style="font-size: 12px!important"><b>Remarks</b></td>
                    <td width="6%"></td>
                </tr>
                <tr>
                    <td class="" style="padding-left: 45px;">Outstanding</td>
                    <td class="text-center">90-100</td>
                    <td class="text-center">Passed</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="" style="padding-left: 45px;">Very Satisfacory</td>
                    <td class="text-center">85-89</td>
                    <td class="text-center">Passed</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="" style="padding-left: 45px;">Satisfacory</td>
                    <td class="text-center">80-84</td>
                    <td class="text-center">Passed</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="" style="padding-left: 45px;">Fairly Satisfacory</td>
                    <td class="text-center">75-79</td>
                    <td class="text-center">Passed</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="" style="padding-left: 45px;">Did not Meet Expectations</td>
                    <td class="text-center">Below 75</td>
                    <td class="text-center">Failed</td>
                    <td></td>
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
            @php
                $width = count($attendance_setup) != 0? 74 / count($attendance_setup) : 0;
            @endphp
            <table width="100%" class="table table-bordered table-sm grades mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="19%" style="border: 1px solid #000; text-align: center; height: 20px;"></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" width="{{$width}}%"><span style="font-size: 12px!important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                    @endforeach
                    <td class="text-center" width="7%" style="vertical-align: middle; font-size: 12px!important;"><span>Total</span></td>
                </tr>
                <tr class="table-bordered">
                    <td>Days of School</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td>Days Present</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td>Days Absent</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td>Days Absent</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle"></td>
                    @endforeach
                    <td class="text-center align-middle" ></td>
                </tr>
            </table>
            <table class="table-sm table mb-0" width="100%" style="table-layout: fixed;">
                <tr>
                    <td class="align-middle text-center"style="font-size: 12px!important;"><b>REPORTS ON LEARNERS OBSERVED VALUES</b></td>
                </tr>
            </table>
            <table class="table-sm table table-bordered mb-0" width="100%" style="table-layout: fixed;">
                <tr>
                    <td class="align-middle text-center" style="font-size: 12px;"><b>Core Values</b></td>
                    <td class="align-middle text-center" style="font-size: 12px;"><b>Behavior Statements</b></td>
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
                {{-- ========================================================== --}}
            </table>
            <table width="100%" class="table-sm mb-0 mt-0" style="table-layour: fixed; font-size: 12px;">
                <tr>
                    <td width="19%" class="p-0 text-center"><b>Marking</b></td>
                    <td width="81%" class="p-0"><b>Non-numerical Rating</b></td>
                </tr>
                <tr>
                    <td class="p-0 text-center">AO</td>
                    <td class="p-0">Always Observed</td>
                </tr>
                <tr>
                    <td class="p-0 text-center">SO</td>
                    <td class="p-0">Sometimes Observed</td>
                </tr>
                <tr>
                    <td class="p-0 text-center">RO</td>
                    <td class="p-0">Rarely Observed</td>
                </tr>
                <tr>
                    <td class="p-0 text-center">NO</td>
                    <td class="p-0">Not Observed</td>
                </tr>
            </table>
            <table class="table-sm table-bordered table mb-0" width="100%" style="table-layout: fixed;">
                <tr>
                    <td class="align-middle text-center p-0"style="font-size: 12px!important;"><b>PARENT'S/GUARDIANS SIGNATURE</b></td>
                </tr>
            </table>
            <table class="table-sm table mb-0" width="100%" style="table-layout: fixed; font-size: 12px!important;">
                <tr>
                    <td width="19%" class="align-middle text-left p-0"><i>First Grading</i></td>
                    <td width="81%" class="border-bottom"></td>
                </tr>
            </table>
            <table class="table-sm table mb-0" width="100%" style="table-layout: fixed; font-size: 12px!important;">
                <tr>
                    <td width="19%" class="align-middle text-left p-0"><i>Second Grading</i></td>
                    <td width="81%" class="border-bottom"></td>
                </tr>
            </table>
            <table class="table-sm table mb-0" width="100%" style="table-layout: fixed; font-size: 12px!important;">
                <tr>
                    <td width="19%" class="align-middle text-left p-0"><i>Third Grading</i></td>
                    <td width="81%" class="border-bottom"></td>
                </tr>
            </table>
            <table class="table-sm table mb-0" width="100%" style="table-layout: fixed; font-size: 12px!important;">
                <tr>
                    <td width="19%" class="align-middle text-left p-0"><i>Fourth Grading</i></td>
                    <td width="81%" class=""></td>
                </tr>
            </table>
            <table class="table-sm table-bordered table mb-0" width="100%" style="table-layout: fixed;">
                <tr>
                    <td class="align-middle text-center p-0"style="font-size: 12px!important;"><b>CERTIFICATE OF TRANSFER</b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px; margin-top: 15px;">
                <tr>
                    <td width="27%" class="p-0">Admitted to Grade:</td>
                    <td width="25%" class="p-0 text-left" style="border-bottom: 1px solid #000"></td>
                    <td width="23%" class="p-0 text-center">Section:</td>
                    <td width="26%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b></b></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px;">
                <tr>
                    <td width="42%" class="p-0 text-left">Eligibility for Admission to Grade:</td>
                    <td width="25%" class="p-0 text-left" style="border-bottom: 1px solid #000"></td>
                    <td width="33%" class="p-0"></td>
                </tr>
            </table>
            <table width="100%" style="font-size: 12px;">
                <tr>
                    <td width="100%" class="p-0 text-left">Approved:</td>
                </tr>
            </table>
            <table width="100%" style="margin-top: 15px;">
                <tr>
                    <td class="p-0 text-center" width="40%" style="border-bottom: 1px solid #000; font-size: 13px;"><b>{{$principal_info[0]->name}}</b></td>
                    <td class="p-0" width="10%" style=""></td>
                    <td class="p-0 text-center" width="40%" style="border-bottom: 1px solid #000; font-size: 13px;"><b>{{$adviser}}</b></td>
                    <td width="10%"></td>
                </tr>
                <tr>
                    {{-- <td class="p-0" width="45 %" style="text-align: center; font-size: 12px;">{{$principal_info[0]->title}}</td> --}}
                    <td class="p-0" style="text-align: center; font-size: 13px;">Principal</td>
                    <td class="p-0" style=""></td>
                    <td class="p-0" style="text-align: center; font-size: 13px;">Teacher</td>
                    <td></td>
                </tr>
            </table>
            <table class="table-sm table-bordered table mb-0" width="100%" style="table-layout: fixed;">
                <tr>
                    <td class="align-middle text-center p-0"style="font-size: 12px!important;"><b>CANCELLATION OF ELIGIBILITY TO TRANSFER</b></td>
                </tr>
            </table>
            <table class="table-sm table mb-0" width="100%" style="table-layout: fixed; font-size: 12px;">
                <tr>
                    <td width="19%" class="text-right p-0">Admitted in&nbsp;</td>
                    <td width="36%" class="text-right p-0 border-bottom"></td>
                    <td width="45%"></td>
                </tr>
                <tr>
                    <td class="text-right p-0">Date&nbsp;</td>
                    <td class="text-right p-0 border-bottom"></td>
                    <td></td>
                </tr>
            </table>
            <table class="table-sm table mb-0" width="100%" style="table-layout: fixed; font-size: 12px;">
                <tr>
                    <td width="55%"></td>
                    <td width="35%" class="text-right p-0 border-bottom"></td>
                    <td width="10%"></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center p-0">Principal</td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>