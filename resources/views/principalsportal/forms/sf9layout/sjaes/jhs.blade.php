<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            table {
                  border-collapse: collapse;
            }

            .text-center {
                  text-align: center !important;
            }

            .table-bordered {
                  border: 1px solid black;
            }

            .table-bordered th,
            .table-bordered td {
                  border: 1px solid black;
            }

            .pl-4, .px-4 {
                padding-left: 1.5rem!important;
            }
            @page {
                margin: 20px !important;
            }
    </style>
    
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

        .p-1{
            padding: .25rem !important;
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

        .text-red{
            color: red;
            border: solid 1px black;
        }

        
    </style>
    
   
</head>
<body>
 
        <table width="100%" style="font-size:13px ; ">
             <tr>
                   <td width="15%" >
                        <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px">
                   </td>
                   <td width="70%" style="text-align: center;">
                        <sup style="font-size: 10px;">Republic of the Philippines</sup>
                        <br/>
                        <sup style="font-size: 10px;">Department of Education</sup>
                        <br/>
                        <sup style="font-size: 15px;font-weight: bold;">HOLY CROSS OF BUNAWAN, INC.</sup>
                        <br/>
                        <sup style="font-size: 10px;">Km. 23 Bunawan, Davao City</sup>
                        <br/>
                        <sup style="font-size: 10px;">Government Recognition # 183 S. 1968</sup>
                   </td>
                   <td width="15%" style="float:right">
                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="60px">
                   </td>
             </tr>
        </table>
        <table width="100%" style="font-size:13px ; ">
              <tr ><td style="border-bottom:solid 2px black !important"><center>REPORT CARD </center><td></td></tr>
        </table>
        <table width="100%" style="font-size:11px; margin-top:10px">
              <tr>
                    <td width="20%"></td>
                    <td width="80%" class="text-center" style="border-bottom:solid 1px  black">{{$student[0]->firstname}} {{$student[0]->lastname}}</td>
                    <td width="20%"></td>
              </tr>
              <tr>
                    <td></td>
                    <td><center>Name of Student</center></td>
                    <td></td>
              </tr>
        </table>
        <table class="table table-bordered grades mb-0" width="100%">
             <tr>
                <td width="15%">Student Name</td>
                <td width="35%"><i>{{$student->student}}</i></td>
                <td width="5%" class="text-center">Sex</td>
                <td width="15%" class="text-center"><i></i>{{$student->gender}}</i></td>
                <td width="5%" class="text-center">LRN:</td>
                <td width="25%" class="text-center"><i>{{$student->lrn}}</i></td>
            </tr>
            <tr>
                <td >Level / Section</td>
                <td colspan="2"><i>{{$student->levelname}} - {{$student->sectionname}}</i></td>
                <td colspan="2" class="text-center">School Year:</td>
                <td class="text-center"><i>{{$schoolyear->sydesc}}</i></td>
            </tr>
        </table>
        <table class="table table-bordered table-sm grades mb-0" width="100%">
            <thead>
                <tr>
                    <td rowspan="2"  class="align-middle text-center" width="50%"><b>SUBJECTS</b></td>
                    <td colspan="5"  class="text-center align-middle" ><b>QUARTERLY RATING</b></td>
                    <td rowspan="2"  class="text-center align-middle" width="10%"><b>Remarks</b></span></td>
                </tr>
                <tr>
                    <td class="text-center align-middle" width="8%"><b>1</b></td>
                    <td class="text-center align-middle" width="8%"><b>2</b></td>
                    <td class="text-center align-middle" width="8%"><b>3</b></td>
                    <td class="text-center align-middle" width="8%"><b>4</b></td>
                    <td class="text-center align-middle" width="8%"><b>Final</b></td>
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
                    <td colspan="1">GRADING SYSTEM: Averaging</td>
                    <td colspan="2">General Average</td>
                    <td colspan="2"></td>
                    <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}">{{collect($finalgrade)->first()->finalrating}}</td>
                    <td class="text-center" style="font-size: 8px !important">{{collect($finalgrade)->first()->actiontaken}}</td>
                </tr>
            </tbody>
        </table>
        <table width="100%">
            <tr classs="p-0">
                <td width="66%">
                    <table class="table table-bordered table-sm grades" width="100%">
                        <tr >
                            <th width="15%"></th>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle" width="{{$width}}%">{{$item->days != 0 ? \Carbon\Carbon::create(null, $item->month)->isoFormat('MMM') : ''}}</th>
                            @endforeach
                            <td class="text-center text-center" width="10%">Total</td>
                        </tr>
                        <tr class="table-bordered">
                            <td >No. of School Days</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                            @endforeach
                            <td class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                        </tr>
                        <tr class="table-bordered">
                            <td>No. of Days Present</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                            @endforeach
                            <td class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                        </tr>
                        <tr class="table-bordered">
                            <td>No. of Day Absent</td>
                            @foreach ($attendance_setup as $item)
                                <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                            @endforeach
                            <td class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                        </tr>
                    </table>
                </td>
                <td width="34%">
                    
                    <table class="table table-sm grades" width="100%">
                        <tr >
                            <td>Eligible for tranfer and admission to _____</td>
                        </tr>
                        <tr>
                             <td>Has advanced credits in : ________</td>
                        </tr>
                        <tr>
                            <td>Lacks credits in: __________</td>
                        </tr>
                        <tr>
                             <td>Date:___________</td>
                        </tr>
                    </table>
                    
                </td>
            </tr>
        </table>
        <table class="table table-sm grades" width="100%"> 
            <tr>
                <td width="10%"></td>
                <td width="30%" class="border-bottom text-center"><i>{{strtoupper($adviser)}}</i></td>
                <td width="20%"></td>
                <td width="30%" class="border-bottom text-center"><i>{{strtoupper($principal)}}</i></td>
                <td width="10%"></td>
            </tr>
             <tr>
                <td width="10%"></td>
                <td width="30%" class="text-center"><b>Class Adviser</b></td>
                <td width="20%"></td>
                <td width="30%" class="text-center"><b>School  Principal</b></td>
                <td width="10%"></td>
            </tr>
        </table>
          <table class="table table-sm grades" width="100%"> 
            <tr>
                <td width="35%"></td>
                <td width="30%" class="border-bottom text-center"><i>{{strtoupper('Rev. Fr. Max V. Ceballos, SSJV')}}</i></td>
                <td width="35%"></td>
            </tr>
             <tr>
                <td></td>
                <td class="text-center"><b>School Director</b></td>
                <td></td>
            </tr>
        </table>
               
</body>
</html>