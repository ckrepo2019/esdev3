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
    
    <table class="table grades mb-0" width="100%">
          <tr>
			<td style="text-align: right !important; vertical-align: top;" width="25%">
				<img src="{{base_path()}}/public/{{$scinfo->picurl}}" alt="school" width="70px">
			</td>
			<td style="width: 50%; text-align: center;" class="align-middle">
				<div style="width: 100%; font-weight: bold; font-size: 15px !important;">{{$scinfo->schoolname}}</div>
				<div style="width: 100%; font-size: 12px;">{{$scinfo->address}}</div>
			</td>
			<td width="25%">
			 
			</td>
		</tr>
     </table>
      <table class="table grades " width="100%">
          <tr>
			<td style="text-align: right !important; vertical-align: top;" width="25%">
			
			</td>
			<td style="width: 50%; text-align: center;" class="align-middle">
				<div style="width: 100%; font-weight: bold; font-size: 15px !important;">RECORDS AND ADMISSIONS OFFICE</div>
				<div style="width: 100%; font-size: 12px;">Grading Sheet</div>
				<div style="width: 100%; font-size: 12px;">AY {{$syinfo->sydesc}} {{$seminfo->semester}}</div>
			</td>
			<td width="25%">
			 
			</td>
		</tr>
     </table>
    {{-- <table class="table grades" width="100%">
        <tr><td class="text-center p-0">OFFICIAL GRADING SHEET</td></tr>
        <tr><td class="text-center p-0">[ {{$schedinfo->subjcode}} - {{$schedinfo->subjdesc}} ]</td></tr>
    </table> --}}
    <table class="table grades" width="100%">
        <tr>
            <td width="16%">ClassID:</td>
            <td width="39%"></td>
            <td width="15%">Units:</td>
            <td width="30%">{{$schedinfo->lecunits}}</td>
        </tr>
         <tr>
            <td >Subject Code:</td>
            <td >{{$schedinfo->subjcode}}</td>
            <td >Section:</td>
            <td >{{$schedinfo->sectionDesc}}</td>
        </tr>
         <tr>
            <td >Subject Title:</td>
            <td >{{$schedinfo->subjdesc}}</td>
            <td >Offere Under:</td>
            <td >{{$schedinfo->courseDesc}}</td>
        </tr>
         <tr>
            <td >Instructor:</td>
            <td >{{$instructor}}</td>
            <td ></td>
            <td ></td>
        </tr>
         <tr>
            <td colspan="2">Room: {{$room}} Schedule: @foreach($time_list as $item)
                                       [{{$item->day}}] {{$item->curtime}}
                                        @if(count($time_list) > 0)
                                            <br>
                                        @endif
                                   @endforeach</td>
           
            <td ></td>
            <td ></td>
        </tr>
        <!--<tr>-->
        <!--    <td >Schedule:</td>-->
        <!--    <td >-->
        <!--        @foreach($time_list as $item)-->
        <!--            [{{$item->day}}] {{$item->curtime}}-->
        <!--            @if(count($time_list) > 0)-->
        <!--                <br>-->
        <!--            @endif-->
        <!--        @endforeach-->
        <!--    </td>-->
        <!--    <td >Semester:</td>-->
        <!--    <td >{{$seminfo->semester}}</td>-->
        <!--</tr>-->
    </table>
    <table class="table grades table-bordered mb-0" width="100%">
        <tr>
            <td width="28%"><b>NAME</b></td>
            <td width="27%"><b>COURSE</b></td>
            <td width="9%" class="text-center"><b>MIDTERM</b></td>
             <td width="9%" class="text-center"><b>FINALTERM</b></td>
            <td width="13%" class="text-center"><b>FINAL GRADE</b></td>
            <td width="9%" class="text-center"><b>REMARKS</b></td>
        </tr>
        @php
            $count = 1;
        @endphp
        @foreach(collect($students)->sortBy('student')->values() as $item)
        
            @php
                $temp_grades = collect($grades)->where('studid',$item->studid)->first();
                $mid = null;
                $finalterm = null;
                $final = null;
                $remarks = null;
                if(isset($temp_grades)){
                    $mid = $temp_grades->midtermgrade;
                    $finalterm = $temp_grades->prefigrade;
                    $final = $temp_grades->finalgrade;
                    if($final != null){
                        if($final < 75){
                            $remarks = 'FAILED';    
                        }else{
                            $remarks = 'PASSED';
                        }
                    }
                }
            @endphp
            <tr>
                <td >{{$count}}. {{$item->student}}</td>
                <td >{{$item->courseabrv}} - {{$item->levelid - 16}}</td>
                <td  class="text-center align-middle">{{$mid}}</td>
                <td class="text-center align-middle">{{$finalterm}}</td>
                <td class="text-center align-middle">{{$final}}</td>
                <td class="text-center">{{$remarks}}</td>
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
   
    
     <table class="table grades" width="100%">
            
            <tr>
                <td width="5%"></td>
                <td width="40%" class="text-center "><b>Submitted By:</b></td>
                <td width="10%"></td>
                <td width="40%" class="text-center"><b>Certified By:</b></td>
                <td width="5%"></td>
            </tr>
            
        </table>  
    <br>
        <table class="table grades" width="100%">
            
            <tr>
                <td width="5%"></td>
                <td width="40%" class="text-center"><b>{{$instructor}}</b></td>
                <td width="10%"></td>
                <td width="40%" class="text-center "><b>CHRISTINE J. CASILAGAN</b></td>
                <td width="5%"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-center">Instructor</td>
                <td></td>
                <td class="text-center">Registrar</td>
                <td></td>
            </tr>
            
        </table> 
        <br>
        <table class="table grades" width="100%">
            
            <tr>
                <td width="5%"></td>
                <td width="20%" class="text-right">Date Submittd:</td>
                 <td width="20%" class="text-right border-bottom"></td>
                <td width="10%"></td>
                <td width="40%" ></td>
                <td width="5%"></td>
            </tr>
          
        </table> 
</div>

</body>
</html>