
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
<style>
    * { font-family: Arial, Helvetica, sans-serif; }
    .header{
        width: 100%;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        /* border: 1px solid black; */
    }
    .header td {
        font-size: 15px !important;
        /* border: 1px solid black; */
    }
    .studentstable{
        width: 100%;
        /* table-layout: fixed; */
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        border: 1px solid black;
        border-collapse: collapse;
    }
    .studentstable td, .enrollees th{
        border: 1px solid black;
        padding: 5px;
    }
    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }
    tbody td {
        font-size: 11px !important;
    }
    footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 2cm;
            }
</style>

</head>
<body>
<script type="text/php">
    if (isset($pdf)) {
        $x = 34;
        $y = 760;
        $text = "Page {PAGE_NUM} of {PAGE_COUNT} pages";
        $font = null;
        $size = 7;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color);
    }
</script>
<table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px"></td>
        <td>
            <strong>{{$schoolinfo->schoolname}}</strong>
            <br/>
            <small style="font-size: 10px !important;">{{$schoolinfo->address}}</small>
        </td>
        <td style="text-align:right;">
            <strong>Summary of Students</strong>
            <br>
            <small style="font-size: 11px !important;">S.Y {{$sy->sydesc}}</small>
            <br>
            <small style="font-size: 11px !important;"><strong>{{$selectedacadprog}}</strong></small>
            @if($selecteddate != null)
            <br>
            <small style="font-size: 11px !important;">Date enrolled: {{$selecteddatefrom}} - {{$selecteddateto}}</small>
            @endif
            
        </td>
    </tr>
</table>
<br>

@if($selectedstudentstatus != 'all' && $selectedstudentstatus != null)
<strong style="font-size: 10px;">ADMISSION STATUS: {{$selectedstudentstatus}}</strong>
<br/>
@else
<strong style="font-size: 10px;">ADMISSION STATUS: ALL</strong>
<br/>
@endif
@if($selectedmode != "")
<strong style="font-size: 10px">MODE OF LEARNING: {{$selectedmode}}</strong>
<br/>
@else
<strong style="font-size: 10px">MODE OF LEARNING: ALL</strong>
<br/>
@endif
@if($selectedgrantee != "")
<strong style="font-size: 10px">GRANTEE: {{$selectedgrantee}}</strong>
<br/>
@else
<strong style="font-size: 10px">GRANTEE: ALL</strong>
<br/>
@endif
@if($shsbystrand == 1)
    @php
        $numofstudents = 1;   
        $numofstudentsall = 1;  
    @endphp
    @if(count($strands) > 0)
        @foreach($strands as $strand)
            @if(count(collect($filteredstud)->where('strandid', $strand->id)) > 0)
                <table class="studentstable">
                    <thead style="border: 1px solid black;">
                        <tr>
                            <th colspan="4">{{$strand->strandname}}<br/>{{$selectedgender}}</th>
                        </tr>
                        <tr>
                            <th colspan="2">Name of Students</th>
                            <th>Grade Level</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredstud as $student)
                            @if($student->strandid == $strand->id)
                                <tr>
                                    <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                    <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                    <td>{{$student->levelname}}</td>
                                    <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                                </tr>
                                @php
                                    $numofstudents+=1;   
                                @endphp
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <br>
            @endif
        @endforeach
    @endif
@else
    @if($trackid == null)
        @php
            $numofstudents = 1;   
            $numofstudentsall = 1;  
        @endphp
        
        <table class="studentstable">
            <thead style="border: 1px solid black;">
                <tr>
                    <th colspan="4">{{strtoupper($selectedstudenttype)}} STUDENTS<br/>{{$selectedgender}}</th>
                </tr>
                <tr>
                    <th colspan="2">Name of Students</th>
                    <th>Grade Level</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filteredstud as $student)
                    @if(strtolower($student->gender) == 'male')
                    <tr>
                        <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                        <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                        <td>{{$student->levelname}}</td>
                        <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                    </tr>
                    @else
                    <tr>
                        <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                        <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                        <td>{{$student->levelname}}</td>
                        <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                    </tr>
                    @endif
                    @php
                        $numofstudents+=1;   
                    @endphp
                @endforeach
            </tbody>
        </table>
    @else
        @if($strandid == null)
            @if($trackid == 'all')
                    @foreach($tracks as $track)
                        @php
                            $numofstudentsall = 1;   
                        @endphp
                        <table class="studentstable">
                            <thead style="border: 1px solid black;">
                                <tr>
                                    <th colspan="4">{{$track->trackname}}<br/>{{$selectedgender}}</th>
                                </tr>
                                <tr>
                                    <th colspan="2">Name of Students</th>
                                    <th>Grade Level</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filteredstud as $student)
                                    @if($student->trackid == $track->id)
                                        @if(strtolower($student->gender) == 'male')
                                        <tr>
                                            <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                            <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                            <td>{{$student->levelname}}</td>
                                            <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                            <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                            <td>{{$student->levelname}}</td>
                                            <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                                        </tr>
                                        @endif
                                        @php
                                            $numofstudentsall+=1;   
                                        @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    @endforeach
            @else
                @php
                    $numofstudents = 1;   
                @endphp
                <table class="studentstable">
                    <thead style="border: 1px solid black;">
                        <tr>
                            <th colspan="4">{{$trackname[0]->trackname}}<br/>{{$selectedgender}}</th>
                        </tr>
                        <tr>
                            <th colspan="2">Name of Students</th>
                            <th>Grade Level</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredstud as $student)
                            @if(strtolower($student->gender) == 'male')
                            <tr>
                                <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                <td>{{$student->levelname}}</td>
                                <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                            </tr>
                            @else
                            <tr>
                                <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                <td>{{$student->levelname}}</td>
                                <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                            </tr>
                            @endif
                            @php
                                $numofstudents+=1;   
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @endif
        @else
            @if($strandid == 'all')
                @foreach($strands as $strand)
                    @php
                        $numofstudentsall = 1;   
                    @endphp
                    <table class="studentstable">
                        <thead style="border: 1px solid black;">
                            <tr>
                                <th colspan="4">{{$strand->trackname}}<br>{{$strand->strandname}}<br/>{{$selectedgender}}</th>
                            </tr>
                            <tr>
                                <th colspan="2">Name of Students</th>
                                <th>Grade Level</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($filteredstud as $student)
                                @if($student->strandid == $strand->id)
                                    @if(strtolower($student->gender) == 'male')
                                    <tr>
                                        <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                        <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                        <td>{{$student->levelname}}</td>
                                        <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                        <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                        <td>{{$student->levelname}}</td>
                                        <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                                    </tr>
                                    @endif
                                    @php
                                        $numofstudentsall+=1;   
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                @endforeach
            @else
                @php
                    $numofstudents = 1;   
                @endphp
                <table class="studentstable">
                    <thead style="border: 1px solid black;">
                        <tr>
                            <th colspan="4">{{$trackname[0]->trackname}}<br>{{$strandname[0]->strandname}}<br/>{{$selectedgender}}</th>
                        </tr>
                        <tr>
                            <th colspan="2">Name of Students</th>
                            <th>Grade Level</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($filteredstud as $student)
                            @if(strtolower($student->gender) == 'male')
                            <tr>
                                <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                <td>{{$student->levelname}}</td>
                                <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                            </tr>
                            @else
                            <tr>
                                <td style="width: 5px !important; text-align:center;">{{$numofstudents}}</td>
                                <td>{{$student->lastname.', '}}{{$student->firstname}} {{$student->middlename}}</td>
                                <td>{{$student->levelname}}</td>
                                <td>{{$student->studentstatus}} <span style="float: right">{{$student->studstatdate}}</span></td>
                            </tr>
                            @endif
                            @php
                                $numofstudents+=1;   
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    @endif
@endif
{{-- <footer>
    <table style="width: 100%;">
        <tr style="border: none !important;">
            <td style="border: none !important; width: 15%;">PREPARED BY :</td>
            <td style="border: none !important; ;border-bottom: 1px solid black; width; 40%;">
                <center>
                    
                </center>
            </td>
            <td style="border: none !important; width: 5%;" >
            </td>
            <td style="border: none !important; width: 25%;">TOTAL NO. OF STUDENTS: </td>
            <td style="border: none !important;width: 5%;" >
                {{$numofstudents}}
            </td>
            <td style="border: none !important; width: 10%;">DATE:</td>
            <td style="border: none !important; width: 10%;" >
            </td>
        </tr>
        <tr style="border: none !important;">
            <td style="border: none !important;"></td>
            <td style="border: none !important;"><center>HR</center></td>
            <td style="border: none !important;"></td>
            <td style="border: none !important;"></td>
            <td style="border: none !important;"></td>
            <td style="border: none !important;"></td>
        </tr>
    </table>
</footer> --}}

</body>
  
    
 
</html>
        