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

            
        /* .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
        } */
        .p-0 {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }
        .mt-0 {
            margin-top: 0px !important;
            margin-bottom: 0px !important;
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
            font-family: Arial, Helvetica, sans-serif;
            
        }
        
            .check_mark {
                /* font-family: ZapfDingbats, sans-serif; */
            }
        @page { size: 4.3in 8.5in; margin: 10px 10px 10px 10px;  }
        
        #watermark1 {
        opacity: 1;
                position: absolute;
                top: 20%;
                left: 30%;
                opacity: .5;
                /* transform-origin: 10 10; */
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                /* width:    50cm;
                height:   50cm; */

                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }
    </style>
</head>
<body>  
    <div id="watermark1" style="padding-top: 100px;">
        <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="160px">
    </div>
    <table class="table table-sm grades mb-0"  width="100%" style="">
        <tr>
            <td width="25%" class="text-center">
                <div><span style="font-size: 6px!important;"><b>DepEd Form 9-Elementary</b></span></div>
                <div style="padding-top: 5px;"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="40px"></div>
            </td>
            <td width="50%" class="text-center" style="font-size: 10px!important;">
                <div><b>Republic of the Philippines</b></div>
                <div>Department of Education</div>
                <div>Region X, Northern Mindanao</div>
                <div>DIVISION OF LANAO DEL NORTE</div>
                <div><b>DISTRICT OF KOLAMBUGAN</b></div>
            </td>
            <td width="25%" class="text-center">
                <div><span style="font-size: 6px!important;">&nbsp;</div>
                <div style="padding-top: 5px;"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="40px"></div>
            </td>
        </tr>
    </table>
    <table class="table table-sm grades mt-0" width="100%" style="padding-top: 3px !important;">
        <tr>
            <td class="text-center p-0"  style="">
                  <div style="font-size:11px !important"><b>HOLY CROSS HIGH SCHOOL OF KOLAMBUGAN, INC.</b></div>
                  <div style="font-size:7px !important"><i>(Formerly Holy Cross High School)</i></div>
                  <div style="font-size:11px !important"><b>PROGRESS REPORT CARD</b></div>
                  <div style="font-size:8px !important">School Year {{$schoolyear->sydesc}}</div>
            </td>
        </tr>
      </table>     
      <table class="table table-sm grades mb-1" width="100%">
          <tr>
              <td width="7%" class="p-0" style="font-size: 10px!important;"><b>LRN:</b></td>
              <td width="83%" class="p-0" style="font-size: 10px!important;"><u>{{$student->lrn}}</u></td>
          </tr>
      </table>
      <div style="border: 2px solid rgb(6, 74, 137); padding: 4px !important; margin-top: 5px;">
        <table class="table table-sm grades mb-1" width="100%" style="font-size: 10px !important">
            @php
                $middle = '';
                if($student->middlename != null){
                    $temp_middle = explode(" ",$student->middlename);
                    foreach($temp_middle as $item){
                        $middle .=  $item[0].'.';
                    }
                }
            @endphp
            <tr>
                <td width="8%" style="font-size: 10px!important" class="p-0"><b>Name:</b></td>
                <td width="82%" class="border-bottom p-0" style="font-size: 10px!important">{{$student->lastname}}, {{$student->firstname}} {{$middle}}</td>
            </tr>
        </table>
      </div>
      <div style="border: 2px solid rgb(6, 74, 137); padding: 4px !important; margin-top: 5px;">
        <table class="table table-sm grades" width="100%" style="font-size: 10px!important; margin-bottom: 3px!important;">
            <tr style="">
                <td width="7%" class="p-0" style="font-size: 10px!important"><b>Age:</b></td>
                <td width="6%" class="p-0" class="border-bottom" style="font-size: 10px!important"><u>{{$student->age}}</u></td>
                <td width="7%" class="p-0" style="font-size: 10px!important"><b>Sex:</b></td>
                <td width="10%" class="p-0 text-center" style="font-size: 10px!important"><u>{{$student->gender}}</u></td>
                <td width="25%" class="p-0" style="font-size: 10px!important"><b>Grade and Section:</b></td>
                <td width="45%" class="border-bottom p-0" style="font-size: 10px!important">{{$student->levelname}}{{$student->levelid != 3 ?  ' - '.$student->sectionname:''}}</td>
            </tr>
          </table>
      </div>
        <table class="table table-sm grades mt-0" width="100%" style="padding-left: 10px!important;">
          <tr>
              <td width="100%" class="p-0">
                  <p style="font-size:9px;margin:0 !important">Dear Parents,</p>
                  <p style="font-size:10px!important; text-indent: 40px;margin:0 !important"><i>This report card shows the ability and progress your child has made in the different learning areas as well as his/her core values.</i></p>
                  <p style="font-size:10px!important; text-indent: 40px;margin:0 !important"><i>The school welcomes you if you desire to know more about the progress of your child.</i></p>
              </td>
          </tr>
        </table>
        <table table class="table table-sm grades mt-0" width="100%" style="padding: 15px 30px 0px 30px; font-size: 10px!important;">
            <tr>
                <td class="text-center p-0" width="45%"><b><u>JUVY BENDISULA LINGUIZ</u></b></td>
                <td width="10%" class="p-0"></td>
                <td class="text-center p-0" width="45%"><b><u>{{$adviser}}</u></b></td>
            </tr>
            <tr>
                <td class="text-center p-0" width="45%"><i>School Principal</i></td>
                <td width="10%" class="p-0"></td>
                <td class="text-center p-0" width="45%"><i>Adviser</i></td>
            </tr>
        </table>
        <table class="table table-sm grades" width="100%" style="padding: 0px 30px 0px 30px;">
            <tr>
                <td class="border-bottom"></td>
            </tr>
        </table>
        <table class="table table-sm grades mt-0" width="100%" style="padding-bottom: 10px;">
          <tr>
              <td class="text-center p-0"><b>CERTIFICATE OF TRANSFER</b></td>
          </tr>
        </table>
        <table class="table table-sm grades mt-0" width="100%" style="padding: 0px 30px 0px 30px; font-size: 10px!important;">
          <tr>
              <td width="27%" class="p-0">Admitted to Grade</td>
              <td width="11%" class="border-bottom p-0"></td>
              <td width="11%" class="p-0">Section</td>
              <td width="58%" class="border-bottom p-0"></td>
          </tr>
        </table>
        <table class="table table-sm grades mt-0" width="100%" style="padding: 5px 30px 0px 30px; font-size: 10px!important;">
          <tr>
              <td width="45%" class="text-left p-0">Eligibility for admission to Grade</td>
              <td width="55%" class="text-left border-bottom p-0"></td>
          </tr>
        </table>
        <table class="table table-sm grades mt-0" width="100%" style="padding: 5px 30px 0px 30px; font-size: 10px!important;">
          <tr>
              <td width="33%" class="p-0">Approved:</td>
              <td width="67%" class="p-0"></td>
          </tr>
        </table>
        <table table class="table table-sm grades" width="100%" style="padding: 15px 30px 0px 30px; font-size: 10px!important;">
          <tr>
              <td class="text-center p-0" width="45%"><b><u>JUVY BENDISULA LINGUIZ</u></b></td>
              <td width="10%" class="p-0"></td>
              <td class="text-center p-0" width="45%"><b><u>{{$adviser}}</u></b></td>
          </tr>
          <tr>
              <td class="text-center p-0" width="45%"><i>School Principal</i></td>
              <td width="10%" class="p-0"></td>
              <td class="text-center p-0" width="45%"><i>Adviser</i></td>
          </tr>
        </table>
        <table class="table table-sm grades mb-0" width="100%" style="padding: 0px 30px 0px 30px;">
            <tr>
                <td class="border-bottom"></td>
            </tr>
        </table>
        <table class="table table-sm grades mb-0" width="100%" style="padding: 7px 30px 9px 30px;">
            <tr>
                <td class="text-center p-0"><b>Cancellation of Eligibility to Transfer</b></td>
            </tr>
        </table>
        <table class="table table-sm grades" width="100%" style="padding: 0px 30px 0px 30px; font-size: 10px!important;">
            <tr>
                <td width="16%" class="p-0">Admitted in </td>
                <td width="35%" class="border-bottom p-0"></td>
                <td width="9%" class="p-0">Date:</td>
                <td width="40%" class="border-bottom p-0"></td>
            </tr>
        </table>
        <table class="table table-sm grades mt-2" width="100%">
          <tr>
             
              <td width="20%" class="p-0"></td>
              <td class="border-bottom text-center p-0" width="40%">&nbsp;</td>
              <td width="20%" class="p-0"></td>
          </tr>
          <tr>
            
              <td class="p-0"></td>
              <td class="text-center p-0"><i>Principal</i></td>
              <td class="p-0"></td>
          </tr>
        </table>
              


                  
              <table class="table table-sm mb-0 table-bordered" width="100%" style="font-size:8px !important; background-color:rgb(6, 74, 137);">
                  <tr>
                      <td class="p-1" style="color:white"><center>REPORT ON ATTENDANCE</center></td>
                  </tr>
              </table >
              <table class="table table-sm table-bordered mb-0" width="100%" style="font-size:8px !important">
                  @php
                       $width = count($attendance_setup) != 0? 65 / count($attendance_setup) : 0;
                  @endphp
                  <tr>
                      <th width="25%"></th>
                      @foreach ($attendance_setup as $item)
                          <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                      @endforeach
                      <th class="text-center text-center"  width="10%">Total </th>
                  </tr>
                  <tr>
                      <td>No. of School Days</td>
                      @foreach ($attendance_setup as $item)
                          <td class="text-center align-middle">{{$item->days}}</td>
                      @endforeach
                      <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                  </tr>
                  <tr >
                      <td>No. of School Days Present</td>
                      @foreach ($attendance_setup as $item)
                          <td class="text-center align-middle">{{$item->present}}</td>
                      @endforeach
                      <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                  </tr>
                  <tr>
                      <td  >No. of Times Absent</td>
                      @foreach ($attendance_setup as $item)
                          <td class="align-middle text-center p-0">{{$item->absent}}</td>
                      @endforeach
                      <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
                  </tr>
              </table>
                      
              <table class="table table-sm mb-0" width="100%" style="font-size:8px !important; background-color:rgb(6, 74, 137); color:white;">
                  <tr clas=" border-blue">
                      <td class="text-center p-1" width="25%" style="border-left: 1px solid #000; border-right: 1px solid #000;">QUARTER</td>
                      <td class="text-center p-1" width="50%" >PARENT’S / GUARDIAN’S SIGNATURE</td>
                      <td class="text-center p-1" width="25%" >DATE</td>
                  </tr>
              </table>
              <table class="table table-sm table-bordered" width="100%" style="font-size:8px !important; page-break-after: always">
                  <tr>
                      <td class="p-1"  width="25%">First</td>
                      <td class="p-1"  width="50%"></td>
                      <td class="p-1"  width="25%"></td>
                  </tr>
                  <tr>
                      <td class="p-1">Second</td>
                      <td class="p-1"></td>
                      <td class="p-1"></td>
                  </tr>
                  <tr>
                      <td class="p-1">Third</td>
                      <td class="p-1"></td>
                      <td class="p-1"></td>
                  </tr>
                  <tr>
                      <td class="p-1">Fourth</td>
                      <td class="p-1"></td>
                      <td class="p-1"></td>
                  </tr>
              </table >
              <!--<div class="watermark">Learning Modality: Modular (Printed)</div>-->
              {{-- <table class="table table-sm mb-0" width="100%" style="background-color:rgb(6, 74, 137); color:white; border: .5px solid #000;">
                  <tr>
                      <th class="text-center p-0" style="padding-top: 2px!important;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                  </tr>
                  <tr>
                      <th class="text-center p-0" style="font-size:7px !important; padding-bottom: 2px!important;"><i></i>Grading System Used: Averaging</i></th>
                  </tr>
              </table>   --}}
              <table class="table table-bordered table-sm grades border-blue mb-0" width="100%" style="font-size: 8px !important;">
                  <thead>
                    <tr style="background-color:rgb(6, 74, 137); color: #fff;">
                        <th colspan="7" class="text-center p-0" style="padding-top: 2px!important; padding-bottom: 3px!important; border: 1px solid #000 !important;">
                            <div style="font-size: 11px!important;">
                                REPORT ON PROGRESS AND ACHIEVEMENT
                            </div>
                            <div style="font-size:7px !important;">
                                Grading System Used: Averaging
                            </div>
                        </th>
                    </tr>
                      <tr>
                          <td rowspan="2"  class="align-middle text-center" width="40%"><b>SUBJECTS</b></td>
                          <td colspan="4"  class="text-center align-middle"><b>PERIODIC RATINGS</b></td>
                          <td rowspan="2"  class="text-center align-middle"><b>Final Rating</b></td>
                          <td rowspan="2"  class="text-center align-middle"><b>Action Takent</b></span></td>
                      </tr>
                      <tr>
                          <td class="text-center align-middle" width="10%"><b>1</b></td>
                          <td class="text-center align-middle" width="10%"><b>2</b></td>
                          <td class="text-center align-middle" width="10%"><b>3</b></td>
                          <td class="text-center align-middle" width="10%"><b>4</b></td>
                      </tr>
                  </thead>
                  
                
                  <tbody>
                      @foreach ($studgrades as $item)
                          <tr>
                              <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" ><b>{{$item->subjdesc!=null ? $item->subjdesc : null}}</b></td>
                              <td class="text-center align-middle {{$item->quarter1 != null ? $item->quarter1 < 75 ? 'bg-red':'':'' }}">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                              <td class="text-center align-middle {{$item->quarter2 != null ? $item->quarter2 < 75 ? 'bg-red':'':'' }}">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                              <td class="text-center align-middle {{$item->quarter3 != null ? $item->quarter3 < 75 ? 'bg-red':'':'' }}">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                              <td class="text-center align-middle {{$item->quarter4 != null ? $item->quarter4 < 75 ? 'bg-red':'':'' }}">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                              <td class="text-center align-middle {{$item->finalrating != null ? $item->finalrating < 75 ? 'bg-red':'':'' }}">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                              <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                          </tr>
                      @endforeach
                      <tr>
                          <td colspan="5" class="text-right"><b>GENERAL AVERAGE</b></td>
                          <td class="text-center align-middle {{$finalgrade[0]->finalrating != null ? $finalgrade[0]->finalrating < 75 ? 'bg-red':'':'' }}"><b>{{$finalgrade[0]->finalrating != null ? number_format($finalgrade[0]->fcomp,2):''}}</b></td>
                          <td class="text-center align-middle"><b>{{$finalgrade[0]->actiontaken != null ? $finalgrade[0]->actiontaken:''}}</b></td>
                      </tr>
              </table>  
              <table class="table table-sm grades mb-0" width="100%" style="font-size:8px !important; margin-top: 5px;">
                <tr>
                    <td width="100%" class="p-0 text-center"><b>RATING GUIDE FOR PENMANSHIP</b></td>
                </tr>
              </table>
              <table class="table table-sm grades mb-0" width="100%" style="font-size:8px !important">
                <tr>
                    <td width="20%" class="p-0"></td>
                    <td width="30%" class="p-0"><b>A</b> - Very Good  &nbsp;<b>B</b> - Good</td>
                    <td width="12%" class="p-0 text-center"></td>
                    <td width="28%" class="p-0 text-left"><b>C</b> - Needs Improvement</td>
                    <td width="10%" class="p-0"></td>
                </tr>
              </table>
              <table class="table table-sm grades mb-0" width="100%" style="font-size:8px !important; margin-top: 5px;">
                  <tr>
                        <td width="20%" class="p-0"></td>
                        <td width="25%" class="p-0"><b>DESCRIPTORS</b></td>
                        <td width="20%" class="p-0 text-center"><b>GRADING SCALE</b></td>
                        <td width="25%" class="p-0 text-center"><b>REMARKS</b></td>
                        <td width="10%" class="p-0"></td>
                  </tr>
                  <tr>
                        <td class="p-0"></td>
                        <td class="p-0">Outstanding</td>
                        <td class="text-center p-0">90-100</td>
                        <td class="text-center p-0">Passed</td>
                        <td class="p-0"></td>
                  </tr>
                  <tr>   
                        <td class="p-0"></td>
                        <td class="p-0">Very Satisfactory</td>
                        <td class="text-center p-0">85-89</td>
                        <td class="text-center p-0">Passed</td>
                        <td class="p-0"></td>
                  </tr>
                  <tr>
                        <td class="p-0"></td>
                        <td class="p-0">Satisfactory</td>
                        <td class="text-center p-0">80-84</td>
                        <td class="text-center p-0">Passed</td>
                        <td class="p-0"></td>
                  </tr>
                  <tr>
                        <td class="p-0"></td>
                        <td class="p-0">Fairly Satisfactory</td>
                        <td class="text-center p-0">75-79</td>
                        <td class="text-center p-0">Passed</td>
                        <td class="p-0"></td>
                  </tr>
                  <tr>
                        <td class="p-0"></td>
                        <td class="p-0">Did Not Meet Expectations</td>
                        <td class="text-center p-0">Below 75</td>
                        <td class="text-center p-0">Failed</td>
                        <td class="p-0"></td>
                  </tr>
              </table>
              <table class="table table-bordered grades mb-0"  width="100%" style="table-layout: fixed;">
                    <tr style="background-color:rgb(6, 74, 137); color: #fff;">
                        <td colspan="6" class="text-center p-0" style="border: 1px solid #000 !important;padding-top: 5px!important; padding-bottom: 5px!important; margin-top: 5px;">
                            <div>
                                REPORT ON LEARNER'S OBSERVED VALUES
                            </div>
                        </td>
                    </tr>
                      <tr>
                          <td rowspan="2" width="18%" class="text-center align-middle p-0" style="font-size: 9px!important;"><b>Core Values</b></td>
                          <td rowspan="2" width="54%" class="text-center align-middle  p-0" style="font-size: 9px!important;"><b>Behavior Statements</b></td>
                          <td colspan="4" class="text-center p-0" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>Quarter</b></td>
                      </tr>
                      <tr>
                          <td width="7%" class="align-middle text-center" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>1</b></td>
                          <td width="7%" class="align-middle text-center" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>2</b></td>
                          <td width="7%" class="align-middle text-center" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>3</b></td>
                          <td width="7%" class="align-middle text-center" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 9px!important;"><b>4</b></td>
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
                                            <td class="text-left p-0" style="font-size: 8px; vertical-align: middle; padding-top: 5px!important; padding-bottom: 5px!important;" rowspan="{{count($groupitem)}}"><b>&nbsp;&nbsp;{{$item->group}}</b></td>
                                            @php
                                                $count = 1;
                                            @endphp
                                    @endif
                                    {{-- <td class="align-middle" style="font-size: 10px;"><b>{{$item->group}}</b></td> --}}
                                    <td class="align-middle p-0" style="font-size: 8px;padding-left: 3px!important; padding-top: 5px!important; padding-bottom: 5px!important;">{{$item->description}}</td>
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
              <table class="table table-sm grades" width="100%" style="font-size: 9px !important; margin-top: -2.5px; border: .5px solid #000; border-top: none !important; padding-bottom: 4px !important;">
                  <thead>
                      <tr>
                          <td class="p-0" width="20%"></td>
                          <td class="text-center p-0" width="18%" style="padding-top: 4px!important;"><b>MARKING</b></td>
                          <td width="2%"></td>
                          <td class="text-center p-0" width="30%" style="padding-top: 4px!important;"><b>NON-NUMERICAL RATING</b></td>
                          <td class="p-0" width="30%"></td>
                      </tr>
                  </thead>
                  <tbody>
                       <tr>
                            <td class="p-0"></td>
                            <td class="text-center p-0">AO</td>
                            <td width="2%"></td>
                            <td class="text-left p-0" style="padding-left: 25px !important; font-size: 8px !important;">Always Observed</td>
                            <td class="p-0"></td>
                       </tr>
                        <tr>
                            <td class="p-0"></td>
                            <td class="text-center p-0">SO</td>
                            <td width="2%"></td>
                            <td class="text-left p-0" style="padding-left: 25px !important; font-size: 8px !important;">Sometimes Observed</td>
                            <td class="p-0"></td>
                        </tr>
                        <tr>
                            <td class="p-0"></td>
                            <td class="text-center p-0">RO</td>
                            <td width="2%"></td>
                            <td class="text-left p-0" style="padding-left: 25px !important; font-size: 8px !important;">Rarely Observed</td>
                            <td class="p-0"></td>
                        </tr>
                        <tr>
                            <td class="p-0"></td>
                            <td class="text-center p-0">NO</td>
                            <td width="2%"></td>
                            <td class="text-left p-0" style="padding-left: 25px !important; font-size: 8px !important;">Not Observed</td>
                            <td class="p-0"></td>
                        </tr>
                  </tbody>
              </table>
</body>
</html>