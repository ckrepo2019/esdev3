
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SHS - School Form 10</title>
        <style>
    * { font-family: Arial, Helvetica, sans-serif; line-height: 11px;}
    @page { margin: 15px 20px 0px 20px; size: {{$papersize != null ? $papersize : '8.5in 13in'}};} /*8.5 13*/

    #table1 td{
        padding: 0px;
    }
    table {
        border-collapse: collapse;
    }
    #table2{
        margin-top: 2px;
        font-size: 11px;
    }

    input[type="checkbox"] {
    /* position: relative; */
    top: 2px;
    box-sizing: content-box;
    width: 14px;
    height: 14px;
    margin: 0 5px 0 0;
    cursor: pointer;
    -webkit-appearance: none;
    border-radius: 2px;
    background-color: #fff;
    border: 1px solid #b7b7b7;
    }

    input[type="checkbox"]:before {
    content: '';
    display: block;
    }

    input[type="checkbox"]:checked:before {
    width: 4px;
    height: 9px;
    margin: 0px 4px;
    border-bottom: 2px solid ;
    border-right: 2px solid ;
    transform: rotate(45deg);
    }
    .text-center{
        text-align: center;
    }
    table{
        page-break-inside: avoid !important; 
    }
</style>
    </head>
    <body>

@php
$guardianinfo = DB::table('studinfo')
    ->where('id',$studinfo->id)
    ->first();
$guardianname = '';
if($guardianinfo->fathername == null)
{
    $guardianname.=$guardianinfo->guardianname;
}else{
    
    $explodename = explode(',',$guardianinfo->fathername);
    if(count($explodename)>1)
    {
        $guardianname.='MR. AND MRS. ';
        $explodelastname = $explodename[0];
        
        $firstname = explode(' ',$explodename[1]);
        if(count($firstname) < 3)
        {
            $guardianname.=$firstname[0];
        }
        else
        {
            $guardianname.=$firstname[0].' '.$firstname[1].' ';
        }
        $guardianname.=$explodelastname;
    }
    
}
$address = '';
if($guardianinfo->street != null)
{
    $address.=$guardianinfo->street.', ';
}
if($guardianinfo->barangay != null)
{
    $address.=$guardianinfo->barangay.', ';
}
if($guardianinfo->city != null)
{
    $address.=$guardianinfo->city.', ';
}
if($guardianinfo->province != null)
{
    $address.=$guardianinfo->province;
}
$studstatdate = '';
$sh_enrolledstud = DB::table('sh_enrolledstud')
    ->where('studid', $studinfo->id)
    ->where('levelid', $studinfo->levelid)
    ->where('semid', $studinfo->semid)
    ->where('deleted','0')
    ->first();

if($sh_enrolledstud)
{
    if($sh_enrolledstud->dateenrolled == null)
    {

    }else{
        $studstatdate.=date('F d, Y', strtotime($sh_enrolledstud->dateenrolled));
    }
}else{

}

function lower_case($word)
{
    return \App\Http\Controllers\SchoolFormsController::lowercase_word($word);
}
@endphp
@if(strtolower(DB::table('schoolinfo')->first()->schoolid) == '405308')
    <table style="width: 100%" id="table1">
        <tr>
            <td width="5%" style="text-align: left;"><sup style="font-size: 9px;">SF10-SHS</sup></td>
            <td width="12%" rowspan="6" style="text-align: right;">
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
            </td>
            <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
            <td width="17%" style="text-align:left;" rowspan="6"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Department of Education</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Region X-Northern Mindanao</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px;">Division of Ozamiz City</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 11px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center; font-size: 12px; font-weight: bold;">Learner's Permanent Academic Record for Senior High School (SF10-SHS)</td>
        </tr>
        <tr style="line-height: 10px;font-size: 11px;">
            <td style="text-align:center; font-weight: bold;" colspan="4">(Formerly Form 137)</td>
        </tr>
    </table>
@else
    @if($format == 'school')
        <table style="width: 100%" >
            <tr>
                <td width="15%" rowspan="2" style="vertical-align:top;"><sup style="font-size: 9px;">SF10-SHS</sup></td>
                <td width="10%"rowspan="2" style="text-align: right;">
                {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct') --}}
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="60px">
                {{-- @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'msmi')
                <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                @else
                <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
                @endif --}}
                </td>
                <td style="text-align:center; font-size: 16px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
                <td width="10%" style="text-align:right;"  rowspan="2"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"></td>
                <td width="15%" rowspan="5" style="border: 1px solid #ddd; vertical-align: middle; text-align: center; font-size: 10px;"><br/>Photo<br/>1x1</td>
            </tr>
            <tr>
                <td style="text-align:center; font-size: 11px;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td rowspan="2" style="border: 1px solid #ddd; vertical-align: top; font-size: 8px;"><sup>Forwarded to:</sup></td>
                <td colspan="3" style="text-align:center; font-size: 14px; font-weight: bold;">SENIOR HIGH SCHOOL STUDENT PERMANENT RECORD</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
        </table>
    @else
        <table style="width: 100%" id="table1">
            <tr>
                <td width="10%" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') rowspan="6" @else rowspan="5" @endif><sup style="font-size: 9px;">SF10-SHS</sup><br/>
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                </td>
                <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
                <td width="10%" style="text-align:right;" @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak') rowspan="6" @else rowspan="5" @endif><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
            </tr>
            <tr>
                <td style="text-align:center; font-size: 11px;">Department of Education</td>
            </tr>
            <tr>
                <td style="text-align:center; font-size: 15px; font-weight: bold; text-transform: uppercase;">Learner's Permanent Academic Record for Senior High School </td>
            </tr>
            
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak')
            <tr>
                <td style="text-align:center; font-size: 20px; font-weight: bold; color: green; padding: 5px 0px;">Holy Cross of Babak, Inc.</td>
            </tr>
            <tr>
                <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-SHS)</td>
            </tr>
            <tr style="font-size: 11px;">
                <td style="text-align:center; font-style: italic; padding: 7px 0px 0px 0px;">(Formerly Form 137)</td>
            </tr>
            @else
            
            <tr>
                <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-SHS)</td>
            </tr>
            <tr style="line-height: 5px; font-size: 11px;">
                <td style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
            </tr>
            @endif
        </table>
    @endif
@endif
<div style="width: 100%; line-height: 1px;">&nbsp;</div>
<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;">LEARNER'S INFORMATION</td>
    </tr>
</table>
<table class="table table-sm" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td style="width: 10%;">LAST NAME:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{$studinfo->lastname}}</td>
        <td style="width: 10%;">FIRST NAME:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{$studinfo->firstname}}</td>
        <td style="width: 11%;">MIDDLE NAME:</td>
        <td style="width: 19%; border-bottom: 1px solid black;">{{$studinfo->middlename}}</td>
    </tr>
</table>
<table class="table table-sm" width="100%" style="font-size: 11px !important;">
    <tr>
        <td style="width: 5%;">LRN:</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{$studinfo->lrn}}</td>
        <td style="width: 20%; text-align: right;">Date of Birth (MM/DD/YYYY):</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{\Carbon\Carbon::create($studinfo->dob)->isoFormat('MMMM DD, YYYY')}}</td>
        <td style="width: 5%; text-align: right;">Sex:</td>
        <td style="width: 7%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
        <td style="width: 26%;">Date of SHS Admission (MM/DD/YYYY):</td>
        <td style="width: 10%; border-bottom: 1px solid black;">@if($eligibility->shsadmissiondate != null ){{date('m/d/Y',strtotime($eligibility->shsadmissiondate)) ?? null}}@endif</td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;"> ELIGIBILITY FOR SHS ENROLMENT</td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important; margin-top:.5rem !important;">
    <tr>
        <td width="2%" style="border:solid 1px black" class="text-center">
            @if($eligibility->completerhs == '1')
             <b>/</b>
            @endif
        </td>
        <td width="16%">
            High School Completer*
        </td>
        <td width="7%">
            Gen. Ave: 
        </td>
        <td width="7%" style="border-bottom: 1px solid;">
            @if($eligibility->completerhs == '1')
                {{$eligibility->genavehs}}
            @endif
        </td>
        <td width="2%"></td>
        <td width="2%" style="border:solid 1px black" class="text-center">
            @if($eligibility->completerjh == '1')
                <b>/</b>
            @endif
        </td>
        <td width="21%">
            Junior High School Completer*
        </td>
        <td width="7%">
            Gen. Ave: 
        </td>
        <td width="7%" style="border-bottom: 1px solid;">
            @if($eligibility->completerjh == '1')
                {{$eligibility->genavejh}}
            @endif
        </td>
        <td width="30%"></td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important;">
    <tr>
        <td width="27%" style="vertical-align: top;">Date of Graduation/Completing (MM/DD/YYYY):<u>@if($eligibility->graduationdate == null)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@else{{date('m/d/Y',strtotime($eligibility->graduationdate))}}@endif</u>
        </td>
        <td  width="40%" style="vertical-align: top;">Name of School:<u>@if($eligibility->schoolname == null)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@else{{$eligibility->schoolname}}@endif</u>
        </td>
        <td style="vertical-align: top;"  width="23%" colspan="2">School Address: <u>@if($eligibility->schooladdress == null)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@else{{$eligibility->schooladdress}}@endif</u></td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important; margin-top:.2rem !important">
    <tr>
        <td width="2%" style="border:solid 1px black" class="text-center">
            @if($eligibility->peptpasser == '1')
             <b>/</b>
            @endif
        </td>
        <td width="16%">
            PEPT Passer**
        </td>
        <td width="5%">
            Rating: 
        </td>
        <td width="9%" style="border-bottom: 1px solid;">
            @if($eligibility->peptpasser == '1')
                {{$eligibility->peptrating}}
            @endif
        </td>
        <td width="2%"></td>
        <td width="2%" style="border:solid 1px black" class="text-center">
            @if($eligibility->alspasser == '1')
             <b>/</b>
            @endif
        </td>
        <td width="12%">
            ALS A&E Passer**
        </td>
        <td width="5%">
            Rating: 
        </td>
        <td width="9%" style="border-bottom: 1px solid;">
            @if($eligibility->peptpasser == '1')
                {{$eligibility->alsrating}}
            @endif
        </td>
        <td width="2%"></td>
        <td width="2%" style="border:solid 1px black" class="text-center">
            @if($eligibility->alspasser == '1')
             <b>/</b>
            @endif
        </td>
        <td width="13%">
            Others (Pls. Specify):
        </td>
        <td width="10%" style="border-bottom: 1px solid;">
            {{$eligibility->others}}
        </td>
       
        <td width="11%"></td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important; ">
    <tr>
        <td width="30%">Date of Examination/Assessment (MM/DD/YYYY):</td>
        <td width="15%" style="border-bottom: 1px solid;">
            @if($eligibility->examdate!=null) {{date('m/d/Y',strtotime($eligibility->examdate))}} @endif
        </td>
        <td width="30%">Name and Address of Community Learning Center:</td>
        <td width="15%" style="border-bottom: 1px solid;">
                {{$eligibility->centername}}
        </td>
        <td width="10%"></td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 7px !important; ">
    <tr>
        <td colspan="2" ><em>*High School Completers are students who graduated from secondary school under the old curriculum</em></td>
        <td colspan="2"><em>***ALS A&E - Alternative Learning System Accreditation and Equivalency Test for JHS</em></td>
    </tr>
    <tr>
        <td colspan="4"><em>**PEPT - Philippine Educational Placement Test for JHS</em></td>
    </tr>
</table>

<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;">SCHOLASTIC RECORD</td>
    </tr>
</table>
<div style="width: 100%; line-height: 4px;">&nbsp;</div>
@php
    $genavetotal = 0;
    $donegenave = 0;
@endphp
@if(count($records)>0)
    @php
        $record_count = 1;
    @endphp
    @foreach($records as $recordkey=>$record)
        @if(count($record)==1)
        
            
            
        @elseif(count($record) == 2)
        
            
            
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                   @if( $record_count == 2)
                       <tr>
                           <td colspan="6">
                                <table class="table" width="100%" style=" font-size: 10px;">
                                    <tr>
                                        <td width="10%">Page 2</td>
                                        <td width="80%">{{$studinfo->lastname}}, {{$studinfo->firstname}}</td>
                                        <td width="10%" style="text-align: right;">SF10-SHS</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif
                    @php
                        $record_count += 1;
                    @endphp
               <thead style="border-left: 2px solid black; border-right: 2px solid black; border-top: 2px solid black">
                    
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 5%;">School:</td>
                                    <td style="width: 38%; border-bottom: 1px solid black;">{{$record[0]->schoolname}}</td>
                                    <td style="width: 7%;">School ID:</td>
                                    <td style="width: 7%; border-bottom: 1px solid black;">{{$record[0]->schoolid}}</td>
                                    <td style="width: 10%;">GRADE LEVEL:</td>
                                    <td style="width: 8%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[0]->levelname)}}</td>
                                    <td style="width: 2%;">SY:</td>
                                    <td style="width: 13%; border-bottom: 1px solid black;">{{$record[0]->sydesc}}</td>
                                    <td style="width: 4%;">SEM:</td>
                                    <td style="width: 6%; border-bottom: 1px solid black;">@if($record[0]->semid == 1) 1st @elseif($record[0]->semid == 2) 2nd @endif</td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 10%;">TRACK/STRAND:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{$record[0]->trackname}} / {{$record[0]->strandname}}</td>
                                    <td style="width: 7%;">SECTION:</td>
                                    <td style="width: 23%; border-bottom: 1px solid black;">{{$record[0]->sectionname}}</td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr style="font-size: 9px !important;">
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                        <th rowspan="2" style="width: 40%; border: solid 1px black;">SUBJECTS</th>
                        <th colspan="2" style="width: 10%; border: solid 1px black;">Quarter</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">ACTION<br/>TAKEN</th>
                    </tr>
                    <tr >
                        <th style="width: 8%; border: solid 1px black;">1</th>
                        <th style="width: 8%; border: solid 1px black;">2</th>
                    </tr>
                </thead>
                <tbody  style="border-left: 2px solid black; border-right: 2px solid black; border-bottom: 2px solid black;">
                @if(collect($record[0]->grades)->where('semid',1)->unique('subjdesc')->count()>1)
                    @php
                        $gen_ave_for_sem = 0;
                        $with_final_rating = true;
                    @endphp
                    @foreach(collect($record[0]->grades)->where('semid',1)->unique('subjdesc') as $grade)
                        @php
                            $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                            $average = $with_final_rating ? ($grade->q1 + $grade->q2 ) / 2 : '';
                            $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                        @endphp
                        @if($record[0]->type == 2)
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{lower_case($grade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q1}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q2}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating > 0 ? $grade->finalrating : ''}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                </tr>
                            @endif
                        @else
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{lower_case($grade->subjdesc)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q1 > 0 ? number_format($grade->q1) : ''}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q2 > 0 ? number_format($grade->q2) : ''}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating > 0 ? $grade->finalrating : ''}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating > 0 ? $grade->remarks : ''}}</td>
                                </tr>
                            @endif
                        @endif
                    @endforeach                    
                    @if($record[0]->type == 1)
                        @if(count($record[0]->subjaddedforauto)>0)
                            @foreach($record[0]->subjaddedforauto as $customsubjgrade)
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{$customsubjgrade->subjdesc}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q1)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q2)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->actiontaken}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr style="font-weight: bold;">
                            @php
                                // $with_final = collect($record[0]->grades)->where('semid',1)->where('q1','!=,',null)->count() == 0 && collect($record[0]->grades)->where('semid',1)->where('q2','!=,',null)->count() == 0 ? true:false;
                                $genave = $record[0]->generalaverage;
                                $genavetotal+= $genave[0]->finalrating; 
                                $donegenave+=1;
                            @endphp
                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                            <td class="text-center" style="border: solid 1px black;">@if(count($genave)>0)@if($genave[0]->finalrating>0){{$genave[0]->finalrating}}@endif @endif</td>
                            <td class="text-center" style="border: solid 1px black;">@if(count($genave)>0)@if($genave[0]->finalrating>0){{ $genave[0]->finalrating >= 75 ? 'PASSED' : 'FAILED'  }}@endif @endif</td>
                            {{-- <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / collect($record[0]->grades)->where('semid',1)->count()) : $grade->finalrating }}</td> --}}
                            {{-- <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / collect($record[0]->grades)->where('semid',1)->count()) >= 75 ? 'PASSED' : 'FAILED' : '' }}</td> --}}
                        </tr>
                    @elseif($record[0]->type == 2)
                    @php
                        // $genave = $record[1]->generalaverage;
                        $genave = $record[0]->generalaverage[0]->finalrating ?? 0;
                        if($genave == 0)
                        {
                            if(count($record[0]->grades) > 0)
                            {
                                foreach($record[0]->grades as $grade)
                                {
                                    if(strtolower($grade->subjdesc) == 'general average')
                                    {
                                        $genave = $grade->finalrating;
                                    }
                                }
                            }
                        }
                        if($genave == 0)
                        {
                            $genave = collect($record[0]->grades)->avg('finalrating');
                        }
                                $genavetotal+= $genave; 
                                $donegenave+=1;
                    @endphp
                    <tr style="font-weight: bold;">
                        <td colspan="4" style="text-align: right;">General Average</td>
                        <td class="text-center">{{$genave > 0 ? $genave : ''}}</td>
                        <td class="text-center">{{$genave >= 75 ? 'PASSED' : ($genave == 0 ? '' : 'FAILED')}}</td>
                    </tr>
                    @endif
                    
                @else
                    @php
                    if($recordkey == 0)
                    {
                    $levelsubjects = collect($gradelevels)->where('id', 14)->first()->subjects;
                    }else{
                    $levelsubjects = collect($gradelevels)->where('id', 15)->first()->subjects;
                    }
                    $defaultsubjects = collect($levelsubjects)->where('semid', 1)->unique('subjcode');
                    @endphp
                    {{-- @if(count($defaultsubjects)>0)
                        @foreach($defaultsubjects as $defaultsubject)
                            <tr>
                                <td class="text-center" style="border: solid 1px black;">{{$defaultsubject->subjcode}}</td>
                                <td style="border: solid 1px black;">{{$defaultsubject->subjtitle}}</td>
                                <td class="text-center" style="border: solid 1px black;"></td>
                                <td class="text-center" style="border: solid 1px black;"></td>
                                <td class="text-center" style="border: solid 1px black;"></td>
                                <td class="text-center" style="border: solid 1px black;"></td>
                            </tr>
                        @endforeach
                    @else --}}
                    @for($x=0; $x<10; $x++)
                        <tr >
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                        </tr>
                    @endfor
                    <tr style="font-weight: bold;">
                        <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                        <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                        <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                    </tr>
                    {{-- @endif --}}
                @endif
            <tbody>
            </table>
            @php
                $certncorrectname = '';
                $certncorrectdesc = '';
                $datechecked = '';       
                $eachsemremarks = '';
                if(collect($eachsemsignatories)->where('levelid', $record[0]->levelid)->where('semid',$record[0]->semid)->first())
                {
                    $signinfo = collect($eachsemsignatories)->where('levelid', $record[0]->levelid)->where('semid',$record[0]->semid)->first();
                    $certncorrectname = $signinfo->certncorrectname;
                    $certncorrectdesc = $signinfo->certncorrectdesc;
                    $eachsemremarks = $signinfo->remarks;
                    $datechecked = $signinfo->datechecked != null ? date('m/d/Y', strtotime($signinfo->datechecked)) : '';
                }else{
                    $certncorrectname = $record[0]->recordincharge;
                    $certncorrectdesc = "SHS-School Record's In-Charge";
                    $datechecked = $record[0]->datechecked != null ?  date('M d, Y',strtotime($record[0]->datechecked)) : '';
                }
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 2px;">
                <tr>
                    <td style="width: 10%;">REMARKS:</td>
                    <td style="border-bottom: 1px solid black;" colspan="4">{{$eachsemremarks}}</td>
                </tr>
                {{-- <tr>
                    <td colspan="5">&nbsp;</td>
                </tr> --}}
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                    <td></td>
                    <td>Date Checked (MM/DD/YYYY)</td>
                </tr>
                <tr>
                    <td colspan="5" style="line-height: 2px;">&nbsp;</td>
                </tr>
                <tr style="font-size:  9px;">
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->teachername}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$certncorrectname}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$datechecked}}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">{{$certncorrectdesc}}</td>
                    <td></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            @php
                $eachsemremedial_teachername = '';
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                @if(collect($record[0]->remedials)->contains('type','2'))
                    @php
                        $eachsemremedial_header = collect($record[0]->remedials)->where('type','2')->values();
                        $eachsemremedial_teachername = $eachsemremedial_header[0]->teachername;
                    @endphp
                    <tr>
                        <td style="width: 15%;">REMEDIAL CLASSES</td>
                        <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->datefrom != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->datefrom)) : ''}}</td>
                        <td style="width: 13%;">to (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->dateto != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->dateto)) : ''}}</td>
                        <td>SCHOOL:</td>
                        <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->schoolname}}</td>
                        <td>SCHOOL ID:</td>
                        <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->schoolid}}</td>
                    </tr>
                @else
                <tr>
                    <td style="width: 15%;">REMEDIAL CLASSES</td>
                    <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                    <td style="width: 10%; border-bottom: 1px solid black;"></td>
                    <td style="width: 13%;">to (MM/DD/YYYY):</td>
                    <td style="width: 10%; border-bottom: 1px solid black;"></td>
                    <td>SCHOOL:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td>SCHOOL ID:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                @endif
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 9px; text-transform: uppercase;" border="1">
                
                <tr style="font-size: 9px !important;">
                    <th style="width: 10%;">INDICATE IF
                        SUBJECT IS
                        CORE, APPLIED,
                        OR
                        SPECIALIZED</th>
                    <th style="width: 40%;">SUBJECTS</th>
                    <th style="width: 10%;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                    <th style="width: 10%;">REMEDIAL<br/>CLASS<br/>MARK</th>
                    <th style="width: 10%;">RECOMPUTED<br/>FINAL GRADE</th>
                    <th style="width: 10%;">ACTION TAKEN</th>
                </tr>
                @if(collect($record[0]->remedials)->contains('type','1'))
                    @foreach($record[0]->remedials as $remedial)
                        @if($remedial->type == 1)
                            <tr>
                                <td class="text-center">{{$remedial->subjectcode}}</td>
                                <td>{{$remedial->subjectname}}</td>
                                <td class="text-center">{{$remedial->finalrating}}</td>
                                <td class="text-center">{{$remedial->remclassmark}}</td>
                                <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                <td class="text-center">{{$remedial->remarks}}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    @for($x = 0; $x<3 ; $x++)
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                @endif 
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td style="width: 20%;">Name of Teacher/Adviser:</td>
                    <td style="width: 60%; border-bottom: 1px solid black;" colspan="2">{{$eachsemremedial_teachername}}</td>
                    <td style="width: 10%;">Signature:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 10px; " border="1">
                <thead>
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 5%;">School:</td>
                                    <td style="width: 38%; border-bottom: 1px solid black;">{{$record[1]->schoolname}}</td>
                                    <td style="width: 7%;">School ID:</td>
                                    <td style="width: 7%; border-bottom: 1px solid black;">{{$record[1]->schoolid}}</td>
                                    <td style="width: 10%;">GRADE LEVEL:</td>
                                    <td style="width: 8%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[1]->levelname)}}</td>
                                    <td style="width: 2%;">SY:</td>
                                    <td style="width: 13%; border-bottom: 1px solid black;">{{$record[1]->sydesc}}</td>
                                    <td style="width: 4%;">SEM:</td>
                                    <td style="width: 6%; border-bottom: 1px solid black;">@if($record[1]->semid == 1) 1st @elseif($record[1]->semid == 2) 2nd @endif</td>
                                </tr>
                            </table>
                            <table style="width: 100%; font-size: 10px;">
                                <tr>
                                    <td style="width: 10%;">TRACK/STRAND:</td>
                                    <td style="width: 60%; border-bottom: 1px solid black;">{{$record[1]->trackname}} / {{$record[1]->strandname}}</td>
                                    <td style="width: 7%;">SECTION:</td>
                                    <td style="width: 23%; border-bottom: 1px solid black;">{{$record[1]->sectionname}}</td>
                                </tr>
                            </table>
                            <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                        </td>
                    </tr>
                    <tr style="font-size: 9px !important;">
                        <th rowspan="2" style="width: 10%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 2nd sem
                        <th rowspan="2" style="width: 40%;">SUBJECTS</th>
                        <th colspan="2">Quarter</th>
                        <th rowspan="2" style="width: 10%;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                        <th rowspan="2" style="width: 10%;">ACTION<br/>TAKEN</th>
                    </tr>
                    <tr>
                        <th style="width: 8%;">3</th>
                        <th style="width: 8%;">4</th>
                    </tr>
                </thead>
                @php
                    $gen_ave_for_sem = 0;
                    $with_final_rating = true;
                @endphp
                @if(collect($record[1]->grades)->where('semid',2)->unique('subjdesc')->count() >0)
                    @foreach(collect($record[1]->grades)->where('semid',2)->unique('subjdesc') as $grade)
                        @php
                            $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                            $average = $with_final_rating ? ($grade->q1 + $grade->q2 ) / 2 : '';
                            $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                        @endphp
                        @if($record[1]->type == 2)
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center">{{$grade->subjcode}}</td>
                                    <td>{{lower_case($grade->subjdesc)}}</td>
                                    <td class="text-center">{{$grade->q1}}</td>
                                    <td class="text-center">{{$grade->q2}}</td>
                                    <td class="text-center">{{$grade->finalrating > 0 ? $grade->finalrating : ''}}</td>
                                    <td class="text-center">{{$grade->remarks}}</td>
                                </tr>
                            @endif
                        @else
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center">{{$grade->subjcode}}</td>
                                    <td>{{lower_case($grade->subjdesc)}}</td>
                                    <td class="text-center">{{$grade->q1 != null || $grade->q2 != 0 ? number_format($grade->q1) : ''}}</td>
                                    <td class="text-center">{{$grade->q2 != null || $grade->q2 != 0 ? number_format($grade->q2) : ''}}</td>
                                    <td class="text-center">
                                        {{$grade->finalrating}}
                                        </td>
                                    <td class="text-center">
                                        {{$grade->remarks}}
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    @if($record[1]->type == 1)
                        @if(count($record[1]->subjaddedforauto)>0)
                            @foreach($record[1]->subjaddedforauto as $customsubjgrade)
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{$customsubjgrade->subjdesc}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q1)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($customsubjgrade->q2)}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->actiontaken}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr style="font-weight: bold;">
                            @php
                                $genave = $record[1]->generalaverage;
                                $genavetotal+= $genave[0]->finalrating;
                                $donegenave+=1;
                            @endphp
                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                            <td class="text-center" style="border: solid 1px black;">@if(count($genave)>0)@if($genave[0]->finalrating>0){{$genave[0]->finalrating}}@endif @endif</td>
                            <td class="text-center" style="border: solid 1px black;">@if(count($genave)>0)@if($genave[0]->finalrating>0){{ $genave[0]->finalrating >= 75 ? 'PASSED' : 'FAILED'  }}@endif @endif</td>
                        </tr>
                    @elseif($record[1]->type == 2)
                        @php
                            $genave = $record[1]->generalaverage[0]->finalrating ?? 0;
                            if($genave == 0)
                            {
                                if(count($record[1]->grades) > 0)
                                {
                                    foreach($record[1]->grades as $grade)
                                    {
                                        if(strtolower($grade->subjdesc) == 'general average')
                                        {
                                            $genave = $grade->finalrating;
                                        }
                                    }
                                }
                            }
                            if($genave == 0)
                            {
                                $genave = collect($record[1]->grades)->avg('finalrating');
                            }
                            $genavetotal+= $genave;
                                $donegenave+=1;
                        @endphp
                        <tr style="font-weight: bold;">
                            <td colspan="4" style="text-align: right;">General Average</td>
                            <td class="text-center">{{$genave > 0 ? $genave : ''}}</td>
                            <td class="text-center">{{$genave >= 75 ? 'PASSED' :  ($genave == 0 ? '' : 'FAILED')}}</td>
                        </tr>
                    @endif
                @else
                    @php
                    if($recordkey == 0)
                    {
                    $levelsubjects = collect($gradelevels)->where('id', 14)->first()->subjects;
                    }else{
                    $levelsubjects = collect($gradelevels)->where('id', 15)->first()->subjects;
                    }
                    $defaultsubjects = collect($levelsubjects)->where('semid', 2)->unique('subjcode');
                    @endphp
                    {{-- @if(count($defaultsubjects)>0)
                        @foreach($defaultsubjects as $defaultsubject)
                            <tr>
                                <td class="text-center" style="border: solid 1px black;">{{$defaultsubject->subjcode}}</td>
                                <td style="border: solid 1px black;">{{$defaultsubject->subjtitle}}</td>
                                <td class="text-center" style="border: solid 1px black;"></td>
                                <td class="text-center" style="border: solid 1px black;"></td>
                                <td class="text-center" style="border: solid 1px black;"></td>
                                <td class="text-center" style="border: solid 1px black;"></td>
                            </tr>
                        @endforeach
                    @else --}}
                    @for($x=0; $x<10; $x++)
                        <tr >
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                        </tr>
                    @endfor
                    <tr style="font-weight: bold;">
                        <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                        <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                        <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                    </tr>
                    {{-- @endif --}}
                @endif
            </table>
            
            @php
                $certncorrectname = '';
                $certncorrectdesc = '';
                $datechecked = '';       
                $eachsemremarks = '';
                if(collect($eachsemsignatories)->where('levelid', $record[1]->levelid)->where('semid',$record[1]->semid)->first())
                {
                    $signinfo = collect($eachsemsignatories)->where('levelid', $record[1]->levelid)->where('semid',$record[1]->semid)->first();
                    $certncorrectname = $signinfo->certncorrectname;
                    $certncorrectdesc = $signinfo->certncorrectdesc;
                    $eachsemremarks = $signinfo->remarks;
                    $datechecked = $signinfo->datechecked != null ? date('m/d/Y', strtotime($signinfo->datechecked)) : '';
                }else{
                    $certncorrectname = $record[1]->recordincharge;
                    $certncorrectdesc = "SHS-School Record's In-Charge";
                    $datechecked = $record[1]->datechecked != null ?  date('M d, Y',strtotime($record[1]->datechecked)) : '';
                }
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 2px;">
                <tr>
                    <td style="width: 10%;">REMARKS:</td>
                    <td style="border-bottom: 1px solid black;" colspan="4">{{$eachsemremarks}}</td>
                </tr>
                {{-- <tr>
                    <td colspan="5">&nbsp;</td>
                </tr> --}}
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                    <td></td>
                    <td>Date Checked (MM/DD/YYYY)</td>
                </tr>
                <tr>
                    <td colspan="5" style="line-height: 2px;">&nbsp;</td>
                </tr>
                <tr style="font-size:  9px;">
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[1]->teachername}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$certncorrectname}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$datechecked}}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">{{$certncorrectdesc}}</td>
                    <td></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            @php
                $eachsemremedial_teachername = '';
            @endphp
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                @if(collect($record[1]->remedials)->contains('type','2'))
                    @php
                        $eachsemremedial_header = collect($record[1]->remedials)->where('type','2')->values();
                        $eachsemremedial_teachername = $eachsemremedial_header[1]->teachername;
                    @endphp
                    <tr>
                        <td style="width: 15%;">REMEDIAL CLASSES</td>
                        <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[1]->datefrom != null ? date('m/d/Y', strtotime($eachsemremedial_header[1]->datefrom)) : ''}}</td>
                        <td style="width: 13%;">to (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[1]->dateto != null ? date('m/d/Y', strtotime($eachsemremedial_header[1]->dateto)) : ''}}</td>
                        <td>SCHOOL:</td>
                        <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[1]->schoolname}}</td>
                        <td>SCHOOL ID:</td>
                        <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[1]->schoolid}}</td>
                    </tr>
                @else
                <tr>
                    <td style="width: 15%;">REMEDIAL CLASSES</td>
                    <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                    <td style="width: 10%; border-bottom: 1px solid black;"></td>
                    <td style="width: 13%;">to (MM/DD/YYYY):</td>
                    <td style="width: 10%; border-bottom: 1px solid black;"></td>
                    <td>SCHOOL:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td>SCHOOL ID:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                @endif
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 9px; text-transform: uppercase;" border="1">
                
                <tr style="font-size: 9px !important;">
                    <th style="width: 10%;">INDICATE IF
                        SUBJECT IS
                        CORE, APPLIED,
                        OR
                        SPECIALIZED</th>
                    <th style="width: 40%;">SUBJECTS</th>
                    <th style="width: 10%;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                    <th style="width: 10%;">REMEDIAL<br/>CLASS<br/>MARK</th>
                    <th style="width: 10%;">RECOMPUTED<br/>FINAL GRADE</th>
                    <th style="width: 10%;">ACTION TAKEN</th>
                </tr>
                @if(collect($record[1]->remedials)->contains('type','1'))
                    @foreach($record[1]->remedials as $remedial)
                        @if($remedial->type == 1)
                            <tr>
                                <td class="text-center">{{$remedial->subjectcode}}</td>
                                <td>{{$remedial->subjectname}}</td>
                                <td class="text-center">{{$remedial->finalrating}}</td>
                                <td class="text-center">{{$remedial->remclassmark}}</td>
                                <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                <td class="text-center">{{$remedial->remarks}}</td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    @for($x = 0; $x<3 ; $x++)
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                @endif 
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                <tr>
                    <td style="width: 20%;">Name of Teacher/Adviser:</td>
                    <td style="width: 60%; border-bottom: 1px solid black;" colspan="2">{{$eachsemremedial_teachername}}</td>
                    <td style="width: 10%;">Signature:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
        @endif
        @if($record_count == 2)
        <div style="page-break-before: always;"></div>
        @endif
    @endforeach
    @if(count($records) == 1)
    @endif
@endif
<table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
    <tr>
        <td style="width:20%"><strong>Track/Strand Accomplished:</strong></td>
        <td style="border-bottom: 1px solid;width:45%; text-align: center;">
            {{$footer->strandaccomplished}}
        </td>
        <td style="width:20%"><strong>SHS General Average:</strong></td>
        <td style="border-bottom: 1px solid; text-align: center;">@if($donegenave>=4){{$footer->shsgenave  == null ? (number_format($genavetotal/4,2) ?? ' ') : $footer->shsgenave}}@else {{$footer->shsgenave}} @endif</td>
    </tr>
</table>
<table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
    <tr>
        <td style="width:20%"><strong>Awards/Honors Received:</strong></td>
        <td style="border-bottom: 1px solid;width:40%">
            {{$footer->honorsreceived}}
        </td>
        <td style="width:28%"><strong>Date of SHS Graduation (MM/DD/YYYY):</strong></td>
        <td style="border-bottom: 1px solid; text-align: center;">{{$footer->shsgraduationdate}}</td>
    </tr>
</table>
<table style="font-size: 10px; width: 100%;">
    <tr>
        <td style="width: 60%;"><strong>Certified by:</strong></td>
        <td style="width: 40%;"><strong>Place School Seal Here:</strong></td>
    </tr>
    <tr>
        <td style="border-right: 1px solid;">
            <table style="width: 100%">
                <tr>
                    <td style="width: 60%;">
                        <div style="width: 90%; border-bottom: 1px solid; text-align: center;">
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            PANIAMOGAN, GERALD C.
                            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mcs')
                            {{strtoupper(DB::table('schoolinfo')->first()->schoolrecordsincharge)}}
                            @else
                            {{$footer->registrar != null ? $footer->registrar : strtoupper(DB::table('schoolinfo')->first()->authorized)}}
                            @endif
                        </div>
                    </td>
                    <td style="width: 40%; ">
                        <div style="width: 90%; border-bottom: 1px solid; text-align: center;">
                            <strong>{{$footer->datecertified}}</strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="width: 90%;">
                            <center>Signature of School Head over Printed Name</center>
                        </div>
                    </td>
                    <td>
                        <div style="width: 90%;">
                            <center>Date</center>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
            <div style="width: 95%; border: 1px solid;padding: 5px;">
                <strong>NOTE:</strong>
                <br>
                <small>
                    <em>
                        This permanent record or a photocopy of this permanent record that bears the seal of the school and the original signature in ink of the School Head shall be considered valid for all legal purposes. Any erasure or alteration made on this copy should be validated by the School Head.
                        <br>
                        If the student transfers to another school, the originating school should produce one (1) certified true copy of this permanent record for safekeeping. The receiving school shall continue filling up the original form.
                        <br>
                        Upon graduation, the school form which the student graduated should keep the original form and produce one (1) certified true copy for the Division Office.
                    </em>
                </small>
            </div>
        </td>
        <td></td>
    </tr>
    {{-- <tr>
        <td colspan="2">
            <strong>Copy for: </strong> 
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br>
            <strong>Date Issued (MM/DD/YYYY): <u>{{$footer->datecertified}}</u></strong>
        </td>
    </tr> --}}
</table>
<br/>
<table style="font-size: 11px; width: 60%;">
    <tr>
        <td style="width: 20%;">
            <strong>REMARKS:</strong> 
        </td>
        <td>
            @if($footer->copyforupper == null || $footer->copyforupper == '') 
            <span  style="font-size: 11px;"><em>(Please indicate the purpose for which this permanent record will be used)</em></span>
            @else
            <strong  style="font-size: 12px;"><u>{{$footer->copyforupper}}</u></strong>
            @endif
        </td>
    </tr>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'dcc')
    <tr>
        <td></td>
        <td style="font-size: 11px;">
            {{$footer->copyforlower}}
        </td>
    </tr>
    @endif
</table>
</body>
</html>