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
        .mt-0{
            margin-top: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family:'Times New Roman', Times, serif;
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
            font-size: 10pt!important;
        }
        .compress{
            margin-right: .3in;
            margin-left: .3in;
        }
        .compressed{
            margin-right: 1in;
            margin-left: 1in;
        }
        .very-compressed{
            margin: 0in 1.5in;
        }
        .times-new{
            font-family: 'Times New Roman', Times, serif!important;
        }
        .subject-type{
            background-color: rgb(207, 232, 240)
        }
        .linespacing{
            line-height: 14px;
        }
        .break{
            background-color:#000;
            border-top: 1px solid #000; 
            border-bottom: 1px solid #000;
            padding: 1px 0px;
        }
		 .check_mark {
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            }
        @page { size: 11in 8.5in; margin: .4in .4in;}
    </style>
</head>
<body>
    <table width="100%" style="page-break-after: always">
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
            <table width="100%" class="table table-bordered table-sm times-new grades mb-0 compress" style="table-layout: fixed; margin-top:.25in" >
                <tr>
                    <td width="10%" style="border: 1px solid #000; text-align: center; height: 20px;"></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle " width="{{$width}}%"><span class="small-font" style="">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                    @endforeach
                    <td class="text-center" width="7%" style="vertical-align: middle;"><span>Total</span></td>
                </tr>
                <tr class="table-bordered">
                    <td style="">No. of School<br> Days</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle" style="">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle" style="">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="">No. of days <br>Present</td>
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
                    <td style="">No. of days<br> Absent</td>
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
            <table width="100%" class="compressed title"  style="margin-top: .2in">
                <tr>
                    <td width="31%" style="padding: 5px">1<sup>st</sup> Quarter</td>
                    <td width="69%" style="padding: 5px" class="underline"></td>
                </tr>
                <tr>
                    <td style="padding: 5px">2<sup>nd</sup> Quarter</td>
                    <td style="padding: 5px" class="underline"></td>
                </tr>
                <tr>
                    <td style="padding: 5px">3<sup>rd</sup> Quarter</td>
                    <td style="padding: 5px" class="underline"></td>
                </tr>
                <tr>
                    <td style="padding: 5px">4<sup>th</sup> Quarter</td>
                    <td style="padding: 5px" class="underline"></td>
                </tr>
            </table>
            </td>
            <td width="50%" class="first-page" style="vertical-align: top">
                <table width="100%" style="">
                    <tr>
                        <td width="16%" class="text-right align-top" style="">
                            <div><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="60px"></div>
                            <div class="font text-center" style="font-size: 12pt;padding-left: 5px!important"><b>FORM 138-E</div>
                        </td>
                        <td width="75%" class="text-center p-0" style="">
                            <div class="times-new" style="font-size: 12pt!important"><b>PHILIPPINE ADVENT COLLEGE ACADEMY</div>
                            <div class="first-page">Sindangan, Zamboanga del Norte</div>
                            <div class="first-page">Philippines</div>
                            <div style="font-size: 8pt!important;margin-top:4px"><b>"The School that Prepares Students to Serve."</div>
                        </td>
                        <td width="9%"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center">
                            <div class="small-font">Association of Christian Schools, Colleges and Universities of the Phil's. (ACSCU)</div>
                            <div class="small-font">Fund for Assistance to Private Education (FAPE)</div>
                            <div class="small-font">Zamboanga, Basilan, Sulu, Tawi-Tawi, Association of Private Schools (ZAMBASULTAPS)</div>
                            <div class="small-font">Tel. No. (065) 224-2700E-mail add: philippineadventcollege@gmail.com</div>
                            <div class="small-font">e-mail add: philippineadventcollege@gmail.com</div>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <table width="100%" class="text-center ">
                    <tr>
                        <td width="100%" class="title times-new" style="margin-top: 5px!important"><b style="color: rgb(39, 39, 107)">JUNIOR HIGH SCHOOL</td>
                    </tr>
                </table>
                <table width="100%" class="compress" style="margin-top: 5px">
                    <tr>
                        <td width="20%">LRN:</td>
                        <td width="40%" class="underline"><b>{{$student->lrn}}</td>
                    </tr>
                    <tr> 
                        <td>Name:</td>
                        <td colspan="2" class="underline"><b>{{$student->student}}</td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td width="20%">Age:</td>
                        <td width="20%" class="underline"><b>{{$student->age}}</td>
                        <td width="15%">Sex:</td>
                        <td width="45%" class="underline"><b>{{$student->gender}}</td>
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
                        <td>
                            <p class="mb-0"><b>Dear Parent:</p>
                            <p class="mt-0 mb-0" style="margin-left:20px; text-indent: .2in"><b>This report card shows the ability and progress your child has made in the different learning areas as well as his/her core values.</p>
                            <p class="mt-0" style="margin-left:20px; text-indent: .2in"><b>This school welcomes you should you desire to know more about your child's progress.</p>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="compress text-center" style="margin-top: 5px">
                    <tr>
                        <td width="50%"></td>
                        <td width="50%"><u><b>{{$adviser}}</td>
                    </tr>
                    <tr>
                        <td ><u><b>{{$principal_info[0]->name}}</td>
                        <td>Teacher</td>
                    </tr>
                    <tr>
                        <td>{{$principal_info[0]->title}}</td>
                        <td></td>
                    </tr>
                </table>
                <table width="100%" class="break" style="margin-top: 20px">
                    <tr>
                        <td width="100%"></td>
                    </tr>
                </table>
                <table width="100%" class="text-center" style="margin-top: 5px">
                    <tr>
                        <td class="title"><b>CERTIFICATE OF TRANSFER</td>
                    </tr>
                </table>
                <table width="100%" class="compress" style="margin-top: 20px">
                    <tr>
                        <td width="28%">Admitted to Grade:</td>
                        <td width="22%" class="underline"></td>
                        <td width="13%">Section:</td>
                        <td width="37%" class="underline"></td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td width="48%">Eligibility for Admission to Grade:</td>
                        <td width="52%" class="underline"></td>
                    </tr>
                </table>
                <table width="100%" class="compress text-center mb-0" style="margin-top: .15in!important">
                    <tr>
                        <td width="33%"></td>
                        <td width="28%"></td>
                        <td width="38%" class="underline"><b>{{$adviser}}</td>
                    </tr>
                    <tr>
                        <td><i></td>
                        <td></td>
                        <td>Teacher</td>
                    </tr>
                </table>
                <table width="100%" class="compress text-center mt-0" style="">
                    <tr>
                        <td class="text-left" style=""><b><i>Approved:</i></b></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="33%" style="margin-top: .1in!important" class="underline"><b>{{$principal_info[0]->name}}</td>
                        <td width="33%"></td>
                        <td width="33%"><b></td>
                    </tr>
                    <tr>
                        <td>{{$principal_info[0]->title}}</td>
                        <td></td>
                        <td><i></td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td colspan="3" class="text-center"><b>Cancellation of Eligibility to Transfer</td>
                    </tr>
                    <tr>
                        <td style="margin-top: 5px!important" width="21%"><b>Admitted in:</td>
                        <td width="29%" class="underline"></td>
                        <td width="50%"></td>
                    </tr>
                </table>
                <table width="100%" class="compress">
                    <tr>
                        <td width="15%"><b>Date in:</td>
                        <td width="35%" class="underline"></td>
                        <td width="5%"></td>
                        <td width="45%" class="text-center underline"><b>{{$principal_info[0]->name}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center"><b>{{$principal_info[0]->title}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" class="second-page">
        <tr>
            <td width="50%" style="vertical-align: top; margin-right: .2in!important">
                <table width="100%" style="margin-top: .5in">
                    <tr>
                        <td class="title times-new text-center" style=""><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm table-bordered mb-0" style="table-layout: fixed; margin-top: 7px;">
                    <thead>
                        <tr style="">
                            <td rowspan="2" class="align-middle text-center" width="43%" style=""><b>LEARNING AREAS</b></td>
                            <td colspan="4"  class="text-center align-middle" style="font-size: 11pt!important;"><b>QUARTER</b></td>                                
                            <td rowspan="2" class="text-center align-middle" width="10%" style=""><b>Final <br> Grade</b></td>
                            <td rowspan="2" width="15%" class="text-center align-middle"><b>Remarks</td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="8%" ><b>1</b></td>
                            <td class="text-center align-middle" width="8%" ><b>2</b></td>
                            <td class="text-center align-middle" width="8%" ><b>3</b></td>
                            <td class="text-center align-middle" width="8%" ><b>4</b></td>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($studgrades as $item)
                        <tr>
                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; padding: 1px 0px 1px 4px !important"><b>{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            <td class="text-center align-middle">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                            <td class="text-center align-middle">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>

                        </tr>
                             @endforeach
                        <tr>
                            <td colspan="5" class="text-right"><b>GENERAL AVERAGE</b></td>
                            <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                            <td class="text-center align-middle" ><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                        </tr>
                    </tbody>
                 </table>
                 <table width="100%" class="compress-2nd" style="margin-top: 20px">
                    <tr>
                        <td width="40%"><b>Descriptors</td>
                        <td class="text-center" width="30%"><b>Grading Scale</td>
                        <td class="text-center" width="30%"><b>Remarks</td>
                    </tr>
                    <tr>
                        <td>Outstanding</td>
                        <td class="text-center" >90-100</td>
                        <td class="text-center" >Passed</td>
                    </tr>
                    <tr>
                        <td>Very Satisfactory</td>
                        <td class="text-center" >85-89</td>
                        <td class="text-center" >Passed</td>
                    </tr>
                    <tr>
                        <td>Satisfactory</td>
                        <td class="text-center" >80-84</td>
                        <td class="text-center" >Passed</td>
                    </tr>
                    <tr>
                        <td>Fairly Satisfactory</td>
                        <td class="text-center">75-79</td>
                        <td class="text-center">Passed</td>
                    </tr>
                    <tr>
                        <td>Did Not Meet Expectations</td>
                        <td class="text-center">Below 75</td>
                        <td class="text-center">Failed</td>
                    </tr>
                </table>
            </td>
            
            <td width="50%" style="vertical-align: top;margin-right: .2in!important">
                <table width="100%" class="" style="margin-top: .5in">
                <tr>
                        <td class="title times-new text-center"><b>REPORT ON LEARNER'S OBSERVED VALUES</td>
                    </tr>
                </table>
                <table class="table-sm table table-bordered compress-2nd"  width="100%" style="margin-top: 10px">
                    <thead>
                        <tr>
                            <th rowspan="2" width="28%" class="align-middle"><center >Core Values</center></th>
                            <th rowspan="2" width="40%" class="align-middle"><center>Behavior Statements</center></th>
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

                <table width="80%" class="second-page compress" style="margin-top: 15px">
                    <tr>
                        <td width="40%" class="title text-center"><b>Marking</td>
                        <td width="60%" class="title text-center"><b>Non-numerical Rating</td>
                    </tr>
                    <tr>
                        <td class="text-center" style="margin-top:20px!important"><b>AO</td>
                        <td class="text-center" style="margin-top:20px!important"><b>Always Observed</td>
                    </tr>
                    <tr>
                        <td class="text-center"><b>SO</td>
                        <td  class="text-center" style=""><b>Sometimes Observed</td>
                    </tr>
                    <tr>
                        <td class="text-center"><b>RO</td>
                        <td  class="text-center" style=""><b>Rarely Observed</td>
                    </tr>
                    <tr>
                        <td class="text-center"><b>NO</td>
                        <td  class="text-center" style=""><b>Never Observed</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>