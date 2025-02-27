<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$studinfo->student}}</title>
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
            font-size: 10px !important;
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
        @page {  
            margin:30px 30px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }

        /* @page { size: 5.5in 8.5in; margin: 10px 40px;  } */
        
    </style>
</head>
<body>  

    <table width="100%">
        <tr>
                <td style="text-align: right !important; vertical-align: top;" width="25%">
                    <img src="{{base_path()}}/public/{{$schoolInfo->picurl}}" alt="school" width="60px">
                </td>
                <td style="width: 50%; text-align: center;">
                    <div style="width: 100%; font-weight: bold; font-size: 15px;">{{$schoolInfo->schoolname}}</div>
                    <div style="width: 100%; font-size: 12px;">{{$schoolInfo->address}}</div>
                </td>
                <td width="25%"></td>
            </tr>
    </table>

    <table class="table-sm table table-bordered" style="font-size:.7rem !important">
        <tr>
              <td colspan="2"><b>Student Name:</b> {{ $studinfo->student}}</td>
        </tr>
        <tr>
              <td width="50%"><b>Course:</b> <span id="eval_course_label">{{$grades[0]->course->courseDesc}}</span></td>
              <td width="50%"><b>Curriculum:</b> <span id="eval_curriculum_label">{{$grades[0]->curriculum->curriculumname}}</span></td>
        </tr>
    </table>   

    @foreach($grades[0]->yearLevel as $yearitem)
        @foreach($grades[0]->semester as $semitem)
            <table class="table-sm table table-bordered" style="font-size:.7rem !important">
                <tr>
                    <td colspan="7"><b>{{$yearitem->levelname}} - {{$semitem->semester}}</b></td>
                </tr>
                <tr>
                    <th width="10%">Code</th><th width="40%">Description</th>
                    <th wdith="8%"  class="text-center">Lec.</th>
                    <th  class="text-center" wdith="8%">Lab.</th>
                    <th wdith="8%" class="text-center">Total</th>
                    <th class="text-center" width="13%">Grades</th>
                    <th class="text-center" width="13%">Remarks</th>
                </tr>
                
                @foreach(collect($grades[0]->evaluation)->where('yearId',$yearitem->id)->where('semesterID',$semitem->id) as $evalitem)
                    <tr>
                        <td >{{$evalitem->subjCode}}</td>
                        <td width="40%">{{$evalitem->subjDesc}}</td>
                        <td class="text-center">{{$evalitem->lecunits}}</td>
                        <td  class="text-center" >{{$evalitem->labunits}}</td>
                        <td class="text-center">{{$evalitem->labunits + $evalitem->lecunits}}</td>
                        <td class="text-center" >{{$evalitem->fg}}</td>
                        <td class="text-center">{{$evalitem->fgremarks}}</td>
                    </tr>
                @endforeach
            </table> 
        @endforeach  
    @endforeach


</body>
</html>