<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Summary</title>
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


        .page_break { page-break-before: always; }

        @page { size:  8.5in 11in; margin: .25in;  }
        
    </style>
</head>
<body>
    
    <table class="table grades " width="100%">
          <tr>
			<td style="text-align: right !important; vertical-align: top;" width="25%">
				<img src="{{base_path()}}/public/{{$scinfo->picurl}}" alt="school" width="70px">
			</td>
			<td style="width: 50%; text-align: center;" class="align-middle">
				<div style="width: 100%; font-weight: bold; font-size: 19px !important;">{{$scinfo->schoolname}}</div>
				<div style="width: 100%; font-size: 12px;">{{$scinfo->address}}</div>
				<div style="width: 100%; font-size: 12px;"></div>
			</td>
			<td width="25%">
			 
			</td>
		</tr>
     </table>
    <table class="table grades" width="100%">
        <tr><td class="text-center p-0">OFFICIAL GRADING SHEET</td></tr>
        <tr><td class="text-center p-0">[ {{$schedinfo->subjcode}} - {{$schedinfo->subjdesc}} ]</td></tr>
    </table>
    <table class="table grades" width="100%">
        <tr>
            <td width="15%">Instructor:</td>
            <td width="35%">{{$instructor}}</td>
            <td width="15%">School Year:</td>
            <td width="35%">{{$syinfo->sydesc}}</td>
        </tr>
        <tr>
            <td >Schedule:</td>
            <td >
                @foreach($time_list as $item)
                    [{{$item->day}}] {{$item->curtime}}
                    @if(count($time_list) > 0)
                        <br>
                    @endif
                @endforeach
            </td>
            <td >Semester:</td>
            <td >{{$seminfo->semester}}</td>
        </tr>
    </table>
    <table class="table grades table-bordered mb-0" width="100%">
        <tr>
            <td width="5%"><b>#</b></td>
            <td width="30%"><b>LAST NAME</b></td>
            <td width="30%"><b>FIRST NAME</b></td>
            <td width="25%"><b>COURSE</b></td>
            <td width="10%" class="text-center"><b>MID</b></td>
            <td width="10%" class="text-center"><b>FINAL</b></td>
            <td width="10%" class="text-center"><b>REMARKS</b></td>
        </tr>
        @php
            $count = 1;
        @endphp
        @foreach(collect($students)->sortBy('student')->values() as $item)
        
            @php
                $temp_grades = collect($grades)->where('studid',$item->studid)->first();
                $mid = null;
                $final = null;
                $remarks = null;
                if(isset($temp_grades)){
                    $mid = $temp_grades->midtermgrade;
                    $final = $temp_grades->finalgrade;
                    if($final < 75){
                        $remarks = 'FAILED';    
                    }else{
                        $remarks = 'PASSED';
                    }
                }
            @endphp
            <tr>
                <td width="5%">{{$count}}</td>
                <td width="30%">{{$item->lastname}}</td>
                <td width="30%">{{$item->firstname}}</td>
                <td width="25%">{{$item->courseabrv}} - {{$item->levelid - 16}}</td>
                <td width="10%" class="text-center">{{$mid}}</td>
                <td width="10%" class="text-center">{{$final}}</td>
                <td width="10%" class="text-center">{{$remarks}}</td>
            </tr>
            @php
                $count += 1;
            @endphp
        @endforeach
        
    </table>
    <table class="table grades" width="100%">
        <tr>
            <td width="100%" class="text-right">  <i style="font-size:.5rem !important">Date Generated: {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh::mm a')}}</i></td>
        </tr>
    </table>
   
    <br>
    <br>
    <br>
        <table class="table grades" width="100%">
            <tr>
                <td width="5%"></td>
                <td width="40%" class="text-center border-bottom">{{$instructor}}</td>
                <td width="10%"></td>
                <td width="40%" class="text-center border-bottom"></td>
                <td width="5%"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-center"><b>Instructor</b></td>
                <td></td>
                <td class="text-center"><b>Dean</b></td>
                <td></td>
            </tr>
        </table>  
</div>

</body>
</html>