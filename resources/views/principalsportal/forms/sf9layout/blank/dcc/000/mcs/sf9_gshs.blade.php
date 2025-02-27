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

        @page { size: 5.5in 8.5in; margin: 50px;  }
        
    </style>
</head>
<body>  

    <table class="table table-sm grades " width="100%">
        <thead>
            <tr>
                <td width="30%" rowspan="2" class="text-right align-middle">
                    <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px">
                </td>
                <td width="70%" class="p-0" >
                    <h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo[0]->schoolname}}</h3>
                </td>
            </tr>
            <tr>
                <td class="p-0">
                    {{$schoolinfo[0]->address}}
                </td>
            </tr>
        </thead>
    </table>
   
    <table class="table table-bordere table-sm grades" width="100%">
        <thead>
            <tr>
                <td width="100%" class="p-0 text-center" style="font-size: 15px !important"><b>
                    @if($student->acadprogid == 2)
                        KINDERGARTEN
                    @elseif($student->acadprogid == 3)
                        GRADE SCHOOL
                    @elseif($student->acadprogid == 4)
                        HIGH SCHOOL
                    @elseif($student->acadprogid == 5)
                        SENIOR HIGH SCHOOL
                    @endif
                     REPORT CARD
                </b></td>
            </tr>
        </thead>
    </table>


        <table class="table table-bordered table-sm grades" width="100%">
            <thead>
                <tr>
                    <td width="70%">
                        @php
                            $temp_middle = explode(" ",$student->middlename);
                            $middle = '';
                            foreach($temp_middle as $item){
                                $middle .=  $item;
                            }
                        @endphp
                        <b>NAME: {{$student->lastname}} , {{$student->firstname}} {{$student->middlename}}.</b>
                    </td>
                    <td width="30%">
                        <b>GRADE: {{$student->levelname}}</b>
                    </td>
                </tr>
                <tr>
                    <td width="50%">
                        <b>GENDER: {{$student->gender}}</b>
                    </td>
                    <td width="50%">
                        <b>SCHOOL YEAR: {{$schoolyear->sydesc}}</b> 
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>LRN: {{$student->lrn}}</b>
                    </td>
                  
                </tr>
            </thead>
        </table>
    
                <table class="table table-bordered table-sm grades" width="100%">
                    <thead>
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
                                <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right" colspan="5">GENERAL AVERAGE</td>
                            <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}">{{collect($finalgrade)->first()->finalrating}}</td>
                            <td class="text-center" style="font-size: 8px !important">{{collect($finalgrade)->first()->actiontaken}}</td>
                        </tr>
                    </tbody>
                </table>  
                <table class="table table-bordered table-sm grades" width="100%">
                    <thead>
                        <tr>
                            <td width="40%" class="align-middle text-center">MONTH</td>
                            <td width="15%" class="align-middle text-center">DAYS IN SCHOOL</td>
                            <td width="15%" class="align-middle text-center">DAYS PRESENT</td>
                            <td width="15%" class="align-middle text-center">DAYS ABSENT</td>
                            <td width="15%" class="align-middle text-center">TIMES TARDY</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendance_setup as $item)
                            <tr>
                                <td class="text-center align-middle" >{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMMM')}}</td>
                                <td class="text-center align-middle" >{{$item->days}}</td>
                                <td class="text-center align-middle" >{{$item->present}}</td>
                                <td class="text-center align-middle" >{{$item->absent}}</td>
                                <td class="text-center align-middle" >0</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($student->acadprogid == 2)
                    @for ($x = 0; $x <= 12; $x++)
                        <br>
                    @endfor
                @elseif($student->acadprogid == 3)
                  

                    @if($student->levelid == 7 || $student->levelid == 9 || $student->levelid == 16)
                        @for ($x = 0; $x <= 7; $x++)
                            <br>
                        @endfor
                    @else
                        @for ($x = 0; $x <= 10; $x++)
                            <br>
                        @endfor
                    @endif
                   
                @elseif($student->acadprogid == 4)
                    @for ($x = 0; $x <= 8; $x++)
                        <br>
                    @endfor
                @endif
               
              
                <table  class="table table-sm grades mb-0" width="100%" style="page-break-inside:avoid;">
                    <tr>
                        <th class="text-center ">REPORT ON LEARNER'S OBSERVED VALUES</th>
                    </tr>
                </table>
                <table class="table table-bordered grades"  width="100%">
                    <thead>
                        <tr>
                            <th rowspan="2" width="23%" class="align-middle"><center >Core Values</center></th>
                            <th rowspan="2" width="45%" class="align-middle"><center>Behavior Statements</center></th>
                            <th colspan="4" class="cellRight" width="32%"><center>Quarter</center></th>
                        </tr>
                        <tr>
                            <th width="8%" class="p-0 align-middle text-center">1</th>
                            <th width="8%" class="p-0 align-middle text-center">2</th>
                            <th width="8%" class="p-0 align-middle text-center">3</th>
                            <th width="8%" class="p-0 align-middle text-center">4</th>
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
                                            <th colspan="6" >{{$item->description}}</th>
                                        </tr>
                                @else
                                        <tr>
                                            @if($count == 0)
                                                    <td class="align-middle" rowspan="{{count($groupitem)}}" style="font-size: 8px !important">
                                                        {{$item->group}}
                                                    </td>
                                                    @php
                                                        $count = 1;
                                                    @endphp
                                            @endif
                                            <td class="align-middle" >
                                                {{$item->description}}
                                            </td>
                                            <td class="text-center align-middle" style="font-size: 8px !important">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle p-0" style="font-size: 8px !important">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                @endforeach 
                                            </td>
                                            <td class="text-center align-middle p-0" style="font-size: 8px !important">
                                                @foreach ($rv as $key=>$rvitem)
                                                    {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}} 
                                                @endforeach
                                            </td>
                                            <td class="text-center align-middle  p-0" style="font-size: 8px !important">
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
              
                <table class="table table-sm grades" width="100%">
                    <thead>
                        <tr>
                            <td width="25%"></td>
                            <td width="20%" class="table-bordered text-center"><b>Marking</b></td>
                            <td width="30%" class="table-bordered"><b>Non- Numerical Rating</b></td>
                            <td width="25%"></td>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach ($rv as $key=>$rvitem)
                            @if($rvitem->value != null)
                                <tr>
                                    <td></td>
                                    <td class="text-center table-bordered">{{$rvitem->value}}</td>
                                    <td class="table-bordered">{{$rvitem->description}}</td>
                                    <td></td>
                                 </tr>
                            @endif
                        @endforeach 
                    </tbody>
                </table>

                <table class="table table-sm grades" width="100%" style="margin-top:20px !important">
                    <tbody>
                        <tr>
                            <td width="100%"><b>Promoted to</b> / Retained in : <u>{{$student->levelname}}</u></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm grades" width="100%" style="margin-top:10px !important">
                    <tbody>
                        <tr>
                            <td width="100%">Eligible for Transfre and Admission to: <u>{{$student->levelname}}</u></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm grades" width="100%" style="margin-top:20px !important">
                    <tbody>
                        <tr>
                            <td width="100%">Date: <u>{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</u></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm grades" width="100%" style="margin-top:40px !important">
                    <tbody>
                        <tr>
                            <td width="50%" class="text-center align-middle"><u>{{$adviser}}</u></td>
                            <td width="50%"></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle">Class Adviser</td>
                            <td ></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-sm grades" width="100%" style="margin-top:40px !important">
                    <tbody>
                        <tr>
                            <td width="1000%" class="text-center align-middle"><u>{{Session::get('prinInfo')->firstname}} {{Session::get('prinInfo')->lastname}}</u></td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle">Class Adviser</td>
                        </tr>
                    </tbody>
                </table>

                
          
</div>

</body>
</html>