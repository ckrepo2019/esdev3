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
             <tr><td width="100%" class="p-0 text-right">Page: 1</td></tr>
         </table>
    <table class="table grades" width="100%">
        <tr><td width="100%" class="text-center p-0">Davao Central College</td></tr>
        <tr><td class="text-center p-0">Office of the College Registrar</td></tr
        <tr><td class="text-center p-0">OFFICIAL GRADING SHEET</td></tr>
        <tr><td class="text-center p-0">Semester @if($semid == 1) FIRST @elseif($semid == 2) SECOND @elseif($semid == 3) SUMMBER @endif, School Year {{$syinfo->sydesc}}</td></tr>
    </table>
    
    
    <table class="table grades" width="100%">
        <tr><td width="18%">Teacher's ID :</td><td width="32%"><b>{{$schedinfo->tid}}</b></td><td width="50%">{{$schedinfo->teacher}}</td></tr>
         <tr><td width="18%">Subject Code : <td width="32%"><b>{{$schedinfo->code}}</b></td></td><td width="50%">{{$schedinfo->subjCode}} - <b>{{$schedinfo->subjDesc}}</b></td></tr>
    </table>
    @if(count($students) < 35)
        <table class="table grades " width="100%">
                <tr style="border:dashed  1px black;">
                    <td width="5%" style="border-top:dashed  1px black; border-bottom:dashed  1px black;  border-left:dashed  1px black;"></td>
                    <td width="40%" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">STUDENT'S NAME</td>
                    <td  width="25%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">COURSE</td>
                    <td  width="10%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">YR</td>
                    <td  width="20%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">ID NUM</td
                    <td  width="15%" class="text-center" style="border:dashed  1px black;">RATINGS FG</td>
                    <td  width="15%"  class="text-center" style="border:dashed  1px black;">REMARKS</td>
                </tr>
            @php
                $count = 0;
            @endphp
            
            @foreach($students as $item)
            
                @php
                
                    $temp_grades = collect($grades)->where('studid',$item->studid)->first();
                    $temp_grade = null;
                    $temp_remark = null;
                    if(isset($temp_grades->grade)){
                        $temp_grade = $temp_grades->grade;
                        $temp_remark = $temp_grades->remarks;
                    }
                    
                    $count += 1;
                @endphp
            
               <tr>
                    <td class="text-center">{{$count}}</td>
                    <td >{{$item->student}}</td>
                    <td >{{$item->courseabrv}}</td>
                    <td class="text-center">
                       {{ $item->levelid - 15 }}
                    </td>
                    <td class="text-center" >{{$item->sid}}</td
                    <td class="text-center" style="border:dashed  1px black;">{{$temp_grade}}</td>
                    <td class="text-center" style="border:dashed  1px black;">{{$temp_remark}}</td>
                </tr>
               
            @endforeach
            
        </table>
        
    @else
        <table class="table grades " width="100%">
                <tr style="border:dashed  1px black;">
                    <td width="5%" style="border-top:dashed  1px black; border-bottom:dashed  1px black;  border-left:dashed  1px black;"></td>
                    <td width="40%" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">STUDENT'S NAME</td>
                    <td  width="25%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">COURSE</td>
                    <td  width="10%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">YR</td>
                    <td  width="20%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">ID NUM</td
                    <td  width="15%" class="text-center" style="border:dashed  1px black;">RATINGS FG</td>
                    <td  width="15%"  class="text-center" style="border:dashed  1px black;">REMARKS</td>
                </tr>
            @php
                $count = 0;
            @endphp
            
            @foreach(collect($students)->take(35)->values() as $item)
            
                @php
                
                    $temp_grades = collect($grades)->where('studid',$item->studid)->first();
                    $temp_grade = null;
                    $temp_remark = null;
                    if(isset($temp_grades->grade)){
                        $temp_grade = $temp_grades->grade;
                        $temp_remark = $temp_grades->remarks;
                    }
                    
                    $count += 1;
                @endphp
            
               <tr>
                    <td class="text-center">{{$count}}</td>
                    <td >{{$item->student}}</td>
                    <td >{{$item->courseabrv}}</td>
                    <td class="text-center">
                       {{ $item->levelid - 15 }}
                    </td>
                    <td class="text-center" >{{$item->sid}}</td
                    <td class="text-center" style="border:dashed  1px black;">{{$temp_grade}}</td>
                    <td class="text-center" style="border:dashed  1px black;">{{$temp_remark}}</td>
                </tr>
              
            @endforeach
            
        </table>    
         <div class="page_break"></div>
         <table class="table grades" width="100%">
            <tr><td width="100%" class="p-0 text-right">Page: 2</td></tr>
         </table>
        <table class="table grades" width="100%">
            <tr><td width="100%" class="text-center p-0">Davao Central College</td></tr>
            <tr><td class="text-center p-0">Office of the College Registrar</td></tr
            <tr><td class="text-center p-0">OFFICIAL GRADING SHEET</td></tr>
            <tr><td class="text-center p-0">Semester @if($semid == 1) FIRST @elseif($semid == 2) SECOND @elseif($semid == 3) SUMMBER @endif, School Year {{$syinfo->sydesc}}</td></tr>
        </table>
        
        
        <table class="table grades" width="100%">
            <tr><td width="18%">Teacher's ID :</td><td width="32%"><b>{{$schedinfo->tid}}</b></td><td width="50%">{{$schedinfo->teacher}}</td></tr>
             <tr><td width="18%">Subject Code : <td width="32%"><b>{{$schedinfo->code}}</b></td></td><td width="50%"></td></tr>
        </table>
        <table class="table grades " width="100%">
                <tr style="border:dashed  1px black;">
                    <td width="5%" style="border-top:dashed  1px black; border-bottom:dashed  1px black;  border-left:dashed  1px black;"></td>
                    <td width="40%" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">STUDENT'S NAME</td>
                    <td  width="25%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">COURSE</td>
                    <td  width="10%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">YR</td>
                    <td  width="20%" class="text-center" style="border-top:dashed  1px black; border-bottom:dashed  1px black;">ID NUM</td
                    <td  width="15%" class="text-center" style="border:dashed  1px black;">RATINGS FG</td>
                    <td  width="15%"  class="text-center" style="border:dashed  1px black;">REMARKS</td>
                </tr>
           
            @foreach(collect($students)->skip(35)->values() as $item)
            
                @php
                
                    $temp_grades = collect($grades)->where('studid',$item->studid)->first();
                    $temp_grade = null;
                    $temp_remark = null;
                    if(isset($temp_grades->grade)){
                        $temp_grade = $temp_grades->grade;
                        $temp_remark = $temp_grades->remarks;
                    }
                    
                    $count += 1;
                @endphp
            
               <tr>
                    <td class="text-center">{{$count}}</td>
                    <td >{{$item->student}}</td>
                    <td >{{$item->courseabrv}}</td>
                    <td class="text-center">
                       {{ $item->levelid - 15 }}
                    </td>
                    <td class="text-center" >{{$item->sid}}</td
                    <td class="text-center" style="border:dashed  1px black;">{{$temp_grade}}</td>
                    <td class="text-center" style="border:dashed  1px black;">{{$temp_remark}}</td>
                </tr>
               
            @endforeach
            
        </table>    
            
    @endif
    <br>
     <table class="table grades " width="100%">
            <tr>
                <td width="47%">
                    <table class="table grades " width="100%">
                        <tr><td>MIDTERM SUBMISSION:</td></tr>
                    </table>
                    <table class="table grades " width="100%">
                        <tr>
                            <td width="35%">Date Submitted</td>
                            <td width="5%">:</td>
                            <td width="60%" style="boder-bottom:solid black"></td>
                        </tr>
                         <tr>
                            <td >Inst/Prof</td>
                             <td>:</td>
                            <td style="boder-bottom:solid black">{{$schedinfo->teacher}}</td>
                        </tr>
                         <tr>
                            <td >Dept. Chairman</td>
                              <td>:</td>
                            <td  style="boder-bottom:solid black"></td>
                        </tr> <tr>
                            <td >College Dean</td>
                              <td>:</td>
                            <td  style="boder-bottom:solid black"></td>
                        </tr>
                    </table>
                </td>
                <td width="6%"></td>
                <td width="47%">
                   <table class="table grades " width="100%">
                        <tr><td>MIDTERM SUBMISSION:</td></tr>
                    </table>
                    <table class="table grades " width="100%">
                        <tr>
                            <td width="35%">Date Submitted</td>
                            <td width="5%">:</td>
                            <td width="60%" style="boder-bottom:solid black"></td>
                        </tr>
                         <tr>
                            <td >Inst/Prof</td>
                             <td>:</td>
                            <td style="boder-bottom:solid black">{{$schedinfo->teacher}}</td>
                        </tr>
                         <tr>
                            <td >Dept. Chairman</td>
                              <td>:</td>
                            <td  style="boder-bottom:solid black"></td>
                        </tr> <tr>
                            <td >College Dean</td>
                              <td>:</td>
                            <td  style="boder-bottom:solid black"></td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
</div>

</body>
</html>