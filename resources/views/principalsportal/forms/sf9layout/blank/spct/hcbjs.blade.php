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
    </style>
    
   
</head>
<body>
      <table width="100%">
            <tr>
                  <td width="50%">
                        <table width="100%" style="font-size:13px ; ">
                             <tr>
                                   <td width="20%" rowspan="2">a</td>
                                   <td width="60%">b</td>
                                   <td width="20%" rowspan="2">c</td>
                             </tr>
                             <tr>
                                   <td>b</td>
                                   <td>c</td>
                             </tr>
                        </table>
                        <table width="100%" style="font-size:13px ; ">
                              <tr ><td style="border-bottom:solid 2px black !important"><center>SECONDARY REPORT CARD</center><td></td></tr>
                        </table>
                        <table width="100%" style="font-size:10px; margin-top:10px">
                              <tr>
                                    <td width="20%"></td>
                                    <td width="80%" class="text-center" style="border-bottom:solid 1px  black">Student Name</td>
                                    <td width="20%"></td>
                              </tr>
                              <tr>
                                    <td></td>
                                    <td><center>Name of Student</center></td>
                                    <td></td>
                              </tr>
                        </table>
                        <table width="100%"  style="font-size:10px; margin-top:10px" >
                              <tr>
                                    <td width="20%">Grade: </td>
                                    <td width="30%" style="border-bottom:solid 1px  black"></td>
                                    <td width="15%">Section: </td>
                                    <td width="30%" style="border-bottom:solid 1px  black"></td>
                              </tr>
                              <tr>
                                    <td >Curriculum: </td>
                                    <td style="border-bottom:solid 1px  black"></td>
                                    <td >Sex: </td>
                                    <td style="border-bottom:solid 1px  black"></td>
                              </tr>
                              <tr>
                                    <td >Birthdate: </td>
                                    <td  style="border-bottom:solid 1px  black"></td>
                                    <td >Age: </td>
                                    <td  style="border-bottom:solid 1px  black"></td>
                              </tr>
                              <tr>
                                    <td >School Year: </td>
                                    <td  style="border-bottom:solid 1px  black"></td>
                                    <td >LRN NO: </td>
                                    <td  style="border-bottom:solid 1px  black"></td>
                              </tr>

                        </table>
                        <hr>
                        <table class="table table-sm" width="100%" style="font-size:10px; border:solid 1px black">
                              <tr class="table-bordered">
                                  <td width="30%" rowspan="2" style="border:solid 1px  black"><center>Learning Areas</center></td>
                                  <td width=50%" colspan="4"  style="border:solid 1px  black"><center>Quarter</center></td>
                                    <td width="10%" rowspan="2" style="border:solid 1px  black"><center>Final Grade</center></td>
                                    <td width="10%" rowspan="2" style="border:solid 1px  black"><center>Remarks</center></td>
                              </tr>
                              <tr class="table-bordered" style="font-size:11px !important;">
                                    <td style="border:solid 1px  black"><center>1</center></td>
                                    <td style="border:solid 1px  black"><center>2</center></td>
                                    <td style="border:solid 1px  black"><center>3</center></td>
                                    <td style="border:solid 1px  black"><center>4</center></td>
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
                                          <td class="p-1 @if($item->mapeh == 1) pl-4 @endif" style="text-align: left !important" >
                                              {{$item->subjectcode}}
                                          </td>
                                      @else
                                          <td class="p-1" style="text-align: left !important;" >
                                              &nbsp;
                                          </td>
                                      @endif

                                      @if($item->quarter1 != null)
                                          <td class="text-center p-0 align-middle" >{{$item->quarter1}}</td>
                                      @else
                                          <td class="text-center p-0 align-middle" >&nbsp;</td>
                                      @endif

                                  

                                      @if($item->quarter2 != null)
                                          <td class="text-center p-0 align-middle" >{{$item->quarter2}}</td>
                                      @else
                                          <td class="text-center p-0 align-middle" >&nbsp;</td>
                                      @endif

                                      @if($item->quarter3 != null)
                                          <td class="text-center p-0 align-middle" >{{$item->quarter3}}</td>
                                      @else
                                          <td class="text-center p-0 align-middle"  >&nbsp;</td>
                                      @endif

                                      <td class="text-center p-0 align-middle" >{{$item->quarter4}}</td>
                                      
                                      @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                          <td class="text-center p-0 align-middle" >{{number_format( ($item->quarter1+$item->quarter2+$item->quarter3+$item->quarter4)/4)}}</td>
                                      @else
                                          <td class="text-center p-0 align-middle" ></td>
                                      @endif

                                      @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                          <td class="text-center p-0 align-middle" ><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                      @else
                                          <td class="text-center p-0 align-middle" ></td>
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
                              
                          {{-- <tr class="table-bordered">
                                  <th class="p-1" style="text-align: left !important">GENERAL AVERAGE {{$quarter2complete}}
                                  </th>

                                  @if($quarter1complete)
                                      <td class="text-center p-1" >{{round(collect($grades)->where('mapeh',0)->avg('quarter1'))}}</td>
                                  @else
                                      <td class="text-center p-1" >&nbsp;</td>
                                  @endif

                                  @if($quarter2complete)
                                      <td class="text-center p-1" >{{round(collect($grades)->where('mapeh',0)->avg('quarter2'))}}</td>
                                  @else
                                      <td class="text-center p-1" >&nbsp;</td>
                                  @endif
                              
                                  @if($quarter3complete)
                                      <td class="text-center p-1" >{{round(collect($grades)->where('mapeh',0)->avg('quarter3'))}}</td>
                                  @else
                                      <td class="text-center p-1" >&nbsp;</td>
                                  @endif

                                  @if($quarter4complete)
                                      <td class="text-center p-1" >{{round(collect($grades)->where('mapeh',0)->avg('quarter4'))}}</td>
                                  @else
                                      <td class="text-center p-1" >&nbsp;</td>
                                  @endif

                                  @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                      <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important">{{number_format($average)}}</td>
                                  @else
                                      <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important"></td>
                                  @endif

                                  @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                      <td class="text-center p-0 align-middle"  ><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                  @else
                                      <td class="text-center p-0 align-middle"  ></td>
                                  @endif
                              </tr> --}}
                          </table>
                          <table width="100%"  style="font-size:11px !important; margin-top:20px">
                              <tr>
                                  <td><center>Report on Attendance</center></td>
                              </tr>
                          </table >
                          <table width="100%" style="border:solid 1px black; font-size:11px !important;" >
                              <tr class="table-bordered">
                                  <td></td>
                                  @foreach ($attSum as $item)
                                      <td class="text-center text-center" style="font-size:10px !important">{{\Carbon\Carbon::create($item->month)->isoFormat('MMM')}}</td>
                                  @endforeach
                                  <td class="text-center text-center" style="font-size:10px !important">Total</td>
                              </tr>
                              <tr class="table-bordered" >
                                  <td style="font-size:9px !important">Days of School</td>
                                  @foreach ($attSum as $item)
                                      <td class="align-middle text-center">{{$item->count}}</td>
                                  @endforeach
                                  <td class="align-middle text-center">{{collect($attSum)->sum('count')}}</td>
                              </tr>
                              <tr class="table-bordered">
                                  <td style="font-size:9px !important">Days of Present</td>
                                  @foreach ($attSum as $item)
                                      <td class="align-middle text-center">{{$item->countPresent}}</td>
                                  @endforeach
                                  <td class="align-middle text-center">{{collect($attSum)->sum('countPresent')}}</td>
                              </tr>
                              <tr class="table-bordered">
                                  <td style="font-size:9px !important">Times Tardy</td>
                                  @foreach ($attSum as $item)
                                      <td class="align-middle text-center">{{$item->countAbsent}}</td>
                                  @endforeach
                                  <td class="align-middle text-center">{{collect($attSum)->sum('countAbsent')}}</td>
                              </tr>
                          </table>
                    

                  </td>   
                  <td width="50%">
                  </td>  
            </tr>
      </table>
</body>
</html>