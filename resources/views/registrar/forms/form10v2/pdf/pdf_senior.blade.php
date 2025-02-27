<style>
    * { font-family: Arial, Helvetica, sans-serif; line-height: 11px;}
    @page {
         margin: 20px 20px 15px 20px; 
         size: 8.5in 13in;
        }

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
// $sh_enrolledstud = DB::table('sh_enrolledstud')
//     ->where('studid', $studinfo->id)
//     ->where('levelid', $studinfo->levelid)
//     ->where('semid', $studinfo->semid)
//     ->where('deleted','0')
//     ->first();

// if($sh_enrolledstud)
// {
//     if($sh_enrolledstud->dateenrolled == null)
//     {

//     }else{
//         $studstatdate.=date('F d, Y', strtotime($sh_enrolledstud->dateenrolled));
//     }
// }else{

// }

$overallgenavetotal = 0;
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
                <td width="10%" rowspan="5"><sup style="font-size: 9px;">SF10-SHS</sup><br/>
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                </td>
                <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
                <td width="10%" style="text-align:right;" rowspan="5"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
            </tr>
            <tr>
                <td style="text-align:center; font-size: 11px;">Department of Education</td>
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
    @endif
@endif
<div style="width: 100%; line-height: 1px;">&nbsp;</div>
<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #bfbfbf; border: 1px solid black;">LEARNER'S INFORMATION</td>
    </tr>
</table>
<table class="table table-sm" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td style="width: 10%;">LAST NAME:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{strtoupper($studinfo->lastname)}}</td>
        <td style="width: 10%;">FIRST NAME:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{strtoupper($studinfo->firstname)}} {{$studinfo->suffix}}</td>
        <td style="width: 11%;">MIDDLE NAME:</td>
        <td style="width: 19%; border-bottom: 1px solid black;">{{strtoupper($studinfo->middlename)}}</td>
    </tr>
</table>
<table class="table table-sm" width="100%" style="font-size: 11px !important;">
    <tr>
        <td style="width: 5%;">LRN:</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{$studinfo->lrn}}</td>
        <td style="width: 20%; text-align: right;">Date of Birth (MM/DD/YYYY):</td>
        <td style="width: 13%; border-bottom: 1px solid black;">{{$studinfo->dob != null ? date('m/d/Y', strtotime($studinfo->dob)) : ''}}</td>
        <td style="width: 5%; text-align: right;">Sex:</td>
        <td style="width: 7%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
        <td style="width: 26%;">Date of SHS Admission (MM/DD/YYYY):</td>
        <td style="width: 12%; border-bottom: 1px solid black;">@if($eligibility->shsadmissiondate != null){{date('m/d/Y',strtotime($eligibility->shsadmissiondate)) ?? $studstatdate}} @endif</td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #bfbfbf; border: 1px solid black;"> ELIGIBILITY FOR SHS ENROLMENT</td>
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
<table class="table table-sm table-bordered" style="font-size: 10px !important; width: 100%;">
    <tr>
        <td style="width: 39%;" >Date of Graduation/Completing (MM/DD/YYYY): <u>@if($eligibility->graduationdate != null){{date('M/d/Y', strtotime($eligibility->graduationdate))}}@endif</u></td>
        <!--<td style="border-bottom: 1px solid;width: 8%;">-->
        <!--    {{$eligibility->graduationdate}}-->
        <!--</td>-->
        <td  style="width: 37%;">Name of School: <u>{{$eligibility->schoolname}}</u></td>
        <!--<td style="border-bottom: 1px solid; width: 25%;" >-->
        <!--    {{$eligibility->schoolname}}-->
        <!--</td>-->
        <td style="width: 27%;"  >School Address: <u>{{$eligibility->schooladdress}}</u></td>
        <!--<td style="border-bottom: 1px solid;width: 15%;">-->
        <!--    {{$eligibility->schooladdress}}-->
        <!--</td>-->
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important; margin-top:.2rem !important">
    <tr>
        <td style="border:solid 1px black; width: 2%;" class="text-center">
            @if($eligibility->completerhs == '1')
             <b>/</b>
            @endif
        </td>
        <td style=" width: 16%;">
            PEPT Passer**
        </td>
        <td style=" width: 5%;">
            Rating: 
        </td>
        <td style="border-bottom: 1px solid; width: 8%;">
            @if($eligibility->peptpasser == '1')
                {{$eligibility->peptrating}}
            @endif
        </td>
        <td style=" width: 2%;"></td>
        <td style="border:solid 1px black; width: 2%;" class="text-center">
            @if($eligibility->alspasser == '1')
             <b>/</b>
            @endif
        </td>
        <td style=" width: 12%;">
            ALS A&E Passer**
        </td>
        <td style=" width: 4%;">
            Rating: 
        </td>
        <td style="border-bottom: 1px solid; width: 9%;">
            @if($eligibility->peptpasser == '1')
                {{$eligibility->alsrating}}
            @endif
        </td>
        <td style=" width: 2%;"></td>
        <td style="border:solid 1px black; width: 2%;" class="text-center">
            @if($eligibility->alspasser == '1')
             <b>/</b>
            @endif
        </td>
        <td style=" width: 13%;">
            Others (Pls. Specify):
        </td>
        <td style="border-bottom: 1px solid; width: 15%;">
            {{$eligibility->others}}
        </td>
       
        <td style=" width: 6%;"></td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important; ">
    <tr>
        <td width="30%">Date of Examination/Assessment (MM/DD/YYYY):</td>
        <td width="15%" style="border-bottom: 1px solid;">
            @if($eligibility->examdate!=null) {{date('m/d/Y',strtotime($eligibility->examdate))}} @endif
        </td>
        <td width="35%">Name and Address of Community Learning Center:</td>
        <td width="15%" style="border-bottom: 1px solid;">
                {{$eligibility->centername}}
        </td>
        <td width="5%"></td>
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
        <td class="text-center" style="font-weight: bold; background-color: #bfbfbf; border: 1px solid black;">SCHOLASTIC RECORD</td>
    </tr>
</table>
<div style="width: 100%; line-height: 4px;">&nbsp;</div>
@if(count($gradelevels)>0)
    @foreach($gradelevels as $eachkey=>$gradelevel)
        <table style="width: 100%; table-layout: fixed; font-size: 9px;">
            @if( $eachkey == 2)
                <tr>
                <td colspan="6">
                        <table class="table" width="100%" style=" font-size: 10px;">
                            <tr>
                                <td width="10%">Page 2</td>
                                <td width="80%">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}}</td>
                                <td width="10%" style="text-align: right;">SF10-SHS</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif
            <thead style="border-left: 2px solid black; border-right: 2px solid black; border-top: 2px solid black">
                <tr>
                    <td colspan="6">
                        <table style="width: 100%; font-size: 10px;">
                            <tr>
                                <td style="width: 5%;">School:</td>
                                <td style="width: 40%; border-bottom: 1px solid black;">{{$gradelevel->headerinfo[0]->schoolname ?? null}}</td>
                                <td style="width: 7%;">School ID:</td>
                                <td style="width: 7%; border-bottom: 1px solid black;">{{$gradelevel->headerinfo[0]->schoolid ?? null}}</td>
                                <td style="width: 10%;">GRADE LEVEL:</td>
                                <td style="width: 6%; border-bottom: 1px solid black; text-align: center;">{{$gradelevel->romannumeral}}</td>
                                <td style="width: 2%;">SY:</td>
                                <td style="width: 13%; border-bottom: 1px solid black;">{{$gradelevel->sydesc}}</td>
                                <td style="width: 4%;">SEM:</td>
                                <td style="width: 6%; border-bottom: 1px solid black;">@if($gradelevel->semid == 1) 1st @elseif($gradelevel->semid == 2) 2nd @endif</td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 10px;">
                            <tr>
                                <td style="width: 10%;">TRACK/STRAND:</td>
                                <td style="width: 60%; border-bottom: 1px solid black;">{{$gradelevel->headerinfo[0]->trackname ?? null}} / {{$gradelevel->headerinfo[0]->strandname ?? null}}</td>
                                <td style="width: 7%;">SECTION:</td>
                                <td style="width: 23%; border-bottom: 1px solid black;">{{$gradelevel->headerinfo[0]->sectionname ?? null}}</td>
                            </tr>
                        </table>
                        <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                    </td>
                </tr>
                <tr>
                    <th rowspan="2" style="width: 10%; border: solid 1px black; background-color: #bfbfbf;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                    <th rowspan="2" style="width: 40%; border: solid 1px black; background-color: #bfbfbf;">SUBJECTS</th>
                    <th colspan="2" style="width: 10%; border: solid 1px black; background-color: #bfbfbf;">Quarter</th>
                    <th rowspan="2" style="width: 10%; border: solid 1px black; background-color: #bfbfbf;">SEM FINAL<br/>GRADE</th>
                    <th rowspan="2" style="width: 10%; border: solid 1px black; background-color: #bfbfbf;">ACTION<br/>TAKEN</th>
                </tr>
                <tr >
                    <th style="width: 8%; border: solid 1px black; background-color: #bfbfbf;">1</th>
                    <th style="width: 8%; border: solid 1px black; background-color: #bfbfbf;">2</th>
                </tr>
            </thead>
            <tbody  style="border-left: 2px solid black; border-right: 2px solid black; border-bottom: 2px solid black;  font-size: 10px !important;">
            @if(collect($gradelevel->grades)->unique('subjdesc')->count()>1)
                @php
                    $gen_ave_for_sem = 0;
                    $with_final_rating = true;
                @endphp
                @foreach(collect($gradelevel->grades)->unique('subjdesc') as $grade)
                    @php
                        // $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                        // $average = $with_final_rating ? ($grade->q1 + $grade->q2 ) / 2 : '';
                        // $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                    @endphp
                    @if($gradelevel->recordinputype == 2)
                        <tr>
                            <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                            <td style="border: 1px solid  black;">{{$grade->subjdesc}}</td>
                            <td class="text-center" style="border: solid 1px black;">{{number_format($grade->q1)}}</td>
                            <td class="text-center" style="border: solid 1px black;">{{number_format($grade->q2)}}</td>
                            <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                            <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center" style="border: solid 1px black;"><?php try{ ?> {{$grade->subjcode}} <?php }catch(\Exception $e){ ?> {{$grade['subjcode']}} <?php } ?></td>
                            <td style="border: 1px solid  black;"><?php try{ ?> {{$grade->subjdesc}} <?php }catch(\Exception $e){ ?> {{$grade['subjdesc']}} <?php } ?></td>
                            <td class="text-center" style="border: solid 1px black;"><?php try{ ?> {{$grade->q1}} <?php }catch(\Exception $e){ ?> {{$grade['q1']}} <?php } ?></td>
                            <td class="text-center" style="border: solid 1px black;"><?php try{ ?> {{$grade->q2}} <?php }catch(\Exception $e){ ?> {{$grade['q2']}} <?php } ?></td>
                            <td class="text-center" style="border: solid 1px black;"><?php try{ ?> {{$grade->finalrating}} <?php }catch(\Exception $e){ ?> {{$grade['finalrating']}} <?php } ?></td>
                            <td class="text-center" style="border: solid 1px black;"><?php try{ ?> {{$grade->remarks}} <?php }catch(\Exception $e){ ?> {{$grade['remarks']}} <?php } ?></td>
                        </tr>
                                
                    @endif
                @endforeach    
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                    @if($studinfo->id == 2161 && $gradelevel->id == 15 && $gradelevel->semid == 2)
                    
                        <tr>
                            <td class="text-center" style="border: solid 1px black;">SPECIALIZED</td>
                            <td style="border: 1px solid  black;">CREATIVE NONFINCTION: THE LITERARY ESSAY</td>
                            <td class="text-center" style="border: solid 1px black;">89</td>
                            <td class="text-center" style="border: solid 1px black;">90</td>
                            <td class="text-center" style="border: solid 1px black;">90</td>
                            <td class="text-center" style="border: solid 1px black;">PASSED</td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: solid 1px black;">SPECIALIZED</td>
                            <td style="border: 1px solid  black;">DISCIPLINE AND IDEAS IN THE APPLIED SCIENCES</td>
                            <td class="text-center" style="border: solid 1px black;">88</td>
                            <td class="text-center" style="border: solid 1px black;">92</td>
                            <td class="text-center" style="border: solid 1px black;">90</td>
                            <td class="text-center" style="border: solid 1px black;">PASSED</td>
                        </tr>
                    @endif
                @endif                
                @if($gradelevel->recordinputype == 1)
                    @if(count($gradelevel->subjaddedforauto)>0)
                        @foreach($gradelevel->subjaddedforauto as $customsubjgrade)
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
                @endif
                
            @else
                @for($x=0; $x<5; $x++)
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
            <tr style="font-weight: bold;">
                @php
                    $genave = $gradelevel->generalaverage;
                    if(count($genave)>0)
                    {
                        $overallgenavetotal += $genave[0]->finalrating;
                    }
                @endphp
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                    
                    @if($studinfo->id == 2161 && $gradelevel->id == 15 && $gradelevel->semid == 2)
                    <td colspan="4" style="text-align: right; border: solid 1px black;background-color: #bfbfbf;">General Ave. for the Semester</td>
                    <td class="text-center" style="border: solid 1px black;">89</td>
                    <td class="text-center" style="border: solid 1px black;">PASSED</td>
                    @else
                    <td colspan="4" style="text-align: right; border: solid 1px black;background-color: #bfbfbf;">General Ave. for the Semester</td>
                    <td class="text-center" style="border: solid 1px black;">@if(count($genave)>0)@if($genave[0]->finalrating>0){{$genave[0]->finalrating}}@endif @endif</td>
                    <td class="text-center" style="border: solid 1px black;">@if(count($genave)>0)@if($genave[0]->finalrating>0){{ $genave[0]->finalrating >= 75 ? 'PASSED' : 'FAILED'  }}@endif @endif</td>
                    @endif
                @else
                
                <td colspan="4" style="text-align: right; border: solid 1px black;background-color: #bfbfbf;">General Ave. for the Semester</td>
                <td class="text-center" style="border: solid 1px black;">@if(count($genave)>0)@if($genave[0]->finalrating>0){{$genave[0]->finalrating}}@endif @endif</td>
                <td class="text-center" style="border: solid 1px black;">@if(count($genave)>0)@if($genave[0]->finalrating>0){{ $genave[0]->finalrating >= 75 ? 'PASSED' : 'FAILED'  }}@endif @endif</td>
                @endif
            </tr>
        <tbody>
        </table>
        <table style="width: 100%; table-layout: fixed; font-size: 10px;">
            <tr>
                <td style="width: 10%;">REMARKS:</td>
                <td style="border-bottom: 1px solid black;" colspan="4">{{(collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->first()->remarks : '') ?? ''}}</td>
            </tr>
            <!--<tr>-->
            <!--    <td colspan="5">&nbsp;</td>-->
            <!--</tr>-->
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
                <td colspan="5" style="line-height: 15px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$gradelevel->headerinfo[0]->teachername ?? null}}</td>
                <td style="width: 5%;"></td>
                <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{(collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->first()->certncorrectname : '') ?? ''}}</td>
                <td style="width: 5%;"></td>
                <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{(collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->first()->datechecked : '') ?? ''}}</td>
            </tr>
            <tr>
                <td class="text-center">Signature of Adviser over Printed Name</td>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                <td class="text-center" colspan="3" style="font-size: 9px !important;">Signature of Authorized Person over Printed Name, Designation</td>
                @else
                <td></td>
                <td class="text-center">{{(collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->count() > 0 ? collect($eachsemsignatories)->where('levelid', $gradelevel->id)->where('semid', $gradelevel->semid)->first()->certncorrectdesc : "SHS-School Record's In-Charge") ?? "SHS-School Record's In-Charge"}}</td>
                <td></td>
                @endif
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
        <table style="width: 100%; table-layout: fixed; border: 2px solid black; text-transform: uppercase; font-size: 9px !important;" border="1">
            <tr>
                <th style="width: 10%; background-color: #bfbfbf;">INDICATE IF
                    SUBJECT IS
                    CORE, APPLIED,
                    OR
                    SPECIALIZED</th>
                <th style="width: 40%; background-color: #bfbfbf;">SUBJECTS</th>
                <th style="width: 10%; background-color: #bfbfbf;">SEM FINAL<br/>GRADE</th>
                <th style="width: 10%; background-color: #bfbfbf;">REMEDIAL<br/>CLASS<br/>MARK</th>
                <th style="width: 10%; background-color: #bfbfbf;">RECOMPUTED<br/>FINAL GRADE</th>
                <th style="width: 10%; background-color: #bfbfbf;">ACTION TAKEN</th>
            </tr>
            @if(count($gradelevel->remedialclasses)>0)
                @if(collect($gradelevel->remedialclasses)->contains('type','1'))
                    @foreach($gradelevel->remedialclasses as $remedial)
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
        <table style="width: 100%; table-layout: fixed; font-size: 10px;">
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
        @if( $eachkey == 1)
        <div style="page-break-before: always;"></div>
        @endif
    @endforeach
@endif
<table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
    <tr>
        <td style="width:20%"><strong>Track/Strand Accomplished:</strong></td>
        <td style="border-bottom: 1px solid;width:45%; text-align: center;">
            @if(collect($gradelevels)->where('withgrades','1')->count() == 4){{$footer->strandaccomplished}}@endif
        </td>
        <td style="width:20%"><strong>SHS General Average:</strong></td>
        <td style="border-bottom: 1px solid; text-align: center;">@if(collect($gradelevels)->where('withgrades','1')->count() == 4) {{number_format($overallgenavetotal/4)}}@else {{$footer->shsgenave}} @endif </td>
    </tr>
</table>
<table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
    @if($footer->honorsreceived == null)
    <tr>
        <td style="width:20%"><strong>Awards/Honors Received:</strong></td>
        <td style="border-bottom: 1px solid;width:40%">
            {{$footer->honorsreceived}}
        </td>
        <td style="width:28%"><strong>Date of SHS Graduation (MM/DD/YYYY):</strong></td>
        <td style="border-bottom: 1px solid; text-align: center;">{{$footer->shsgraduationdate}}</td>
    </tr>
    @else
    <tr>
        <td style="width:20%; vertical-align: top; paddin-top: 2px;" rowspan="2"><strong>Awards/Honors Received:</strong></td>
        <td style="width:40% vertical-align: top;" rowspan="2">
            <u>{{$footer->honorsreceived}}</u>
        </td>
        <td style="width:28% vertical-align: top;"><strong>Date of SHS Graduation (MM/DD/YYYY):</strong></td>
        <td style="border-bottom: 1px solid; text-align: center;">{{$footer->shsgraduationdate}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
    @endif
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
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width: 60%;">
                        <div style="width: 90%; border-bottom: 1px solid; text-align: center;">
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                            PANIAMOGAN, GERALD C.
                            @else
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc') MERLIE S. SABUELO, SCHOOL REGISTRAR @else  {{$footer->certifiedby ?? strtoupper(DB::table('schoolinfo')->first()->authorized)}} @endif
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
    <tr>
        <td></td>
        <td style="font-size: 11px;">
            {{$footer->copyforlower}}
        </td>
    </tr>
</table>