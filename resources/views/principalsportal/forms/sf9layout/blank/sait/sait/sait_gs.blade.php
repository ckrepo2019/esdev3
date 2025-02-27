<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>Recognition</title>
    <style>
        @page { size: 5.5in 8.5in; margin: .25in;  }
    
        /* table{
            border: 1px solid black;
        }
        table td {
            border: 1px solid black; 
        }
        .table {
            border:none;
        } */


        table.no-spacing {
            border-spacing:0; /* Removes the cell spacing via CSS */
            border-collapse: collapse;  /* Optional - if you don't want to have double border where cells touch */
        }
        .text-center-row {
            text-align: center;
            vertical-align: middle;
        }
        .table td, .table th {
            font-size: 12px;
        }
        .text-red{
            color: red;
        }
        .text-center {
            text-align: center;
        }
        .text-alignmentL {
            text-align: left;
        }
        .text-alignmentR {
            text-align: right;
        }
        .vertical-align {
            vertical-align: middle;
        }
        .border-0 td {
            border: none;
        }
        .border-2 {
            border:1px solid black !important;
        }
        .border-bottom{
            border-bottom: 1px solid black !important;
        }
        .text-font {
            font-size: 11px;
        }
        .text-font-sait {
            font-size: 16px;
        }
        .text-font-parents {
            font-size: 14px;
        }
        .background-color {
            background-color: #8bd0e9;
        }
        .background-colorA {
            background-color: #BAF3BC;
        }
        .background-colorB {
            background-color: #F5ebe3;
        }
        .text-style {
            style: italic;
        }
        .font-arial {
            font-family: Arial 16px !important;
        }
        .p-0{
            padding: 0 !important;
        }
        .mb-0{
            margin-bottom: 0 !important;
        }
    </style>
</head>
    <body>
        {{-- <div class="text-center text-font"><strong>Republic of the Philippines</strong></div>
        <div class="text-center text-font"><strong>DEPARTMENT OF EDUCATION</strong></div>
        <div class="text-center text-font"><strong>Region X</strong></div>
        <div class="text-center text-font"><strong>DIVISION OF VALENCIA CITY</strong></div> --}}
        <table class="text-font mb-0" style="width:100%;">
            <tr>
                <td>DepEd FORM 138-E</td>
            </tr>
        </table>
        <table class="table table-sm grades mb-0"  width="100%" style="font-size:13px ; ">
            <tr>
                   <td width="20%" rowspan="4" class="text-center align-middle">
                        <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="50px">
                   </td>
                   <td width="60%" style="text-align: center;">
                        <b>Republic of the Philippines</b>
                   </td>
                   <td width="20%" rowspan="4" class="text-center align-middle">
                       <img src="{{base_path()}}/public/sait_iso.jpg" alt="school" width="90px">
                   </td>
             </tr>
            <tr>
                <td class="text-center p-0">
                    <b>DEPARTMENT OF EDUCATION</b>
                </td>
            </tr>
            <tr>
                <td class="text-center p-0">
                    Region X
                </td>
            </tr>
            <tr>
                <td class="text-center p-0">
                    <b>DIVISION OF VALENCIA CITY</b>
                </td>
            </tr>
        </table>
        <div class="text-font-sait background-color text-center font-arial" style="background-color:#030a66; color:white"><strong>SAN AGUSTIN INSTITUTE OF TECHNOLOGY</strong></div>
        <div class="text-center text-font"><strong>@if($acad == 2) PRE-SCHOOL @elseif($acad == 3) GRADE SCHOOL @elseif($acad == 4) HIGH SCHOOL @endif REPORT CARD</strong></div>
        <div class="text-center text-font"><strong>S.Y. {{$schoolyear->sydesc}}</strong></div>
    
        <table class="text-font" style="width:100%; margin-top: 10px !important;">
            <tr class="border-0">
                <td width="10%">NAME:</td>
                <td width="50%" class="border-bottom"><strong>{{$student->student}}</strong></td>
                <td width="10%" >LRN#:</td>
                <td width="30%" class="border-bottom"><strong>{{$student->lrn}}</strong></td>
            </tr>
            <tr class="border-0">
                <td>AGE:</td>
                <td class="border-bottom"><strong>{{$student->age}}</td>
                <td >GENDER:</td>
                <td class="border-bottom"><strong>{{$student->gender}}</strong></td>
            </tr>
            <tr class="border-0">
                <td>GRADE:</td>
                <td class="border-bottom"><strong>{{str_replace('GRADE ','',$student->levelname)}}</strong></td>
                <td >SECTION:</td>
                <td class="border-bottom"><strong>{{$student->sectionname}}</strong></td>
            </tr>
            
        </table>
       
        <table class="border-0 text-font-parents" style="width:100%; margin-top:5px !important">
            <tr>
                <td class="text-style"><i><b>Dear Parent,</i></b></td>
            </tr>
            <tr>
                <td style="text-indent: 50px;">This report card shows the ability and the progress your child has made
                    in different learning areas.
                </td>
            </tr>
        </table>
        <table class="no-spacing table border-0" style="width:100%; margin-top:10px">
            <tr>
                <th style="width: 50%" class="text-center"></th>
                <td style="width: 50%" class="text-center"><u>{{$adviser}}</u></td>
            </tr>
            <tr>
                <th style="width: 50%" class="text-center"></th>
                <td style="width: 50%" class="text-center">Teacher</td>
            </tr>
            <tr>
                <td style="width: 50%" class="text-center"><u>{{$principal}}</u></td>
                <th style="width: 50%" class="text-center"></th>
            </tr>
            <tr>
                <td style="width: 50%" class="text-center">School Head</td>
                <th style="width: 50%" class="text-center"></th>
            </tr>
        </table>
      
         <table border="1" class="no-spacing table" style="width:100%; margin-top: 5px !important;" > 
            <tr>
                <th colspan="7" class="background-colorA"> REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
            </tr>
            <tr class="background-colorB">
                <th style="width: 50%">Learning Areas</th>
                <th style="width: 5%">1</th>
                <th style="width: 5%">2</th>
                <th style="width: 5%">3</th>
                <th style="width: 5%">4</th>
                <th style="width: 10%">Final Rating</th>
                <th style="width: 20%">Remarks</th>
            </tr>
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
                <td colspan="5"><b>GENERAL AVERAGE</b></td>
                <td class="text-center align-middle">{{$finalgrade[0]->finalrating != null ? $finalgrade[0]->finalrating:''}}</td>
                <td class="text-center align-middle">{{$finalgrade[0]->actiontaken != null ? $finalgrade[0]->actiontaken == "PASSED" ? "PROMOTED":'':''}}</td>
            </tr>
           

        </table>
        <table class="no-spacing table text-center"  style="width:100%; margin-top:10px !important;">
            <tr>
                <td width="80%">
                    <table class="no-spacing table text-center border-2"  style="width:100%;">
                        <tr>
                            <th rowspan="4" class="border-2">Learning Modality</th>
                            <th class="border-2">Q1</th>
                            <th class="border-2">Q3</th>
                        </tr>
                        <tr>
                            <td class="border-2">Printed Module</td>
                            <td class="border-2">Printed/Online </td>
                        </tr>
                        <tr>
                            <th class="border-2">Q2</th>
                            <th class="border-2">Q4</th>
                        </tr>
                        <tr>
                            <td class="border-2">Printed Module</td>
                            <td class="border-2">Printed/Online </td>
                    </tr>
                    </table>
                </td>
                <td width="20%">
                </td>
            </tr>
           
            
        </table> 
        {{-- <table class="no-spacing table text-center border-0"  style="width:100%; font-size:9px !important">
            <tr>
                <th>Descriptors</th>
                <th>Grade Scale</th>
                <th>Remarks</th>
            </tr>
            <tr>
                <td>Outstanding</td>
                <td>90-100</td>
                <td>Passed</td>
            </tr>
            <tr>
                <td>Very Satisfacory</td>
                <td>85-89</td>
                <td>Passed</td>
            </tr>
            <tr>
                <td>Satisfactory</td>
                <td>80-84</td>
                <td>Passed</td>
            </tr>
            <tr>
                <td>Fairly Satisfactory</td>
                <td>75-79</td>
                <td>Passed</td>
            </tr>
            <tr>
                <td>Did not meet Expectations</td>
                <td>74 Below</td>
                <td>Failed</td>
            </tr>
        </table> --}}
    <h1></h1>
    
</div>
{{-- <div style="page-break-before:always"></div>
        <table border="1" class="no-spacing table" style="width:100%">
            @php
                 $width = count($attendance_setup) != 0? 60 / count($attendance_setup) : 0;
            @endphp
            <tr>
                <th width="30%"></th>
                @foreach ($attendance_setup as $item)
                    <th class="text-center align-middle"  width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                @endforeach
                <th class="text-center text-center"  width="10%">Total</th>
            </tr>
            <tr>
                <td>No. of School Days</td>
                @foreach ($attendance_setup as $item)
                    <td class="text-center align-middle">{{$item->days}}</td>
                @endforeach
                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
            </tr>
            <tr >
                <td>No. of Presentt</td>
                @foreach ($attendance_setup as $item)
                    <td class="text-center align-middle">{{$item->present}}</td>
                @endforeach
                <th class="text-center text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
            </tr>
            <tr>
                <td >No. of Absent</td>
                @foreach ($attendance_setup as $item)
                    <td class="align-middle text-center">{{$item->absent}}</td>
                @endforeach
                <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
            </tr>
            
        </table>
        
        <table border="0" class="no-spacing table" style="width:100%">
            <tr>
                <th>REPORT ON LEARNERS OBSERVED VALUES</th>
            </tr>
        </table>
        <table border="1" class="no-spacing table" style="width:100%">
            <tr class="text-center">
                <td style="width: 20%">Core Values</td>
                <td style="width: 60%">Behavioral Statement</td>
                <td style="width: 5%">1</td>
                <td style="width: 5%">2</td>
                <td style="width: 5%">3</td>
                <td style="width: 5%">4</td>
            </tr>
            
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
                                        <td class="align-middle" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                        @php
                                            $count = 1;
                                        @endphp
                                @endif
                                <td class="align-middle">{{$item->description}}</td>
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
        </table>
        <table class="no-spacing table" style="width:100%">
            <tr>
                <td style="width: 20%" class="text-center"><b>Marking</b></td>
                <td style="width: 80%"><b>Non-Numerical Rating</b></td>
            </tr>
            <tr>
                <td style="width: 20%" class="text-center">AO</td>
                <td style="width: 80%">Always Observed</td>
            </tr>
            <tr>
                <td style="width: 20%" class="text-center">SO</td>
                <td style="width: 80%">Sometimes Observed</td>
            </tr>
            <tr>
                <td style="width: 20%" class="text-center">RO</td>
                <td style="width: 80%">Rarely Observed</td>
            </tr>
            <tr>
                <td style="width: 20%" class="text-center">NO</td>
                <td style="width: 80%">Not Observed</td>
            </tr>
        </table>
        <table border="1" class="no-spacing table" style="width:100%">
            <tr>
                <th>PARENTS/GUARDIANS SIGNATURE</th>
            </tr>
        </table>
        <table class="no-spacing table" style="width:100%">
            <tr>
                <td style="width: 20%">First Grading</td>
                <td class="border-bottom" style="width: 80%"></td>
            </tr>
            <tr>
                <td style="width: 20%">Second Grading</td>
                <td class="border-bottom" style="width: 80%"></td>
            </tr>
            <tr>
                <td style="width: 20%">Third Grading</td>
                <td class="border-bottom" style="width: 80%"></td>
            </tr>
            <tr>
                <td style="width: 20%">Fourth Grading</td>
                <td class="border-bottom" style="width: 80%"></td>
            </tr>
        </table>
        <br>
        <table border="1" class="no-spacing table" style="width:100%">
            <tr>
                <th>CERTIFICATE OF TRANSFER</th>
            </tr>
        </table>
        <table class="no-spacing table" style="width:100%">
            <tr>
                <td style="width: 30%">Admitted to Grade:</td>
                <td class="border-bottom text-center" style="width: 25%">FOUR</td>
                <td style="width: 20%" class="text-center">Section:</td>
                <td class="border-bottom text-center" style="width: 25%">ST. CATHERINE</td>
            </tr>
        </table>
        <table class="no-spacing table" style="width:100%">
            <tr>
                <td style="width: 40%">Eligible for admission to Grade:</td>
                <td class="border-bottom text-center" style="width: 20%">FIVE</td>
                <td style="width: 40%"></td>
            </tr>
        </table>
        <table class="no-spacing table" style="width:100%">
            <tr>
                <td>Approved:</td>
            </tr>
        </table>
        <br>
        <table class="no-spacing table" style="width:100%">
            <tr class="text-center">
                <td style="width: 40%"><u>DORIE G. KIBOS</u></td>
                <td style="width: 60%"><u>REBECCA C. ZANORIA, LPT</u></td>
            </tr>
            <tr class="text-center">
                <td style="width: 40%">Principal</td>
                <td style="width: 60%">Teacher</td>
            </tr>
        </table>
        <table border="1" class="no-spacing table" style="width:100%">
            <tr>
                <th>CANCELATION OF ELIGIBILITY TO TRANSFER</th>
            </tr>
        </table>
        <table class="no-spacing table" style="width:100%">
            <tr>
                <td style="width: 20%" class="text-alignmentR">Admitted in:</td>
                <td class="border-bottom text-center" style="width: 40%"></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td style="width: 20%" class="text-alignmentR">Date:</td>
                <td class="border-bottom text-center" style="width: 40%"></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td style="width: 20%">&nbsp;</td>
                <td style="width: 40%"></td>
                <td style="width: 40%" class="text-center"></td>
            </tr>
            <tr>
                <td style="width: 20%"></td>
                <td style="width: 40%"></td>
                <td style="width: 40%" class="border-bottom text-center"></td>
            </tr>
            <tr>
                <td style="width: 20%"></td>
                <td style="width: 40%"></td>
                <td style="width: 40%" class="text-center">Principal</td>
            </tr>
        </table> --}}
</body>
</html>