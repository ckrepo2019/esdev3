<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title>
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
            font-size: 9px !important;
        }
        
        .plot_10 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 10px !important;
        }
        
        .border-bottom{
            border-bottom:1px solid black;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        @page {  
            margin:0;
            
        }
        body { 
            margin:0;
            
        }
        
        .page_break { page-break-before: always; }

        @page { size: 5.5in 8.5in; margin: .5in !important;  }
        
    </style>
</head>
<body>  

        <img src="{{base_path()}}/public/xai_header_2.PNG" alt="school" width="100%">

        <table class="table table-sm grades mb-1" width="100%" style="margin-top:.5rem !important">
            <tr>
                <td class="text-center p-0" width="100%">&nbsp;&nbsp;Government Recognition #328. Series 1956</td>
            </tr>
        </table>
        <table class="table table-sm grades mb-1" width="100%" style="margin-top:.5rem !important">
            <thead>

                <tr>
                    <td colspan="2">
                      {{$student->student}}
                    </td>
                </tr>
                <tr>
                    <td  width="70%">
                      LRN {{$student->lrn}}
                    </td>
                    <td width="50%">
                       School Year: {{$schoolyear->sydesc}}
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                        Grade/Level: {{$student->levelname}}
                    </td>
                    <td>
                        Section: {{$student->sectionname}}
                    </td>
                </tr>
            </thead>
        </table>
    
                <table class="table table-bordered table-sm grades mb-0" width="100%" style="margin-top:.5rem !important">
                    <thead>
                        <tr>
                            <td rowspan="2"  class="align-middle text-center" width="36%"><b>SUBJECTS</b></td>
                            <td colspan="4"  class="text-center align-middle" width="36%"><b>PERIODIC RATINGS</b></td>
                            <td rowspan="2"  class="text-center align-middle" width="14%"><b>FINAL<br>RATING</b></td>
                            <td rowspan="2"  class="text-center align-middle" width="14%"><b>REMARKS</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="9%"><b>1st</b></td>
                            <td class="text-center align-middle" width="9%"><b>2nd</b></td>
                            <td class="text-center align-middle" width="9%"><b>3rd</b></td>
                            <td class="text-center align-middle" width="9%"><b>4th</b></td>
                        </tr>
                    </thead>
                    
                  
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle">{{isset($item->finalrating)? $item->finalrating:''}}</td>
                                <td class="text-center align-middle">{{isset($item->actiontaken)? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                            <tr>
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >&nbsp;</td>
                                <td class="text-center align-middle"></td>
                                <td class="text-center align-middle"></td>
                                <td class="text-center align-middle"></td>
                                <td class="text-center align-middle"></td>
                                <td class="text-center align-middle"></td>
                                <td class="text-center align-middle"></td>
                            </tr>
                            
                        @if($gradelevel == 2 || $gradelevel == 3 || $gradelevel == 4)
                            <tr>
                                <td >GENERAL AVERAGE</td>
                                <td class="text-center align-middle">{{isset(collect($finalgrade)->first()->quarter1) ?collect($finalgrade)->first()->quarter1 : ''}}</td>
                                <td class="text-center align-middle">{{isset(collect($finalgrade)->first()->quarter2) ?collect($finalgrade)->first()->quarter2: ''}}</td>
                                <td class="text-center align-middle">{{isset(collect($finalgrade)->first()->quarter3) ?collect($finalgrade)->first()->quarter3: ''}}</td>
                                <td class="text-center align-middle">{{isset(collect($finalgrade)->first()->quarter4) ?collect($finalgrade)->first()->quarter4: ''}}</td>
                                <td class="text-center ">{{collect($finalgrade)->first()->finalrating}}</td>
                                <td class="text-center" style="font-size: 8px !important">{{collect($finalgrade)->first()->actiontaken}}</td>
                            </tr>
                        @else
                        <tr>
                            <td >GENERAL AVERAGE</td>
                            <td class="text-center align-middle">{{isset(collect($finalgrade)->first()->quarter1) ?number_format(collect($finalgrade)->first()->quarter1,2) : ''}}</td>
                            <td class="text-center align-middle">{{isset(collect($finalgrade)->first()->quarter2) ? number_format(collect($finalgrade)->first()->quarter2,2) : ''}}</td>
                            <td class="text-center align-middle">{{isset(collect($finalgrade)->first()->quarter3) ?number_format(collect($finalgrade)->first()->quarter3,2) : ''}}</td>
                            <td class="text-center align-middle">{{isset(collect($finalgrade)->first()->quarter4) ?number_format(collect($finalgrade)->first()->quarter4,2) : ''}}</td>
                            <td class="text-center ">{{collect($finalgrade)->first()->finalrating}}</td>
                            <td class="text-center" style="font-size: 8px !important">{{collect($finalgrade)->first()->actiontaken}}</td>
                        </tr>
                        
                        @endif
                        <tr>
                            <td  colspan="7">GRADING SYSTEM: <b>Averaging</b></td>
                           
                        </tr>
                    </tbody>
                </table>  
               <table class="table table-sm grades table-bordered" width="100%" style="font-size:.3rem !important"> 
                    <tr>
                        <td width="5%" class=" p-0"></td>
                        <td width="30%" class="text-center p-0"><b>DESCRIPTORS</b></td>
                        <td width="30%" class=" text-center p-0"><b>GRADING SCALE</b></td>
                        <td width="30%" class=" text-center p-0"><b>REMARKS</b></td>
                        <td width="5%" class=" p-0"></td>
                    </tr>
                    <tr>
                        <td class=" p-0"></td>
                        <td class="text-center p-0">Outstanding</td>
                        <td class=" text-center p-0">90-100</td>
                        <td class=" text-center p-0">Passed</td>
                        <td class=" text-center p-0"></td>
                    </tr>
                    <tr>
                        <td class=" p-0"></td>   
                        <td class="text-center p-0">Very Satisfactory</td>
                        <td class=" text-center p-0">85-89</td>
                        <td class=" text-center p-0">Passed</td>
                         <td class=" text-center p-0"></td>
                    </tr>
                    <tr>
                        <td class=" p-0"></td>
                        <td class="text-center p-0">Satisfactory</td>
                        <td class="text-center p-0">80-84</td>
                        <td class=" text-center p-0">Passed</td>
                         <td class=" text-center p-0"></td>
                    </tr>
                    <tr>
                        <td class=" p-0"></td>
                        <td class="text-center p-0">Fairly Satisfactory</td>
                        <td class=" text-center p-0">75-79</td>
                        <td class=" text-center p-0">Passed</td>
                         <td class=" text-center p-0"></td>
                    </tr>
                    <tr>
                        <td class=" p-0"></td>
                        <td class="text-center p-0">Did Not Meet Expectations</td>
                        <td class=" text-center p-0">Below 75</td>
                        <td class=" text-center p-0">Failed</td>
                         <td class=" text-center p-0"></td>
                    </tr>
                </table>
                <table class="table table-sm grades mb-1" width="100%">
                    <tr class="text-center">
                        <td>Eligible for transfer and admission to: _______ Date Issued:_________</td>
                    </tr>
                </table>
                @php
                    $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm grades mb-0" width="100%">
                    <tr class=" ">
                        <td width="25%">ATTENDANCE</td>
                        @foreach ($attendance_setup as $att_item)
                            <td class="text-center align-middle" width="{{$width}}%">{{\Carbon\Carbon::create(null, $att_item->month)->isoFormat('MMM')}}</td>
                        @endforeach
                        <td class="text-center text-center" width="10%">Total</td>
                    </tr>
                    <tr class="table-bordered">
                        <td >School Days</td>
                        @foreach ($attendance_setup as $att_item)
                            <td class="text-center align-middle">{{$att_item->days != 0 ? $att_item->days : '' }}</td>
                        @endforeach
                        <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Days Present</td>
                        @foreach ($attendance_setup as $att_item)
                            <td class="text-center align-middle">{{$att_item->days != 0 ? $att_item->present : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Day Tardy</td>
                        @foreach ($attendance_setup as $att_item)
                            <td class="text-center align-middle" >{{$att_item->days != 0 ? $att_item->absent : ''}}</td>
                        @endforeach
                        <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
               
                <table class="table table-sm grades" width="100%">
                    <tr class="text-center">
                        <td>NOTICE: This F138 is not valid without original signatures. </td>
                    </tr>
                </table>
                @if($gradelevel == 2 || $gradelevel == 3 || $gradelevel == 4)
                <table  class="table table-sm grades" width="100%" style="margin-top:2.1rem !important">
                @else
                <table  class="table table-sm grades" width="100%" style="margin-top:2.5rem !important">
                @endif
                    <tr>
                 
                        <th class="text-center p-0 border-bottom" width="47%">{{$adviser}}</th>
                        <th class="text-center p-0 " width="6%"></th>
                        <th class="text-center p-0 border-bottom" width="47%">REV. FR.  MARK HARVEY T. ELLOREN</th>
                      
                    </tr>
                    <tr>
                        <td class="text-center p-0" >Class Adviser</td>
                         <td class="text-center p-0"></td>
                        <td class="text-center p-0" >School Director </td>
                    </tr>
                </table>
                <table class="table table-sm grades mb-0" width="100%">
                    <tr class="text-center">
                        <td>CANCELLATION OF ELIGIBILITY TO TRANSFER</td>
                    </tr>
                </table>
                <table class="table table-sm grades" width="100%" style="margin-top:.2rem !important">
                    <tr>
                        <td width="50%">Has been admitted to: </td>
                        <td width="50%">School:</td>
                    </tr>
                </table>
                <table  class="table table-sm grades mb-0" width="100%" style="margin-top:2rem !important">
                    <tr>
                 
                        <th class="text-center p-0 border-bottom" width="47%"></th>
                        <th class="text-center p-0 " width="6%"></th>
                        <th class="text-center p-0 border-bottom" width="47%"></th>
                      
                    </tr>
                    <tr>
                        <td class="text-center p-0" >Director/Principal</td>
                         <td class="text-center p-0"></td>
                        <td class="text-center p-0" >Date Received</td>
                    </tr>
                </table>
                
                <div class="page_break"></div>
              
                <table  class="table table-sm grades mb-0" width="100%" >
                    <tr>
                        <th class="text-center ">REPORT ON LEARNER'S OBSERVED VALUES</th>
                    </tr>
                </table>
                <table class="table table-bordered plot_10"  width="100%">
                    <thead>
                        <tr>
                            <th rowspan="2" width="20%" class="align-middle p-0"><center >Core Values</center></th>
                            <th rowspan="2" width="56%" class="align-middle p-0"><center>Behavior Statements</center></th>
                            <th colspan="4" class="p-0" width="24%" ><center>Quarter</center></th>
                        </tr>
                        <tr>
                            <th width="6%" class="p-0 align-middle text-center">1</th>
                            <th width="6%" class="p-0 align-middle text-center">2</th>
                            <th width="6%" class="p-0 align-middle text-center">3</th>
                            <th width="6%" class="p-0 align-middle text-center">4</th>
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
                                            <th colspan="6"  style="font-size:.55rem !important">{{$item->description}}</th>
                                        </tr>
                                @else
                                        <tr>
                                            @if($count == 0)
                                                    <td class="align-middle" rowspan="{{count($groupitem)}}" style="font-size:.45rem !important">
                                                        {{$item->group}}
                                                    </td>
                                                    @php
                                                        $count = 1;
                                                    @endphp
                                            @endif
                                            <td class="align-middle"  style="font-size:.55rem !important">
                                                {{$item->description}}
                                            </td>
                                            <td class="text-center align-middle p-0">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle p-0" >
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle p-0">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}} 
                                                @endforeach
                                            </td>
                                            <td class="text-center align-middle  p-0">
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
                @if($gradelevel !=  2 && $gradelevel !=  3 && $gradelevel !=  4)
                    <table class="table table-sm grades" width="100%">
                        <thead>
                            <tr>
                                <td width="10%"></td>
                                <td width="40%" class="text-center"><b>Marking</b></td>
                                <td width="40%" class="text-center"><b>Non- Numerical Rating</b></td>
                                <td width="10%"></td>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($rv as $key=>$rvitem)
                                @if($rvitem->value != null)
                                    <tr>
                                        <td></td>
                                        <td class="text-center">{{$rvitem->value}}</td>
                                        <td class="text-center">{{$rvitem->description}}</td>
                                        <td></td>
                                     </tr>
                                @endif
                            @endforeach 
                        </tbody>
                    </table>
                @endif
                
                <table class="table table-sm grades"  width="100%">
                    <tr class="text-center">
                        <td colspan="2">PARENT/GUARDIAN SIGNATURE</td>
                    </tr>
                    <tr>
                        <td width="15%" class="align-middle">1<sup>st</sup> Quarter</td>
                        <td width="85%" class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle">2<sup>nd</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle">3<sup>rd</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle">4<sup>th</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                </table> 
                @if($gradelevel ==  2 || $gradelevel ==  3 || $gradelevel ==  4)
                 <table class="table table-sm grades"  width="100%">
                    <tr class="text-center">
                        <td colspan="2">COMMENTS/RECOMMENDATIONS</td>
                    </tr>
                    <tr>
                        <td width="15%" class="align-middle">1<sup>st</sup> Quarter</td>
                        <td width="85%" class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle">2<sup>nd</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle">3<sup>rd</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                    <tr>
                        <td class="align-middle">4<sup>th</sup> Quarter</td>
                        <td class="border-bottom align-middle" style="padding:.75rem !important"></td>
                    </tr>
                </table> 
                
                @endif
                <table  class="table table-sm" width="100%" style="margin-top:4rem !important">
                    <tr>
                        <td width="50%" class="p-0"></td>
                        <td width="50%" class="border-bottom text-center p-0"><b>{{$adviser}}</b></td>
                    </tr>
                    <tr>
                        <td class="p-0"></td>
                        <td class="text-center p-0"><i>Adviser</i></td>
                    </tr>
                     <tr>
                        <td class="border-bottom text-center p-0"><b>REV. FR. MARK HARVEY T. ELLOREN</b></td>
                        <td class="p-0"></td>
                    </tr>
                     <tr>
                        <td class="text-center p-0"><i>School Director/School Principal</i></td>
                        <td class="p-0"></td>
                    </tr>
                </table>

                
                
          
</div>

</body>
</html>