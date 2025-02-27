<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
    <style>
        @page{
            size: 8.5in 13in;
            margin: 0.3in 0.5in
        }
        *{
            
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        /* font-family:  "Times New Roman", Georgia, serif; */
            /* font-size: 12px; */
        }
        footer {
                    position: fixed; 
                    bottom: 0cm; 
                    left: 0cm; 
                    right: 0cm;
                    height: 2cm;
                }

        table{
            border-collapse: collapse;
        }
		
		.text-center{
            text-align: center !important;
        } 
            
.table-layout td, .table-layout th{
    padding: 0px !important;
}

.grades {
               font-family: ZapfDingbats, sans-serif;
            }
    </style>
</head>
<body>
    @php
        $header_dept = 'BASIC EDUCATION DEPARTMENT';
        if($studentinfo->acadprogid == 4)
        {
            $header_dept .= ' - High  School';
        }
        elseif($studentinfo->acadprogid == 3)
        {
            $header_dept .= ' - Elementary';
        }
        elseif($studentinfo->acadprogid == 2)
        {
            $header_dept .= ' - Pre-school';
        }
        elseif($studentinfo->acadprogid == 5)
        {
            $header_dept .= ' - Senior High  School';
        }
    @endphp
    <table style="width: 100%; table-layout: fixed; font-weight:">
            <tr>
                <th rowspan="5" style="text-align: right; vertical-align: middle; padding-right: 20px;"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="100px"></th>
                <th style="width: 50%;"></th>
                <th rowspan="5"></th>
            </tr>
            <tr>
                <th style="font-size: 15px !important; vertical-align: top;">{{$schoolinfo->schoolname}}</th>
            </tr>
            <tr>
                <th style="vertical-align: top;font-size: 12px !important;">{{$schoolinfo->address}}</th>
            </tr>
            <tr>
                <th style=" vertical-align: top;font-size: 12px !important;">{{$header_dept}}</th>
            </tr>
            <tr>
                <th></th>
            </tr>
        <tr>
            <th style="font-size: 17px !important; vertical-align: top; text-align: center;" colspan="3">STUDENT'S INFORMATION</th>
        </tr>        
    </table>
    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="width: 13%; ">Grade Level:</td>
            <td style="width: 30%; border-bottom: 1px solid black;">&nbsp;{{$studentinfo->levelname}}</td>
            <td style="width: 2%;">&nbsp;<td>
            <td style="width: 3%; border: 1px solid black; text-align: center;" >{{strtolower($studentinfo->studtype) != 'old' ? '/' : ''}}<td>
            <td style="width: 24.5%; vertical-align: bottom;">
                <span style="  line-height: 5px;">&nbsp;&nbsp;&nbsp;New/transferee</span>
            </td>
            <td style="width: 3%; border: 1px solid black !important; text-align: center;" >{{strtolower($studentinfo->studtype) == 'old' ? '/' : ''}}<td>
            <td style="width: 24.5%; vertical-align: bottom;">
                <span style="  line-height: 5px;">&nbsp;&nbsp;&nbsp;Old</span>
            </td>
        </tr> 
    </table>
    <table style="width: 100%; margin-top: 5px">
        <tr>
            <td style="width: 5%;">LRN:</td>
			<td style="width: 25%; border-bottom: 1px solid black;">{{$studentinfo->lrn}}</td>
			<td style="width: 15%;"></td>
            <td style="width: 9%;; font-size: 11px !important; "><em>For SHS:</em></td>
            <td style="width: 4%; border: 1px solid black; text-align: center;">{{strtolower($studentinfo->strandcode) == 'abm' ? '/' : ''}}</td>
            <td style="">&nbsp;&nbsp;ABM</td>
            <td style="width: 4%; border: 1px solid black; text-align: center;">{{strtolower($studentinfo->strandcode) == 'humss' ? '/' : ''}}</td>
            <td style="">&nbsp;&nbsp;HUMSS</td>
            <td style="width: 4%; border: 1px solid black; text-align: center;">{{strtolower($studentinfo->strandcode) == 'stem' ? '/' : ''}}</td>
            <td style="">&nbsp;&nbsp;STEM</td>
            <td style="width: 4%; border: 1px solid black; text-align: center;">{{strtolower($studentinfo->strandcode) == 'gas' ? '/' : ''}}</td>
            <td style="">&nbsp;&nbsp;GAS</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 13%; ">Last Name:</td>
            <td style="width: 37%; border-bottom: 1px solid black;">{{$studentinfo->lastname}}</td>
            <td style="text-align: right; ">Gender:&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td style="width: 4%; border: 1px solid black; text-align: center;">{{strtolower($studentinfo->gender) == 'male' ? '/' : ''}}</td>
            <td style=" ">&nbsp;&nbsp;Male</td>
            <td style="width: 4%; border: 1px solid black; text-align: center;">{{strtolower($studentinfo->gender) == 'female' ? '/' : ''}}</td>
            <td style=" ">&nbsp;&nbsp;Female</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 13%; ">First Name:</td>
            <td style="width: 37%; border-bottom: 1px solid black;">{{$studentinfo->firstname}}</td>
            <td style="text-align: right; ">Date of Birth:&nbsp;&nbsp;</td>
            <td style="width: 35%; border-bottom: 1px solid black;">@if($studentinfo->dob != null){{date('M d, Y', strtotime($studentinfo->dob))}}@endif</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 15%; ">Middle Name:</td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->middlename}}</td>
            <td style="text-align: right; ">Ethnicity:&nbsp;&nbsp;</td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->egname}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 13%; ">Suffix:</td>
            <td style="width: 37%; border-bottom: 1px solid black;">{{$studentinfo->suffix}}</td>
            <td style="text-align: right; ">Religion:&nbsp;&nbsp;</td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->religionname}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 15%; ">Place of Birth:</td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->pob ?? ''}}</td>
            <td style="text-align: right; ">Nationality:&nbsp;&nbsp;</td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->nationality}}</td>
        </tr>
    </table>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sait')
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 27%; ">Number of Children in the Family:</td>
            <td style="width: 24%; border-bottom: 1px solid black;">{{$studentinfo->nocitf}}</td>
            <td style="width: 40%;text-align: right; ">Number of Children Enrolled at {{DB::table('schoolinfo')->first()->abbreviation}}: &nbsp;&nbsp;</td>
            <td style="width: 10%; border-bottom: 1px solid black;">{{$studentinfo->noce}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 35%; ">Order in the Family (please check):   </td>
            <td style="width: 5%; border-bottom: 1px solid black;" class="text-center"><div class="grades">{{$studentinfo->oitfitf == 'eldest' ? '4' : ''}}</div></td>
            <td style="text-align: right; ">eldest</td>
            <td style="width: 5%; border-bottom: 1px solid black;" class="text-center"><div class="grades">{{$studentinfo->oitfitf == '2nd' ? '4' : ''}}</div></td>
            <td style="text-align: right; ">2<sup>nd</sup></td>
            <td style="width: 5%; border-bottom: 1px solid black;"class="text-center"><div class="grades">{{$studentinfo->oitfitf == '3rd' ? '4' : ''}}</div></td>
            <td style="text-align: right; ">3<sup>rd</sup></td>
            <td style="width: 5%; border-bottom: 1px solid black;"class="text-center"><div class="grades">{{$studentinfo->oitfitf == 'youngest' ? '4' : ''}}</div></td>
            <td style="text-align: right; ">youngest</td>
            <td style="width: 5%; border-bottom: 1px solid black;" class="text-center"></td>
            <td style="text-align: right; ">others:</td>
            <td style="width: 5%; border-bottom: 1px solid black;" class="text-center"><div class="grades">{{$studentinfo->oitfitf != 'eldest' && $studentinfo->oitfitf != '2nd' && $studentinfo->oitfitf != '3rd' && $studentinfo->oitfitf != 'youngest'  ? $studentinfo->oitfitf : ''}}</div></td>
        </tr>
    </table>
    @endif
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="">Complete Home Address: <u>{{$studentinfo->street}} {{$studentinfo->barangay}} {{$studentinfo->city}} {{$studentinfo->province}}</u></td>
        </tr>
    </table>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sait')
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 23%; ">Language/s spoken at home: </td>
            <td  width="77%" style="border-bottom: 1px solid black;">{{$studentinfo->mtname}}</td>
        </tr>
    </table>
    @endif
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 24%; ">Name of School Last Attended:  </td>
            <td width="76%" style="border-bottom: 1px solid black;">{{$studentinfo->schoollastattended ?? null}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 21%; ">Grade Level in that School: </td>
            <td style="width: 29%; border-bottom: 1px solid black;">{{$studentinfo->glits}}</td>
            <td style="width: 25%;text-align: right; ">School’s Contact No.: &nbsp;&nbsp;</td>
            <td style="width: 25%; border-bottom: 1px solid black;">{{$studentinfo->scn}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width:40%; ">Complete Mailing Address of School last Attended: </td>
            <td style="border-bottom: 1px solid black;" width="60%">{{$studentinfo->cmaosla}}</td>
        </tr>
    </table>
    <div style="width: 100%; font-weight: bold;font-size: 17px !important; text-align: center; margin-top: 10px;">PARENT/GUARDIAN INFORMATION</div>
    @php
        $fathername = $studentinfo->fathername;
        
        if( strpos($fathername, ',') !== false ) {
            $fathername = explode(',', $fathername);
        }else{            
            $fathername = explode(' ', $fathername);
			$fathername = array_reverse($fathername);
        }
        
        $studentinfo->flname = $studentinfo->flname ?? null;
        $studentinfo->ffname = $studentinfo->ffname ?? null;
        $studentinfo->fmname = $studentinfo->fmname ?? null;
        if($studentinfo->flname == null)
        {
            $studentinfo->flname = $fathername[0] ?? null;
        }
        if($studentinfo->ffname == null)
        {
            if(count($fathername) == 2)
            {
            $studentinfo->ffname = $fathername[1] ?? null;
            }else{
            $studentinfo->ffname = $fathername[2] ?? null;
            }
        }
        if($studentinfo->fmname == null)
        {
            if(count($fathername) > 2)
            {
            $studentinfo->fmname = $fathername[1] ?? null;
            }
        }
        $mothername = $studentinfo->mothername;
        if( strpos($mothername, ',') !== false ) {
            $mothername = explode(',', $mothername);
        }else{            
            $mothername = explode(' ', $mothername);
			$mothername = array_reverse($mothername);
        }
        
        $studentinfo->mlname = $studentinfo->mlname ?? null;
        $studentinfo->mfname = $studentinfo->mfname ?? null;
        $studentinfo->mmname = $studentinfo->mmname ?? null;
        if($studentinfo->mlname == null)
        {
            $studentinfo->mlname = $mothername[0] ?? null;
        }
        if($studentinfo->mfname == null)
        {
            if(count($mothername) == 2)
            {
            $studentinfo->mfname = $mothername[1] ?? null;
            }else{
            $studentinfo->mfname = $mothername[2] ?? null;
            }
        }
        if($studentinfo->mmname == null)
        {
            if(count($mothername) > 2)
            {
            $studentinfo->mmname = $mothername[1] ?? null;
            }
        }

        $guardianname = $studentinfo->guardianname;
        if( strpos($guardianname, ',') !== false ) {
            $guardianname = explode(',', $guardianname);
        }else{            
            $guardianname = explode(' ', $guardianname);
			$guardianname = array_reverse($guardianname);

        }
        $studentinfo->glname = $studentinfo->glname ?? null;
        $studentinfo->gfname = $studentinfo->gfname ?? null;
        $studentinfo->gmname = $studentinfo->gmname ?? null;
        if($studentinfo->glname == null)
        {
            $studentinfo->glname = $guardianname[0] ?? null;
        }
        if($studentinfo->gfname == null)
        {
            if(count($mothername) == 2)
            {
            $studentinfo->gfname = $guardianname[1] ?? null;
            }else{
            $studentinfo->gfname = $guardianname[2] ?? null;
            }
        }
        if($studentinfo->gmname == null)
        {
            if(count($guardianname) > 2)
            {
            $studentinfo->gmname = $guardianname[1] ?? null;
            }
        }

    @endphp
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td colspan="4" style="font-weight: bold;"><u>FATHER</u></td>
        </tr>
        <tr>
            <td style="width: 13%; ">Last Name: </td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->flname}}</td>
            <td style="width: 25%; ">&nbsp;&nbsp;Educational Attainment:   </td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->fea}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 13%; ">First Name: </td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->ffname}}</td>
            <td style="width: 15%; ">&nbsp;&nbsp;Occupation:   </td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->foccupation}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 15%; ">Middle Name: </td>
            <td style="width: 33%; border-bottom: 1px solid black;">{{$studentinfo->fmname}}</td>
            <td style="width: 20%; ">&nbsp;&nbsp;Contact Number:</td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->fcontactno}}</td>
        </tr>
    </table>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sait')
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 17%; ">Home Address:</td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->fha}}</td>
        </tr>
    </table>
    @endif
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td colspan="4" style="font-weight: bold;"><u>MOTHER</u></td>
        </tr>
        <tr>
            <td style="width: 13%; ">Last Name: </td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->mlname}}</td>
            <td style="width: 25%; ">&nbsp;&nbsp;Educational Attainment:   </td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->mea}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 13%; ">First Name: </td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->mfname}}</td>
            <td style="width: 15%; ">&nbsp;&nbsp;Occupation:   </td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->moccupation}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 15%; ">Middle Name: </td>
            <td style="width: 33%; border-bottom: 1px solid black;">{{$studentinfo->mmname}}</td>
            <td style="width: 20%; ">&nbsp;&nbsp;Contact Number:</td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->mcontactno}}</td>
        </tr>
    </table>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sait')
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 17%; ">Home Address:</td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->mha}}</td>
        </tr>
    </table>
    @endif
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td colspan="4" ><u style="font-weight: bold;">GUARDIAN</u> <em>(Please fill-out if the student is living with a guardian)</em></td>
        </tr>
        <tr>
            <td style="width: 13%; ">Last Name: </td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->glname}}</td>
            <td style="width: 25%; ">&nbsp;&nbsp;Educational Attainment:   </td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->gea}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 13%; ">First Name: </td>
            <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->gfname}}</td>
            <td style="width: 15%; ">&nbsp;&nbsp;Occupation:   </td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->goccupation}}</td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 15%; ">Middle Name: </td>
            <td style="width: 33%; border-bottom: 1px solid black;">{{$studentinfo->gmname}}</td>
            <td style="width: 20%; ">&nbsp;&nbsp;Contact Number:</td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->gcontactno}}</td>
        </tr>
    </table>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sait')
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td style="width: 17%; ">Home Address:</td>
            <td style="border-bottom: 1px solid black;">{{$studentinfo->gha}}</td>
        </tr>
    </table>
    @endif
    <table style="width: 100%; margin-top: 8px;">
        <tr>
            <td >Relationship with the student: </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black;">&nbsp;{{$studentinfo->guardianrelation}}</td>
        </tr>
        <tr>
            <td ><em style="font-weight: bold;">Contact person in case of emergency </em> <em style="">(Recipient for news, announcement and school information)</em></td>
        </tr>
    </table>
    <table style="width: 100%; margin-top: 8px; font-weight: bold;">
        <tr>
            <td style="width: 10%;"></td>
            <td style="width: 10%; border-bottom: 1px solid black; text-align: center;">{{strtolower($studentinfo->ismothernum) == 1 ? '/' : ''}}</td>
            <td>Mother</td>
            <td style="width: 10%; border-bottom: 1px solid black; text-align: center;">{{strtolower($studentinfo->isfathernum) == 1 ? '/' : ''}}</td>
            <td>Father</td>
            <td style="width: 10%; border-bottom: 1px solid black; text-align: center;">{{strtolower($studentinfo->isguardannum) == 1 ? '/' : ''}}</td>
            <td>Guardian</td>
        </tr>
    </table>
    {{-- <div style="page-break-before: always;">&nbsp;</div> --}}
    @php
        $signatoryname = '';
        if($studentinfo->ismothernum == 1)
        {
            $signatoryname.=$studentinfo->mfname.' ';
            $signatoryname.=($studentinfo->mmname ? $studentinfo->mmname[0].'. ' : '');
            $signatoryname.=$studentinfo->mlname;
        }
        if($studentinfo->isfathernum == 1)
        {
            $signatoryname.=$studentinfo->ffname.' ';
            $signatoryname.=($studentinfo->fmname ? $studentinfo->fmname[0].'. ' : '');
            $signatoryname.=$studentinfo->flname;
        }
        if($studentinfo->isguardannum == 1)
        {
            $signatoryname.=$studentinfo->gfname.' ';
            $signatoryname.=($studentinfo->gmname ? $studentinfo->gmname[0].'. ' : '');
            $signatoryname.=$studentinfo->glname;
        }
    @endphp
    <table style="width: 100%;">
        <tr>
            <td colspan="5">
                <div style="width: 100%; font-weight: bold; margin-top: 18px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>I hereby certify that all the above information are TRUE and CORRECT.</em></div>
                <div style="width: 100%; font-weight: bold;font-size: 12.5px !important; border: 1px solid black; padding: 5px; text-align: justify;"><em style="line-height: 20px;">By affixing my signature, I authorize and give my full consent to the {{DB::table('schoolinfo')->first()->schoolname}}, to collect, store, transmit, use, distribute, disclose, share, retain, dispose, destroy, and process my personal  information and/or other information contained in this Registration Form for legitimate purpose, in compliance with the Data Privacy Act of 2012 and its Implementing Rules and Regulations.</em></div>
                <br/>
            </td>
        </tr>
            <tr style="">
                <td style="width: 5%;">&nbsp;</td>
                <td style="border-bottom: 1px solid black; text-align: center;">{{$signatoryname}}</td>
                <td style="width: 5%;">&nbsp;</td>
                <td style="border-bottom: 1px solid black; text-align: center;">{{$studentinfo->firstname}} {{isset($studentinfo->middlename) && $studentinfo->middlename != null && $studentinfo->middlename != "" ? $studentinfo->middlename[0].'.' : ''}} {{$studentinfo->lastname}} {{$studentinfo->suffix}}</td>
                <td style="width: 5%;">&nbsp;</td>    
            </tr>
            <tr style="">
                <td></td>
                <td style="text-align: center;">Name & Signature of Parent/Guardian</td>
                <td></td>
                <td style="text-align: center;">Name & Signature of Student</td>
                <td></td>
            </tr>
            <tr style="">
                <td></td>
                <td style="text-align: center;">Date:<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                <td></td>
                <td style="text-align: center;">Date:<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
                <td></td>
            </tr>
        </table>
    {{-- </div> --}}
</body>
</html>