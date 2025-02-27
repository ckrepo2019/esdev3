<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title>
    <style>
        @page{
            margin: 20px 50px;
        }
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
            font-size: 11px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        body { 
            margin:0;
            
        }

        @page { size: 8.5in 13in; }
        .text-center{
            text-align: center;
        }
        
    </style>
</head>
<body>  

    <table class="table table-sm" width="100%">
        <thead>
            <tr>
                <td width="25%" rowspan="2" class="text-left align-middle">
                    <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px">
                </td>
                <td width="50%" class="p-0 text-center" >
                    <br/>
                    <h5 class="mb-0" style="font-size:15px !important">{{$schoolinfo[0]->schoolname}}</h5>
                </td>
                <td width="25%" class="p-0" >
                    <!--<h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo[0]->schoolname}}</h3>-->
                </td>
            </tr>
            <tr>
                <td class="p-0">
                    {{$schoolinfo[0]->address}}
                </td>
            </tr>
        </thead>
        <tr>
            <th class="text-center p-0" colspan="3">OFFICIAL REPORT CARD (SF 9)</th>
        </tr>
        <tr>
            <td class="text-center p-0" colspan="3">(Government Recognition No.03,s.1985)</td>
        </tr>
        <tr>
            <th class="text-center p-0" colspan="3">A Y {{$schoolyear->sydesc}}</th>
        </tr>
    </table>


        <table class="table table-sm" width="100%">
            <thead>
                <tr>
                    <td style="width: 15%;" class="p-0">Name:</td>
                    <td class="p-0">
                        @php
                            $temp_middle = explode(" ",$student->middlename);
                            $middle = '';
                            foreach($temp_middle as $item){
                                $middle .=  $item;
                            }
                        @endphp
                        <b>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}.</b>
                    </td>
                </tr>
                <tr>
                    <td style="width: 15%;" class="p-0">Grade/Section:</td>
                    <td class="p-0">
                        <b>{{$student->levelname}} - {{$student->sectionname}}</b>
                    </td>
                </tr>
                <tr>
                    <td style="width: 15%;" class="p-0">LRN:</td>
                    <td class="p-0">
                        <b>{{$student->lrn}}</b>
                    </td>
                  
                </tr>
            </thead>
        </table>
    
                <table class="table table-bordered table-sm grades" width="100%" style="font-size: 20px!important;">
                    <thead>
                        <tr>
                            <td rowspan="2"  class="align-middle text-center" width="40%"><b>Learning Areas</b></td>
                            <td colspan="4"  class="text-center align-middle"><b>GRADING PERIOD</b></td>
                            <td rowspan="2"  class="text-center align-middle"><b>FINAL</b></td>
                            <td rowspan="2"  class="text-center align-middle"><b>REMARKS</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="10%"><b>1<sup>st</sup></b></td>
                            <td class="text-center align-middle" width="10%"><b>2<sup>nd</sup></b></td>
                            <td class="text-center align-middle" width="10%"><b>3<sup>rd</sup></b></td>
                            <td class="text-center align-middle" width="10%"><b>4<sup>th</sup></b></td>
                        </tr>
                    </thead>
                    
                  
                    <tbody>
                         @foreach ($studgrades as $item)
                                    <tr>
                                        <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                        <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                        <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                        <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                        <td class="text-center align-middle" >{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                        <td class="text-center align-middle" >{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                        <td class="text-center align-middle" >{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><b>GENERAL AVERAGE</b></td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle"></td>
                                    <td class="text-center align-middle">{{$finalgrade[0]->finalrating != null ? $finalgrade[0]->finalrating:''}}</td>
                                    <td class="text-center align-middle">{{$finalgrade[0]->actiontaken != null ? $finalgrade[0]->actiontaken:''}}</td>
                        </tr>
                    </tbody>
                </table>  
        <div style="width: 100%; text-align: center; font-size: 11px; font-weight: bold;">CHARACTER DEVELOPMENT</div>
                <table class="table table-bordered table-sm grades" width="100%">
                    <thead>
                        <tr>
                            <td rowspan="2"  class="align-middle text-center" width="40%"><b>BEHAVIOR IN SCHOOL</b></td>
                            <td colspan="4"  class="text-center align-middle"><b>QUARTER</b></td>
                            <td rowspan="2"  class="text-center align-middle"><b>Marking</b></td>
                            <td rowspan="2"  class="text-center align-middle"><b>Non-numerical Rating</b></span></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="10%"><b>1</b></td>
                            <td class="text-center align-middle" width="10%"><b>2</b></td>
                            <td class="text-center align-middle" width="10%"><b>3</b></td>
                            <td class="text-center align-middle" width="10%"><b>4</b></td>
                        </tr>
                    </thead>
                    
                  
                    <tbody>
                        <tr>
                            <td>PUNCTUALITY</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>NEATNESS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>COURTESY</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>SOCIABILITY</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>SPORTSMANSHIP</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>LEADERSHIP</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>OBSERVANCE OF SCHOOL RULES</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td rowspan="2" colspan="5" class="text-center" style="vertical-align: middle;">ATTITUDE TOWARDS SCHOOL WORK</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>INDEPENDENCE IN DOING SCHOOL WORK</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>ASSIGNMENTS</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>INTEREST & ENTHUSIASM</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>WORKING CAPACITY</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>GENERAL AVERAGE</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>  
                <table class="table table-sm table-bordered" width="100%">
                    @php
                         $width = count($attendance_setup) != 0? 70 / count($attendance_setup) : 0;
                    @endphp
                    <tr>
                        <th width="22%">ATTENDANCE RECORD</th>
                        @foreach ($attendance_setup as $item)
                            <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                        @endforeach
                        <th class="text-center aling-middle"  width="8%">Total</th>
                    </tr>
                    <tr>
                        <td>Number of School Days</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days}}</td>
                        @endforeach
                        <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr >
                        <td>Number of Days Present</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->present}}</td>
                        @endforeach
                        <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                    </tr>
                    <tr>
                        <td  >Number of Days Tardy</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->absent}}</td>
                        @endforeach
                        <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
                
                <br/>
                <br/>
                <br/>
                <table class="table table-sm" width="100%">
                    <tr>
                        <td class="p-0" width="5%"></td>
                        <td class="text-center p-0 border-bottom" style="width: 30%;"><b>{{$adviser}}</b></td>
                        <td style="width: 30%;" class="p-0"></td>
                        <td class="text-center p-0 border-bottom" style="width: 30%;"><b>ELENITA CASTAÑEDA-APOSAGA, Ph.D</b></td>
                        <td class="p-0" width="5%"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center">Adviser</td>
                        <td></td>
                        <td class="text-center">Principal</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th colspan="5">&nbsp;</th>
                    </tr>
                    <tr>
                        <th colspan="5">&nbsp;</th>
                    </tr>
                    <tr>
                        <td colspan="5" style="font-weight: bold;">REMARKS: Eligible for transfer and admission to Grade</td>
                    </tr>
                </table>
                

                
          
</div>

</body>
</html>