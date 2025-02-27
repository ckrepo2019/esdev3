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
            height: 50px;
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
            transform-origin: 17 17;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        @page {  
            margin:20px 20px;
            
        }
        .yunit {
            position: absolute; 
            top: 23.8%;
            left: 230;
            /* -moz-transform: rotate(-90.0deg);
            -o-transform: rotate(-90.0deg);  
            -webkit-transform: rotate(-90.0deg);  Saf3.1+, Chrome */
            filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -2em;
            margin-right: -2em;
            transform: rotate(-90deg);
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 4in 8.5in; margin: 15px 15px;  }
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed; page-break-after: always;">
    <tr>
        <td class="p-0">
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px">
                <tr>
                    <td width="30%" class="text-left p-0" style="">DepEd Form 138</td>
                    <td width="32%" class="text-left p-0" style=""></td>
                    <td width="8%" class="text-left p-0" style="">LRN</td>
                    <td width="30%" class="text-left p-0" style="border-bottom: 1px solid #000;">{{$student->lrn}}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-left p-0" style="">DepEd Order No. 08 s. 2015</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td style="width: 15%; text-align: right; vertical-align: bottom;">
                        <div class="p-0" style="width: 100%;"><img style="padding-top: 4px;" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="40px"></div>
                    </td>
                    <td style="width: 70%; text-align: center; vertical-align: middle; margin-top: 10px;">
                        <div class="p-0" style="width: 100%; font-size: 10px;">Republic of the Philippines</div>
                        <div class="p-0" style="width: 100%; font-size: 10px;">Department of Education</div>
                        <div class="p-0" style="width: 100%; font-size: 10px;">Region X</div>
                        <div class="p-0" style="width: 100%; font-size: 10px;">Division of Bukidnon</div>
                        <div class="p-0" style="width: 100%; font-size: 10px;"><b>NUESTRA SEÑORA DEL PILAR HIGH SCHOOL</b></div>
                        <div class="p-0" style="width: 100%; font-size: 10px;"><i>Quezon, Bukidnon</i></div>
                    </td>
                    <td style="width: 15%; text-align: center; vertical-align: middle;">
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px;">
                <tr>
                    <td width="10%" class="text-left p-0" style="vertical-align: top;">Name:</td>
                    <td width="60%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</td>
                    <td width="7%" class="text-left p-0" style="vertical-align: top;">Sex:</td>
                    <td width="23%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->gender}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 3px;">
                <tr>
                    <td width="9%" class="text-left p-0" style="vertical-align: top;">Age:</td>
                    <td width="23%" class="text-left p-0" style="border-bottom: 1px solid #000;">{{$student->age}}</td>
                    <td width="15%" class="text-right p-0" style="vertical-align: top;">Grade&nbsp;</td>
                    <td width="15%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{str_replace('GRADE', '', $student->levelname)}}</td>
                    <td width="15%" class="text-center p-0" style="vertical-align: top; ">Section</td>
                    <td width="23%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->sectionname}}</td>

                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 3px;">
                <tr>
                    <td width="17%" class="text-left p-0" style="vertical-align: top;">Curriculum</td>
                    <td width="20%" class="text-center p-0" style="border-bottom: 1px solid #000;">K to 12</td>
                    <td width="8%" class="text-center p-0" style=""></td>
                    <td width="20%" class="text-left p-0" style="vertical-align: top;">School Year</td>
                    <td width="35%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$schoolyear->sydesc}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 11px; margin-top: 5px;">
                <tr>
                    <td width="100%" class="text-center p-0" style=""><b>Report on Learning Process and Achievement</b></td>
                </tr>
            </table>
            <table width="100%" class="table table-sm table-bordered grades" style="margin-top: 5px;">
                <thead>
                    <tr>
                        <td rowspan="2"  class="align-middle text-center" width="42%" style="font-size: 10px!important;"><b>Learning Areas</b></td>
                        <td colspan="4"  class="text-center align-middle" style="font-size: 10px!important;"><b>Quarter</b></td>
                        <td rowspan="2" class="text-center align-middle p-0" width="12%"  style="font-size: 9px!important;">
                            <div style="padding-top: 0px;"><b>Final</b></div>
                            <div style="padding-top: 5px;"><b>Rating</b></div>
                        </td>
                        <td rowspan="2" class="text-center align-middle" width="6%"  style="font-size: 7.8px!important;">
                            <div class="yunit">
                                <b>YUNIT</b>
                            </div>
                        </td>
                        <td rowspan="2"  class="text-center align-middle" width="16%" style="font-size: 9px!important;"><b>Remarks</b></span></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" width="6%" style="font-size: 9px!important;"><b>1</b></td>
                        <td class="text-center align-middle" width="6%" style="font-size: 9px!important;"><b>2</b></td>
                        <td class="text-center align-middle" width="6%" style="font-size: 9px!important;"><b>3</b></td>
                        <td class="text-center align-middle" width="6%" style="font-size: 9px!important;"><b>4</b></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studgrades as $item)
                        <tr>
                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 9px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                            <td></td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-left" style="font-size: 10px!important;">GEN. AVERAGE</td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 10px!important;">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                        <td></td>
                        <td class="text-center align-middle" style="font-size: 10px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px; padding-left: 20px; padding-right: 20px;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 10px;"><b>Descriptors</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 0px; padding-left: 20px; padding-right: 10px;">
                <tr>
                    <td width="20%" class="text-left p-0" style="font-size: 8px;">Outstanding</td>
                    <td width="30%" class="text-left p-0" style="font-size: 8px;">- 90% and above</td>
                    <td width="30%" class="text-left p-0" style="font-size: 8px;">Fairly Satisfactory</td>
                    <td width="20%" class="text-left p-0" style="font-size: 8px;">- 75% - 79%</td>
                </tr>
                <tr>
                    <td width="20%" class="text-left p-0" style="font-size: 8px;">Very Satisfactory</td>
                    <td width="30%" class="text-left p-0" style="font-size: 8px;">- 85% - 89%</td>
                    <td width="30%" class="text-left p-0" style="font-size: 8px;">Did not Meet Expectations</td>
                    <td width="20%" class="text-left p-0" style="font-size: 8px;">- 75% and below</td>
                </tr>
                <tr>
                    <td width="20%" class="text-left p-0" style="font-size: 8px;">Satisfactory</td>
                    <td width="30%" class="text-left p-0" style="font-size: 8px;">- 80% - 84%</td>
                    <td width="30%" class="text-left p-0" style="font-size: 8px;"></td>
                    <td width="20%" class="text-left p-0" style="font-size: 8px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 11px;"><b>Report on Attendance</b></td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 66 / count($attendance_setup) : 0;
            @endphp
            <table class="table table-bordered table-sm grades mb-0" width="100%" style="table-layout: fixed;">
                <tr>
                    <td style="padding-top: 13px!important;border: 1px solid #000; text-align: center;"></td>
                    @foreach ($attendance_setup as $item)
                        <td class="aside text-center align-middle;" width="{{$width}}%"><span style="font-size: 8px!important;"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</b></span></td>
                    @endforeach
                    <td class="text-left p-0" width="10%" style="vertical-align: bottom; font-size: 7px!important;"><span style="">&nbsp;<b>TOTAL</b></span></td>
                </tr>
                <tr class="table-bordered">
                    <td width="24%"  style="font-size: 7px!important;"><b>No. of school days</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 7px!important;"><b>No. of days present</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 7px!important;"><b>No. of days absent</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 11px;"><b><i>Certificate of Transfer</i></b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 3px;">
                <tr>
                    <td width="28%" class="text-left p-0"><i>Admitted to Grade</i></td>
                    <td width="34%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="11%" class="text-center p-0">Section</td>
                    <td width="27%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 3px;">
                <tr>
                    <td width="50%" class="text-left p-0"><i>Eligibility for Admission to Grade:</i></td>
                    <td width="30%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="20%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 3px;">
                <tr>
                    <td width="100%" class="text-left p-0"><i>Approved:</i></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 8.5px; margin-top: 15px; padding-left: 5px;">
                <tr>
                    <td width="40%" class="text-left p-0" style=""><b><u>SR. LEONILA S. SAJELAN,MCM</u></b></td>
                    <td width="10%" class="text-left p-0"><b></b></td>
                    <td width="50%" class="text-left p-0" style=""><b><u>MS. SHEENA VERL T. BAGALOYOS, LPT</u></b></td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-0" style=""><i>School Principal</i></td>
                    <td width="10%" class="text-left p-0"><b></b></td>
                    <td width="50%" class="text-center p-0" style=""><i>Adviser</i></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 3px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 11px;"><b><i>Cancellation of Eligibility to Transfer</i></b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 3px;">
                <tr>
                    <td width="18%" class="text-left p-0">Admitted in:</td>
                    <td width="27%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="55%" class="text-left p-0"></td>
                </tr>
            </table><table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px; margin-top: 3px;">
                <tr>
                    <td width="9%" class="text-left p-0">Date:</td>
                    <td width="36%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="55%" class="text-left p-0"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td class="p-0">
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 11px;"><b>PROGRESS ON STUDENTS VALUES <br>AND ATTITUDES</b></td>
                </tr>
            </table>
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; font-size: 8.5px">
                <tr>
                    <td class="p-0" style=""><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Instruction:</b> Write the appropriate letters for each value/virtue <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; in the appropriate column as indicated in the legend</td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="100%">
                        <div><b>&nbsp;&nbsp;Observed Values</b></div>
                        <div><b>&nbsp;&nbsp;&nbsp;Marking</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Non-numerical Rating</b></div>
                        <div>&nbsp;&nbsp;&nbsp;AO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Always Observed</div>
                        <div>&nbsp;&nbsp;&nbsp;SO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sometimes Observed</div>
                        <div>&nbsp;&nbsp;&nbsp;RO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rarely Observed</div>
                        <div>&nbsp;&nbsp;&nbsp;NO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Not Observed</div>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered grades mb-0"  width="100%" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td rowspan="2" width="70%" class="text-center align-middle  p-0" style="font-size: 11px!important;"><b>Observed Values and Attitudes</b></td>
                    <td colspan="4" class="text-center p-0" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>Quarter</b></td>
                </tr>
                <tr>
                    <td width="7.5%" class="align-middle text-center p-0" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>1</b></td>
                    <td width="7.5%" class="align-middle text-center p-0" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>2</b></td>
                    <td width="7.5%" class="align-middle text-center p-0" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>3</b></td>
                    <td width="7.5%" class="align-middle text-center p-0" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>4</b></td>
                </tr>
                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                  @php
                      $count = 0;
                  @endphp
                  @foreach ($groupitem as $item)
                      @if($item->value == 0)
                      @else
                          <tr>
                              @if($count == 0)
                                      <td class="text-left p-0" style="font-size: 9px!important; vertical-align: middle; padding-top: 5px!important; padding-bottom: 5px!important; padding-left: 4px!important;" rowspan="{{count($groupitem)}}"><b>{{$item->group}}</b> - {{$item->description}}</td>
                                      @php
                                          $count = 1;
                                      @endphp
                              @endif
                              {{-- <td class="align-middle" style="font-size: 10px;"><b>{{$item->group}}</b></td> --}}
                              {{-- <td class="align-middle p-0" style="font-size: 8px;padding-left: 3px!important; padding-top: 5px!important; padding-bottom: 5px!important;">{{$item->description}}</td> --}}
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
                              <td class="text-center align-middle p-0" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                  @foreach ($rv as $key=>$rvitem)
                                      {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                  @endforeach 
                              </td>
                          </tr>
                      @endif
                  @endforeach
              @endforeach
            </table>
            <table class="table grades mb-0"  width="100%" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td class="text-left p-0"><b>Dear Parent,</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" style="font-size: 8.7px!important; padding-top: 3px!important;">This report card shows the ability and progress  your child has made in the <br> different learning areas as well as his/her progress in character <br> development.</td>
                </tr>
            </table>
            <table class="table grades mb-0"  width="100%" style="table-layout: fixed;">
                <tr>
                    <td class="text-left p-0" style="font-size: 8px!important; padding-top: 5px!important;">This school welcomes you if you desire to know more about your child's progress.</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 11px;"><b>Parent/Guardian's Signature</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="27%" class="text-left p-0" style="font-size: 11px;"><i>1st Quarter</i></td>
                    <td width="71%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="12%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 1px;">
                <tr>
                    <td width="27%" class="text-left p-0" style="font-size: 11px;"><i>2nd Quarter</i></td>
                    <td width="71%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="12%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 1px;">
                <tr>
                    <td width="27%" class="text-left p-0" style="font-size: 11px;"><i>3rd Quarter</i></td>
                    <td width="71%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="12%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 1px;">
                <tr>
                    <td width="27%" class="text-left p-0" style="font-size: 11px;"><i>4th Quarter</i></td>
                    <td width="71%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="12%" class="text-left p-0" style=""></td>
                </tr>
            </table>
        </td>
    </tr>
</body>
</html>