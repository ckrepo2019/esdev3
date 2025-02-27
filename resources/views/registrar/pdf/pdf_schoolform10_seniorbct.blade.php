<style>
    * { font-family: Arial, Helvetica, sans-serif; line-height: 11px;}
    @page { margin: 40px 20px 30px 20px; size: 8.5in 13in ;}

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

@endphp
    <table style="width: 100%" id="table1">
        <tr>
            <td width="10%" rowspan="5"><sup style="font-size: 9px;">SF10-SHS</sup><br/><img src="{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px"></td>
            <td style="text-align:center; font-size: 20px; color: #3c9e3a; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
            <td width="10%" style="text-align:right;" rowspan="5"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="90px"></td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 11px;"><i>Purok 8 Pagkakaisa Lubogan, Toril, Davao City</i></td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold; text-transform: uppercase;">Learner's Permanent Academic Record for Senior High School </td>
        </tr>
        <tr>
            <td style="text-align:center; font-size: 15px; font-weight: bold;">(SF10-SHS)</td>
        </tr>
        <tr style="line-height: 5px;font-size: 11px;">
            <td style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
        </tr>
    </table>
<div style="width: 100%; line-height: 1px;">&nbsp;</div>
<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #d6d08b; border: 1px solid black;">LEARNER'S INFORMATION</td>
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
        <td style="width: 13%; border-bottom: 1px solid black;">{{\Carbon\Carbon::create($studinfo->dob)->isoFormat('MMMM DD, YYYY')}}</td>
        <td style="width: 5%; text-align: right;">Sex:</td>
        <td style="width: 7%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
        <td style="width: 26%;">Date of SHS Admission (MM/DD/YYYY):</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{$studinfo->enlevelid == 15 ? 'April 19, 2019' : 'June 29, 2020'}}</td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #d6d08b; border: 1px solid black;"> ELIGIBILITY FOR SHS ENROLMENT</td>
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
        <td width="28%">Date of Graduation/Completing (MM/DD/YYYY):</td>
        <td style="border-bottom: 1px solid;"  width="9%">
            {{$eligibility->graduationdate}}
        </td>
        <td  width="10%">Name of School:</td>
        <td style="border-bottom: 1px solid"  width="30%">
            {{$eligibility->schoolname}}
        </td>
        <td style=""  width="10%">School Address:</td>
        <td style="border-bottom: 1px solid"  width="13%">
            {{$eligibility->schooladdress}}
        </td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important; margin-top:.2rem !important">
    <tr>
        <td width="2%" style="border:solid 1px black" class="text-center">
            @if($eligibility->completerhs == '1')
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
        <td class="text-center" style="font-weight: bold; background-color: #d6d08b; border: 1px solid black;">SCHOLASTIC RECORD</td>
    </tr>
</table>
<div style="width: 100%; line-height: 4px;">&nbsp;</div>
@if(count($records)>0)
    @php
        $record_count = 1;
    @endphp
    @foreach($records as $record)
        @if(count($record)==2)
        
        
            
            
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                   @if( $record_count == 2)
                       <tr>
                           <td colspan="6">
                                <table class="table" width="100%" style=" font-size: 9px;">
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
                            <table style="width: 100%; font-size: 9px;">
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
                            <table style="width: 100%; font-size: 9px;">
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
                    <tr>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                        <th rowspan="2" style="width: 40%; border: solid 1px black;">SUBJECTS</th>
                        <th colspan="2" style="width: 10%; border: solid 1px black;">Quarter</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">SEM FINAL<br/>GRADE</th>
                        <th rowspan="2" style="width: 10%; border: solid 1px black;">ACTION<br/>TAKEN</th>
                    </tr>
                    <tr >
                        <th style="width: 8%; border: solid 1px black;">1</th>
                        <th style="width: 8%; border: solid 1px black;">2</th>
                    </tr>
                </thead>
                <tbody  style="border-left: 2px solid black; border-right: 2px solid black; border-bottom: 2px solid black">
                @if(collect($record[0]->grades)->where('semid',1)->unique('subjdesc')->count()>0)
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
                                    <td style="border: solid 1px black;">{{$grade->subjdesc}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q1}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->q2}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                </tr>
                            @endif
                        @else
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                    <td style="border: solid 1px black;">{{$grade->subjdesc}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($grade->q1) > 0 ? number_format($grade->q1) : '' }}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{number_format($grade->q2) > 0 ? number_format($grade->q2) : '' }}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
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
                                $with_final = collect($record[0]->grades)->where('semid',1)->where('q1','!=,',null)->unique('subjdesc')->count() == 0 && collect($record[0]->grades)->where('semid',1)->unique('subjdesc')->where('q2','!=,',null)->count() == 0 ? true:false;
                            @endphp
                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                            <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / collect($record[0]->grades)->where('semid',1)->unique('subjdesc')->count()) : '' }}</td>
                            <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / collect($record[0]->grades)->where('semid',1)->unique('subjdesc')->count()) >= 75 ? 'PASSED' : 'FAILED' : '' }}</td>
                        </tr>
                    @elseif($record[0]->type == 2)
                        @if(count($record[0]->grades) > 1)
                            @foreach($record[0]->grades as $grade)
                                @if(strtolower($grade->subjdesc) == 'general average')
                                    <tr style="font-weight: bold;">
                                        <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                        <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                        <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                    
                @else
                
                    @for($x=0; $x<$maxgradecount; $x++)
                        <tr >
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                            <td style="border: solid 1px black;">&nbsp;</td>
                        </tr>
                    @endfor
                @endif
            <tbody>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                <tr>
                    <td style="width: 10%;">REMARKS:</td>
                    <td style="border-bottom: 1px solid black;" colspan="4"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                    <td></td>
                    <td>Date Checked (MM/DD/YYYY)</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->teachername}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->recordincharge}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->datechecked}}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">SHS-School Record's In-Charge</td>
                    <td></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
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
                <tr>
                    <td colspan="9"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 9px; text-transform: uppercase;" border="1">
                <tr>
                    <th style="width: 10%;">INDICATE IF
                        SUBJECT IS
                        CORE, APPLIED,
                        OR
                        SPECIALIZED</th>
                    <th style="width: 40%;">SUBJECTS</th>
                    <th style="width: 10%;">SEM FINAL<br/>GRADE</th>
                    <th style="width: 10%;">REMEDIAL<br/>CLASS<br/>MARK</th>
                    <th style="width: 10%;">RECOMPUTED<br/>FINAL GRADE</th>
                    <th style="width: 10%;">ACTION TAKEN</th>
                </tr>
                @if(count($record[0]->remedials)>0)
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
                    @endif 
                @else
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                <tr>
                    <td style="width: 20%;">Name of Teacher/Adviser:</td>
                    <td style="width: 60%; border-bottom: 1px solid black;" colspan="2"></td>
                    <td style="width: 10%;">Signature:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 9px; " border="1">
                <thead>
                    <tr>
                        <td colspan="6">
                            <table style="width: 100%; font-size: 9px;">
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
                            <table style="width: 100%; font-size: 9px;">
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
                    <tr>
                        <th rowspan="2" style="width: 10%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 2nd sem
                        <th rowspan="2" style="width: 40%;">SUBJECTS</th>
                        <th colspan="2">Quarter</th>
                        <th rowspan="2" style="width: 10%;">SEM FINAL<br/>GRADE</th>
                        <th rowspan="2" style="width: 10%;">ACTION<br/>TAKEN</th>
                    </tr>
                    <tr>
                        <th style="width: 8%;">1</th>
                        <th style="width: 8%;">2</th>
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
                                    <td>{{$grade->subjdesc}}</td>
                                    <td class="text-center">{{$grade->q1}}</td>
                                    <td class="text-center">{{$grade->q2}}</td>
                                    <td class="text-center">{{$grade->finalrating}}</td>
                                    <td class="text-center">{{$grade->remarks}}</td>
                                </tr>
                            @endif
                        @else
                            @if(strtolower($grade->subjdesc) != 'general average')
                                <tr>
                                    <td class="text-center">{{$grade->subjcode}}</td>
                                    <td>{{$grade->subjdesc}}</td>
                                    <td class="text-center">{{$grade->q1 ?? $grade->q3}}</td>
                                    <td class="text-center">
                                        {{$grade->q2 ?? $grade->q4}}
                                    </td>
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
                                $with_final = collect($record[1]->grades)->where('semid',2)->where('q1','!=,',null)->unique('subjdesc')->count() == 0 && collect($record[1]->grades)->where('semid',2)->where('q2','!=,',null)->unique('subjdesc')->count() == 0 ? true:false;
                            @endphp
                            <td colspan="4" style="text-align: right;">General Average</td>
                            <td class="text-center">@if(collect($record[1]->grades)->where('semid',2)->unique('subjdesc')->count() > 0){{ $with_final ? number_format($gen_ave_for_sem / collect($record[1]->grades)->where('semid',2)->unique('subjdesc')->count()) : '' }} @endif</td>
                            <td class="text-center">@if(collect($record[1]->grades)->where('semid',2)->unique('subjdesc')->count()) {{ $with_final ? number_format($gen_ave_for_sem / collect($record[1]->grades)->where('semid',2)->unique('subjdesc')->count()) >= 75 ? 'PASSED' : 'FAILED' : '' }} @endif</td>
                        </tr>
                    @elseif($record[1]->type == 2)
                        @if(count($record[1]->grades) > 1)
                            @foreach($record[1]->grades as $grade)
                                @if(strtolower($grade->subjdesc) == 'general average')
                                    <tr style="font-weight: bold;">
                                        <td colspan="4" style="text-align: right;">General Average</td>
                                        <td class="text-center">{{$grade->finalrating}}</td>
                                        <td class="text-center">{{$grade->remarks}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @else
                    @for($x=0; $x<$maxgradecount; $x++)
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
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                <tr>
                    <td style="width: 10%;">REMARKS:</td>
                    <td style="border-bottom: 1px solid black;" colspan="4"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                <tr>
                    <td>Prepared by:</td>
                    <td></td>
                    <td>&nbsp;&nbsp;Certified True and Correct:</td>
                    <td></td>
                    <td>Date Checked (MM/DD/YYYY)</td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->teachername}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->recordincharge}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->datechecked}}</td>
                </tr>
                <tr>
                    <td class="text-center">Signature of Adviser over Printed Name</td>
                    <td></td>
                    <td class="text-center">SHS-School Record's In-Charge</td>
                    <td></td>
                    <td class="text-center"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
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
                <tr>
                    <td colspan="9"></td>
                </tr>
            </table>
            <div style="width: 100%; line-height: 1px;">&nbsp;</div>
            <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 9px; text-transform: uppercase;" border="1">
                <tr>
                    <th style="width: 10%;">INDICATE IF
                        SUBJECT IS
                        CORE, APPLIED,
                        OR
                        SPECIALIZED</th>
                    <th style="width: 40%;">SUBJECTS</th>
                    <th style="width: 10%;">SEM FINAL<br/>GRADE</th>
                    <th style="width: 10%;">REMEDIAL<br/>CLASS<br/>MARK</th>
                    <th style="width: 10%;">RECOMPUTED<br/>FINAL GRADE</th>
                    <th style="width: 10%;">ACTION TAKEN</th>
                </tr>
                @if(count($record[1]->remedials)>0)
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
                    @endif 
                @else
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                <tr>
                    <td style="width: 20%;">Name of Teacher/Adviser:</td>
                    <td style="width: 60%; border-bottom: 1px solid black;" colspan="2"></td>
                    <td style="width: 10%;">Signature:</td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
            </table>
        {{-- <div style="page-break-before: always;"></div> --}}
        @endif
    @endforeach
    @if(count($records) == 1)
    @endif
@endif
<table style="text-align: none; text-transform:none; font-size: 9px; width: 100%;">
    <tr>
        <td style="width:20%"><strong>Track/Strand Accomplished:</strong></td>
        <td style="border-bottom: 1px solid;width:45%; text-align: center;">
            {{$footer->strandaccomplished}}
        </td>
        <td style="width:20%"><strong>SHS General Average:</strong></td>
        <td style="border-bottom: 1px solid; text-align: center;">{{$footer->shsgenave}}</td>
    </tr>
</table>
<table style="text-align: none; text-transform:none; font-size: 9px; width: 100%;">
    <tr>
        <td style="width:20%"><strong>Awards/Honors Received:</strong></td>
        <td style="border-bottom: 1px solid;width:40%">
            {{$footer->honorsreceived}}
        </td>
        <td style="width:28%"><strong>Date of SHS Graduation (MM/DD/YYYY):</strong></td>
        <td style="border-bottom: 1px solid; text-align: center;">{{$footer->shsgraduationdate}}</td>
    </tr>
</table>
<table style="font-size: 9px; width: 100%;">
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
                            @else
                            {{strtoupper(DB::table('schoolinfo')->first()->authorized)}}
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
                            <center>School Registrar</center>
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
            <strong>Copy for: </strong> 
        </td>
        <td style="font-size: 13.5px;">
            <strong><u>{{$footer->copyforupper}}</u></strong> 
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size: 11px;">
            {{$footer->copyforlower}}
        </td>
    </tr>
</table>