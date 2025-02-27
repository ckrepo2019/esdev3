<style>
    * { font-family: Arial, Helvetica, sans-serif;}
    @page { margin: 20px;}

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
</style>

@php
$guardianinfo = DB::table('studinfo')
    ->where('id',collect($student)->first()->id)
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
    ->where('studid', collect($student)->first()->id)
    ->where('levelid', collect($student)->first()->levelid)
    ->where('semid', collect($student)->first()->semid)
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
<table style="width: 100%" >
    <tr>
        <td width="5%" style="text-align: left;"></td>
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
        <td style="text-align:center; font-size: 12px; font-weight: bold;">Learner's Permanent Academic Record for Pre-school</td>
    </tr>
    <tr style="line-height: 10px;font-size: 11px;">
        <td style="text-align:center; font-weight: bold;" colspan="4">(Formerly Form 137)</td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #d6d08b; border: 1px solid black;">LEARNER'S INFORMATION</td>
    </tr>
</table>
<table class="table table-sm" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td style="width: 10%;">LAST NAME:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{collect($student)->first()->lastname}}</td>
        <td style="width: 10%;">FIRST NAME:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{collect($student)->first()->firstname}}</td>
        <td style="width: 11%;">MIDDLE NAME:</td>
        <td style="width: 19%; border-bottom: 1px solid black;">{{collect($student)->first()->middlename}}</td>
    </tr>
</table>
<table class="table table-sm" width="100%" style="font-size: 11px !important;">
    <tr>
        <td style="width: 5%;">LRN:</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{collect($student)->first()->lrn}}</td>
        <td style="width: 20%; text-align: right;">Date of Birth (MM/DD/YYYY):</td>
        <td style="width: 13%; border-bottom: 1px solid black;">{{\Carbon\Carbon::create(collect($student)->first()->dob)->isoFormat('MMMM DD, YYYY')}}</td>
        <td style="width: 5%; text-align: right;">Sex:</td>
        <td style="width: 7%; border-bottom: 1px solid black;">{{collect($student)->first()->gender}}</td>
        <td style="width: 26%;">Date of SHS Admission (MM/DD/YYYY):</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{collect($student)->first()->enlevelid == 15 ? 'April 19, 2019' : 'June 29, 2020'}}</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 11px; font-weight: bold; text-align: center;" id="table4">
    <tr>
        <td style="border: 1px solid black; background-color: #d6d08b">
            ELIGIBILITY FOR PRE-SCHOOL ENROLMENT
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<div style="width: 100%; border: 1px solid black; padding-top: 4px;">
    <table style="width: 100%; font-size: 11px;" id="table5">
        <tr style="font-style: italic;">
            <td>Credential Presented for Grade 1:</td>
            <td><input type="checkbox" name="check-1">Kinder Progress Report</td>
            <td><input type="checkbox" name="check-1">ECCD Checklist</td>
            <td><input type="checkbox" name="check-1">Kindergarten Certificate of Completion</td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 11px;" id="table6">
        <tr>
            <td style="width: 13%;">Name of School:</td>
            <td style="width: 25%; border-bottom: 1px solid black;"></td>
            <td style="width: 10%;">School ID:</td>
            <td style="border-bottom: 1px solid black;"></td>
            <td style="width: 15%;">Address of School:</td>
            <td style="width: 20%; border-bottom: 1px solid black;"></td>
        </tr>
    </table>
    <div style="width: 100%; line-height: 3px;">&nbsp;</div>
</div>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 11px;" id="table7" >
    <tr>
        <td colspan="5">Other Credential Presented</td>
    </tr>
    <tr>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="check-1">PEPT Passer &nbsp;&nbsp;&nbsp;&nbsp;Rating:<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
        <td style="width: 32%;">Date of Examination/Assessment (mm/dd/yyyy):</td>
        <td style="width: 10%; border-bottom: 1px solid black;"></td>
        <td style="width: 18%;"><input type="checkbox" name="check-1">Others (Pls. Specify):</td>
        <td style="width: 12%; border-bottom: 1px solid black;"></td>
    </tr>
</table>

<table style="width: 100%; font-size: 11px;" id="table8" >
    <tr>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Name and Address of Testing Center:</td>
        <td style="border-bottom: 1px solid black;" colspan="2"></td>
        <td style="width: 5%;">Remark:</td>
        <td style="border-bottom: 1px solid black;"></td>
    </tr>
</table>
<table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
    <tr>
        <td class="text-center" style="font-weight: bold; background-color: #d6d08b; border: 1px solid black;">SCHOLASTIC RECORD</td>
    </tr>
</table>
<div style="width: 100%; line-height: 5px;">&nbsp;</div>
<table style="width: 100%; text-align: left !important; font-size: 11px ;">
    <thead>
        <tr>
            <th colspan="3">
                Marks for the Progress Report:
            </th>
        </tr>
    </thead>
    <tr>
        <td>A- Highly Advanced Development	</td>
        <td>B- Slightly Advanced Development </td>
        <td>B- Slightly Advanced Development </td>
    </tr>
    <tr>
        <td>C - Average Development		</td>
        <td>D- Slight Delay in Development</td>
        <td>E – Significant Delay in Development</td>
    </tr>
</table>
{{-- <div style="width: 100%; page-break-inside: always; border: 1px solid black; vertical-align: top;"> --}}
    <table style="width: 100%; font-size: 10px;" border="1">
        <thead style="text-align: center;">
            <tr>
                <th style="width: 70%;"></th>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
            </tr>
        </thead>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">I. GROSS MOTOR</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Coordination of leg movements</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1B')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1B')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1B')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1B')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Coordination of arm movements</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1C')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1C')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1C')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1C')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Movement of body parts as instructed.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1D')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1D')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1D')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','1D')->first()->q4eval}}</td>
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">II. FINE MOTOR</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Coordination in the use of fingers in picking objects</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2B')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2B')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2B')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2B')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Coordination of fingers for scribbling and drawing.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2C')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2C')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2C')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2C')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Display of definite hand preference (either left or right)</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2D')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2D')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2D')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','2D')->first()->q4eval}}</td>
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">IV. RECEPTIVE LANGUAGE</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Following instructions correctly.</td>
           <td class="text-center">{{collect($checkGrades)->where('sort','3B')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3B')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3B')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3B')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Pointing family members correctly when ask to do so</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3C')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3C')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3C')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3C')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Pointing named objects correctly when ask to do so</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3D')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3D')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3D')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','3D')->first()->q4eval}}</td>
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">V. EXPRESSIVE LANGUAGE</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Using recognizable words correctly.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4B')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4B')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4B')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4B')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Naming objects and pictures correctly</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4C')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4C')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4C')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4C')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Asking questions appropriately (who, what, when, why, how?)</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4D')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4D')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4D')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4D')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Telling account of recent experiences.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4E')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4E')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4E')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','4E')->first()->q4eval}}</td>
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">VI. COGNITIVE DEVELOPMENT</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">A. WRITING READINESS</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;"> Exhibition of left to right progression.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA2')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Writing name correctly</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA3')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;"> Writing upper case or lower case letters from memory</td>
             <td class="text-center">{{collect($checkGrades)->where('sort','FA4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA4')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;"> Correctly copying shapes</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FA5')->first()->q4eval}}</td>
        </tr>
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">B. READING READINESS</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Correct Identification of objects and pictures</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB2')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Correctly identifies similarities and differences of objects and pictures</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB3')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Correct identification of upper or lower case letters from memory </td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB4')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Correctly matching objects or pictures with the alphabet</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB5')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Correctly sorting out pictures, alphabet, or shapes</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB6')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB6')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB6')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB6')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Correctly following signs and symbols</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB7')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB7')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB7')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FB7')->first()->q4eval}}</td>
        </tr>
       
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">C. LANGUAGE</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Listening attentively to someone who speaks.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC2')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;"> Correctly distinguish different type of sounds.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC3')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Responding correctly to different type of sounds.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC4')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Recalling significant facts in a story.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC5')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Expressing own thoughts, feelings and ideas.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC6')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC6')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC6')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC6')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Exhibiting comprehension of learned concepts.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC7')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC7')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC7')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC7')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Responding correctly to questions asked.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC8')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC8')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC8')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC8')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Reciting poems and verses.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC9')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC9')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC9')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FC9')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Reciting correctly numbers 1 to 10.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD2')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;"> Writing numerals 1 to 10.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD3')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Identifying correctly the number of animals, objects, or pictures</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD4')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Correct identification of shapes </td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD5')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Showing understanding on the concept of length, mass, volume/capacity</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD6')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD6')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD6')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD6')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Exhibiting interests and curiosity about the environment</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD7')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD7')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD7')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD7')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Showing interests and curiosity about living organism.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD8')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD8')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD8')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FD8')->first()->q4eval}}</td>
        </tr>
        <tr style="background-color:#cbedaf; font-weight: bold;">
            <td style="padding-left:9px;">E. MUSIC AND ARTS</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Participation in music and art related activities</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE2')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Skill in drawing, singing, dancing, and/or acting.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE3')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Exhibiting interests in music and rhythm</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE4')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">Exhibiting ideas and feelings through print or art media</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FE5')->first()->q4eval}}</td>
        </tr>
        <tr style="background-color:#8dcf5f; font-weight: bold;">
            <td style="padding-left:9px;">VII. SOCIAL, EMOTIONAL AND SPIRITUAL DEVELOPMENT</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left:9px;">1.Exhibiting concepts and feelings about self, family, school and community</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG2')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG2')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG2')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG2')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">2. Willingness to be with peers, adults and strangers.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG3')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG3')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG3')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG3')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">3. Demonstration of courtesy and respect</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG4')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG4')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG4')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG4')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">4. Correct identification of feelings of others</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG5')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG5')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG5')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG5')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">5. Showing cooperation in group situations.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG6')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG6')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG6')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG6')->first()->q4eval}}</td>
        </tr>
        <tr>
            <td style="padding-left:9px;">6. Expressing own feelings.</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG7')->first()->q1eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG7')->first()->q2eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG7')->first()->q3eval}}</td>
            <td class="text-center">{{collect($checkGrades)->where('sort','FG7')->first()->q4eval}}</td>
        </tr>
    </table>
    <br/>
<table style="width: 100%; font-size: 11px; border: 1px solid black;">
    <tr>
        <td colspan="10" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d08b; font-weight: bold;" class="text-center">
            CERTIFICATION
        </td>
    </tr>
    <tr>
        <td style="width: 3%;"></td>
        <td colspan="8" style="text-align: justify;">
            I CERTIFY that this is a true record of <u>{{collect($student)->first()->lastname}}, {{collect($student)->first()->firstname}} {{collect($student)->first()->middlename[0]}}.</u> with LRN <u>{{collect($student)->first()->lrn}}</u> and that he/she is eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>.
        </td>
        <td style="width: 3%;"></td>
    </tr>
    <tr>
        <td style="width: 3%;"></td>
        <td style="width: 10%;">School Name:</td>
        <td colspan="3" style="width: 45%; border-bottom: 1px solid black;">{{DB::table('schoolinfo')->first()->schoolname}}</td>
        <td style="width: 7%;">School ID:</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{DB::table('schoolinfo')->first()->schoolid}}</td>
        <td style="width: 6%;">Division:</td>
        <td style="width: 14%; border-bottom: 1px solid black;">{{DB::table('schoolinfo')->first()->division}}</td>
        <td style="width: 3%;"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
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
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td style="border-bottom: 1px solid black; text-align: center;">{{date('M d, Y')}}</td>
        <td>&nbsp;</td>
        <td colspan="3" style="border-bottom: 1px solid black; text-align: center;">{{strtoupper(DB::table('schoolinfo')->first()->authorized)}}</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td class="text-center">Date</td>
        <td>&nbsp;</td>
        <td colspan="3" class="text-center" style="font-size: 10px;">Name of Principal/School Head over Printed Name</td>
        <td colspan="2"style="text-align: right;">(Affix School Seal here)</td>
        <td></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
<span style="font-size: 11px;">
    May add Certification Box if needed
</span>
<span style="font-size: 11px; float: right; text-align: right; font-style: italic;">
    SFRT Revised 2017
</span>