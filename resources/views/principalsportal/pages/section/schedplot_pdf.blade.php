<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>

        .table {
            width: 100%;
            margin-bottom: 0px;
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

        .td-bordered {
            border-right: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: .3rem;
        }

        .table-md td {
            font-size: .8rem !important;
            padding: .2rem;
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
            padding-top: 10px!important;
            padding-bottom: 10px!important;
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
            font-family: Arial, Helvetica, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .7rem !important;
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
        
/* 		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            } */

       
        
        @page { size: 8.5in 11in; margin: .25in .25in;  }
        
    </style>
</head>
      <body>

            <table class="table table-sm"  width="100%">
                  <tr>
                        <td rowspan="2" width="25%" class="text-right" style="padding:0 !important"><img  src="{{public_path($schoolinfo->picurl)}}" style="width:50px;"></td>
                        <td width="50%" style="font-size: 15px !important; padding-bottom:0 !important" class="text-center"><b> {{$schoolinfo->schoolname}}</b></td>
                        <td rowspan="2" width="25%" class="text-right" style="padding:0 !important"></td>
                  </tr>
                  <tr>
                        <td style="padding-top:0 !important; font-size:11px !important"  class="text-center">{{$schoolinfo->address}}</td>
                  </tr>
            </table>

            <table class="table table-sm " width="100%">
                  <tr>
                        <td width="100%" style="font-size: 1.2rem !important" class="text-center"><b>
                              @if($schedtype == 'room')
                                    ROOM SCHEDULE
                              @elseif($schedtype == 'section')
                                    SECTION SCHEDULE
                              @elseif($schedtype == 'teacher')
                                    TEACHER SCHEDULE
                              @endif </b>
                        </td>
                  </tr>
            </table>
            
            @if($schedtype == 'room')
                  <table class="table table-md mb-1 " width="100%">
                        <tr>
                              <td width="15%"><b>School Year: <b></td>
                              <td width="35%">{{$sy->sydesc}}</td>
                              <td width="50%"></td>
                        </tr>
                        <tr>
                              <td width="15%"><b>Room: <b></td>
                              <td width="35%">{{$roominfo->roomname}}</td>
                              <td width="50%"></td>
                        </tr>
                  </table>
            @elseif($schedtype == 'section')
                  <table class="table mb-1 table-md mb-1" width="100%">
                        <tr>
                              <td width="15%" ><b>School Year: <b></td>
                              <td width="35%" >{{$sy->sydesc}}</td>
                              <td width="50%" ></td>
                        </tr>
                        <tr>
                              <td width="10%" ><b>Section Name: <b></td>
                              <td width="40%" >@if(isset($sectioninfo->sectionname)) {{$sectioninfo->sectionname}} @else @endif</td>
                              <td width="50%" ></td>
                        </tr>
                        <tr>
                              <td width="10%" ><b>Adviser: <b></td>
                              <td width="40%" >@if(isset($adviser->lastname)) {{$adviser->lastname}}, {{$adviser->firstname}} @else @endif</td>
                              <td width="50%" class="p-0"></td>
                        </tr>
                  </table>
            @elseif($schedtype == 'teacher')
                  <table class="table table-md  mb-1" width="100%">
                        <tr>
                              <td width="15%"><b>School Year: <b></td>
                              <td width="35%">{{$sy->sydesc}}</td>
                              <td width="50%"></td>
                        </tr>
                        <tr>
                              <td ><b>Teacher: <b></td>
                              <td >@if(isset($adviser->lastname)) {{$adviser->lastname}}, {{$adviser->firstname}} @else @endif</td>
                              <td ></td>
                        </tr>
                  </table>
            @endif



            
            <table class="table table-sm" width="100%">
                  <tr><td width="100%" class="text-right">Date Generated: {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh:mm A')}}</td></tr>
            </table>
            @if(count($day_list) > 0)
                  <table class="table table-sm table-bordered" width="100%">
                        <tr>
                              <td width="12%" style="background-color:CornflowerBlue; color:white" class="text-center"><b>TIME</b></td>
                              @php
                                    $day_width = 88 / count($day_list);
                              @endphp
                              @foreach($day_list as $item)
                                    <td width="{{$day_width}}%" class="text-center align-middle" style="background-color:CornflowerBlue; color:white"><b>{{strtoupper($item)}}</b></td>
                              @endforeach
                        </tr>
                        @foreach($time_list as $time_item)
                              <tr>
                                    @php
                                          $timeinfo = collect($sched)->where('time',$time_item)->first();
                                    @endphp
                                    <td class="text-center align-middle">{{$timeinfo->stime}}<br>{{$timeinfo->etime}}</td>
                                    @foreach($day_list as $day_item)
                                          @php
                                                $day_sched = collect($sched)->where('description',$day_item)->where('time',$time_item)->values();
                                          @endphp
                                          @if(count( $day_sched) == 1)
                                                <td class="text-center align-middle">
                                                      <b>{{$day_sched[0]->subjdesc}}</b><br>
                                                      @if($schedtype != 'section')
                                                            <span style="font-size:.6rem !important">{{$day_sched[0]->sectionname}}</span><br>
                                                      @endif
                                                      @if($schedtype != 'teacher')
                                                            <span style="font-size:.6rem !important; "><i>{{$day_sched[0]->teacher}}</i></span><br>
                                                      @endif
                                                      @if($schedtype != 'room')
                                                            <span style="font-size:.6rem !important; color:blue">{{$day_sched[0]->roomname}}</span><br>
                                                      @endif
                                                      <span style="font-size:.6rem !important; color:red">{{$day_sched[0]->classification}}</span>
                                                </td>
                                          @elseif(count( $day_sched) > 1)
                                                <td class="text-center align-middle">
                                                      @foreach($day_sched as $day_sched_item)
                                                            <b>{{$day_sched_item->subjdesc}}</b><br>
                                                            @if($schedtype != 'section')
                                                                  <span style="font-size:.6rem !important">{{$day_sched_item->sectionname}}</span><br>
                                                            @endif
                                                            @if($schedtype != 'teacher')
                                                                  <span style="font-size:.6rem !important; "><i>{{$day_sched_item->teacher}}</i></span><br>
                                                            @endif
                                                            @if($schedtype != 'room')
                                                                  <span style="font-size:.6rem !important; color:blue">{{$day_sched_item->roomname}}</span><br>
                                                            @endif
                                                            <span style="font-size:.6rem !important; color:red">{{$day_sched_item->classification}}</span>
                                                            <br>
                                                            <br>
                                                      @endforeach
                                                </td>
                                          @else
                                                <td></td>
                                          @endif
                                    @endforeach
                              </tr>
                        @endforeach
                  </table>
            @else
                  <table class="table table-sm table-bordered" width="100%">
                        <tr>
                              <td class="text-center" width="100%">No Schedule Available</td>
                        </tr>
                  </table>

            @endif

      </body>
</html>