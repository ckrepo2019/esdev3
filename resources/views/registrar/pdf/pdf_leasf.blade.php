<style>
    
    * {
        
        font-family: Arial, Helvetica, sans-serif;
    }

    .grades {
        font-family: ZapfDingbats, sans-serif;
    }

    .text-center{
        text-align: center !important;
    }
    .align-middle{
        vertical-align: middle !important;    
    }

    @page{
        size: 8.5in 13in;
        margin: 20px 40px;
    }
    table{
        border-collapse: collapse;
    }

    table{
        border-collapse: collapse;
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

    .align-middle{
        vertical-align: middle !important;    
    }

    .table-sm td, .table-sm th {
        padding: .3rem;
    }
    
</style>
@php
    if($checkIfExist > 0)
    {
        $surveyAns->a1 = DB::table('sy')->where('id', $surveyAns->a1)->first()->sydesc;
    }
@endphp
<table style="width: 100%; margin-bottom: 10px;">
    <tr>
        <th colspan="3" style="font-size: 12px;">
            <div style="width: 16%; border: 1px solid black; float: right; padding: 5px;">ANNEX A (English)</div>&nbsp;
            <br/><br/>
        </th>
    </tr>
    <tr style="font-size: 13px;">
        <th style="width: 23%; text-align: right; padding: 0px; padding-right: 20px;" rowspan="2">
            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="52px">
        </th>
        <th  style="width: 54%; padding: 0px;">
            <H4 style="margin: 0px;"><center>LEARNER ENROLLMENT AND SURVEY FORM</center></H4>
        </th>
        <th style="width: 23%; padding: 0px;" rowspan="2">
        </th>
    </tr>
    <tr style="font-size: 13px;">
        <td style="text-align: center; vertical-align: top; padding: 0px;">THIS FORM IS NOT FOR SALE</td>
    </tr>
    <tr>
        <td colspan="3" style="padding-left: 5%; font-size: 9.5px; vertical-align: top;">
            <p style="margin: 0px;">Instructions:</p>
            <p style="margin-left: 2%; margin-top: 0px; margin-bottom: 0px;">1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This enrollment survey shall be answered by the parent/guardian of the learner.</p>
            <p style="margin-left: 2%; margin-top: 0px; margin-bottom: 0px;">2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please read the questions carefully and fill in all applicable spaces and write your answers legibly in CAPITAL letters. For items not applicable, write N/A.</p>
            <p style="margin-left: 2%; margin-top: 0px; margin-bottom: 0px;">3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For questions/ certifications, please ask for the assistance of the teacher/ person-in-charge.</p>
        </td>
    </tr>
</table>
<table style="width: 100%; margin: 0px 2px;">
    <tr style="font-size: 12px; text-align: left !important; background-color: #cee5ff;">
        <th colspan="23">A. GRADE LEVEL AND SCHOOL INFORMATION</th>
    </tr>
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td style="width: 10.5%; padding-bottom: 10px;">A1. School Year</td>
        @if($checkIfExist == 0)
        <td style="width: 2.8%; border: 1.5px solid black;"></td>
        <td style="width: 2.8%; border: 1.5px solid black;"></td>
        <td style="width: 2.8%; border: 1.5px solid black;"></td>
        <td style="width: 2.8%; border: 1.5px solid black;" colspan="2"></td>
        <td style="width: 3%; text-align: center;">-</td>
        <td style="width: 2.8%; border: 1.5px solid black;"></td>
        <td style="width: 2.8%; border: 1.5px solid black;"></td>
        <td style="width: 2.8%; border: 1.5px solid black;"></td>
        <td style="width: 2.8%; border: 1.5px solid black;"></td>
        @else
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">{{$surveyAns->a1[0]}}</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">{{$surveyAns->a1[1]}}</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">{{$surveyAns->a1[2]}}</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;" colspan="2">{{$surveyAns->a1[3]}}</td>
        <td style="width: 3%; text-align: center; font-size: 12px; text-align: center;">{{$surveyAns->a1[4]}}</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">{{$surveyAns->a1[5]}}</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">{{$surveyAns->a1[6]}}</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">{{$surveyAns->a1[7]}}</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">{{$surveyAns->a1[8]}}</td>
        @endif
        <td colspan="3" style="width: 20%;">&nbsp;A2. Check the appropriate boxes only</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">@if($surveyAns->a2 != 1) <div class="grades">4</div> @endif</td>
        <td style="width: 7%; " colspan="2">&nbsp;No LRN</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">@if($surveyAns->a2 == 1) <div class="grades">4</div> @endif</td>
        <td>&nbsp;With LRN</td>
        <td style="width: 2.8%; ">A3.</td>
        <td style="width: 2.8%; border: 1.5px solid black; font-size: 12px; text-align: center;">@if($surveyAns->a3 != 1) <div class="grades">4</div> @endif</td>
        <td style="width: 10%; " colspan="2">&nbsp;Returning (Balik-Aral)</td>
    </tr>
    <tr>
        <td colspan="23" style="line-height: 5px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td colspan="4" style="border-right: 1px solid black !important;">A4. Grade Level to enroll:</td>
        <td style="width: 1.4% !important;">&nbsp;</td>
        <td colspan="8" style="width: 13%;">A7. Last School Attended:</td>
        <td style="width: 8%;">A8. School ID:</td>
        <td style=" border-right: 1px solid black;"></td>
        <td style="width: 2% !important;">&nbsp;</td>
        <td colspan="6">A11. School to enroll in:</td>
        <td style="width: 10%;">A12. School ID:</td>
    </tr>
    <tr style="line-height: 5px;">
        <td colspan="3">&nbsp;</td>
        <td style=" border-right: 1px solid black !important;"></td>
        <td></td>
        <td colspan="7"></td>
        <td></td>
        <td></td>
        <td style=" border-right: 1px solid black !important;"></td>
        <td></td>
        <td colspan="5"></td>
        <td></td>
        <td></td>
    </tr>
    {{-- if($surveyAns->a5 != null){{DB::table('gradelevel')->where('id',$surveyAns->a5)->first()->levelname}}@endif --}}
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td colspan="3" style="border-bottom: 1px solid black; font-size: 12px; ">{{$surveyAns->a4}}</td>
        <td style=" border-right: 1px solid black !important; font-size: 12px; "></td>
        <td></td>
        <td colspan="7" style="border-bottom: 1px solid black; font-size: 8.5px; ">{{$surveyAns->a7}}</td>
        <td></td>
        <td style="border-bottom: 1px solid black;">{{$surveyAns->a8}}</td>
        <td style=" border-right: 1px solid black !important; font-size: 12px; "></td>
        <td></td>
        <td colspan="5" style="border-bottom: 1px solid black; font-size: 8.5px; ">{{$surveyAns->a11}}</td>
        <td></td>
        <td style="border-bottom: 1px solid black; font-size: 12px; ">{{$surveyAns->a12}}</td>
    </tr>
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td colspan="4" style="border-right: 1px solid black !important;">A5. Last grade elvel completed:</td>
        <td style="width: 1.4% !important;">&nbsp;</td>
        <td colspan="9">A9. School Address:</td>
        <td style=" border-right: 1px solid black;"></td>
        <td style="width: 2% !important;">&nbsp;</td>
        <td colspan="7">A13. School Address:</td>
    </tr>
    <tr style="line-height: 5px;">
        <td colspan="3">&nbsp;</td>
        <td style=" border-right: 1px solid black !important;"></td>
        <td></td>
        <td colspan="9"></td>
        <td style=" border-right: 1px solid black !important;"></td>
        <td></td>
        <td colspan="7"></td>
    </tr>
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td colspan="3" style="border-bottom: 1px solid black; font-size: 12px; vertical-align: bottom;">@if($surveyAns->a5 != null){{DB::table('gradelevel')->where('id',$surveyAns->a5)->first()->levelname}}@endif</td>
        <td style=" border-right: 1px solid black !important;"></td>
        <td></td>
        <td colspan="9" style="border-bottom: 1px solid black; font-size: 8.5px; vertical-align: bottom;">{{$surveyAns->a9}}</td>
        <td style=" border-right: 1px solid black !important;"></td>
        <td></td>
        <td colspan="7" style="border-bottom: 1px solid black; font-size: 8.5px; ">{{$surveyAns->a13}}</td>
    </tr>
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td colspan="4" style="border-right: 1px solid black !important;">A6. last school year completed:</td>
        <td style="width: 1.4% !important;">&nbsp;</td>
        <td colspan="18">A10. School type:</td>
    </tr>
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td colspan="3" style="border-bottom: 1px solid black; font-size: 11px;">{{$surveyAns->a6}}</td>
        <td style=" border-right: 1px solid black !important;"></td>
        <td></td>
        <td></td>
        <td style="border: 1px solid black; font-size: 12px; text-align: center;">@if($surveyAns->a10 == 1 ) <div class="grades">4</div> @endif</td>
        <td colspan="2">&nbsp;Public</td>
        <td>

        </td>
        <td style="border: 1px solid black; font-size: 12px; text-align: center;">@if($surveyAns->a10 == 2 ) <div class="grades">4</div> @endif</td>
        <td>&nbsp;Private</td>
        <td colspan="11">

        </td>
    </tr>
    <tr>
        <td colspan="23" style="line-height: 8px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 8px; vertical-align: top;">
        <th colspan="23" style="text-align: left;">FOR SENIOR HIGH SCHOOL ONLY:</th>

    </tr>
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td colspan="7">A14. Semester (1<sup>st</sup>/2<sup>nd</sup>):</td>
        <td colspan="7">A15. Track:</td>
        <td colspan="9">A16. Strand (if any):</td>
    </tr>
    <tr>
        <td colspan="23" style="line-height: 8px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 8.5px; vertical-align: top;">
        <td colspan="4" style="border-bottom: 1px solid black; font-size: 11px;">@if($surveyAns->a14 != null ) {{DB::table('semester')->where('id', $surveyAns->a14)->first()->semester}} @endif</td>
        <td colspan="3"></td>
        <td colspan="6" style="border-bottom: 1px solid black;; font-size: 11px;">{{$surveyAns->a15}}</td>
        <td></td>
        <td colspan="9" style="border-bottom: 1px solid black; font-size: 11px;">{{$surveyAns->a16}}</td>
    </tr>
</table>
<br/>
@php
$lrn = preg_replace("/[^A-Za-z0-9.!?]/","",$surveyAns->b2);
$countlrn  = strlen($lrn);
@endphp
<table style="width: 100%; table-layout: fixed; margin: 0px 2px;">
    <tr style="font-size: 12px; text-align: left !important; background-color: #cee5ff;">
        <th colspan="17">B. STUDENT INFORMATION</th>
    </tr>
    <tr>
        <td colspan="17" style="line-height: 10px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 8.5px">
        <td style="width: 2%;"></td>
        <td style="width: 17%;">B1. PSA Birth Certificate No. (If available upon enrolment)</td>
        <td style="width: 23%; border: 1.5px solid black; font-size: 12px;">{{$surveyAns->b1}}</td>
        <td style="width: 17%; padding-left: 7px;">B2. learner Reference Number (LRN)</td>
        @if($countlrn == 0)
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        <td style="border: 1.5px solid black;"></td>
        @else
            @for($x = 0; $x <= ($countlrn-1); $x++)
                <td style="border: 1.5px solid black; font-size: 12px; text-align: center;">{{$lrn[$x]}}</td>
            @endfor
        @endif
        <td style="width: 2%;"></td>
    </tr>
</table>
@php
$lastname = $surveyAns->b3;
$countlastname  = strlen($lastname);
$firstname = $surveyAns->b4;
$countfirstname  = strlen($firstname);
$middlename = $surveyAns->b5;
$countmiddlename  = strlen($middlename);
@endphp
<table style="width: 100%; table-layout: fixed; margin: 10px 2px;">
    <tr style="font-size: 12px; text-align: left !important; background-color: #cee5ff;">
        <td colspan="26" style="line-height: 8px;">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="26" style="line-height: 8px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 13px;">
        <td style="width: 2%;"></td>
        <td style="width: 17%; padding: 3px;">B3. LAST NAME</td>        
        <td style="width: 11%;"></td>
        @if($countlastname == 0)
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
        @else
            @for($x = 0; $x <= ($countlastname-1); $x++)
                <td style="border: 1.5px solid black; font-size: 12px; text-align: center;">{{$lastname[$x]}}</td>
            @endfor
            @for($x = $countlastname; $x < 22; $x++)
            <td style="border: 1.5px solid black;"></td>
            @endfor
        @endif
        <td style="width: 2%;"></td> 
    </tr>
    <tr>
        <td colspan="26" style="line-height: 8px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 13px;">
        <td style="width: 2%;"></td>
        <td style="width: 17%; padding: 3px;">B4. FIRST NAME</td>        
        <td style="width: 11%;"></td>
        @if($countfirstname == 0)
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
        @else
            @for($x = 0; $x <= ($countfirstname-1); $x++)
                <td style="border: 1.5px solid black; font-size: 12px; text-align: center;">{{$firstname[$x]}}</td>
            @endfor
            @for($x = $countfirstname; $x < 22; $x++)
            <td style="border: 1.5px solid black;"></td>
            @endfor
        @endif
        <td style="width: 2%;"></td> 
    </tr>
    <tr>
        <td colspan="26" style="line-height: 8px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 13px;">
        <td style="width: 2%;"></td>
        <td style="width: 17%; padding: 3px;">B5. MIDDLE NAME</td>        
        <td style="width: 11%;"></td>
        @if($countmiddlename == 0)
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
            <td style="border: 1.5px solid black;"></td>
        @else
            @for($x = 0; $x <= ($countmiddlename-1); $x++)
                <td style="border: 1.5px solid black; font-size: 12px; text-align: center;">{{$middlename[$x]}}</td>
            @endfor
            @for($x = $countmiddlename; $x < 22; $x++)
            <td style="border: 1.5px solid black;"></td>
            @endfor
        @endif
        <td style="width: 2%;"></td> 
    </tr>
    <tr>
        <td colspan="26" style="line-height: 5px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 11px;">
        <td style="width: 2%;"></td>
        <td style="padding: 3px;" colspan="4">B6. EXTENSION NAME e.g. Jr., III (if applicable)</td>   
        <td style="border-bottom: 1px solid black;" colspan="13">{{$surveyAns->b6}}</td>   
        <td colspan="7"></td>
    </tr>
</table>
@php
$dob = null;
if($surveyAns->b7 != null)
{
    $dob = date('m/d/Y', strtotime($surveyAns->b7));
}
@endphp
<table style="width: 100%; margin: 20px 2px; table-layout: fixed;">
    <tr>
        <td style="width: 52%; padding: 0px; vertical-align: top;">
            <table style="width: 100%; table-layout: fixed;">
                <tr style="font-size: 11px;">
                    <td style="width: 5%;"></td>
                    <td style="text-align: justify; width: 30%;" colspan="3">B7. Date of Birth<br/>(Month/Day/Year)</td>   
                    @if($dob == null)
                    <td style="width: 5.6%; border: 1px solid black;"></td>
                    <td style="width: 5.6%; border: 1px solid black;" colspan="2"></td>
                    <td style="width: 5.6%; border: 1px solid black; text-align: center;">/</td>
                    <td style="width: 5.6%; border: 1px solid black;"></td>
                    <td style="width: 5.6%; border: 1px solid black;"></td>
                    <td style="width: 5.6%; border: 1px solid black; text-align: center;" colspan="2">/</td>
                    <td style="width: 5.6%; border: 1px solid black;"></td>
                    <td style="width: 5.6%; border: 1px solid black;"></td>
                    <td style="width: 5.6%; border: 1px solid black;"></td>
                    <td style="width: 5.6%; border: 1px solid black;"></td>
                    @else
                        @for($x = 0; $x < strlen($dob); $x++)
                            @if($x == 1 || $x == 5)
                            <td style="width: 5.6%; border: 1px solid black; text-align: center;" colspan="2">{{$dob[$x]}}</td>
                            @else
                            <td style="width: 5.6%; border: 1px solid black; text-align: center;">{{$dob[$x]}}</td>
                            @endif
                        @endfor
                    @endif
                    <td style="width: 10%;"></td>
                </tr>
                <tr>
                    <td colspan="17" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td style="padding: 3px; width: 15%; text-align: justify;">B8. Age</td>   
                    <td style="border: 1px solid black; text-align: center;">@if($dob != null){{\Carbon\Carbon::parse($surveyAns->b7)->age}}@endif</td>
                    <td style="text-align: right;" colspan="3">B9. Sex&nbsp;&nbsp;&nbsp;</td>   
                    <td style="border: 1px solid black; text-align: center;" colspan="2">@if($surveyAns->b9 != null)@if(strtolower($surveyAns->b9[0]) == 'm') <div class="grades">4</div> @endif @endif</td>
                    <td style="text-align: justify; text-align: center;" colspan="3">&nbsp;Male</td>   
                    <td style="border: 1px solid black;" colspan="2" class="text-center">@if($surveyAns->b9 != null)@if(strtolower($surveyAns->b9[0]) == 'f') <div class="grades">4</div> @endif @endif</td>
                    <td style="text-align: justify; text-align: center;" colspan="3">Female</td> 
                    <td></td>
                </tr>
                <tr>
                    <td colspan="17" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td colspan="11" style="padding: 3px;text-align: justify;">B10. Belonging to Indigenous Peoples (IP) Community/Indigenous Cultural Community</td>
                    <td style="border: 1px solid black; text-align: center;">@if($surveyAns->b10 == 1)<div class="grades">4</div>@endif</td>
                    <td colspan="2">&nbsp;&nbsp;Yes</td>
                    <td style="border: 1px solid black; text-align: center;">@if($surveyAns->b10 == 2)<div class="grades">4</div>@endif</td>
                    <td>&nbsp;&nbsp;No</td>
                </tr>
                <tr>
                    <td colspan="17" style="line-height: px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td colspan="4" style="padding: 3px;text-align: justify;">B11. If yes, please specify:</td>
                    <td colspan="6" style="border-bottom: 1px solid black;" >{{$surveyAns->b11}}</td>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td colspan="17" style="line-height: 5px; padding: 0px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td colspan="3" style="padding: 3px;">B12. Mother Tongue:</td>
                    <td colspan="12" style="border-bottom: 1px solid black;" >{{$surveyAns->b12}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="17" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td colspan="2" style="padding: 3px;">B13. Religion:</td>
                    <td colspan="13" style="border-bottom: 1px solid black;" >{{$surveyAns->b13}}</td>
                    <td></td>
                </tr>
            </table>
        </td>
        <td style="width: 48%; font-size: 10.5px; vertical-align: top;">
            <table style="width: 100%; margin: 0px;">
                <tr style="text-align: left !important; background-color: #cee5ff;">
                    <th colspan="5" style="text-align: left; padding-left: 5px; border-bottom: 0.5px solid black;">For Learners with Special Education Needs</th>
                </tr>
                <tr>
                    <td colspan="5" style=" padding-left: 5px; text-align: justify;">B14. Does the learner have special education needs? (i.e. physical, mental, development disability, medical condition, giftedness, among others)</td>
                </tr>
                <tr>
                    <td style="width: 1%;padding: 0px;"></td>
                    <td style="width: 1%;padding: 0px; border: 0.5px solid black;" class="text-center align-middle">
                        @if($surveyAns->b14 == 1)<div class="grades">4</div>@endif
                    </td>
                    <td style="width: 15%;padding: 0px;">&nbsp;&nbsp;Yes

                    </td>
                    <td style="width: 1%;padding: 0px; border: 0.5px solid black;" class="text-center align-middle">
                        @if($surveyAns->b14 == 2)<div class="grades">4</div>@endif
                    </td>
                    <td style="padding: 0px;">
                        &nbsp;&nbsp; No
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding: 0px; padding-left: 5px; padding-top: 5px;border-bottom: 0.5px solid black;">B15. If yes, please specify: {{$surveyAns->b15}}</td>
                </tr>
                <tr>
                    <td colspan="5" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" style="padding: 0px; padding-left: 5px; text-align: justify;">B16. Do you have any assistive-technology devices available at home? (i.e. screen reader, Braille, DAISY)</td>
                </tr>
                <tr>
                    <td style="width: 1%;padding: 0px;"></td>
                    <td style="width: 1%;padding: 0px; border: 0.5px solid black;" class="text-center align-middle">
                        @if($surveyAns->b16 == 1)<div class="grades">4</div>@endif
                    </td>
                    <td style="width: 15%;padding: 0px;">&nbsp;&nbsp;Yes

                    </td>
                    <td style="width: 1%;padding: 0px; border: 0.5px solid black;" class="text-center align-middle">
                        @if($surveyAns->b16 == 2)<div class="grades">4</div>@endif
                    </td>
                    <td style="padding: 0px;">
                        &nbsp;&nbsp; No
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding: 0px; padding-left: 5px; padding-top: 5px;">B17. If yes, please specify:</td>
                </tr>
                <tr>
                    <td colspan="5" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5" style="border-bottom: 0.5px solid black;">{{$surveyAns->b17}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 0px;">
            <table style="width: 100%; table-layout: fixed; margin: 5px 0px 0px 20px;">
                <tr style="font-size: 12px; text-align: left !important; background-color: #cee5ff;">
                    <th colspan="3">ADDRESS</th>
                </tr>
                <tr style="font-size: 10.5px;">
                    <td style="padding: 3px;">B18. House Number and Street</td>
                    <td style="padding: 3px;">B19. Subdivision/ Village/ Zone</td>
                    <td style="padding: 3px;">B20. Barangay</td>
                </tr>
                <tr>
                    <td colspan="3" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 10.5px;">
                    <td style="border-bottom: 1px solid black;">{{$surveyAns->b18}}</td>
                    <td style="border-bottom: 1px solid black;">{{$surveyAns->b23}}</td>
                    <td style="border-bottom: 1px solid black;">{{$surveyAns->b19}}</td>
                </tr>
                <tr style="font-size: 10.5px;">
                    <td style="padding: 3px;">B21. City/ Municipality</td>
                    <td style="padding: 3px;">B22. Province</td>
                    <td style="padding: 3px;">B23. Region</td>
                </tr>
                <tr>
                    <td colspan="3" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 10.5px;">
                    <td style="border-bottom: 1px solid black;">{{$surveyAns->b20}}</td>
                    <td style="border-bottom: 1px solid black;">{{$surveyAns->b21}}</td>
                    <td style="border-bottom: 1px solid black;">{{$surveyAns->b22}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin: 0px 2px; page-break-inside: always;">
    <tr style="font-size: 12px; text-align: left !important; background-color: #cee5ff;">
        <th colspan="3" style="">C. PARENT / GUARDIAN INFORMATION</th>
    </tr>
    <tr style="font-size: 10px;">
        <th style="width: 33.3%;">Father</th>
        <th style="width: 33.3%;">Mother</th>
        <th style="width: 33.3%;">Guardian</th>
    </tr>
    <tr style="font-size: 8.5px;">
        <td style="padding: 0px 8px;  border-bottom: 1px solid black; border-right: 1px solid black;">C1. Full Name (last name, first name, middle name)<br/>&nbsp;<br/>{{$surveyAns->c1}}</td>
        <td style="padding: 0px 8px; border-bottom: 1px solid black; border-right: 1px solid black;">C6. Full Maiden Name (last name, first name, middle name)<br/>&nbsp;<br/>{{$surveyAns->c7}}</td>
        <td style="padding: 0px 8px; border-bottom: 1px solid black;">C11. Full Name (last name, first name, middle name)<br/>&nbsp;<br/>{{$surveyAns->c13}}</td>
    </tr>
    <tr style="font-size: 9px;">
        <td style="padding: 0px 8px; border-bottom: 1px solid black; border-right: 1px solid black;">
           
            <table style="width: 100%; font-size: 9px !important;  margin-bottom:0" class="table table-sm">
                <tr>
                    <td style="padding: 6px 0 !important"> C2. Highest Educational Attainment</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 9px !important;" class="table table-sm">
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c2 == 1)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Elementary graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c2 == 21)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">School graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c2 == 3)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding: 2px 6px !important">College graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c2 == 4)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Vocational</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c2 == 5)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Master's/Doctorate degree</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c2 == 6)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Did not attend school</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">&nbsp;&nbsp;</td>
                    <td style="padding:  2px 6px !important">Others: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                </tr>
            </table>&nbsp;
        </td>
        <td style="padding: 0px 8px; border-bottom: 1px solid black; border-right: 1px solid black;">
            <table style="width: 100%; font-size: 9px !important;  margin-bottom:0" class="table table-sm">
                <tr>
                    <td style="padding: 6px 0 !important"> C7. Highest Educational Attainment</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 9px !important;" class="table table-sm">
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c8 == 1)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Elementary graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c8 == 2)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">High School graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c8 == 3)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">College graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c8 == 4)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Vocational</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c8 == 5)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Master's/Doctorate degree</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c8 == 6)<div class="grades">4</div>@else &nbsp;&nbsp;@endif</td>
                    <td style="padding:  2px 6px !important">Did not attend school</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle"></td>
                    <td style="padding:  2px 6px !important">Others: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                </tr>
            </table>&nbsp;
        </td>
        <td style="padding: 0px 8px; border-bottom: 1px solid black;">
            <table style="width: 100%; font-size: 9px !important;  margin-bottom:0" class="table table-sm">
                <tr>
                    <td style="padding: 6px 0 !important"> C12. Highest Educational Attainment</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 9px !important;" class="table table-sm">
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c14 == 1)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Elementary graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c14 == 2)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">High School graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c14 == 3)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">College graduate</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c14 == 4)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Vocational</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c14 == 5)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Master's/Doctorate degree</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">@if($surveyAns->c14 == 6)<div class="grades">4</div>@else &nbsp;&nbsp; @endif</td>
                    <td style="padding:  2px 6px !important">Did not attend school</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important"  class="text-center align-middle">&nbsp;&nbsp;</td>
                    <td style="padding:  2px 6px !important">Others: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                </tr>
            </table>&nbsp;
        </td>
    </tr>
    <tr style="font-size: 9px;">
        <td style="padding: 0px 8px; border-bottom: 1px solid black; border-right: 1px solid black;">
            <table style="width: 100%; font-size: 9px !important; margin-bottom: 0 !important" class="table table-sm">
                <tr>
                    <td style="padding: 6px 0 !important">C3. Employment Status</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 9px !important; margin-bottom: 0 !important" class="table table-sm">
                <tr>
                    <td style="width: 8%; border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c3 == 1)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Full time</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c3 == 2)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Part time</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c3 == 3)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Self-employed (i.e. family business)</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c3 == 4)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Unemployed due to community quarantine</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c3 == 5)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Not working</td>
                </tr>
                <tr>
                    <td colspan="4"  style="padding: 0 !important">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 0 !important">C4. Working from home due to community quarantine?</td>
                </tr>
                <tr>
                    <td colspan="4"  style="padding: 0 !important">&nbsp;</td>
                </tr>
               
            </table>
            <table style="width: 100%; font-size: 9px !important;" class="table table-sm">
                <tr>
                    <td  style="width: 8%; border: 1px solid black; padding:  0 !important" class="text-center align-middle">@if($surveyAns->c4 == 1)<div class="grades">4</div>@endif</td>
                    <td  style="width: 20%; padding:  2px 6px !important">Yes</td>
                    <td  style="width: 8%; border: 1px solid black; padding:  0 !important" class="text-center align-middle">@if($surveyAns->c4 == 2)<div class="grades">4</div>@endif</td>
                    <td  style=" width: 64%; padding:  2px 6px !important"> No</td>
                </tr>
            </table>
        </td>
        <td style="padding: 0px 8px; border-bottom: 1px solid black; border-right: 1px solid black;">
            <table style="width: 100%; font-size: 9px !important; margin-bottom: 0 !important" class="table table-sm">
                <tr>
                    <td style="padding: 6px 0 !important">C8. Employment Status</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 9px !important; margin-bottom: 0 !important" class="table table-sm">
                <tr>
                    <td style="width: 8%; border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c8 == 1)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Full time</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c8 == 2)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Part time</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c8 == 3)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Self-employed (i.e. family business)</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c8 == 4)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Unemployed due to community quarantine</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c8 == 5)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Not working</td>
                </tr>
                <tr>
                    <td colspan="4"  style="padding: 0 !important">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 0 !important">C9. Working from home due to community quarantine?</td>
                </tr>
                <tr>
                    <td colspan="4"  style="padding: 0 !important">&nbsp;</td>
                </tr>
               
            </table>
            <table style="width: 100%; font-size: 9px !important;" class="table table-sm">
                <tr>
                    <td  style="width: 8%; border: 1px solid black; padding:  0 !important" class="text-center align-middle">@if($surveyAns->c10 == 1)<div class="grades">4</div>@endif</td>
                    <td  style="width: 20%; padding:  2px 6px !important">Yes</td>
                    <td  style="width: 8%; border: 1px solid black; padding:  0 !important" class="text-center align-middle">@if($surveyAns->c10 == 2)<div class="grades">4</div>@endif</td>
                    <td  style=" width: 64%; padding:  2px 6px !important"> No</td>
                </tr>
            </table>
        </td>
        <td style="padding: 0px 8px; border-bottom: 1px solid black; border-right: 1px solid black;">
            <table style="width: 100%; font-size: 9px !important; margin-bottom: 0 !important" class="table table-sm">
                <tr>
                    <td style="padding: 6px 0 !important">C13. Employment Status</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 9px !important; margin-bottom: 0 !important" class="table table-sm">
                <tr>
                    <td style="width: 8%; border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c14 == 1)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Full time</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c14 == 2)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Part time</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c14 == 3)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Self-employed (i.e. family business)</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c14 == 4)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Unemployed due to community quarantine</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 0 !important" class="text-center align-middle">@if($surveyAns->c14 == 5)<div class="grades">4</div>@endif</td>
                    <td colspan="3" style="padding:  2px 6px !important">Not working</td>
                </tr>
                <tr>
                    <td colspan="4"  style="padding: 0 !important">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 0 !important">C14. Working from home due to community quarantine?</td>
                </tr>
                <tr>
                    <td colspan="4"  style="padding: 0 !important">&nbsp;</td>
                </tr>
               
            </table>
            <table style="width: 100%; font-size: 9px !important;" class="table table-sm">
                <tr>
                    <td  style="width: 8%; border: 1px solid black; padding:  0 !important" class="text-center align-middle">@if($surveyAns->c16 == 1)<div class="grades">4</div>@endif</td>
                    <td  style="width: 20%; padding:  2px 6px !important">Yes</td>
                    <td  style="width: 8%; border: 1px solid black; padding:  0 !important" class="text-center align-middle">@if($surveyAns->c16 == 2)<div class="grades">4</div>@endif</td>
                    <td  style=" width: 64%; padding:  2px 6px !important"> No</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="font-size: 8.5px;">
        <td style="padding: 0px 8px;  border-bottom: 1px solid black; border-right: 1px solid black;">C5. Contact number/s (cellphone/ telephone)<br/>&nbsp;{{$surveyAns->c5}}</td>
        <td style="padding: 0px 8px; border-bottom: 1px solid black; border-right: 1px solid black;">C10. Contact number/s (cellphone/ telephone)<br/>&nbsp;{{$surveyAns->c11}}</td>
        <td style="padding: 0px 8px; border-bottom: 1px solid black;">C15. Contact number/s (cellphone/ telephone)<br/>&nbsp;{{$surveyAns->c17}}</td>
    </tr>
    <tr style="font-size: 8.5px;">
        <td colspan="3" style="padding: 0px 8px;">
            <table style="width: 100%; margin: 0px;">
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width: 25%;">C16. Is your family a beneficiary of 4Ps?</td>
                    <td style="width: 2%; border: 1px solid black; text-align: center;">@if($surveyAns->c16 == 1)<div class="grades">4</div>@endif</td>
                    <td style="width: 5%;">&nbsp;&nbsp;Yes</td>
                    <td style="width: 2%; border: 1px solid black; text-align: center;">@if($surveyAns->c16 == 2)<div class="grades">4</div>@endif</td>
                    <td>&nbsp;&nbsp;No</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="width: 100%; table-layout: fixed; margin: 10px 2px;">
    <tr style="font-size: 12px; text-align: left !important; background-color: #cee5ff;">
        <th>&nbsp;&nbsp;D. HOUSEHOLD CAPACITY AND ACCESS TO DISTANCE LEARNING</th>
    </tr>
    <tr style="font-size: 11px;">
        <td>&nbsp;&nbsp;D1. How does your child go to school? Choose all the applies.</td>
    </tr>
    <tr>
        <td style="line-height: 5px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 11.5px;">
        <td>
            @php
                $d1  = explode(" ",$surveyAns->d1)
            @endphp
            <table style="width: 100%;">
                <tr>
                    <td style="width: 1%;"></td>
                    <td style="width: 2%; border: 1px solid black; text-align: center;">@if(collect($d1)->contains(1))<div class="grades">4</div>@endif</td>
                    <td style="width: 8%;"> &nbsp;&nbsp;walking</td>
                    <td style="width: 2%; border: 1px solid black; text-align: center;">@if(collect($d1)->contains(2))<div class="grades">4</div>@endif</td>
                    <td style="width: 22%;"> &nbsp;&nbsp;public commute (land/water)</td>
                    <td style="width: 2%; border: 1px solid black; text-align: center;">@if(collect($d1)->contains(3))<div class="grades">4</div>@endif</td>
                    <td style="width: 17%;"> &nbsp;&nbsp;family-owned vehicle</td>
                    <td style="width: 2%; border: 1px solid black; text-align: center;">@if(collect($d1)->contains(4))<div class="grades">4</div>@endif</td>
                    <td style="width: 34%;"> &nbsp;&nbsp;school service</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="line-height: 5px;">&nbsp;</td>
    </tr>
    <tr style="font-size: 11.5px;">
        <td style="vertical-align: top; padding-left: 5px;">
            <table style="width: 100%;">
                <tr>
                    
                    <td style="width: 50%; border-right: 1px solid black; vertical-align: top;">
                        D2. How many of your household members (including the enrollee) are studying in School Year {{$surveyAns->a1}}? Please specify each.
                        <table style="width: 100%;">
                            <tr>
                                <td></td>
                                <td>Kinder</td>
                                <td style="width:10%"></td>
                                <td>Grade 4</td>
                                <td style="width:10%"></td>
                                <td>Grade 8</td>
                                <td style="width:10%"></td>
                                <td>Grade 12</td>
                                <td style="width:10%"></td>
                            </tr>
                            <tr>
                                <td colspan="9" style="line-height: 5px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Grade 1</td>
                                <td style="width:10%"></td>
                                <td>Grade 5</td>
                                <td style="width:10%"></td>
                                <td>Grade 9</td>
                                <td style="width:10%"></td>
                                <td colspan="2"><em>Others (ie college, vocational, etd.)</em></td>
                            </tr>
                            <tr>
                                <td colspan="9" style="line-height: 5px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Grade 2</td>
                                <td style="width:10%"></td>
                                <td>Grade 6</td>
                                <td style="width:10%"></td>
                                <td colspan="2">Grade 10</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="9" style="line-height: 5px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Grade 3</td>
                                <td style="width:10%"></td>
                                <td>Grade 7</td>
                                <td style="width:10%"></td>
                                <td colspan="2">Grade 11</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="9" style="line-height: 5px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td colspan="3"></td>
                            </tr>
                        </table>
                    </td>
                    @php
                        $d3  = explode(" ",$surveyAns->d3)
                    @endphp
                    <td style="width: 50%; padding-left: 10px; vertical-align: top;">
                        D3. Who among the household members can provide instructional support to the child's distance learning? Choose all that applies.<br/>&nbsp;
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d3)->contains(1))<div class="grades">4</div>@endif</td>
                                <td style="width: 40%;">&nbsp;parents/ guardians</td>
                                <td></td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d3)->contains(5))<div class="grades">4</div>@endif</td>
                                <td style="width: 40%;">&nbsp;others (tutor, house helper)</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d3)->contains(2))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;elder siblings</td>
                                <td></td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d3)->contains(4))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;none</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d3)->contains(3))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;grandparents</td>
                                <td></td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d3)->contains(7))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">able to do independent learning</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d3)->contains(4))<div class="grades">4</div>@endif</td>
                                <td colspan="4">&nbsp;extended members of the family</td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr style="font-size: 11.5px;">
        <td style="vertical-align: top; padding-left: 5px;">
            <table style="width: 100%;">
                <tr>
                    @php
                        $d4  = explode(" ",$surveyAns->d4)
                    @endphp
                    <td style="width: 35%; border-right: 1px solid black; vertical-align: top; padding-left: 5px;">
                        D4. What devices are available at home that the learner can use for learning? Check all that applies.
                        <table style="width: 100%; margin-top: 10px;">
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(1))<div class="grades">4</div>@endif</td>
                                <td style="width: 40%;">&nbsp;cable TV</td>
                                <td></td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(6))<div class="grades">4</div>@endif</td>
                                <td style="width: 40%;">&nbsp;radio</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(2))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;non-cable TV</td>
                                <td></td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(7))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;desktop computer</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(3))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;basic cellphone</td>
                                <td></td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(8))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">laptop</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(4))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;smartphone</td>
                                <td></td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(9))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">none</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d4)->contains(5))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;tablet</td>
                                <td></td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(isset($surveyAns->d4others))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">others:<u>&nbsp;&nbsp;&nbsp;{{$surveyAns->d4others}}</u></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 25%; border-right: 1px solid black; vertical-align: top; padding-left: 10px;">
                        D5. Do you have a way to connect to the internet?<br/>&nbsp;
                        <table style="width: 100%; margin-top: 10px;">
                            <tr>
                                <td style="width: 10%; border: 1px solid black; text-align: center;">@if($surveyAns->d5 == 1)<div class="grades">4</div>@endif</td>
                                <td>&nbsp;Yes</td>
                            </tr>
                            <tr>
                                <td style="width: 10%; border: 1px solid black; text-align: center;">@if($surveyAns->d5 == 2)<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">No<br/>(If NO, proceed to D7)</td>
                            </tr>
                        </table>
                    </td>
                    @php
                        $d6  = explode(" ",$surveyAns->d6)
                    @endphp
                    <td style="width: 40%; padding-left: 10px; vertical-align: top;">
                        D6. How do you connect to the internet? Choose all that applies.<br/>&nbsp;
                        <table style="width: 100%; margin-top: 10px;">
                            <tr>
                                <td style="width: 8%; border: 1px solid black; text-align: center;">@if(collect($d6)->contains(1))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;own mobile date</td>
                            </tr>
                            <tr>
                                <td style="width: 8%; border: 1px solid black; text-align: center;">@if(collect($d6)->contains(4))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">own broadband internet (DSL, wireless fiber, satellite)</td>
                            </tr>
                            <tr>
                                <td style="width: 8%; border: 1px solid black; text-align: center;">@if(collect($d6)->contains(2))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">computer shop</td>
                            </tr>
                            <tr>
                                <td style="width: 8%; border: 1px solid black; text-align: center;">@if(collect($d6)->contains(3))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">other places outside the home with internet connection (library, barangay/ municipal hall, neighbor, relatives)</td>
                            </tr>
                            <tr>
                                <td style="width: 8%; border: 1px solid black; text-align: center;">@if(collect($d6)->contains(5))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">none</td>
                            </tr>
                        </table>
                        <br/>&nbsp;
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    {{-- <tr>
        <td style="line-height: 5px;">&nbsp;</td>
    </tr> --}}
    <tr style="font-size: 11.5px;">
        <td style="vertical-align: top; padding-left: 5px;">
            <table style="width: 100%;">
                <tr>
                    @php
                        $d7  = explode(" ",$surveyAns->d7)
                    @endphp
                    <td style="width: 38%; border-right: 1px solid black; vertical-align: top;">
                        D7. What distance learning modality/ies do you prefer for your child? Chooose all that applies.
                        <table style="width: 100%; margin-top: 10px; margin-left: 5px;">
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d7)->contains(1))<div class="grades">4</div>@endif</td>
                                <td style="width: 28%; padding-left: 5px;">online learning</td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d7)->contains(4))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;modular learning</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d7)->contains(2))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;television</td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d7)->contains(5))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">combination of face to face with other modalities</td>
                            </tr>
                            <tr>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(collect($d7)->contains(3))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;radio</td>
                                <td style="width: 6%; border: 1px solid black; text-align: center;">@if(isset($surveyAns->d7others))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">others: <br/><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$surveyAns->d7others}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                            </tr>
                        </table>
                    </td>
                    @php
                        $d8  = explode(" ",$surveyAns->d8)
                    @endphp
                    <td style="width: 62%; padding-left: 10px; vertical-align: top;">
                        D8. What are the challenger that may affect your child's learning process through distance education? Choose all that applies.<br/>&nbsp;
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(collect($d8)->contains(1))<div class="grades">4</div>@endif</td>
                                <td style="width: 40%; padding-left: 5px;">lack of available gadgets/ equipment</td>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(collect($d8)->contains(6))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">conflict with other activities (i.e., house chores)</td>
                            </tr>
                            <tr>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(collect($d8)->contains(2))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;insufficient load/ data allowance</td>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(collect($d6)->contains(7))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">High electrical consumption</td> 
                            </tr>
                            <tr>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(collect($d8)->contains(3))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">unstable mobile/ internet connection</td>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(collect($d8)->contains(8))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">distractions (i.e., social media, noise from community/neighbor, relatives)</td>
                            </tr>
                            <tr>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(collect($d8)->contains(4))<div class="grades">4</div>@endif</td>
                                <td>&nbsp;existing health condition/s</td>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(isset($surveyAns->d8others))<div class="grades">4</div>@endif</td>
                                <td style="padding-left: 5px;">others:<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$surveyAns->d8others}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                            </tr>
                            <tr>
                                <td style="width: 4%; border: 1px solid black; text-align: center;">@if(collect($d8)->contains(5))<div class="grades">4</div>@endif</td>
                                <td colspan="3">&nbsp;difficulty in independent learning</td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
<table>
    
<table style="width: 100%; margin-top: 5px;">
    <tr>
        <td colspan="5" style=" font-size: 13px; text-align: justify;">
            <br/>&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify that the above information given are true and correct to the best of my knowledge and I allow the Department of Education to use my child's details to create and/or update his/her learner profile in the Learner Information System. The information herein shall be treated as confidential in compliance with the Data privacy Act of 2012.
        </td>
    </tr>
    <tr>
        <td colspan="5" style="line-height: 30px;">&nbsp;</td>
    </tr>
    <tr>
        <td></td>
        <td style="width: 45%; border-bottom: 1px solid black;"></td>
        <td style="width: 5%;"></td>
        <td style="width: 25%; border-bottom: 1px solid black;"></td>
        <td></td>
    </tr>
    <tr style="font-size: 12px;">
        <td></td>
        <td style="text-align: center;">Signature Over Printed Name of Parent/Guardian</td>
        <td></td>
        <td style="text-align: center;">Date</td>
        <td></td>
    </tr>
    <tr>
        <td colspan="5" style="line-height: 15px;">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="5" style=" border-top: 1px dashed black;">
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td colspan="16" style="font-size: 11px;">For use of School Personnel Only. To be filled up by the Class Adviser.</td>
                </tr>
                <tr>
                    <td colspan="16" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 12px;">
                    <td style="width: 18%;"></td>
                    <td colspan="2" style="width: 28%; line-height: 10px;">DATE OF FIRST ATTENDANCE<br><sup>(Month/Day/Year)</sup></td>
                    <td style="width: 3.8%; border: 1px solid black;"></td>
                    <td colspan="2" style="width: 3.8%; border: 1px solid black;"></td>
                    <td style="width: 3.8%; text-align: center;">/</td>
                    <td style="width: 3.8%; border: 1px solid black;"></td>
                    <td colspan="2" style="width: 3.8%; border: 1px solid black;"></td>
                    <td style="width: 3.8%; text-align: center;">/</td>
                    <td style="width: 3.8%; border: 1px solid black;"></td>
                    <td style="width: 3.8%; border: 1px solid black;"></td>
                    <td style="width: 3.8%; border: 1px solid black;"></td>
                    <td style="width: 3.8%; border: 1px solid black;"></td>
                    <td style="width: 16%;"></td>
                </tr>
                <tr>
                    <td colspan="16" style="line-height: 5px;">&nbsp;</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td style="width: 5%;">Grade Level</td>
                    <td colspan="3" style="border-bottom: 1px solid black"></td>
                    <td colspan="4">Track (for SHS)</td>
                    <td colspan="6" style="border-bottom: 1px solid black;"></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br/>&nbsp;