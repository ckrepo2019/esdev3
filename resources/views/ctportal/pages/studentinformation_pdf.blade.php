<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
<style>
    html{
        /* text-transform: uppercase; */
        
    font-family: Arial, Helvetica, sans-serif;
    }
.logo{
    width: 100%;
    table-layout: fixed;
}
.header{
    width: 100%;
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

.text-center {
    text-align: center !important;
}

.text-left {
    text-align: left !important;
}

.align-top {
    vertical-align: top !important;
}

.dashed-border {
    border-top: 1px dashed black !important;
    border-bottom: 1px dashed black !important;
}

.grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: 12px !important;
            font-family: "Lucida Console", "Courier New", monospace;
        }

        .grades-header td{
            font-size: 12px !important;
        }


</style>
@php
 
 $numlimit = count($students)/2;   
 if (strpos($numlimit,'.') !== false) {
     $numlimit+=0.5;
 }

 $scinfo = DB::table('schoolinfo')->first();
@endphp
</head>
<body>
   <table class="table grades " width="100%">
        <tr>
          <td style="text-align: right !important; vertical-align: top;" width="15%">
              <img src="{{base_path()}}/public/{{$scinfo->picurl}}" alt="school" width="70px">
          </td>
          <td style="width: 70%; text-align: center;" class="align-middle">
              <div style="width: 100%; font-weight: bold; font-size: 19px !important;">{{$scinfo->schoolname}}</div>
              <div style="width: 100%; font-size: 12px;">{{$scinfo->address}}</div>
              <div style="width: 100%; font-size: 12px;"></div>
          </td>
          <td width="15%">
           
          </td>
      </tr>
   </table>
   <table class="table grades" width="100%">
        <tr><td class="text-center p-0">OFFICIAL CLASS LIST</td></tr>
        <tr><td class="text-center p-0">[ {{collect($schedules)->first()->subjCode}} - {{collect($schedules)->first()->subjDesc}} ]</td></tr>
    </table>
    <table class="table grades" width="100%">
        <tr>
            <td width="15%">Instructor:</td>
            <td width="45%">
                {{$instructor}}
            </td>
            <td width="16%">School Year:</td>
            <td width="24%">{{$sydesc}}</td>
        </tr>
        <tr>
            <td >Schedule:</td>
            <td >
                {{-- @foreach($schedules[0]->schedule as $item)
                    [{{$item->day}}] {{$item->curtime}}
                    @if(count($time_list) > 0)
                        <br>
                    @endif
                @endforeach --}}
                @foreach ($schedules[0]->schedule as $item)
                    {{$item->time}} - <i>{{$item->day}}</i> <br>
                @endforeach
            </td>
            <td >Semester:</td>
            <td >{{$semester}}</td>
        </tr>
    </table>
    {{-- <div style="width: 100%; text-align: center; font-size: 12px;">
        {{DB::table('schoolinfo')->first()->schoolname}}
        <br/>
           {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}
        <br/>
        <br/>
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
            GRADING SHEET
        @else
            OFFICIAL CLASS LIST
        @endif
        <br/>
        {{$semester}} S.Y. {{$sydesc}}
    </div> --}}
    <br>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'spct')
        <table style="width: 100%; font-size: 11px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th style="width: 15%;">Subjects Code:</th>     
                <td><u>{{collect($schedules)->first()->subjCode}}</u></td>
                <th style="width: 15%;">Credit Units:</th>
                <td>{{collect($schedules)->first()->lecunits + collect($schedules)->first()->labunits}}</td>
            </tr>
            <tr> 
                <th>Descriptive Title:</th>     
                <td><u>{{collect($schedules)->first()->subjDesc}}</u></td>
                <th>Time:</th>
                <td><u>{{date('h:i A',strtotime(collect($schedules)->first()->stime))}} - {{date('h:i A',strtotime(collect($schedules)->first()->etime))}}</u></td>
            </tr>
        </table>
        <br>
        
        <table  style="width:100%; font-size: 11px; border-collapse: collapse;" cellpadding="0" cellspacing="0" >
            <thead style="text-align: left !important;">
                <tr>
                    <th  width="3%" class="text-center">NO</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th style="width: 35%;">Name</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th width="10%">Program</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th  width="10%">Year Level</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th width="10%">Midterm</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th  width="10%">Final</th>
                    <th style="width: 2%;">&nbsp;</th>
                    <th  width="10%">Sem Grade</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $num = 1;
                @endphp
                    @foreach ($students as $key => $student)
                            <tr>
                                <td class="text-center">{{$num}}</td>
                                <td></td>
                                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                <td></td>
                                <td>{{$student->courseabrv}}</td>
                                <td></td>
                                <td >{{str_replace("COLLEGE","",$student->levelname)}}</td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                            </tr>
                            @php
                                $num += 1;
                            @endphp
                    @endforeach
        
            </tbody>
        </table>
        <br/>
        <br/>
        <br/>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="width: 10%;"></td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="width: 10%;"></td>
                <td style="border-bottom: 1px solid black;"></td>
            </tr>
            <tr>
                <td style="text-align: center;">Instructor</td>
                <td></td>
                <td style="text-align: center;">Dean</td>
                <td></td>
                <td style="text-align: center;">Registrar</td>
            </tr>
            <tr>
                <td>Date Signed:</td>
                <td></td>
                <td>Date Signed:</td>
                <td></td>
                <td>Date Signed:</td>
            </tr>
        </table>
    @else
        
        {{-- <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th width="15%">Subject:</th>     
                <td width="85%">{{collect($schedules)->first()->subjCode}} - <i>{{collect($schedules)->first()->subjDesc}}</i></td>
            </tr>
        </table>
        <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th width="15%">Section / Course:</th>     
                <td width="85%">{{collect($schedules)->first()->sectionDesc}} - <i>{{collect($schedules)->first()->courseabrv}}</i></td>
            </tr>
        </table> --}}
        {{-- <table style="width: 100%; font-size: 12px; border-collapse: collapse; text-align: left !important;">
            <tr> 
                <th width="15%" class="align-top">Schedule:</th>     
                <td width="85%">
                    @foreach ($schedules[0]->schedule as $item)
                        {{$item->time}} - <i>{{$item->day}}</i> <br>
                    @endforeach
                </td>
            </tr>
        </table> --}}
        @php
            $num = 1;
            $num2 = $numlimit+1;
            $studarray = $students;
        @endphp
        {{-- @if(count($students) <= 40) --}}
            <table class="table grades table-bordered" >
                {{-- <thead  class="grades-header"> --}}
                    <tr  class="grades-header">
                        <td width="5%" class="text-left"><b>NO</b></td>
                        <td width="65%" class="text-left"><b>Student's Name</b></td>
                        <td  width="15%" class="text-left"><b>Year Level</b></td>
                        <td width="15%" class="dashed-border text-left"><b>Course</b></td>
                    </tr>
                {{-- </thead> --}}
                {{-- <tbody> --}}
                    @php
                        $num = 1;
                    @endphp
                    @foreach ($students as $key => $student)
                            <tr>
                                <td>{{$num}}</td>
                                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                <td>{{str_replace("COLLEGE","",$student->levelname)}}</td>
                                <td>{{$student->courseabrv}}</td>
                            </tr>
                            @php
                                $num += 1;
                            @endphp
                    @endforeach
                {{-- </tbody> --}}
               
            </table>
        {{-- @else
            <table style="width: 100%; border-collapse: collapse; font-size: 11px; page-break-inside: auto;">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="dashed-border text-left">NO</th>
                        <th style="width: 30%; " class="dashed-border text-left">STUDENT'S NAME</th>
                        <th style="width: 15%;" class="dashed-border text-left">COURSE</th>
                        <th style="width: 5%;" class="dashed-border text-left">NO</th>
                        <th style="width: 30%;" class="dashed-border text-left">STUDENT'S NAME</th>
                        <th style="width: 15%;" class="dashed-border text-left">COURSE</th>
                    </tr>
                </thead>
                @foreach ($students as $student)
                    @if($num<=$numlimit)
                        <tr>
                            <td>{{$num}}</td>
                            <td>{{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} @if($student->middlename !=null) {{$student->middlename[0]}}. @endif {{ucwords(strtolower($student->suffix))}}</td>
                            <td>{{$student->courseabrv}}</td>
                            @php
                                $student->done = 1;
                                $num += 1;
                            @endphp
                            
                            @if($num2<=count($students))
                                <td>{{$num2}}</td>
                                <td>
                                    {{ucwords(strtolower($students[$num2-1]->lastname))}}, {{ucwords(strtolower($students[$num2-1]->firstname))}} @if($students[$num2-1]->middlename !=null) {{$students[$num2-1]->middlename[0]}}. @endif {{ucwords(strtolower($students[$num2-1]->suffix))}}
                                </td>
                                <td>
                                    {{$students[$num2-1]->courseabrv}}
                                </td>
                                @php
                                    $students[$num2-1]->done = 1;
                                    $num2+= 1;
                            @endphp
                            @endif
                        </tr>
                    @endif
                @endforeach
            </table>
        @endif --}}
    @endif

</body>
</html>