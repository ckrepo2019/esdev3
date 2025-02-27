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
            font-size: 10px !important;
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
        @page {  
            margin:30px 30px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }

        /* @page { size: 5.5in 8.5in; margin: 10px 40px;  } */
        
    </style>
</head>
<body>  

    <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="text-align: right; vertical-align: top;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
            </td>
            <td style="width: 50%; text-align: center;">
                <div style="width: 100%; font-weight: bold; font-size: 18px;">ST. PETER'S COLLEGE OF TORIL, INC.</div>
                <div style="width: 100%; font-size: 11px;">Mac Arthur Highway, Crossing Bayabas, Toril, Davao City</div>
                <div style="width: 100%; font-weight: bold; font-size: 11px; font-style: italic;">SENIOR HIGH SCHOOL (GRADE)</div>
                <div style="width: 100%; font-size: 11px;">Government Permit No. 096, s. 2020</div>
                {{-- <div style="width: 100%; font-weight: bold; font-size: 13px; line-height: 5px;">&nbsp;</div> --}}
                <div style="width: 100%; font-weight: bold; font-size: 11px;">OFFICIAL REPORT CARD (SF 9)</div>
                <div style="width: 100%; font-size: 11px;">School Year:</div>
            </td>
            <td></td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 12px; margin-top: 5px;" border="1">
        <tr>
            <td colspan="3" style="width: 50%;">Name : {{$student->student}}</td>
            <td colspan="3" style="width: 15%;">Gender : {{$student->gender}}</td>
            <td colspan="2">Grade & Section : {{$student->levelname}} - {{$student->sectionname}}</td>
        </tr>
        <tr>
            <td style="width: 20%;">LRN : {{$student->lrn}}</td>
            <td>Track :  Academic</td>
            <td colspan="2">Strand : {{$strandInfo->strandcode}}</td>
            <td colspan="4">Adviser : {{$adviser}}</td>
        </tr>
    </table>
    <br/>
 
    @php
        $x = $semid;
    @endphp
    @if($x == 1)
       <div style="font-size: 11px; width: 100%; font-weight: bold; font-style: italic;">FIRST SEMESTER</div> 
    @else
        <div style="font-size: 11px; width: 100%; font-weight: bold; font-style: italic;">SECOND SEMESTER</div> 
    @endif
    
    <table class="table table-sm table-bordered grades" width="100%">
       
        <tr>
            <td width="60%" rowspan="2"  class="align-middle text-center"><b>LEARNING AREAS</b></td>
            <td width="20%" colspan="2"  class="text-center align-middle" ><b>QUARTER</b></td>
            <td width="10%" rowspan="2"  class="text-center align-middle" ><b>FINAL GRADE</b></td>
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
        <tr>
            <th style="text-align: left; font-style: italic;">CORE SUBJECTS</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
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
            </tr>
        @endforeach
        <tr>
            <th style="text-align: left; font-style: italic;">APPLIED SUBJECTS</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
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
            </tr>
        @endforeach
        <tr>
            <th style="text-align: left; font-style: italic;">SPECIALIZED SUBJECTS</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
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
            </tr>
        @endforeach
        <tr>
            @php
                $genave = collect($finalgrade)->where('semid',$x)->first();
            @endphp
            <td colspan="3"><b>GENERAL AVERAGE</b></td>
            <td class="text-center align-middle">{{ isset($genave->fcomp) ? $genave->fcomp != null ? $genave->fcomp:'' :''}}</td>
        </tr>
    </table>
    {{-- <table style="width: 100%; font-size: 11px;" border="1">
        <thead>
            <tr>
                <th rowspan="2" style="width: 60%;">LEARNING AREAS</th>
                <th colspan="2">QUARTER</th>
                <th rowspan="2">FINAL GRADE</th>
            </tr>
            <tr>
                <th>1(OR 3)</th>
                <th>2(OR 4)</th>
            </tr>
        </thead>
        <tr>
            <th style="text-align: left; font-style: italic;">CORE SUBJECTS</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: left; font-style: italic;">APPLIED SUBJECTS</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: left; font-style: italic;">SPECIALIZED SUBJECTS</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align: left; font-style: italic;">ELECTIVE SUBJECTS</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th>GENERAL AVERAGE</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table> --}}
    <table style="width: 100%; margin-top: 5px;">
        <tr>
            <td style="width: 60%; padding: 0px;">
                @php
                    $width = count($attendance_setup) != 0? 55 / count($attendance_setup) : 0;
                @endphp
                <table class="table table-bordered table-sm" width="100%">
                    <tr>
                        <th colspan="{{count($attendance_setup)+2}}" class="text-center">REPORT ON STUDENT ATTENDANCE</th>
                    </tr>
                    <tr class=" ">
                        <th width="25%">Months of the S.Y</th>
                        @foreach ($attendance_setup as $item)
                            <th class="text-center align-middle" width="{{$width}}%">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                        @endforeach
                        <th class="text-center text-center" width="10%">Total</th>
                    </tr>
                    <tr class="table-bordered">
                        <td >School Day</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <th class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td>Days Present</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <th class="text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</th>
                    </tr>
                    <tr class="table-bordered">
                        <td>Days Absent</td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <th class="text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
            </td>
            <td></td>
            <td style="width: 38%; padding: 0px; vertical-align: top;">
                <!--<table style="width: 100%; font-size: 10px;" border="1">-->
                <!--    <tr>-->
                <!--        <th colspan="3">REPORT ON STUDENT OBSERVED VALUES</th>-->
                <!--    </tr>-->
                <!--    <tr>-->
                <!--        <td style="width: 70%;">Component</td>-->
                <!--        <td>1</td>-->
                <!--        <td>2</td>-->
                <!--    </tr>-->
                <!--    <tr>-->
                <!--        <td>&nbsp;</td>-->
                <!--        <td></td>-->
                <!--        <td></td>-->
                <!--    </tr>-->
                <!--</table>-->
            </td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 10px; margin-top: 3px;">
        {{-- <tr style=" font-weight: bold;">
            <td style="width: 60%;">Academic Rating</td>
            <td style="width: 2%;"></td>
            <td>Observed Values</td>
        </tr> --}}
        <tr>
            <td style="border: 1px solid black; vertical-align: top;">
                <table style="width: 100%;">
                    <tr>
                        <th colspan="3">Academic Rating</th>
                    </tr>
                    <tr style=" font-style: italic;">
                        <th style="text-align: left !mportant; width: 40%;">Descriptors</th>
                        <th>Grading Scale</th>
                        <th>Remark</th>
                    </tr>
                    <tr>
                        <td>Outstanding</td>
                        <td style="text-align: center;">90-100</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Very Satisfactory</td>
                        <td style="text-align: center;">85-89</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Satisfactory</td>
                        <td style="text-align: center;">80-84</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Fairly Satisfactory</td>
                        <td style="text-align: center;">75-79</td>
                        <td style="text-align: center;">Passed</td>
                    </tr>
                    <tr>
                        <td>Did Not Meet Expectations</td>
                        <td style="text-align: center;">Beloww 75</td>
                        <td style="text-align: center;">Failed</td>
                    </tr>
                </table>
            </td>
            <td></td>
            <td style="border: 1px solid black; vertical-align: top;">
                <table style="width: 100%;">
                    <tr>
                        <th colspan="2">Observed Values</th>
                    </tr>
                    <tr style=" font-style: italic;">
                        <th>Non-Numerical Ratings</th>
                        <th style="width: 40%;">Marking</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Always Observed</td>
                        <td style="text-align: center;">AO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Sometimes Observed</td>
                        <td style="text-align: center;">SO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Rarely Observed</td>
                        <td style="text-align: center;">RO</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">Not Observed</td>
                        <td style="text-align: center;">NO</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-top: 3px; font-size: 10px;" border="1">
        <tr>
            <th>Transfer Eligibility</th>
        </tr>
        <tr>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 25%;">Eligible for transfer and admission to:</td>
                        <td style="width: 30%; border-bottom: 1px solid black;"></td>
                        <td style="width: 5%; "></td>
                        <td style="width: 8%; ">Remark/s:</td>
                        <td style="width: 28%; border-bottom: 1px solid black;"></td>
                        <td></td>
                    </tr>
                </table>
                <br/>
                <br/>
                <br/>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td style="width: 35%;"></td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td style="width: 10%;"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center;">Principal</td>
                        <td></td>
                        <td style="text-align: center;">Date</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>