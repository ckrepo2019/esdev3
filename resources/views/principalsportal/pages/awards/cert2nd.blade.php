<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .page_break { page-break-before: always; }
        .absolute{
            position: absolute;
            transform-origin: 50 50;
            top: 100;
            left: 220;

        }

        .ordinal{
            font-variant-numeric: ordinal;
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
        
        .cell-background{
            background-color: grey;
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
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
            
        }
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 8.5in 11in; margin: .1in .1in;}
    </style>
</head>
<body>
    @php
        $x = 1;
    @endphp
    @foreach($students as $enrolleditem)
        @php
			
			if($quartertext == '1st Quarter'){
				$awards = collect($enrolleditem->grades)->where('subjdesc','GENERAL AVERAGE')->first()->q1award;
			}else if($quartertext == '2nd Quarter'){
				$awards = collect($enrolleditem->grades)->where('subjdesc','GENERAL AVERAGE')->first()->q2award;
			}else if($quartertext == '3rd Quarter'){
				$awards = collect($enrolleditem->grades)->where('subjdesc','GENERAL AVERAGE')->first()->q3award;
			}else if($quartertext == '4th Quarter'){
				$awards = collect($enrolleditem->grades)->where('subjdesc','GENERAL AVERAGE')->first()->q4award;
			}

            
            $awardtemp = '';
			if($awards != "" && $awards != null){
				$awardtemp = $awards;
			}
           
        @endphp
        @if($awardtemp != '')
            <table class="" style="position: relative; z-index: -1">
                <img src="{{base_path()}}/public/assets/images/pssc/bgimage.png" alt="" width="100%" height="516">
                <table class="absolute" width="86.5%"style="z-index: 1; left:40!important; top:{{$x == 2 ? '428' : '40'}}!important;">
                    <tr>
                        <td style="margin-top: -15px!important">
                            <table class="" width="100%" style="">
                                <tr>
                                    <td class="" style="padding: 0"><img src="{{base_path()}}/public/assets/images/pssc/bgimage2.png" alt="" width="680px"></td>
                                </tr>
                            </table>
                            <table class="m-0 p-0 text-center" width="100%" style="z-index: 2">
                                <tr>
                                    <td style="font-size: 25pt; letter-spacing: -1;">
                                    
                                        {{$enrolleditem->student}}
                                    </td>
                                </tr>       
                            </table>
                            <table style="margin-top: -30px; z-index: 1; padding-left: -10px">
                                <tr>
                                    <td>
                                        <td><img src="{{base_path()}}/public/assets/images/pssc/{{$awardtemp}}" alt="" width="650px"></td>
                                    </td>
                                </tr>
                            </table>
                            <table class="m-0 p-0 text-center" width="100%">
                                <tr>
                                    <td style="font-family: 'Times New Roman', Times, serif; font-size: 11pt">
                                        {{-- {{$student->levelname}} {{$strandInfo->strandcode}} - {{$student->sectionname}} --}}
                                    </td>
                                </tr>
                            </table>
                            <table class="text-center" style="" width="100%">
                                <tr>
                                    <td style="font-family: Arial Narrow, sans-serif; font-size: 10pt; letter-spacing: -.2px">For her excellent academic performance during the <b>{{$quartertext}}</b> of the school year  {{$sy->sydesc}}<br>at Passionist Sisters' School (Catmon), Inc.<br> Given on this 
                                        {{-- {date ex(20th day of February 2023)} --}}
                                        {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('Do')}} day of {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM YYYY')}}
                                        
                                        at Passionist Sister's School (Catmon), Inc.<br> of Bagalnga, Flores, Catmon, Cebu.</td>
                                </tr>
                            </table>
                            <table class="text-center" width="100%" style="font-size: 10pt; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; margin-top: 6px">
                                <tr>
                                    <td style="border-bottom: 1px solid black">{{$adviser}}<b>
                                    {{-- {{$adviser}} --}} 
                                    </td>
                                    <td width="33%"></td>
                                    <td style="border-bottom: 1px solid black"><b>
                                        {{-- {Registrar} --}} 
                                    </td>
                                </tr>
                                <tr>
                                    <td style=""><b>School Adviser</td>
                                    <td width="33%"></td>
                                    <td style=""><b>School Registrar</td>
                                </tr>
                                <tr>
                                    <td style=""><b></td>
                                    <td width="33%" style="border-bottom: 1px solid black">{{$principal_info[0]->name}}<b>
                                        {{-- {{$principal_info[0]->name}} --}} 
                                    </td>
                                    <td style=""><b></td>
                                </tr>
                                <tr style="">
                                    <td style=""><b></td>
                                    <td width="33%"><b>{{$principal_info[0]->title}}</td>
                                    <td style=""><b></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </table>
            @if($x == 2)
                <div class="page_break"></div>
            @endif
            @php
                if($x == 1){
                    $x = 2;    
                }else{
                    $x = 1;
                }
            @endphp
        @endif
       
         
    @endforeach
</body>
</html>