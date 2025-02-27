
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
    <style>
        *   {
                font-family: Arial, Helvetica, sans-serif;
            }

        .header{
            width: 100%;
            table-layout: fixed;
            font-family: Arial, Helvetica, sans-serif;
        }

        .header td {
            font-size: 15px !important;
        }

        .studentstable{
            width: 100%;
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
@php
 
    $schoolinfo = Db::table('schoolinfo')
        ->select(
            'schoolinfo.schoolid',
            'schoolinfo.schoolname',
            'schoolinfo.authorized',
            'refcitymun.citymunDesc as city',
            'schoolinfo.district',
            'schoolinfo.address',
            'schoolinfo.picurl',
            'refregion.regDesc as region'
        )
        ->leftJoin('refregion','schoolinfo.region','=','refregion.regCode')
        ->leftJoin('refcitymun','schoolinfo.division','=','refcitymun.citymunCode')
        ->first();
    
    if($requestdata->trackid != null){

        if($requestdata->trackid == 'all'){

            $tracks = Db::table('sh_track')
                ->where('deleted', '0')
                ->get();

            $trackname = "";

        }else{

            $tracks = "";

            $trackname = Db::table('sh_track')
            ->where('id', $requestdata->trackid)
            ->get();
        }

    }else{

        $trackname = "";

        $tracks = "";

    }

    if($requestdata->strandid != null){

        if($requestdata->strandid == 'all'){

            $strands = Db::table('sh_strand')
                ->select(
                    'sh_strand.id',
                    'sh_strand.strandname',
                    'sh_track.id as trackid',
                    'sh_track.trackname'
                )
                ->join('sh_track','sh_strand.trackid','=','sh_track.id')
                ->where('sh_strand.active', '1')
                ->where('sh_strand.deleted', '0')
                ->where('sh_track.deleted', '0')
                ->get();

            $strandname = "";

        }else{

            $strands = "";

            $strandname = Db::table('sh_strand')
                ->where('id', $requestdata->strandid)
                ->get();
            

        }

    }else{


        $strands = DB::table('sh_strand')
            ->where('deleted','0')
            ->get();

        $strandname = "";

    }

    if($requestdata->selectedstudenttype == 'all'){
        
        $filteredstud = collect($data)->sortBy('sortid')->values()->all();

    }else{

        $filteredstud = collect($data)->sortBy('lastname')->values()->all();

    }

    $filteredstud = collect($filteredstud)->sortBy('lastname')->values()->all();

    $sy = DB::table('sy')
        ->where('id',$requestdata->selectedschoolyear)
        ->first();

    if($requestdata->selecteddate != null){
        $selecteddatefrom   = date('M d,Y',strtotime($selecteddate[0]));
        $selecteddateto     = date('M d,Y',strtotime($selecteddate[1]));
    }

    $shsbystrand = 0;

    if($requestdata->selectedgradelevel >= 14){
        if($requestdata->trackid == null && $requestdata->strandid == null){
            $shsbystrand = 1;
        }
    }
    
    if($requestdata->selectedgender == 'all'){
        $selectedgender = "";
    }
    elseif($requestdata->selectedgender == 'male'){
        $selectedgender = "(MALE)";
    }
    elseif($requestdata->selectedgender == 'female'){
        $selectedgender = "(FEMALE)";
    }
    if($requestdata->selectedstudentstatus == 'all'){
        $selectedstudentstatus = "";
    }else{
        $selectedstudentstatus = Db::table('studentstatus')
            ->where('id', $requestdata->selectedstudentstatus)
            ->first()
            ->description;
    }
    if($requestdata->selectedacadprog == 'all'){
        $selectedacadprog = "";
    }else{
        $selectedacadprog = Db::table('academicprogram')
            ->where('id', $requestdata->selectedacadprog)
            ->first()
            ->progname;
    }   
@endphp
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
            @if($requestdata->selecteddate != null)
            <br>
            <small style="font-size: 11px !important;">Date enrolled: {{$selecteddatefrom}} - {{$selecteddateto}}</small>
            @endif
            
        </td>
    </tr>
</table>
<br>
{{-- @if($selectedgender == all) --}}
{{-- {{$shsbystrand}} --}}
@if($selectedstudentstatus != 'all')
    <strong>{{$selectedstudentstatus}}</strong>
@endif
@php
    $numofstudents = 1;   
    $numofstudentsall = 1;  
@endphp
@if($shsbystrand == 1)
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
    @if($requestdata->trackid == null)
        <table class="studentstable">
            <thead style="border: 1px solid black;">
                <tr>
                    <th colspan="4">{{strtoupper($requestdata->selectedstudenttype)}} STUDENTS<br/>{{$selectedgender}}</th>
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
        @if($requestdata->strandid == null)
            @if($requestdata->trackid == 'all')
                    @foreach($tracks as $track)
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
            @if($requestdata->strandid == 'all')
                @foreach($strands as $strand)
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

</body>
  
    
 
</html>
        