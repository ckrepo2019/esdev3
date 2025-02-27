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

        .mt-0{
            margin-top: 0;
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
        @page {  
            margin:20px 20px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }

        .p-1{
                  padding: .25rem !important;
            }

        .copy{
            height: 5.1in;
        }
		
        @page { size: 8.5in 11in; margin: 10px 15px;  }
        
    </style>
</head>
<body>
    <div class="copy" style="border-bottom: 1px black dashed">
        <table style="width: 100%; table-layout: fixed;" class="">
            <tr>
                <td width="25%" class="text-center" style="vertical-align: middle;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
                </td>
                <td width="50%" class="text-center" style="vertical-align: middle; font-size: 13px;">
                    <span><b>{{$schoolInfo->schoolname}}</b></span><br>
                    <span>{{$schoolInfo->address}}</span><br>
                    <span><b>Records and Admission Office</b></span><br><br>
                    <span><b>CERTIFICATE OF REGISTRATION</b></span><br>
                </td>
                <td width="25%"></td>
            </tr>
        </table>

        <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid #000; margin-top: 5px;">
            <tr>
                <td class="text-center p-0" style="font-size: 12px; padding-top:5px !important"><b>[{{$studentInfo->sid}}] {{$studentInfo->student}}</b></td>
            </tr>
            <tr>
                <td class="text-center p-0" style="font-size: 10px; ">{{$studentInfo->courseDesc}}, {{$studentInfo->levelname}}</td>
            </tr>
            <tr>
                <td class="text-center p-0" style="font-size: 11px; padding-top: 0 !important; padding-bottom: 5px !important">AY {{$activeSy->sydesc}} {{$activeSem->semester}}</td>
            </tr>
        </table>


        {{-- <table width="100%" class="table table-sm mb-0 mt-0 table-bordered" style="table-layout: fixed; font-size: 9px;border: 1px solid #000;!important; background-color: #32038f; color: #fff;">
            <tr>
                <th width="7.5%" class="text-left" style="padding-left: 5px;"><b>Class</b></th>
                <th width="10%" class="text-left" style=""><b>Subj. Code</b></th>
                <th width="30%" class="text-left" style=""><b>Subject Title</b></th>
                <th width="5%" class="text-center" style=""><b>Units</b></th>
                <th width="21%" class="text-left" style=""><b>Schedule</b></th>
                <th width="18.5%" class="text-left" style=""><b>Instructor</b></th>
                <th width="8%" class="text-left" style=""><b>Room</b></th>
            </tr>
        </table> --}}
        <table width="100%" class="table table-sm mb-0 mt-0 " style="table-layout: fixed; font-size: 9px;border: 1px solid #000;!important">
            @php  
                $totalUnits = 0.0;
            @endphp
            <tr style="table-layout: fixed; font-size: 9px;border: 1px solid #000;!important; background-color: #32038f; color: #fff;">
                <th width="7.5%" class="text-left" style="padding-left: 5px;"><b>Class</b></th>
                <th width="8%" class="text-left" style=""><b>Subj. Code</b></th>
                <th width="30%" class="text-left" style=""><b>Subject Title</b></th>
                <th width="5%" class="text-center" style=""><b>Units</b></th>
                <th width="23%" class="text-left" style=""><b>Schedule</b></th>
                <th width="18.5%" class="text-left" style=""><b>Instructor</b></th>
                <th width="8%" class="text-left" style=""><b>Room</b></th>
            </tr>
            @foreach ($schedules as $item)
                <tr>
                    <td class="text-left p-1" style="padding-left: 5px;">{{$item[0]->sectionDesc}}</td>
                    <td class="text-left p-1" style="">{{$item[0]->subjCode}}</td>
                    <td class="text-left p-1" style="">{{$item[0]->subjDesc}}</td>
                    <td class="text-center p-1" style=""> {{number_format($item[0]->lecunits + $item[0]->labunits,1)}}</td>
                    <td class="text-left p-1" style="">
                        @foreach($item as $timeitem)
                            @php
                                $schedother = $timeitem->schedotherclass == 'Laboratory' ? 'Lab:. ' : 'Lec:. ';
                            @endphp
                            @if($timeitem->stime != null && $timeitem->etime != null)
                                <p class="mb-0 mt-0">{{$schedother}} {{$timeitem->description}} 
                                    @if($timeitem->etime != null && $timeitem->stime != null)
                                            {{\Carbon\Carbon::create($timeitem->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($timeitem->etime)->isoFormat('hh:mm A')}}
                                    @endif
                                </p>
                            @else
                                <p class="mb-0  mt-0"></p>
                            @endif
                    @endforeach
                        {{-- {{$item->description}} @if($item->etime != null && $item->stime != null)
                                                                                    {{\Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A')}}
                                                                            @endif</td> --}}
                    <td class="text-left p-1" style="">
                        {{-- {{$item->lastname != null ? $item->lastname.', '.$item->firstname  : ''}} --}}
                        {{$item[0]->teacher}}
                    </td>
                    <td class="text-left p-1" style="">
                        {{-- {{$item->roomname}} --}}
                    
                        @foreach($item as $roomitem)
                                @if($roomitem->roomname != null)
                                    <p class="mb-0 mt-0">{{$roomitem->roomname}}</p>
                                @else
                                    <p class="mb-0 mt-0">TBA</p>
                                @endif
                        @endforeach
                    </td>
                </tr>
                    @php  
                        $totalUnits += number_format($item[0]->lecunits + $item[0]->labunits,1);
                    @endphp
            @endforeach
            <tr style="border:solid 1px black;">
                    <th width="7.5%" class="text-left" style="padding-left: 5px;border-top:solid 2px black;"></th>
                    <th width="12.5%" class="text-left" style="border-top:solid 2px black;"></th>
                    <th width="30%" class="text-left" style="border-top:solid 2px black;"><b>Total</b></th>
                    <th width="5%" class="text-center" style="border-top:solid 2px black;"><b>{{number_format($totalUnits,1)}}</b></th>
                    <th width="18.5%" class="text-left" style="border-top:solid 2px black;"></th>
                    <th width="18.5%" class="text-left" style="border-top:solid 2px black;"></th>
                    <th width="8%" class="text-left" style="border-top:solid 2px black;"></th>
                </tr>
        </table>
        <table width="100%" class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 9px;">
            <tr>
                <td width="100%" class="p-1">
                    <span>This is your official certificate of registration. Please check and verify thoroughly the correctness of these data. If you have question or verification on.</span><br>
                    <span>the data found in this report, you may visit the RECORDS AND ADMISSION OFFICE @if($schoolInfo->abbreviation == 'HCCSI')or you may call us at +63 82 2330013 @else. @endif</span>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 9px;">
            <tr>
                <td center="p-0" width="60%"></td>
                <td class="text-center p-1" width="20%" style="font-size:11px !important"><b>{{$registrar_sig}}</b></td>
                <td center="p-0" width="20%"></td>
            </tr>
            <tr>
                <td center="p-0" width="60%"></td>
                <td class="text-center p-0" width="20%" style="font-size:10px !important">Registar</td>
                <td center="p-0" width="20%"></td>
            </tr>
        </table>
    </div>
    <div class="copy" style=" margin-top: 20px !important">
        <table style="width: 100%; table-layout: fixed;" class="">
            <tr>
                <td width="25%" class="text-center" style="vertical-align: middle;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
                </td>
                <td width="50%" class="text-center" style="vertical-align: middle; font-size: 13px;">
                    <span><b>{{$schoolInfo->schoolname}}</b></span><br>
                    <span>{{$schoolInfo->address}}</span><br>
                    <span><b>Records and Admission Office</b></span><br><br>
                    <span><b>CERTIFICATE OF REGISTRATION</b></span><br>
                </td>
                <td width="25%"></td>
            </tr>
        </table>

        <table width="100%" class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid #000; margin-top: 5px;">
            <tr>
                <td class="text-center p-0" style="font-size: 12px; padding-top:5px !important"><b>[{{$studentInfo->sid}}] {{$studentInfo->student}}</b></td>
            </tr>
            <tr>
                <td class="text-center p-0" style="font-size: 10px; ">{{$studentInfo->courseDesc}}, {{$studentInfo->levelname}}</td>
            </tr>
            <tr>
                <td class="text-center p-0" style="font-size: 11px; padding-top: 0 !important; padding-bottom: 5px !important">AY {{$activeSy->sydesc}} {{$activeSem->semester}}</td>
            </tr>
        </table>


        {{-- <table width="100%" class="table table-sm mb-0 mt-0 table-bordered" style="table-layout: fixed; font-size: 9px;border: 1px solid #000;!important; background-color: #32038f; color: #fff;">
            <tr>
                <th width="7.5%" class="text-left" style="padding-left: 5px;"><b>Class</b></th>
                <th width="10%" class="text-left" style=""><b>Subj. Code</b></th>
                <th width="30%" class="text-left" style=""><b>Subject Title</b></th>
                <th width="5%" class="text-center" style=""><b>Units</b></th>
                <th width="21%" class="text-left" style=""><b>Schedule</b></th>
                <th width="18.5%" class="text-left" style=""><b>Instructor</b></th>
                <th width="8%" class="text-left" style=""><b>Room</b></th>
            </tr>
        </table> --}}
        <table width="100%" class="table table-sm mb-0 mt-0 " style="table-layout: fixed; font-size: 9px;border: 1px solid #000;!important">
            @php  
                $totalUnits = 0.0;
            @endphp
            <tr style="table-layout: fixed; font-size: 9px;border: 1px solid #000;!important; background-color: #32038f; color: #fff;">
                <th width="7.5%" class="text-left" style="padding-left: 5px;"><b>Class</b></th>
                <th width="8%" class="text-left" style=""><b>Subj. Code</b></th>
                <th width="30%" class="text-left" style=""><b>Subject Title</b></th>
                <th width="5%" class="text-center" style=""><b>Units</b></th>
                <th width="23%" class="text-left" style=""><b>Schedule</b></th>
                <th width="18.5%" class="text-left" style=""><b>Instructor</b></th>
                <th width="8%" class="text-left" style=""><b>Room</b></th>
            </tr>
            @foreach ($schedules as $item)
                <tr>
                    <td class="text-left p-1" style="padding-left: 5px;">{{$item[0]->sectionDesc}}</td>
                    <td class="text-left p-1" style="">{{$item[0]->subjCode}}</td>
                    <td class="text-left p-1" style="">{{$item[0]->subjDesc}}</td>
                    <td class="text-center p-1" style=""> {{number_format($item[0]->lecunits + $item[0]->labunits,1)}}</td>
                    <td class="text-left p-1" style="">
                        @foreach($item as $timeitem)
                            @php
                                $schedother = $timeitem->schedotherclass == 'Laboratory' ? 'Lab:. ' : 'Lec:. ';
                            @endphp
                            @if($timeitem->stime != null && $timeitem->etime != null)
                                <p class="mb-0 mt-0">{{$schedother}} {{$timeitem->description}} 
                                    @if($timeitem->etime != null && $timeitem->stime != null)
                                            {{\Carbon\Carbon::create($timeitem->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($timeitem->etime)->isoFormat('hh:mm A')}}
                                    @endif
                                </p>
                            @else
                                <p class="mb-0  mt-0">TBA</p>
                            @endif
                    @endforeach
                        {{-- {{$item->description}} @if($item->etime != null && $item->stime != null)
                                                                                    {{\Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A')}}
                                                                            @endif</td> --}}
                    <td class="text-left p-1" style="">
                        {{-- {{$item->lastname != null ? $item->lastname.', '.$item->firstname  : ''}} --}}
                        {{$item[0]->teacher}}
                    </td>
                    <td class="text-left p-1" style="">
                        {{-- {{$item->roomname}} --}}
                    
                        @foreach($item as $roomitem)
                                @if($roomitem->roomname != null)
                                    <p class="mb-0 mt-0">{{$roomitem->roomname}}</p>
                                @else
                                    <p class="mb-0 mt-0">TBA</p>
                                @endif
                        @endforeach
                    </td>
                </tr>
                    @php  
                        $totalUnits += number_format($item[0]->lecunits + $item[0]->labunits,1);
                    @endphp
            @endforeach
            <tr style="border:solid 1px black;">
                    <th width="7.5%" class="text-left" style="padding-left: 5px;border-top:solid 2px black;"></th>
                    <th width="12.5%" class="text-left" style="border-top:solid 2px black;"></th>
                    <th width="30%" class="text-left" style="border-top:solid 2px black;"><b>Total</b></th>
                    <th width="5%" class="text-center" style="border-top:solid 2px black;"><b>{{number_format($totalUnits,1)}}</b></th>
                    <th width="18.5%" class="text-left" style="border-top:solid 2px black;"></th>
                    <th width="18.5%" class="text-left" style="border-top:solid 2px black;"></th>
                    <th width="8%" class="text-left" style="border-top:solid 2px black;"></th>
                </tr>
        </table>
        <table width="100%" class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 9px;">
            <tr>
                <td width="100%">
                    <span>This is your official certificate of registration. Please check and verify thoroughly the correctness of these data. If you have question or verification on.</span><br>
                    <span>the data found in this report, you may visit the RECORDS AND ADMISSION OFFICE @if($schoolInfo->abbreviation == 'HCCSI')or you may call us at +63 82 2330013 @else. @endif</span>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 9px;">
            <tr>
                <td center="p-0" width="60%"></td>
                <td class="text-center p-1" width="20%" style="font-size:11px !important"><b>{{$registrar_sig}}</b></td>
                <td center="p-0" width="20%"></td>
            </tr>
            <tr>
                <td center="p-0" width="60%"></td>
                <td class="text-center p-0" width="20%" style="font-size:10px !important">Registar</td>
                <td center="p-0" width="20%"></td>
            </tr>
        </table>
    </div>
</body>
</html>