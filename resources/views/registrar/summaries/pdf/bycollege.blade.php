

<style>
    * {
        
        font-family: Arial, Helvetica, sans-serif;
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

@if($list == 0)
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


@php
$numofstudents = 1;   
$numofstudentsall = 1;  
$html = '';
$malecount = 1;
$femalecount = 1;
@endphp
<table border="1" cellpadding="2" style="font-size: 10px; width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
        <th style="" width="5%">#</th>                    
        <th style="" width="40%" >College</th>
        <th style="">Male</th>
        <th style="">Female</th>
        <th style="">Total</th>
        </tr>
    </thead>
    <tbody>
    @if(count($records[0]->colleges) > 0)
    @foreach($records[0]->colleges as $collegekey => $college)

        <tr>
        <td style="text-align: center" width="5%">{{$collegekey+1}}</td>                    
        <td style="" width="40%" >{{$college->collegeDesc}}</th>
        <td style="text-align: center;">{{$college->countmale}}</td>
        <td style="text-align: center;">{{$college->countfemale}}</th>
        <td style="text-align: center;">{{$college->total}}</th>
        </tr>
    @endforeach
    <tr>
        <td style="" width="5%"></td>                    
        <td style="" width="40%" >TOTAL</th>
        <td style="text-align: center;">{{collect($records[0]->colleges)->sum('countmale')}}</td>
        <td style="text-align: center;">{{collect($records[0]->colleges)->sum('countfemale')}}</th>
        <td style="text-align: center;">{{collect($records[0]->colleges)->sum('total')}}</th>
        </tr>
    @endif

    </tbody>
</table>
@else
@foreach($records[0]->colleges as $college)
@php
$students = collect($records[0]->students)->where('collegeid', $college->id)->where('acadprogid','6')->values();
$students = collect($students)->sortBy('fullname');
$maxcount = max(collect($students)->where('gender','MALE')->count(),collect($students)->where('gender','FEMALE')->count());
$malestudents = collect($students)->where('gender','MALE')->values();
$femalestudents = collect($students)->where('gender','FEMALE')->values();
@endphp
<table border="1" style="width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 10px; page-break-inside: auto;">
<thead>
    <tr>
        <th colspan="4" style="text-align:left; font-size: 15px;">{{$college->collegeDesc}}</th>
    </tr>
</thead>
@if(count($students) == 0)
<tr>
    <th colspan="4">No students enrolled!</th>
</tr>
@else
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
                {{-- <ol style="page-break-inside: auto;">
                    @foreach($eachlevel as $eachstudent)
                        @if(strtolower($eachstudent->gender) == 'male')
                        <li>{{ucwords(strtolower($eachstudent->lastname))}}, {{ucwords(strtolower($eachstudent->firstname))}}</li>
                        @endif
                    @endforeach
                </ol> --}}
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
</table>
@endforeach
@endif
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
        {{-- <td style="border-bottom: 1px solid black;">{{$preparedby}}</td> --}}
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
        {{-- <td style="border-bottom: 1px solid black;">{{$preparedby}}</td> --}}
        <td style="width: 70%;">&nbsp;</td>
        <td style="border-bottom: 1px solid black; text-align: center;">{{$generatedby}}</td>
    </tr>
</table>