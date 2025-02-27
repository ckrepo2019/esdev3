<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tap Monitoring (Late)</title>
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
        @page { size: 8.5in 13in; margin: .25in;  }
        .page_break { page-break-before: always; }
        
    </style>
</head>
<body>

    @foreach($alldates as $dateitem)
        <table class="table table-s " style="font-size:13px;">
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
        <table class="table table-sm text-right mb-0" >
            <tr>
                <td class="p-0">
                    <b>Date : {{\Carbon\Carbon::create($dateitem->tdate)->isoFormat('MMMM DD, YYYY')}}</b>
                </td>
            </tr>
        </table>
        <hr class="p-0">
       
        <table class="table table-sm  mb-0" >
            <tr>
                <td>
                   Morning Late 
                </td>
                <td class="text-right">
                    Time: {{\Carbon\Carbon::create($amstarttime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($amendtime)->isoFormat('hh:mm A')}}
                </td>
            </tr>
        </table>
        <table class="table-hover table table-striped table-sm table-bordered " width="100%" >
            <tr>
                    <td width="20%"><b>SID</b></td>
                    <td width="60%"><b>Student</b></td>
                    <td width="20%"><b>Time</b></td>
                </tr>
            @foreach($dateitem->morninglate as $item)
                <tr>
                    <td >{{$item->sid}}</td>
                    <td >{{$item->studentname}}</td>
                    <td >{{\Carbon\Carbon::create($item->ttime)->isoFormat('hh:mm A')}}</td>
                </tr>
            @endforeach
            @if(count($dateitem->morninglate) == 0)
                <tr><td colspan="3" class="text-center">No student found.</td></tr>
            @endif
        </table>
        
        <table class="table table-sm  mb-0" >
            <tr>
                <td>
                    Afternoon Late
                </td>
                <td class="text-right">
                    Time: {{\Carbon\Carbon::create($pmstarttime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($pmendtime)->isoFormat('hh:mm A')}}
                </td>
            </tr>
        </table>
        <table class="table-hover table table-striped table-sm table-bordered " width="100%" >
            <tr>
                <td width="20%"><b>SID</b></td>
                <td width="60%"><b>Student</b></td>
                <td width="20%"><b>Time</b></td>
            </tr>
            @foreach($dateitem->afternoonlate as $item)
                <tr>
                    <td >{{$item->sid}}</td>
                    <td >{{$item->studentname}}</td>
                    <td >{{\Carbon\Carbon::create($item->ttime)->isoFormat('hh:mm A')}}</td>
                </tr>
            @endforeach
            @if(count($dateitem->afternoonlate) == 0)
                <tr><td colspan="3" class="text-center">No student found.</td></tr>
            @endif
        </table>
        <div class="page_break"></div>
    @endforeach
</body>
</html>