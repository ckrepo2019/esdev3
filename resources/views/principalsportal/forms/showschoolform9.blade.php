<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    {{-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> --}}
    <title>Document</title>
    <style>

        table {
            border-collapse: collapse;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: transparent;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid black;
        }

        .table tbody + tbody {
            border-top: 2px solid black;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid black;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .text-center {
            text-align: center !important;
        }

        .p-0 {
            padding: 0 !important;
        }
        .border-0 {
            border: 0 !important;
        }
        .pt-2,
        .py-2 {
            padding-top: 0.5rem !important;
        }
        .pt-4{
            padding-top: 1.5rem !important;
        }

        .container {
            min-width: 992px !important;
        }
       
        .p-1 {
            padding: 0.25rem !important;
        }
        
        .pr-2,
        .px-2 {
            padding-right: 0.5rem !important;
        }
        pl-2,
        .px-2 {
            padding-left: 0.5rem !important;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        .rt-90 {
            /* Abs positioning makes it not take up vert space */
            position: absolute;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 30 22;
            transform: rotate(-90deg);
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .reportat td{
            border: 1px solid #000;
        }

        .reportat .toping td{
            border: 1px solid #fff;
            border-bottom: 1px solid #000;
        }

        .pl-4, .px-4 {
            padding-left: 1.5rem!important;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <table class="table" width="100%">
                <tr>
                    <td width="50%">
                        <table width="100%">
                            <tr>
                                <td><center>REPORT ON ATTENDANCE</center></td>
                            </tr>
                        </table >
                        <table width="100%" style="border:solid 1px black">
                            <tr class="table-bordered">
                                <td></td>
                                @foreach ($attSum as $item)
                                    <td class="text-center text-center" style="font-size:10px !important">{{\Carbon\Carbon::create($item->month)->isoFormat('MMM')}}</td>
                                @endforeach
                                <td class="text-center text-center" style="font-size:10px !important">Total</td>
                            </tr>
                            <tr class="table-bordered" >
                                <td style="font-size:9px !important">No. of Days Present</td>
                                @foreach ($attSum as $item)
                                    <td class="align-middle text-center">{{$item->count}}</td>
                                @endforeach
                                <td class="align-middle text-center">{{collect($attSum)->sum('count')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td style="font-size:9px !important">No. of Days Present</td>
                                @foreach ($attSum as $item)
                                    <td class="align-middle text-center">{{$item->countPresent}}</td>
                                @endforeach
                                <td class="align-middle text-center">{{collect($attSum)->sum('countPresent')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td style="font-size:9px !important">No. of Days Absent</td>
                                @foreach ($attSum as $item)
                                    <td class="align-middle text-center">{{$item->countAbsent}}</td>
                                @endforeach
                                <td class="align-middle text-center">{{collect($attSum)->sum('countAbsent')}}</td>
                            </tr>
                        </table>
                        <table class="table" style="margin-top:100px !important" width="100%">
                            <tr class="text-center">
                                <td colspan="2">Homeroom Remarks & Parent's Signature</td>
                            </tr>
                            <tr>
                                <td>1<sup>st</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>2<sup>nd</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>3<sup>rd</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>4<sup>th</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                        </table> 
                    </td>
                    <td width="50%"  style="padding:20px !important">
                        <table class="table" style="font-size:13px !important;"  width="100%">
                            <tr>
                                <td class="text-center  p-0 ">REPUBLIKA NG PILIPINAS</td>
                            </tr>
                            <tr>
                                <td class="p-2 " colspan="5">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">KAGAWARAN NG EDUKASYON</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">{{$schoolinfo[0]->regDesc}}</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">Sangay ng {{$schoolinfo[0]->citymunDesc}}</td>
                            </tr>
                            <tr>
                                <td class="p-1" colspan="5">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">{{$schoolinfo[0]->schoolname}}</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">{{$schoolinfo[0]->address}}</td>
                            </tr>
                            <tr>
                            <td class="text-center  p-2"><img src="{{asset($schoolinfo[0]->picurl)}}" alt="school" width="80px"></td>
                            </tr>
                            <tr>
                                <td class="p-1 " colspan="5">&nbsp;</td>
                            </tr>
                        </table>
                        <table class="table p-0" style="font-size:13px; !important" width="100%">
                            @php
                                $midname = [];
                                $midnamestring = '';
                                if(count($midname) > 0){
                                    $midnamestring = substr($midname[0], 0, 1);
                                }
                            @endphp
                            <tr>
                                <td class="p-1" colspan="2">Surname:  <u>{{$student[0]->lastname}}</u></td>
                                <td class="p-1 " colspan="2">First Name: <u>{{$student[0]->firstname}}</u></td>
                                <td class="p-1 " colspan="1">M.I.: <u>{{$midnamestring}}</u></td>
                            </tr>
                            <tr>
                                <td class="p-1" colspan="5">LRN: <u>{{$student[0]->lrn}}</u></td>
                            </tr>
                            <tr>
                                <td class="p-1" colspan="1">Age: <u>{{\Carbon\Carbon::parse($student[0]->dob)->age}}</u></td>
                                <td class="p-1" colspan="2">Sex: <u>{{$student[0]->gender}}</u></td>
                                <td class="p-1" colspan="2">Year & Section: <u>{{$student[0]->enlevelname}} - {{$student[0]->ensectname}}</u></td>
                            </tr>
                            <tr>
                                <td class="p-1" colspan="5">School Year: <u>{{Session::get('schoolYear')->sydesc}}</u></td>
                            </tr>
                            <tr>
                                <td class="p-2 " colspan="5">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="p-0 " colspan="5">Dear Parents,</td>
                            </tr>
    
                            <tr>
                                <td class="p-0 pt-4 " colspan="5">This report card shows the ability and progress your child in the different learning areas including its core values.</td>
                            </tr>
                            <tr>
                                <td class="p-0 pt-4 " colspan="5">
                                    {{-- The School welcomes you should desire to know more about your child's progress. --}}
                                    Should you desire to inquire more about your childs performance, you may contact the undersigned below.
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 " colspan="5">&nbsp;</td>
                            </tr>
                            <tr>
                                @php
                                    $midname = [];
                                    $midnamestring = '';
                                    if(count($midname) > 0){
                                        $midnamestring = substr($midname[0], 0, 1).'.';
                                    }
                                @endphp
                                <td colspan="3" class="text-center p-0 ">{{$student[0]->teacherfirstname}} {{$midnamestring}} {{$student[0]->teacherlastname}}</td>
                                @php
                                    $midname = [];
                                    $midnamestring = '';
                                    if(count($midname) > 0){
                                        $midnamestring = substr($midname[0], 0, 1).'.';
                                    }
                                @endphp
                                <td colspan="2" class="text-center p-0 ">{{Session::get('prinInfo')->firstname}} {{$midnamestring}} {{Session::get('prinInfo')->lastname}}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center p-0 ">Class Advicer</td>
                                <td colspan="2" class="text-center p-0 ">School Principal</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
    </div>
   <div class="container">

   

        <table class="table" width="100%">
                <tr>
                    @if($student[0]->acadprogid != 5)
                        <td width="50%" >
                            <table  class="table table-sm" width="100%">
                                <tr>
                                    <th class="p-2 text-center border-0" colspan="7" style="font-size:15px !important;font-family: Arial, Helvetica, sans-serif;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                </tr>
                            
                            </table>
                            <table class="table table-sm" width="100%">
                                <tr class="table-bordered">
                                    <th width="50%" rowspan="2"  class="align-middle"  style="text-align: left !important;">SUBJECTS</th>
                                
                                    <td width=30%" colspan="4"  class="text-center align-middle" style="font-size:15px !important; font-family: Arial, Helvetica, sans-serif;">PERIODIC RATINGS</td>
                                

                                    <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;">FINAL RATING</td>
                                    <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;"><span class="p-1" >ACTION TAKEN</span></td>
                                </tr>
                                <tr class="table-bordered" style="font-size:11px !important;">
                                    <td class="text-center align-middle">1</td>
                                    <td class="text-center align-middle" >2</td>

                                
                                        <td class="text-center align-middle" >3</td>
                                        <td class="text-center align-middle" >4</td>
                                
                                </tr>
                                
                                
                            @php
                                $quarter1complete = true;
                                $quarter2complete = true;
                                $quarter3complete = true;
                                $quarter4complete = true;
                            @endphp
                        
                            @if( count($grades) != 0)
                                @foreach ($grades as $item)

                                    @if($item->quarter1 == null)
                                        @php
                                            $quarter1complete = false;
                                        @endphp
                                    @endif

                                    @if($item->quarter2 == null)
                                        @php
                                            $quarter2complete = false;
                                        @endphp
                                    @endif

                                    @if($item->quarter3 == null)
                                        @php
                                            $quarter3complete = false;
                                        @endphp
                                    @endif

                                    @if($item->quarter4 == null)
                                        @php
                                            $quarter4complete = false;
                                        @endphp
                                    @endif

                                    @php
                                        $average = ($item->quarter1 + $item->quarter2 + $item->quarter3 + $item->quarter4) / 4 ;
                                    @endphp

                                    <tr class="table-bordered">
                                        
                                        @if($item->subjectcode!=null)
                                            <td class="p-1 @if($item->mapeh == 1) pl-4 @endif" style="text-align: left !important;font-size:13px !important" >
                                                {{$item->subjectcode}}
                                            </td>
                                        @else
                                            <td class="p-1" style="text-align: left !important;" >
                                                &nbsp;
                                            </td>
                                        @endif

                                        @if($item->quarter1 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter1}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                        @endif

                                    

                                        @if($item->quarter2 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter2}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                        @endif

                                        @if($item->quarter3 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter3}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle"  style="font-size:13px !important">&nbsp;</td>
                                        @endif

                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter4}}</td>
                                        
                                        @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{number_format( ($item->quarter1+$item->quarter2+$item->quarter3+$item->quarter4)/4)}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important"></td>
                                        @endif

                                        @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $average = null;
                                @endphp
                            @endif
                            @if( count($grades) != 0)
                                @php
                                    $genaverage =  (collect($grades)->where('mapeh',0)->avg('quarter1') + collect($grades)->where('mapeh',0)->avg('quarter2') + collect($grades)->where('mapeh',0)->avg('quarter3') + collect($grades)->where('mapeh',0)->avg('quarter4')) / 4 ;
                                @endphp
                            @else
                                @php
                                    $genaverage = null;    
                                @endphp
                            @endif
                                
                            <tr class="table-bordered genave">
                                
                                    <th class="p-1" style="text-align: left !important">GENERAL AVERAGE {{$quarter2complete}}
                                    </th>

                                    @if($quarter1complete)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter1'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if($quarter2complete)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter2'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif
                                
                                    @if($quarter3complete)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter3'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if($quarter4complete)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter4'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                        <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important">{{number_format($average)}}</td>
                                    @else
                                        <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important"></td>
                                    @endif

                                    @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                        <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                    @else
                                        <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                    @endif
                                </tr>
                            </table>
                            <table class="table  p-0 " style="font-size:11px !important" width="100%">
                                <tr>
                                    <td width="10%"  class="p-0  "></td>
                                    <td width="20%" class="p-0  ">Grading Scale</td>
                                    <td width="40%"  class="p-0  ">Descriptors</td>
                                    <td width="20%"  class="p-0  ">Remarks</td>
                                    <td width="10%"  class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">A - 90 - 100</td>
                                    <td width="40%" class="p-0  ">Outstanding</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">B - 85 - 90</td>
                                    <td width="40%" class="p-0  ">Satisfactory</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">C - 80 - 84</td>
                                    <td width="40%" class="p-0  ">Needs Improvement</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">D - 75 - 79</td>
                                    <td width="40%" class="p-0  ">Fairly Satisfactory</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">E - Below  75</td>
                                    <td width="40%" class="p-0  ">Did Not Meet Expectation</td>
                                    <td width="20%" class="p-0  ">Failed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                            </table>
                        </td>
                    @else
                    <td width="50%" >
                        <table  class="table table-sm" width="100%">
                            <tr>
                                <th class="p-2 text-center border-0" colspan="7" style="font-size:15px !important;font-family: Arial, Helvetica, sans-serif;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                            </tr>
                        
                        </table>

                        <p>FIRST SEMESTER</p>

                        <table class="table table-sm" width="100%">
                            <tr class="table-bordered">
                                <th width="50%" rowspan="2"  class="align-middle"  style="text-align: left !important;">SUBJECTS</th>
                            
                                <td width=30%" colspan="2"  class="text-center align-middle" style="font-size:15px !important; font-family: Arial, Helvetica, sans-serif;">PERIODIC RATINGS</td>
                            

                                <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;">FINAL RATING</td>
                                <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;"><span class="p-1" >ACTION TAKEN</span></td>
                            </tr>
                            <tr class="table-bordered" style="font-size:11px !important;">
                                <td class="text-center align-middle">1</td>
                                <td class="text-center align-middle" >2</td>
                            </tr>
                            
                        @if( count($grades) != 0 && collect($grades)->where('semid',1)->count() > 0)

                            @foreach (collect($grades)->where('semid',1) as $item)

                                

                                @php
                                    $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                                @endphp

                                <tr class="table-bordered">
                                    @if($item->subjectcode!=null)
                                        <td class="p-1" style="text-align: left !important" >
                                            {{$item->subjectcode}}
                                        </td>
                                    @else
                                        <td class="p-1" style="text-align: left !important;" >
                                            &nbsp;
                                        </td>
                                    @endif

                                    @if($item->quarter1 != null)
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter1}}</td>
                                    @else
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                    @endif

                                    @if($item->quarter2 != null)
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter2}}</td>
                                    @else
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                    @endif

                                
                                    
                                    @if($item->quarter1 != null && $item->quarter2 != null)
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">{{number_format( ($item->quarter1+$item->quarter2) / 2 )}}</td>
                                    @else
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important"></td>
                                    @endif

                                    @if($item->quarter1 != null && $item->quarter2 != null )
                                        <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                    @else
                                        <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            @php
                                $average = null;
                            @endphp
                        @endif

                        @if( count($grades) != 0)
                            @php
                                $genaverage = (collect($grades)->avg('quarter1') + collect($grades)->avg('quarter2') ) / 2 ;
                            @endphp
                        @else
                            @php
                                $genaverage = null;    
                            @endphp
                        @endif
                            
                            <tr class="table-bordered genave">
                                
                                    <th class="p-1" style="text-align: left !important">GENERAL AVERAGE
                                    </th>

                                    @if(collect($grades)->where('semid',1)->where('quarter1',null)->count() == 0)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('semid',1)->avg('quarter1'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if(collect($grades)->where('semid',1)->where('quarter2',null)->count() == 0)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('semid',1)->avg('quarter2'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif
                                
                                    
                                    @if( collect($grades)->where('semid',1)->where('quarter1',null)->count() == 0 && collect($grades)->where('semid',1)->where('quarter2',null)->count() == 0 )

                                        <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important">{{number_format(collect($grades)->where('semid',1)->avg('finalRating'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if(collect($grades)->where('semid',1)->where('finalRating',null)->count() == 0)
                                        <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                    @else
                                        <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                    @endif
                                </tr>
                            </table>

                            <p>SECOND SEMESTER</p>
    
    
                            <table class="table table-sm" width="100%">
                                <tr class="table-bordered">
                                    <th width="50%" rowspan="2"  class="align-middle"  style="text-align: left !important;">SUBJECTS</th>
                                
                                    <td width=30%" colspan="2"  class="text-center align-middle" style="font-size:15px !important; font-family: Arial, Helvetica, sans-serif;">PERIODIC RATINGS</td>
                                
    
                                    <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;">FINAL RATING</td>
                                    <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;"><span class="p-1" >ACTION TAKEN</span></td>
                                </tr>
                                <tr class="table-bordered" style="font-size:11px !important;">
                                    <td class="text-center align-middle">3</td>
                                    <td class="text-center align-middle">4</td>
                                </tr>
                                
                                
                            @php
                                $quarter1complete = false;
                                $quarter2complete = false;
                            @endphp
                        
                            @if( count($grades) != 0 && collect($grades)->where('semid',2)->count() > 0)
    
                                @foreach (collect($grades)->where('semid',2) as $item)
    
                                    @php
                                        $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                                    @endphp
    
                                    <tr class="table-bordered">
                                        @if($item->subjectcode!=null)
                                            <td class="p-1" style="text-align: left !important" >
                                                {{$item->subjectcode}}
                                            </td>
                                        @else
                                            <td class="p-1" style="text-align: left !important;" >
                                                &nbsp;
                                            </td>
                                        @endif
    
                                        @if($item->quarter1 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter1}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                        @endif
    
                                        @if($item->quarter2 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter2}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                        @endif
    
                                       
                                        
                                        @if($item->quarter1 != null && $item->quarter2 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{number_format( ($item->quarter1+$item->quarter2 ) / 2 )}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important"></td>
                                        @endif
    
                                        @if($item->quarter1 != null && $item->quarter2 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $average = null;
                                @endphp
                            @endif
                            @if( count($grades) != 0 && collect($grades)->where('semid',2)->count() > 0)
                                @php
                                    $genaverage =  (collect($grades)->where('semid',2)->avg('quarter1') + collect($grades)->where('semid',2)->avg('quarter2') ) / 2  ;
                                @endphp
                            @else
                                @php
                                    $genaverage = null;    
                                @endphp
                            @endif
                                
                                <tr class="table-bordered genave">
                                    
                                        <th class="p-1" style="text-align: left !important">GENERAL AVERAGE
                                        </th>
    
                                        @if(collect($grades)->where('semid',2)->where('quarter1',null)->count() == 0)
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('semid',2)->avg('quarter1'))}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                        @endif
    
                                        @if(collect($grades)->where('semid',2)->where('quarter2',null)->count() == 0)
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('semid',2)->avg('quarter2'))}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                        @endif
                                    
                                        
                                        @if( collect($grades)->where('semid',2)->where('quarter1',null)->count() == 0 && collect($grades)->where('semid',2)->where('quarter2',null)->count() == 0 )

                                            <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important">{{number_format( collect($grades)->where('semid',2)->avg('finalRating') )}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                        @endif
    
                                        @if(collect($grades)->where('semid',2)->where('finalRating',null)->count() == 0)
                                            <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                        @else
                                            <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                        @endif
                                    </tr>
                            </table>

                            <table class="table  p-0 " style="font-size:11px !important" width="100%">
                                <tr>
                                    <td width="10%"  class="p-0  "></td>
                                    <td width="20%" class="p-0  ">Grading Scale</td>
                                    <td width="40%"  class="p-0  ">Descriptors</td>
                                    <td width="20%"  class="p-0  ">Remarks</td>
                                    <td width="10%"  class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">A - 90 - 100</td>
                                    <td width="40%" class="p-0  ">Outstanding</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">B - 85 - 90</td>
                                    <td width="40%" class="p-0  ">Satisfactory</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">C - 80 - 84</td>
                                    <td width="40%" class="p-0  ">Needs Improvement</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">D - 75 - 79</td>
                                    <td width="40%" class="p-0  ">Fairly Satisfactory</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">E - Below  75</td>
                                    <td width="40%" class="p-0  ">Did Not Meet Expectation</td>
                                    <td width="20%" class="p-0  ">Failed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                            </table>
                        </td>
                    @endif

                    <td width="50%" style="padding:20px !important">
                        <table class="table table-sm" width="100%"> 
                            <tr>
                                <th class="p-2 text-center border-0" colspan="6" style="font-size:15px !important;font-family: Arial, Helvetica, sans-serif;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                            </tr>
                        
                        </table>
                        <table class="table table-bordered table-sm" style="font-size:11px !important" width="100%">
                            <tr>
                                <th colspan="2" rowspan="2"  width="75%" class="align-middle">Core Values</th>
                                <th colspan="4" width="25%" class="text-center">MARKAHAN</th>
                            </tr>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="text-center">2</td>
                                <td class="text-center">3</td>
                                <td class="text-center">4</td>
                            </tr>
                            <tr>
                                <td rowspan="2" colspan="1">1. Maka-DIYOS</td>
                                <td colspan="1" style="margin-bottom: 20px !important">Expresses one's spiritual beliefs while respecting the beliefs of others</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaDiyos_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaDiyos_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaDiyos_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaDiyos_1')->first()}}</td>
                            </tr>
                            <tr>
                                <td colspan="1" style="margin-bottom: 20px !important">Shows adherence to ethical principles by upholding the truth in all undertakings</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaDiyos_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaDiyos_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaDiyos_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaDiyos_2')->first()}}</td>
                            </tr>
                            <tr>
                                <td rowspan="1" colspan="1">2. Maka-Tao</td>
                                <td colspan="1" style="margin-bottom: 20px !important">Is sensitive to individual, social and cultural differences; resists stereotyping people</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaTao_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaTao_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaTao_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaTao_1')->first()}}</td>
                            </tr>
                            {{-- <tr>
                                <td colspan="1" style="margin-bottom: 20px !important">Demonstrates contributions towards solidarity</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaTao')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaTao')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaTao')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaTao')->first()}}</td>
                            </tr> --}}
                            <tr>
                                <td rowspan="2" colspan="1">3. Maka-KALIKASAN</td>
                                <td colspan="1" style="margin-bottom: 20px !important">Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaKalikasan_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaKalikasan_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaKalikasan_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaKalikasan_1')->first()}}</td>
                            </tr>

                            <tr>
                                <td colspan="1" style="margin-bottom: 20px !important">Demonstrates contributions towards solidarity</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaKalikasan_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaKalikasan_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaKalikasan_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaKalikasan_2')->first()}}</td>
                            </tr>
                        
                            <tr>
                                <td rowspan="2" colspan="1">4. Maka-BANSA</td>
                                <td colspan="1" style="margin-bottom: 20px !important">Demonstrates pride in being a Filipino, exercises the rights and responsibilities of a Filipino Citizen</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaBansa_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaBansa_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaBansa_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaBansa_1')->first()}}</td>
                            </tr>
                            <tr>
                                <td colspan="1" style="margin-bottom: 20px !important">Demonstrates appropriate behavior in carrying out activities in the school, community and country</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaBansa_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaBansa_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaBansa_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaBansa_2')->first()}}</td>
                            </tr>
                        </table>
                        <table class="table table-sm" style="font-size:11px !important" width="100%">
                            <tr>
                                <td width="20%" class="p-0">Marking</td>
                                <td width="80%" class="p-0">Non-Numerical Rating</td>
                            </tr>
                            <tr>
                                <td width="20%" class="p-0">AO</td>
                                <td width="80%" class="p-0">Always Observed</td>
                            </tr>
                            <tr>
                                <td width="20%" class="p-0">SO</td>
                                <td width="80%" class="p-0">Sometimes Observed</td>
                            </tr><tr>
                                <td width="20%" class="p-0">RO</td>
                                <td width="80%" class="p-0">Rarely Observed</td>
                            </tr>
                            <tr>
                                <td width="20%" class="p-0">NO</td>
                                <td width="80%" class="p-0">Not Observed</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>

    
</div>

</body>
</html>