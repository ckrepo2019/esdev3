<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>Certificate of Enrollment</title>
    <style>
        @page { size: 11in 8.5in; margin: .25in;  }
        
        #watermark1 {
       position: fixed;
            text-align: center !important;
                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                //bottom:   5cm;
                /*left:     1cm;*/
                opacity: 0.1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
        
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


       
       

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: "Lucida Console", "Courier New", monospace;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 11px !important;
            font-family: "Lucida Console", "Courier New", monospace;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .text-red{
            color: red;
            border: solid 1px black;
        }

        .mb-0{
            margin-bottom: 0!important;
        }

    </style>
    <style>
        .double {
            border-style: double;
        }
    </style>
    <style>
        .detail-margin {
            margin-right: 50px; margin
            margin-left: 50px;
        }
        .detail-margin1 {
            margin-right: 70px;
            margin-left: 70px;
        }
        .pl-2, .px-2 {
            padding-left: 0.5rem!important;
        }
    </style>
</head>


<head>
     <body>
         <div id="watermark1" >
            <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" height="50%" width="50%" />
         </div>
         <table class="table grades " width="100%">
            <tr>
                <td style="text-align: right !important; vertical-align: top;" width="25%">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px">
                </td>
                <td style="width: 50%; text-align: center;" class="align-middle">
                    <div style="width: 100%; font-weight: bold; font-size: 19px !important;">{{$schoolinfo->schoolname}}</div>
                    <div style="width: 100%; font-size: 12px;">{{$schoolinfo->address}}</div>
                </td>
                <td width="25%">
                    {{-- <img src="{{base_path()}}/public/uccplogo.png" alt="school" width="70px"> --}}
                </td>
            </tr>
        </table>
        <br>
      
        <table class="table grades " width="100%">
            <tr>
                <td width="20%" class="text-center"><b>{{$sydesc->sydesc}}</b></td>
                <td width="20%" class="text-center"><b>{{$semdesc->semester}}</b></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            </tr>
            <tr>
                <td class="text-center">School Year</td>
                <td class="text-center">Semester</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            </tr>
        </table>
        <table class="table grades " width="100%">
            <tr>
                <td width="20%" class="text-center"><b>{{$sectioninfo->sectionDesc}}</b></td>
                <td width="20%" class="text-center"><b>{{$sectioninfo->leveltext}}</b></td>
                <td width="40%" class="text-center"><b>{{$sectioninfo->courseDesc}}</b></td>
                <td width="20%" class="text-center"></td>
            </tr>
            <tr>
                <td class="text-center">Section Name</td>
                <td class="text-center">Year Level</td>
                <td class="text-center">Course</td>
                <td class="text-center"></td>
            </tr>
        </table>
        <table class="table table-bordered table-sm" style="font-size: 12px !important">
            <thead>
            <tr>
                  <th class="text-center align-middle p-0" width="45%">Subject</th>
                  <th class="text-center align-middle p-0" width="5%">Units</th>
                  <th class="text-center align-middle p-0" width="10%">Day</th>
                  <th class="text-center align-middle p-0" width="10%">Time</th>
                  <th class="text-center align-middle p-0" width="10%" >Room</th>
                  <th class="text-center align-middle p-0" width="20%">Teacher</th>
            </tr>
            </thead>
            <tbody >
                @foreach($schedule as $sched)

                    @php  
                        $comp = '';
                        $consolidate = '';
                        $spec = '';
                        $type = '';
                    @endphp
    
                    @if(count($sched->schedule) > 0)
                            @php
                                $first = true;
                                $first_id = null;
                            @endphp
                            <tr style="font-size:.7rem !important">
                                <td class="align-middle " rowspan="{{count($sched->schedule)}}" >
                                    
                                        <p class="mb-0" style="font-size:.9rem !important; margin-top:0 !important" >{{$sched->subjDesc}}</p>
                                        <p class="text-muted mb-0" style="font-size:.7rem;  margin-top:0 !important">{{$sched->subjCode}} <i class="text-danger">{{$type}}</i></p>
                                </td>
                                <td class=" p-0 align-middle text-center" rowspan="{{count($sched->schedule)}}">
                                    {{$sched->units}}
                                </td>
                                @foreach ($sched->schedule as $item)
                                        @if($first)
                                            <td class="text-center align-middle p-0">
                                                    @if(isset($item->classification))
                                                        <span class="text-primary text-bold">{{$item->classification}}</span><br>
                                                    @endif
                                                    {{$item->day}}
                                            </td>
                                            <td class=" text-center align-middle p-0">
                                                {{$item->start}}<br>{{$item->end}}
                                            </td>
                                            <td class=" text-center align-middle p-0">
                                                {{$item->roomname}}
                                            </td>
                                            <td class=" text-center align-middle p-0" rowspan="{{count($sched->schedule)}}">
                                                    <p class="mb-0" style="margin-top:0 !important">{{$sched->teacher}}</p>
                                            </td>
                                            @php
                                                    $first_id = $item->sched_count;
                                                    $first = false;
                                            @endphp
                                        @endif
                                @endforeach
                            </tr>
                            @foreach (collect($sched->schedule)->where('sched_count','!=',$first_id)->values() as $item)
                                <tr style="font-size:11px !important">
                                        <td class="text-center align-middle  p-0">
                                            @if(isset($item->classification))
                                                    <span class="text-primary text-bold">{{$item->classification}}</span><br>
                                            @endif
                                            {{$item->day}}
                                        </td>
                                        <td class="text-center align-middle  p-0"> {{$item->start}}<br>{{$item->end}}</td>
                                        <td class="text-center align-middle  p-0">{{$item->roomname}}</td>
                                </tr>
                            @endforeach
                    @else
                            <tr  style="font-size:11px !important">
                                <td  class="align-middle pl-2" style="font-size:11px !important">
                                        <p class="mb-0"  style="font-size:.89rem !important; margin-top:0 !important">{{$sched->subjDesc}}</p>
                                        <p class="text-muted mb-0" style="font-size:.7rem; margin-top:0 !important">{{$sched->subjCode}} <i class="text-danger">{{$type}}</i></p>
                                </td>
                                <td class="text-center align-middle  p-0">{{$sched->units}}</td>
                                <td class=" p-0"></td>
                                <td class=" p-0"></td>
                                <td class=" p-0"></td>
                                <td class=" p-0 text-center align-middle">
                                        <p class="mb-0" style="margin-top:0 !important">{{$sched->teacher}}</p>
                                        <p class="text-muted mb-0" style="font-size:.7rem;margin-top:0 !important">{{$sched->teacherid}}</p>
                                </td>
                            </tr>
                    @endif
                @endforeach
                <tr style="font-size:11px !important">
                    <td class="text-right">Total Units</td>
                    <td class="text-center">{{collect($schedule)->sum('units')}}</td>
                    <td colspan="4"></td>
                </tr>

            </tbody>
      </table>
    </body>
</head>
</html>