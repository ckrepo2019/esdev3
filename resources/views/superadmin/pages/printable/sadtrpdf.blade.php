<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$name}} Tap Monitoring</title>
    <style>

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:12px ;
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


        .bg-danger {
            background-color: #dc3545!important;
        }
        @page { size: 8.5in 11in; margin: .25in;  }
        
    </style>
</head>
<body>

    <table class="table table-sm mb-0 " style="font-size:13px;">
        <tr>
            <td width="20%" rowspan="2" class="text-right align-middle p-0">
                <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px">
            </td>
            <td width="60%" class="p-0 text-center" >
                <h3 class="mb-0" style="font-size:18px !important">{{$schoolinfo->schoolname}}</h3>
            </td>
            <td width="20%" rowspan="2" class="text-right align-middle p-0">
            
            </td>
        </tr>
        <tr>
            <td class="p-0 text-center">
                {{$schoolinfo->address}}
            </td>
        </tr>
        
    </table>
    <table class="table table-sm" >
        <tr>
            <td class="p-0 text-center align-middle">
                <span style="font-size:18px !important"><b>Attendance Logs</b></span>
            </td>
        </tr>
    </table>
    <table class="table-hover table table-striped table-sm mb-0 " width="100%" >
        <tr>
            <td> Name : {{$name}}</td>
        </tr>
    </table>
    <table class="table-hover table table-striped table-sm table-bordered" width="100%" >
      
              <tr>
                    <th width="20%" rowspan="2" class="align-middle text-left">Date</th>
                    <th width="20%" rowspan="2" class="text-center align-middle">Day</th>
                    <th width="60%" colspan="2" class="text-center">Time Logs</th>
              </tr>
              <tr>
                    <th  width="30%" class="text-center">Time</th>
                    <th  width="30%" class="text-center">Tap</th>
              </tr>
      
            @foreach($attendance as $item)
                @if(count($item->logs) > 0)
                    @php
                        $firstitem = $item->logs[0];
                        $rowspan = count($item->logs);
                    @endphp
                    {{-- <tr>
                        <td  class="align-middle">{{$item->newdate}}</td>
                        <td   class="text-center align-middle">{{$item->day}}</td>
                        <td  class="text-center">{{$firstitem->newtime}}</td>
                        <td  class="text-center">{{$firstitem->tapstate}}</td>
                    </tr> --}}
                    @foreach($item->logs as $key=>$logitem)
                        {{-- @if($key != 0) --}}
                            <tr>
                                @if($key == 0)
                                    <td  class="align-middle" style="background-color:gainsboro">{{$item->newdate}}</td>
                                    <td   class="text-center align-middle" style="background-color:gainsboro; ">{{$item->day}}</td>
                                    <td   class="text-center align-middle" style="background-color:gainsboro; ">{{$logitem->newtime}}</td>
                                    <td   class="text-center align-middle" style="background-color:gainsboro; ">{{$logitem->tapstate}}</td>
                                @else
                                    <td  class="align-middle"  ></td>
                                    <td  class="align-middle"></td>
                                    <td class="text-center">{{$logitem->newtime}}</td>
                                     <td  class="text-center">{{$logitem->tapstate}}</td>
                                @endif

                                 {{--@if($key == 0)
                               
                                @else
                                    
                                @endif --}}
                              
                               
                                
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                @else
                    <tr>
                        <td>{{$item->newdate}}</td>
                        <td  class="text-center">{{$item->day}}</td>
                        <td colspan="2"  class="text-center">No logs found.</td>
                    </tr>
                @endif
            @endforeach
            @if(count($attendance) == 0)
            <tr>
                <td colspan="4"  class="text-center">No logs found.</td>
            </tr>
            @endif
      
  </table>

    

</body>
</html>