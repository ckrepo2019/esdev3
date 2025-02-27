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
    /* @page {  
        margin:20px 20px;
        
    } */
    body { 
        /* margin:0px 10px; */
        
    }
    
        .check_mark {
            font-family: ZapfDingbats, sans-serif;
        }
    @page { size: 11in 8.5in; margin: .25in .25in;}
    
</style>
</head>
<body>  
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td width="50%" style="height; 100px; vertical-align: top;font-size: 14px; padding-right: .25in!important;">
                {{-- attendance --}}
                <table width="100%" style="margin-top: 5px;">
                    <tr>
                        <td width="100%" class="text-center"><b>REPORT ON ATTENDANCE</b></td>
                    </tr>
                </table>
                @php
                    $width = count($attendance_setup) != 0? 66 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm grades mb-0" width="100%">
                    <tr>
                        <td width="24%" style="border: 1px solid #000; text-align: center;"></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle;" width="{{$width}}%"><span style="text-transform: capitalize; font-size: 10px!important"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}.</b></span></td>
                        @endforeach
                        <td class="text-center" width="10%" style="vertical-align: middle; font-size: 9px!important;"><span><b>TOTAL</b></span></td>
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
                    <tr class="table-bordered">
                        <td>No. of  tardy</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
                <br>
                <br>
                <br>
                <table width="100%" class="mb-0 mt-0" style="font-size: 15px;" >
                    <tr>
                        <td colspan="4" width="100%" class="p-0 text-center"><b>PARENT/ GUARDIAN'S NAME & SIGNATURE</b></td>
                    </tr>
                    <tr>
                        <td width="24%" class="p-0 text-left"></td>
                        <td width="48%" class="p-0 text-center">NAME</td>
                        <td width="5%" class="p-0"></td>
                        <td width="23%" class="p-0 text-center">SIGNATURE</td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px; margin-top: 5px;">
                    <tr>
                        <td width="24%" class="p-0 text-left"><b>1ST QUARTER</b></td>
                        <td width="48%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="5%" class="p-0"></td>
                        <td width="23%" class="p-0" style="border-bottom: 1px solid #000"></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px; margin-top: 5px;">
                    <tr>
                        <td width="24%" class="p-0 text-left"><b>2ND QUARTER</b></td>
                        <td width="48%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="5%" class="p-0"></td>
                        <td width="23%" class="p-0" style="border-bottom: 1px solid #000"></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px; margin-top: 5px;">
                    <tr>
                        <td width="24%" class="p-0 text-left"><b>3RD QUARTER</b></td>
                        <td width="48%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="5%" class="p-0"></td>
                        <td width="23%" class="p-0" style="border-bottom: 1px solid #000"></td>
                    </tr>
                </table>
                <table width="100%" style="font-size: 12px; margin-top: 5px;">
                    <tr>
                        <td width="24%" class="p-0 text-left"><b>4TH QUARTER</b></td>
                        <td width="48%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="5%" class="p-0"></td>
                        <td width="23%" class="p-0" style="border-bottom: 1px solid #000"></td>
                    </tr>
                </table>
                
            </td>
            <td width="50%" style="vertical-align: top; padding-left: .25in!important;">
                <table class="table mb-0 mt-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="25%" class="p-0"></td>
                        <td width="50%" class="p-0"></td>
                        <td width="25%" class="p-0 text-center">DepEd FORM 138</td>
                    </tr>
                </table>
                <table class="table mt-0" style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td width="22%" style="text-align: left;">
                            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px">
                        </td>
                        <td style="width: 56%; text-align: center;">
                            <div style="width: 100%;">Republic of the Philippines</div>
                            <div style="width: 100%;">Department of Education</div>
                            <div style="width: 100%;"><b>REGION XI</b></div>
                            <div style="width: 100%;">&nbsp;</div>
                            <div style="width: 100%;"><b><u>DAVAO ORIENTAL</u></b></div>
                            <div style="width: 100%;"><i>Division</i></div>
                            <div style="width: 100%;"><b><u>MANAY</u></b></div>
                            <div style="width: 100%;"><i>District</i></div>
                            <div style="width: 100%;"><b><u>MARYKNOLL SCHOOL OF MANAY, INC</u></b></div>
                            <div style="width: 100%;"><i>School</i></div>
                            <div style="width: 100%;"><b>SCHOOL YEAR: {{$schoolyear->sydesc}}</b></div>
                        </td>
                        <td width="22%">
                            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
                        </td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px; margin-top: 10px;" >
                    <tr>
                        <td width="15%" class="p-0"><b>NAME:</b></td>
                        <td width="55%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->student}}</b></td>
                        <td width="10%" class="p-0 text-right"><b>AGE:</b></td>
                        <td width="10%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->age}}</b></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px; margin-top: 10px;" >
                    <tr>
                        <td width="15%" class="p-0"><b>SECTION</b></td>
                        <td width="45%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->sectionname}}</b></td>
                        <td width="10%" class="p-0 text-left">SEX:</td>
                        <td width="20%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->gender}}</b></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 11px; margin-top: 10px;">
                    <tr>
                        <td width="17%" class="p-0"><b>LRN:</b></td>
                        <td width="48%" class="p-0 text-left" style="border-bottom: 1px solid #000"><b>{{$student->lrn}}</b></td>
                        <td width="35%" class="p-0"></td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="15%" class="p-0"><i>Dear Parent:</i></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="18%" class="p-0"></td>
                        <td width="82%" class="p-0"><i>This report card shows the ability and progress your child has</i></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="82%" class="p-0"><i>made in the different learning areas well as his/her core values.</i></td>
                        <td width="18%" class="p-0"></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="15%" class="p-0"></td>
                        <td width="85%" class="p-0"><i>The school welcomes you should you desire to know more about</i></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td width="82%" class="p-0"><i>your child's progress.</i></td>
                        <td width="18%" class="p-0"></td>
                    </tr>
                </table>
    
                <br>
    
                <table style="width: 100%; padding-top: 25px; text-align: center;">
                    <tr>
                        <td class="p-0" width="45%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$adviser}}</b></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">{{$principal_info[0]->title}}</td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">Class Adviser</td>
                    </tr>
                </table>
    
                <br>
                <table style="width: 100%;">
                    <tr>
                        <td class="text-center" style="font-size: 12px;"><b>CERTIFICATE OF TRANSFER</b></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="25%" class="p-0">Admitted to Grade:</td>
                        <td width="20%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b>10</b></td>
                        <td width="45%" class="p-0" style="padding-left: 10px!important;">Eligibility for Admission to Grade:</td>
                        <td width="10%" class="p-0 text-center" style="border-bottom: 1px solid #000"><b>{{$student->sectionname}}</b></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="100%" class="p-0 text-left">Approved:</td>
                    </tr>
                </table>
                <br>
                <table width="100%">
                    <tr>
                        <td class="p-0 text-center" width="45%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0 text-center" width="45%" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$adviser}}</b></td>
                    </tr>
                    <tr>
                        <td class="p-0" width="45 %" style="text-align: center; font-size: 12px;">{{$principal_info[0]->title}}</td>
                        <td class="p-0" width="10%" style=""></td>
                        <td class="p-0" width="45%" style="text-align: center; font-size: 12px;">Class Adviser</td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="100%" class="p-0 text-center" style="padding-left: 130px!important;">Cancellation of Eligibility to Transfer</td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="15%" class="p-0">Admitted in:</td>
                        <td width="20%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="65%" class="p-0"></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="15%" class="p-0">Date:</td>
                        <td width="20%" class="p-0 text-center" style="border-bottom: 1px solid #000"></td>
                        <td width="15%" class="p-0"></td>
                        <td width="50%" class="p-0 text-center" style="border-bottom: 1px solid #000; font-size: 12px;"><b>{{$principal_info[0]->name}}</b></td>
                    </tr>
                </table>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td width="15%" class="p-0"></td>
                        <td width="20%" class="p-0"></td>
                        <td width="15%" class="p-0"></td>
                        <td width="50%" class="p-0 text-center">{{$principal_info[0]->title}}</td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td width="50%" style="font-size: 12px; height; 100px; vertical-align: top; padding-right: .25in!important;">
                <table class="table-sm" width="100%">
                    <tr>
                        <td colspan="6" class="align-middle text-center" style="font-size: 14px!important;"><b>REPORTS ON LEARNER'S OBSERVED VALUES</b></td>
                    </tr>
                </table>
                <table class="table-sm table table-bordered" width="100%" style="margin-top: 10px;">
                    {{-- <tr>
                        <td colspan="6" class="align-middle text-center">Reports on Learner's Observed Values</td>
                    </tr> --}}
                    <tr>
                        <td rowspan="2" class="text-center" style="vertical-align: bottom!important;"><b>Core Values</b></td>
                        <td rowspan="2" class="text-center" style="vertical-align: bottom!important;"><b>Behavior Statements</b></td>
                        <td colspan="4" class="cellRight"><center><b>QUARTER</b></center></td>
                    </tr>
                    <tr>
                        <td width="7.5%"><center><b>1</b></center></td>
                        <td width="7.5%"><center><b>2</b></center></td>
                        <td width="7.5%"><center><b>3</b></center></td>
                        <td width="7.5%"><center><b>4</b></center></td>
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
                                    <td width="52%" class="align-middle">{{$item->description}}</td>
                                    <td class="text-center" style="vertical-align: bottom!important;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center" style="vertical-align: bottom!important;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-centere" style="vertical-align: bottom!important;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center" style="vertical-align: bottom!important;">
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
                <table class="table-sm mb-0 mt-0" width="100%">
                    <tr>
                        <td width="15%" class="p-0 text-center"><b>Marking</b></td>
                        <td width="20%" class="p-0"></td>
                        <td width="25%" class="p-0"><b>Non-numerical Rating</b></td>
                        <td width="40%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="p-0 text-center">AO</td>
                        <td width="20%" class="p-0"></td>
                        <td width="30%" class="p-0">Always Observed</td>
                        <td width="40%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="p-0 text-center">SO</td>
                        <td width="20%" class="p-0"></td>
                        <td width="25%" class="p-0">Sometimes Observed</td>
                        <td width="40%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="p-0 text-center">RO</td>
                        <td width="20%" class="p-0"></td>
                        <td width="25%" class="p-0">Rarely Observed</td>
                        <td width="40%" class="p-0"></td>
                    </tr>
                    <tr>
                        <td width="15%" class="p-0 text-center">NO</td>
                        <td width="20%" class="p-0"></td>
                        <td width="25%" class="p-0">Not Observed</td>
                        <td width="40%" class="p-0"></td>
                    </tr>
                </table>
            </td>
            <td width="50%" style="height; 100px; vertical-align: top; padding-left: .25in!important;">
                @php
                    $x = 1;
                @endphp
                <table class="table table-bordered table-sm grades mb-0" width="100%">
                    <tr>
                        <td class="text-center" colspan="5" style="font-size: 14px!important;"><i><b>FIRST SEMESTER</b></i></td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5" style="font-size: 14px!important;"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td>
                    </tr>
                    <tr>
                        <td width="52%" rowspan="2"  class="text-center" style="vertical-align: bottom!important; background-color: #EEECE1;"><b>Learning Areas</b></td>
                        <td width="24%" colspan="2"  class="text-center align-middle" style=" background-color: #E6B8B7;"><b>Quarter</b></td>
                        <td width="12%" class="text-center align-middle" style="background-color:#EBF1DE;"><b>Final</b></td>
                        <td width="12%" rowspan="2"  class="text-center align-middle" style="font-size: 9px!important;background-color:#FDE9D9;"><b>Remarks</b></td>
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="12%" class="text-center align-middle" style="background-color:#E4DFEC;"><b>1</b></td>
                            <td width="12%" class="text-center align-middle" style="background-color:#DAEEF3;"><b>2</b></td>
                            <td class="text-center align-middle" style="background-color:#EBF1DE;"><b>Grade</b></td>
                        @elseif($x == 2)
                            <td width="12%" class="text-center align-middle" style="background-color:#E4DFEC;"><b>3</b></td>
                            <td width="12%" class="text-center align-middle" style="background-color:#DAEEF3;"><b>4</b></td>
                            <td class="text-center align-middle" style="background-color:#EBF1DE;"><b>Grade</b></td>
                        @endif
                    </tr>
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="5"><b>Core</b></td>
                    </tr> --}}
                    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="5"><b>Applied</b></td>
                    </tr> --}}
                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
                    </tr> --}}
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="5"><b>Specialized</b></td>
                    </tr> --}}
                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td class="text-center"><b>GEN. AVERAGE</b></td>
                        <td class="text-right" colspan="3" style="font-weight: bold; font-size: 13px!important;padding-right: 32px;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                        <td></td>
                    </tr>
                </table>

                {{-- second sem subject shs--}}
                @php
                    $x = 2;
                @endphp
                <table class="table table-bordered table-sm grades" width="100%">
                    <tr>
                        <td class="text-center" colspan="5" style="font-size: 14px!important;"><i><b>SECOND SEMESTER</b></i></td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="5" style="font-size: 14px!important;"><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</b></td>
                    </tr>

                    <tr>
                        <td width="52%" rowspan="2"  class="text-center" style="vertical-align: bottom!important; background-color: #EEECE1;"><b>Learning Areas</b></td>
                        <td width="24%" colspan="2"  class="text-center align-middle" style=" background-color: #E6B8B7;"><b>Quarter</b></td>
                        <td width="12%" class="text-center align-middle" style="background-color:#EBF1DE;"><b>Final</b></td>
                        <td width="12%" rowspan="2"  class="text-center align-middle" style="font-size: 9px!important;background-color:#FDE9D9;"><b>Remarks</b></td>
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="12%" class="text-center align-middle" style="background-color:#E4DFEC;"><b>1</b></td>
                            <td width="12%" class="text-center align-middle" style="background-color:#DAEEF3;"><b>2</b></td>
                            <td class="text-center align-middle" style="background-color:#EBF1DE;"><b>Grade</b></td>
                        @elseif($x == 2)
                            <td width="12%" class="text-center align-middle" style="background-color:#E4DFEC;"><b>3</b></td>
                            <td width="12%" class="text-center align-middle" style="background-color:#DAEEF3;"><b>4</b></td>
                            <td class="text-center align-middle" style="background-color:#EBF1DE;"><b>Grade</b></td>
                        @endif
                    </tr>
                    {{-- <tr>
                        <td width="52%" rowspan="2"  class="text-left" style="vertical-align: bottom!important;"><b>Learning Areas</b></td>
                        <td width="24%" colspan="2"  class="text-center align-middle" ><b>Quarter</b></td>
                        <td width="12%" class="text-center align-middle" ><b>Final</b></td>
                        <td width="12%" rowspan="2"  class="text-center align-middle" style="font-size: 9px!important;"><b>Remarks</b></td>
                    </tr>
                    <tr>
                        @if($x == 1)
                            <td width="12%" class="text-center align-middle"><b>1</b></td>
                            <td width="12%" class="text-center align-middle"><b>2</b></td>
                            <td class="text-center align-middle" ><b>Grade</b></td>
                        @elseif($x == 2)
                            <td width="12%" class="text-center align-middle"><b>3</b></td>
                            <td width="12%" class="text-center align-middle"><b>4</b></td>
                            <td class="text-center align-middle" ><b>Grade</b></td>
                        @endif
                    </tr> --}}
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>Core</b></td>
                    </tr> --}}
                    @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>Applied</b></td>
                    </tr> --}}
                    @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>CONTEXTUALIZED SUBJECTS</b></td>
                    </tr> --}}
                    {{-- <tr class="trhead">
                        <td style="text-align: left;" colspan="4"><b>Specialized</b></td>
                    </tr> --}}
                    @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                        <tr>
                            <td>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            @if($x == 1)
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            @elseif($x == 2)
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            @endif
                            <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    
                    <tr>
                        @php
                            $genave = collect($finalgrade)->where('semid',$x)->first();
                        @endphp
                        <td class="text-center"><b>GEN. AVERAGE</b></td>
                        <td class="text-right" colspan="3" style="font-weight: bold; font-size: 13px!important;padding-right: 32px;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>