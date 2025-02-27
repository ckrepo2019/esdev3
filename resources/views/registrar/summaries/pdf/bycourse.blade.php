

<style>
    * {
        
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

@if($list < 2)
<style>
    @page{
        size: 8.5in 13in;
    }
</style>
<table style="width: 100%; text-align: center; font-size: 11px;">
                            <thead>
                                <tr>
                                    <th style="font-weight: bold;">{{$schoolinfo->schoolname}}</th>
                                </tr>
                                <tr>
                                    <th>{{$schoolinfo->address}}</th>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold;">OFFICIAL ENROLMENT SUMMARY</th>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold;">S.Y {{$sy->sydesc}}</th>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold;">{{$descacad}}</th>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                        </table>
                            <table style="height: 20px; font-size: 10px; width: 100%;">
                                <tr>
                                    <td>SCHOOL YEAR</td>
                                    <td>: {{$sy->sydesc}}</td>
                                    <td>COLLEGE/TRACK</td>
                                    <td>: {{$trackname}}</td>
                                    <td>GENDER</td>
                                    <td>: {{$selectedgender}}</td>
                                </tr>
                                <tr>
                                    <td>DEPARTMENT</td>
                                    <td>: {{$selectedacadprog}}</td>
                                    <td>COURSE/STRAND</td>
                                    <td>: {{$strandname}}</td>
                                    <td>GRANTEE</td>
                                    <td>: {{$selectedgrantee}}</td>
                                </tr>
                                <tr>
                                    <td>GRADE LEVEL</td>
                                    <td>: {{$selectedgradelevel}}</td>
                                    <td>ADMISSION STATUS</td>
                                    <td>: {{$selectedstudentstatus}}</td>
                                    <td>STUDENT TYPE</td>
                                    <td>: {{$selectedstudenttype}}</td>
                                </tr>
                                <tr>
                                    <td>SECTION</td>
                                    <td>: {{$selectedsection}}</td>
                                    <td>MOL</td>
                                    <td>: {{$selectedmode}}</td>
                                    <td>ENROLLMENT PERIOD</td>
                                    <td>: {{$selecteddate}}</td>
                                </tr>
                            </table>
                            <table style="font-size: 11px;margin-top: 5px;">
                                <tr>
                                    <td></td>
                                </tr>
                            </table>
@endif

@php
$numofstudents = 1;   
$numofstudentsall = 1;  
$html = '';
$malecount = 1;
$femalecount = 1;

$students = collect($filteredstudents)->where('acadprogid','6')->groupBy('course')->all();
@endphp
@if($list == 0)
    <table border="1" cellpadding="2" style="font-size: 10px; width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="" width="5%">#</th>                    
                <th style="" width="40%" >Course</th>
                <th style="">Male</th>
                <th style="">Female</th>
                <th style="">Total</th>
            </tr>
        </thead>
        <tbody>
            @if(count($records[0]->courses) > 0)
                @foreach($records[0]->courses as $coursekey => $course)
                
                    <tr>
                        <td style="text-align: center" width="5%">{{$coursekey+1}}</td>                    
                        <td style="" width="50%" >{{$course->courseDesc}}</th>
                        <td style="text-align: center;">{{$course->countmale}}</td>
                        <td style="text-align: center;">{{$course->countfemale}}</th>
                        <td style="text-align: center;">{{$course->total}}</th>
                    </tr>
                @endforeach
                <tr>
                    <td style="" width="5%"></td>                    
                    <td style="" width="50%" >TOTAL</th>
                    <td style="text-align: center;">{{collect($records[0]->courses)->sum('countmale')}}</td>
                    <td style="text-align: center;">{{collect($records[0]->courses)->sum('countfemale')}}</th>
                    <td style="text-align: center;">{{collect($records[0]->courses)->sum('total')}}</th>
                </tr>
            @endif     
        </tbody>
    </table>
@elseif($list == 1)
    @if(count($students)>0)
        <table border="1" cellpadding="2" style="font-size: 10px; width: 100%; border-collapse: collapse;">
            @foreach($students as $studentkey => $student)            
                <tr>
                    <th style="text-align: left; font-size: 13px;" colspan="4">{{$studentkey}}</th>
                </tr>
                @if(count($student) == 0)
                <tr>
                    <th colspan="4">No students enrolled!</th>
                </tr>
                @else
                    @php
                        $malestudents = collect($student)->where('gender','male')->values()->all();
                        $femalestudents = collect($student)->where('gender','female')->values()->all();
                        $maxcount = max(collect($malestudents)->count(),collect($femalestudents)->count());
                    @endphp
                    <tr>
                        <th colspan="2">MALE</th>
                        <th colspan="2">FEMALE</th>
                    </tr>
                    @for($x=0; $x<$maxcount; $x++)
                        <tr style="page-break-inside: auto;">
                
                            <td style="text-align: center; width: 5%;">{{$x+1}}</td>
                            <td style=" width: 45%; vertical-align: top;page-break-inside: auto; padding-left: 5px;">
                                @if(isset($malestudents[$x]))
                                {{ucwords(mb_strtolower($malestudents[$x]->lastname))}}, {{ucwords(mb_strtolower($malestudents[$x]->firstname))}} {{ucwords(mb_strtolower($malestudents[$x]->middlename))}} {{$malestudents[$x]->suffix}}
                                @endif
                            </td>
                            <td style="text-align: center; width: 5%;">{{$x+1}}</td>
                            <td style=" width: 45%; vertical-align: top;page-break-inside: auto; padding-left: 5px;">
                                @if(isset($femalestudents[$x]))
                                {{ucwords(mb_strtolower($femalestudents[$x]->lastname))}}, {{ucwords(mb_strtolower($femalestudents[$x]->firstname))}} {{ucwords(mb_strtolower($femalestudents[$x]->middlename))}} {{$femalestudents[$x]->suffix}}
                                @endif
                            </td>
                        </tr> 
                    @endfor
                @endif
            @endforeach
        </table>
    @else
    <table border="1" cellpadding="2" style="font-size: 10px; width: 100%; border-collapse: collapse;">
        <tr>
            <th colspan="4">No students enrolled!</th>
        </tr>
    </table>
    @endif
@endif
@if($list == 2)
    <style>
        @page{
            size: 13in 8.5in;
            margin: 0.5in;
        }
    </style>
    <table style="width: 100%; text-align: center;">
        <tr>
            <th>{{DB::table('schoolinfo')->first()->schoolname}}</th>
        </tr>
        <tr>
            <th>{{DB::table('schoolinfo')->first()->address}}</th>
        </tr>
        <tr>
            <th>{{strtoupper($semester)}} {{$sy->sydesc}}</th>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse; font-size: 13px;" border="1">
        <thead style="text-align: center;">
            <tr>
                <th rowspan="3" style="vertical-align: bottom;">No</th>
                <th rowspan="3" style="width: 25%; vertical-align: bottom;">Name of Program</th>
                <th colspan="14">NUMBER OF ENROLLEES</th>
                <th></th>
            </tr>
            <tr>
                <th colspan="2">1ST YEAR</th>
                <th rowspan="2">TOTAL</th>
                <th colspan="2">2ND YEAR</th>
                <th rowspan="2">TOTAL</th>
                <th colspan="2">3RD YEAR</th>
                <th rowspan="2">TOTAL</th>
                <th colspan="2">4TH YEAR</th>
                <th rowspan="2">TOTAL</th>
                <th colspan="2">GRAND TOTAL</th>
                <th rowspan="2" style="vertical-align: bottom;">Overall</th>
            </tr>
            <tr>
                <th>Male</th>
                <th>Female</th>
                <th>Male</th>
                <th>Female</th>
                <th>Male</th>
                <th>Female</th>
                <th>Male</th>
                <th>Female</th>
                <th>Male</th>
                <th>Female</th>
            </tr>
        </thead>        
        @foreach(collect($records[0]->courses)->where('id','>',0)->values() as $coursekey => $course)
            <tr>
                <td style="text-align: center;">{{$coursekey+1}}</td>
                <td>{{$course->courseDesc}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','male')->where('levelid','17')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','female')->where('levelid','17')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('levelid','17')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','male')->where('levelid','18')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','female')->where('levelid','18')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('levelid','18')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','male')->where('levelid','19')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','female')->where('levelid','19')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('levelid','19')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','male')->where('levelid','20')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','female')->where('levelid','20')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('levelid','20')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','male')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->where('gender','female')->count()}}</td>
                <td style="text-align: center;">{{collect($filteredstudents)->where('courseid',$course->id)->count()}}</td>
            </tr>
        @endforeach
    </table>
    <br/>
    <table style="width: 100%;">
        <tr>
            <td style="width: 25%;">Prepared by:</td>
            <td style="width: 20%;"></td>
            <td>Noted by:</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: center;">{{auth()->user()->name}}</td>
            <td></td>
            <td style="font-weight: bold; text-align: center;">{{DB::table('schoolinfo')->first()->authorized}}</td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center;">Registrar</td>
            <td></td>
            <td style="text-align: center;">President</td>
            <td></td>
        </tr>
    </table>
@endif
@if($list < 2)
    <br/>
    <br/>

    <table style="width: 100%; font-size: 11px;">
        <tr>
            <td></td>
            <td>Prepared by:</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 70%;">&nbsp;</td>
            <td style="border-bottom: 1px solid black; text-align: center;">{{$preparedby}}</td>
        </tr>
    </table>
    <br/>
    <br/>

    <table style="width: 100%; font-size: 11px;">
        <tr>
            <td></td>
            <td>Generated by:</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 70%;">&nbsp;</td>
            <td style="border-bottom: 1px solid black; text-align: center;">{{$generatedby}}</td>
        </tr>
    </table>
@endif