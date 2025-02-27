
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <style>
        .font-one{
            font-family: Arial, Helvetica, sans-serif !important;
            font-stretch: semi-expanded
    ;
        }
        .font-two{
            font-family: 'Bookman', 'URW Bookman L', serif;
            
        }
        .table-records td{
            padding: 0px;
        }
          *{
              
            font-family: Arial, Helvetica, sans-serif;
          }
          table{
              border-collapse: collapse;
          }
        @page { margin: 30px 50px 0px; size: 8.5in 13in}
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 320px;}
        footer { position: fixed; bottom: 30; left: 0px; right: 0px; height: 190px;}
        /* p { page-break-after: always;} */
        p:last-child { page-break-after: never; }
        
        #watermark {
            position: fixed;
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif !important;
            /** 
                Set a position in the page for your image
                This should center it vertically
            **/
            bottom:   0.5cm;
            left:     0.2cm;
            opacity: 1;

            /** Change image dimensions**/
            /* width:    8cm;
            height:   8cm; */

            /** Your watermark should be behind every content**/
            z-index:  -1000;
        }
      </style>
    </head>
    <body>
        
        <table style="width: 100%; margin-bottom: 10px;">
            <tr style=" font-size: 13px;">
                <th colspan="2">DAILY TIME RECORD</th>
            </tr>
            <tr>
                <th colspan="2"><br/></th>
            </tr>
            <tr style=" font-size: 11px;">
                <td style="width: 15%; ">Employee Name:</td>
                <td style="border-bottom: 1px solid black;">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</td>
                {{-- <td>Month Starting:</td>
                <td style="width: 20%; border-bottom: 1px solid black;"></td>
                <td style="width: 20%; "></td> --}}
            </tr>
            <tr style=" font-size: 11px;">
                <td>Designation:</td>
                <td style="border-bottom: 1px solid black;">{{$employee->utype}}</td>
                {{-- <td>Month Ending:</td>
                <td style="border-bottom: 1px solid black;"></td>
                <td></td> --}}
            </tr>
            <tr style=" font-size: 11px;">
                <td>Date Period:</td>
                <td style="border-bottom: 1px solid black;">
                    @if($datefrom == $dateto)
                    {{date('F d, Y', strtotime($datefrom))}}
                    @else
                    {{date('F d, Y', strtotime($datefrom))}} - {{date('F d, Y', strtotime($dateto))}}
                    @endif
                </td>
                {{-- <td>Month Ending:</td>
                <td style="border-bottom: 1px solid black;"></td>
                <td></td> --}}
            </tr>
        </table>
                <table style="width: 100%; table-layout: fixed; font-size: 11px;" border="1">
                    <thead>
                        <tr>
                            <th colspan="2" style="width: 20%;">Date</th>
                            <th colspan="2">AM</th>
                            <th colspan="2">PM</th>
                            <th rowspan="2" style="width: 25%;">Remarks</th>
                            <th rowspan="2" style="width: 25%;">School Activities/Events</th>
                            {{-- <th rowspan="2" style="width: 15%;"></th> --}}
                        </tr>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>IN</th>
                            <th>OUT</th>
                            <th>IN</th>
                            <th>OUT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendance as $eachdayatt)
                            {{-- @php
                                if(collect($finalcalendar)->where('date', $eachdayatt->date)->count() > 0)
                                {
                                    $rowspan = (collect($finalcalendar)->where('date', $eachdayatt->date)->count())+1;
                                }else{
                                    $rowspan = 1;
                                }

                            @endphp --}}
                            <tr>
                                <th style="vertical-align: middle;">{{date('m/d/Y', strtotime($eachdayatt->date))}}</th>
                                <td style="vertical-align: middle; text-align: center;">{{date('D', strtotime($eachdayatt->date))}}</td>
                                <td style="text-align: center; vertical-align: middle;">
                                    
                                    {{$eachdayatt->timeinam != null ? date('h:i', strtotime($eachdayatt->timeinam)) : ''}}
                                
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    
                                    {{$eachdayatt->timeoutam != null ? date('h:i', strtotime($eachdayatt->timeoutam)) : ''}}
                                
                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    
                                    {{$eachdayatt->timeinpm != null ? date('h:i', strtotime($eachdayatt->timeinpm)) : ''}}
                                
                                </td>
                                <td style="text-align: center; vertical-align: middle;">                                    
                                    {{$eachdayatt->timeoutpm != null ? date('h:i', strtotime($eachdayatt->timeoutpm)) : ''}}                                
                                </td>
                                <td>
                                    {{$eachdayatt->remarks}}&nbsp;
                                </td>
                                <td style="vertical-align: middle;">
                                    @if(collect($finalcalendar)->where('date', $eachdayatt->date)->count() > 0)
                                        @foreach(collect($finalcalendar)->where('date', $eachdayatt->date)->values() as $eachdatecal)
                                        {{$eachdatecal->title}}<br/>
                                        @endforeach
                                    @endif
                                </td>
                                {{-- <td style="vertical-align: middle;"><button class="btn btn-secondary btn-sm btn-submitremarks" data-date="{{$eachdayatt->date}}"><i class="fa fa-share"></i> Submit Remarks</button></td> --}}
                            </tr>
                            {{-- @if($rowspan > 1)
                                @foreach(collect($finalcalendar)->where('date', $eachdayatt->date)->values() as $eachdatecal)
                                <tr>
                                    <td>
                                        Event/Activity Title: {{$eachdatecal->title}}
                                    </td>
                                </tr>
                                @endforeach
                            @endif --}}
                        @endforeach
                    </tbody>
                </table>
                <table style="width: 70%; font-size: 11px; margin-top: 15px;">
                    {{-- <tr>
                        <td colspan="2" style="width: 20%;">Actions to be Taken</td>
                        <td style="border-bottom: 1px solid black;"></td>
                    </tr> --}}
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 25%;">Employee Signature:</td>
                        <td style="width: 30%; border-bottom: 1px solid black;"></td>
                        <td >&nbsp;</td>
                        {{-- <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;Total Hours:</td>
                        <td colspan="2" style="border-bottom: 1px solid black;">{{$overalltotalhours}}h {{$overalltotalminutes}}m</td> --}}
                    </tr>
                    {{-- <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="2">Prepared By:</td>
                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;"></td>
                        <td colspan="2" rowspan="2" style="vertical-align: bottom;">&nbsp;&nbsp;&nbsp;&nbsp;Total Late Hours:</td>
                        <td colspan="2" rowspan="2" style="border-bottom: 1px solid black;"></td>
                    </tr>
                    <tr>
                        <td style="font-size: 9px;">DTR Monitoring In-Charge /
                            Principal's Office Clerk</td>
                    </tr> --}}
                </table>
                @if(count($finalcalendar)>0)
                    @php
                        $schoolcalendar = collect($finalcalendar)->whereIn('date', collect($attendance)->pluck('date'))->groupBy('title');
                    @endphp
                <table style="width: 100%; font-size: 10px; margin-top: 30px; page-break-inside: avoid;" border="1">
                    <tr>
                        <th>
                            School Activities/Events
                        </th>
                    </tr>
                    @foreach($schoolcalendar as $eachtitlekey => $eachvalue)
                        <tr>
                            <td>
                                Title: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$eachtitlekey}}<br/>
                                Venue: &nbsp;{{$eachvalue[0]->venue}}<br/>
                                Start: &nbsp;&nbsp;&nbsp;&nbsp;{{date('m/d/Y h:i A , l ', strtotime($eachvalue[0]->startwithtime))}}<br/>
                                End: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{date('m/d/Y h:i A , l ', strtotime($eachvalue[0]->endwithtime))}}
                            </td>
                        </tr>
                    @endforeach
                </table>
                @endif
    </body>
</html>