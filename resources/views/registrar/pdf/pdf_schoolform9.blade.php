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
            /* margin-bottom: 1rem; */
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
        .pl-2,
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
        
    </style>
</head>
<body>
    <div class="container">
        <table class="table ">
                <tr>
                    <td width="50%">
                        <table class="table " style="width:95% !important">
                            <tr>
                                <td colspan="13"><center>REPORT ON ATTENDANCE</center></td>
                            </tr>
                            {{-- <tr class="table-bordered">
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
                            </tr> --}}
                        </table>
                        <table class="table" style="margin-top:100px !important">
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
                        <table class="table" style="font-size:13px !important; width:100% !importantl; "  >
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
                                <td class="text-center  p-0">{{$schoolinfo->regDesc}}</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">Sangay ng {{$schoolinfo->citymunDesc}}</td>
                            </tr>
                            <tr>
                                <td class="p-1" colspan="5">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">{{$schoolinfo->schoolname}}</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">{{$schoolinfo->address}}</td>
                            </tr>
                            <tr>
                            <td class="text-center  p-2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px"></td>
                            </tr>
                            <tr>
                                <td class="p-1 " colspan="5">&nbsp;</td>
                            </tr>
                        </table>
                        <table class="table p-0" style="font-size:13px; !important" >
                            @php
                                $midname = explode(' ',$student[0]->middlename);
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
                                    Should you desire to inquire more about your childs performance, you may contact the undersigned below.
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 " colspan="5">&nbsp;</td>
                            </tr>
                            <tr>
                                @php
                                    $midname = explode(' ',$student[0]->teachermiddlename);
                                    $midnamestring = '';
                                    if(count($midname) > 0){
                                        $midnamestring = substr($midname[0], 0, 1).'.';
                                    }
                                @endphp
                                <td colspan="3" class="text-center p-0 ">{{$student[0]->teacherfirstname}} {{$student[0]->teacherlastname}}</td>
                                {{-- @php
                                    $midname = explode(' ',Session::get('prinInfo')->middlename);
                                    $midnamestring = '';
                                    if(count($midname) > 0){
                                        $midnamestring = substr($midname[0], 0, 1).'.';
                                    }
                                @endphp --}}
                                <td colspan="2" class="text-center p-0 ">
                                    @if(count($principal) > 0)
                                        {{$principal[0]->firstname}} @if($principal[0]->middlename != null) {{$principal[0]->middlename[0]}}. @endif {{$principal[0]->lastname}} {{$principal[0]->suffix}}
                                    @endif
                                    {{-- {{Session::get('prinInfo')->firstname}} {{$midnamestring}} {{Session::get('prinInfo')->lastname}} --}}
                                </td>
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
    <table class="table" >
            <tr>
                <td width="50%" >
                    <table  class="table table-sm" style="width:90% !important">
                        <tr>
                            <th class="p-2 text-center border-0" colspan="7" style="font-size:15px !important;font-family: Arial, Helvetica, sans-serif;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                        </tr>
                       
                    </table>
                    <table class="table table-sm" style="width:95% !important">
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
                        $q1complete = true;
                        $q2complete = true;
                        $q3complete = true;
                        $q4complete = true;
                    @endphp
                
                    @if( count($grades) != 0)
                        @foreach ($grades as $item)

                            @if($item->q1 == null)
                                @php
                                    $q1complete = false;
                                @endphp
                            @endif

                            @if($item->q2 == null)
                                @php
                                    $q2complete = false;
                                @endphp
                            @endif

                            @if($item->q3 == null)
                                @php
                                    $q3complete = false;
                                @endphp
                            @endif

                            @if($item->q4 == null)
                                @php
                                    $q4complete = false;
                                @endphp
                            @endif

                            @php
                                $average = ($item->q1 + $item->q2 + $item->q3 + $item->q4) / 4 ;
                            @endphp

                            <tr class="table-bordered">
                                @if($item->subjdesc!=null)
                                    <td class="p-1" style="text-align: left !important" >
                                        {{$item->subjdesc}}
                                    </td>
                                @else
                                    <td class="p-1" style="text-align: left !important;" >
                                        &nbsp;
                                    </td>
                                @endif

                                @if($item->q1 != null)
                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->q1}}</td>
                                @else
                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                @endif

                                @if($item->q2 != null)
                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->q2}}</td>
                                @else
                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                @endif

                                @if($item->q3 != null)
                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->q3}}</td>
                                @else
                                    <td class="text-center p-0 align-middle"  style="font-size:13px !important">&nbsp;</td>
                                @endif

                                <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->q4}}</td>
                                
                                @if($item->q1 != null && $item->q2 != null && $item->q3 != null && $item->q4 != null)
                                    <td class="text-center p-0 align-middle" style="font-size:13px !important">{{number_format(($item->q1+$item->q2+$item->q3+$item->q4)/4)}}</td>
                                @else
                                    <td class="text-center p-0 align-middle" style="font-size:13px !important"></td>
                                @endif

                                @if($item->q1 != null && $item->q2 != null && $item->q3 != null && $item->q4 != null)
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
                            $genaverage = (collect($grades)->avg('q1') + collect($grades)->avg('q2') + collect($grades)->avg('q3') + collect($grades)->avg('q4')) / 4 ;
                        @endphp
                    @else
                        @php
                            $genaverage = null;    
                        @endphp
                    @endif
                        
                    <tr class="table-bordered genave">
                           
                            <th class="p-1" style="text-align: left !important">GENERAL AVERAGE
                            </th>

                            @if($q1complete)
                                <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->avg('q1'))}}</td>
                            @else
                                <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                            @endif

                            @if($q2complete)
                                <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->avg('q2'))}}</td>
                            @else
                                <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                            @endif

                            @if($q3complete)
                                <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->avg('q3'))}}</td>
                            @else
                                <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                            @endif

                            @if($q4complete)
                                <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->avg('q4'))}}</td>
                            @else
                                <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                            @endif

                            @if($item->q1 != null && $item->q2 != null && $item->q3 != null && $item->q4 != null)
                                <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important">{{number_format($average)}}</td>
                            @else
                                <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important"></td>
                            @endif

                            @if($item->q1 != null && $item->q2 != null && $item->q3 != null && $item->q4 != null)
                                <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                            @else
                                <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                            @endif
                        </tr>
                 
                    
                    </table>
                    <br/>
                    <table class="table  p-0 " style="font-size:11px !important">
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
                <td width="50%" style="padding:20px !important">
                    <table class="table table-sm"> 
                        <tr>
                            <th class="p-2 text-center border-0" colspan="6" style="font-size:15px !important;font-family: Arial, Helvetica, sans-serif;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                        </tr>
                       
                    </table>
                    <table class="table table-bordered table-sm" style="font-size:11px !important">
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
                            <td class="text-center">                                
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '1')
                                            {{$getvalue->makaDiyos_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">                            
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '2')
                                            {{$getvalue->makaDiyos_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '3')
                                            {{$getvalue->makaDiyos_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '4')
                                            {{$getvalue->makaDiyos_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" style="margin-bottom: 20px !important">Shows adherence to ethical principles by upholding the truth in all undertakings</td>
                            <td class="text-center">                                
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '1')
                                            {{$getvalue->makaDiyos_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">                            
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '2')
                                            {{$getvalue->makaDiyos_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '3')
                                            {{$getvalue->makaDiyos_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '4')
                                            {{$getvalue->makaDiyos_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1">2. Maka-Tao</td>
                            <td colspan="1" style="margin-bottom: 20px !important">Is sensitive to individual, social and cultural differences; resists stereotyping people</td>
                            <td class="text-center">                                
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '1')
                                            {{$getvalue->makaTao_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">                            
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '2')
                                            {{$getvalue->makaTao_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '3')
                                            {{$getvalue->makaTao_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '4')
                                            {{$getvalue->makaTao_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2" colspan="1">3. Maka-KALIKASAN</td>
                            <td colspan="1" style="margin-bottom: 20px !important">Demonstrates contributions towards solidarity</td>
                            <td class="text-center">                                
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '1')
                                            {{$getvalue->makaKalikasan_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">                            
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '2')
                                            {{$getvalue->makaKalikasan_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '3')
                                            {{$getvalue->makaKalikasan_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '4')
                                            {{$getvalue->makaKalikasan_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" style="margin-bottom: 20px !important">Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                            <td class="text-center">                                
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '1')
                                            {{$getvalue->makaKalikasan_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">                            
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '2')
                                            {{$getvalue->makaKalikasan_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '3')
                                            {{$getvalue->makaKalikasan_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '4')
                                            {{$getvalue->makaKalikasan_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                      
                        <tr>
                            <td rowspan="2" colspan="1">4. Maka-BANSA</td>
                            <td colspan="1" style="margin-bottom: 20px !important">Demonstrates pride in being a Filipino, exercises the rights and responsibilities of a Filipino Citizen</td>
                            <td class="text-center">                                
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '1')
                                            {{$getvalue->makaBansa_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">                            
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '2')
                                            {{$getvalue->makaBansa_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '3')
                                            {{$getvalue->makaBansa_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '4')
                                            {{$getvalue->makaBansa_1}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" style="margin-bottom: 20px !important">Demonstrates appropriate behavior in carrying out activities in the school, community and country</td>
                            <td class="text-center">                                
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '1')
                                            {{$getvalue->makaBansa_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">                            
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '2')
                                            {{$getvalue->makaBansa_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '3')
                                            {{$getvalue->makaBansa_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">           
                                @if(count($getValues) > 0)
                                    @foreach($getValues as $getvalue)
                                        @if($getvalue->quarter == '4')
                                            {{$getvalue->makaBansa_2}}
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    </table>
                    <table class="table table-sm" style="font-size:11px !important">
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