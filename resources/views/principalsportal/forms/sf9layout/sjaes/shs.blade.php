<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
            * { font-family: Arial, Helvetica, sans-serif;}
        @page{
            margin: 20px 50px;
        }
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
            #table-grades td, #table-grades th{
                border: 1px solid black;
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
        <table width="100%" style="font-size:13px;">
         <tr>
             <td colspan="3"><img src="{{base_path()}}/public/assets/images/sjaes/header.png" alt="school" width="715px"/></td>
         </tr>
          <tr >
              <th colspan="3"><center>REPORT CARD</center></th>
          </tr>
          <tr >
              <td colspan="3"><center>K to 12 Curriculum</center></td>
          </tr>
          <tr>
              <td></td>
              <td style="text-align: center;">Senior High Department</td>
              <td class="text-right">Student No.: </td>
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
                <td colspan="2"><i></i></td>
                <td colspan="2" class="text-center">School Year:</td>
                <td class="text-center"><i>{{$schoolyear->sydesc}}</i></td>
            </tr>
        </table>
        <table  class="table table-sm table-bordered grades mb-0" width="100%">
            <tr>
                <th class="text-center">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
            </tr>
        </table>
        @for ($x=1; $x <= 2; $x++)
            <table class="table table-sm table-bordered grades  mb-0" width="100%">
                <tr>
                    <td colspan="5"  class="align-middle text-center"><b>{{$x == 1 ? '1ST SEMESTER' : '2ND SEMESTER'}}</b></td>
                </tr>
                <tr>
                    <td width="60%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                    <td width="20%" colspan="2"  class="text-center align-middle" ><b>PERIODIC RATINGS</b></td>
                    <td width="10%" rowspan="2"  class="text-center align-middle" ><b>FINAL RATING</b></td>
                    <td width="10%" rowspan="2"  class="text-center align-middle"><b>ACTION TAKEN</b></td>
                </tr>
                <tr>
                    @if($x == 1)
                        <td class="text-center align-middle"><b>1</b></td>
                        <td class="text-center align-middle"><b>2</b></td>
                    @elseif($x == 2)
                        <td class="text-center align-middle"><b>3</b></td>
                        <td class="text-center align-middle"><b>4</b></td>
                    @endif
                </tr>
                @foreach (collect($studgrades)->where('semid',$x) as $item)
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
                        <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                    </tr>
                @endforeach
                <tr>
                    @php
                        $genave = collect($finalgrade)->where('semid',$x)->first();
                    @endphp
                    <td colspan="3"><b>GENERAL AVERAGE</b></td>
                    <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? number_format($genave->fcomp,2):'' :''}}</td>
                    <td class="text-center align-middle">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>
                </tr>
            </table>
        @endfor
        <table width="100%">
            <tr classs="p-0">
                <td width="66%">
                    <table class="table table-bordered table-sm grades" width="100%">
                         @php
                            $width = count($attendance_setup) != 0? 70 / count($attendance_setup) : 0;
                        @endphp
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
                            <td>No. of Days Absent</td>
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
                <td width="30%" class="border-bottom text-center"><i></i></td>
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
        
</html>