
<style>
    *{
        
        font-family: Arial, Helvetica, sans-serif;
    }
    @page{
        margin: 25px 20px;;
        size: 14in 8.5in;
    }
    
    .rotate {
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        width: 1.5em;
        padding: 10px !important;
        font-size: 11px;
    }
    .rotate div {
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -10em;
            margin-right: -10em;
    }
    .header th{
        padding-top: 2px;
        padding-bottom: 2px;
    }
</style>
{{-- <div style="page-break-inside: avoid;"> --}}
<table style="width: 100%;">
    <tr>
        <th style="font-size: 25px;">School Form 1 (SF 1) School Register</th>
    </tr>
    <tr>
        <th style="font-size: 12px;">This replaced Form 1, Master List & STS Form 2-Family Background and Profile</th>
    </tr>
</table>
<table style="width: 100%;" class="header">
    <thead>
        <tr style="font-size: 11px;">
            <th style="width: 20%; text-align: right;">School ID</th>
            <th style="width: 13%; border: 1px solid black;">{{$forms[0]->schoolinfo->schoolid}}</th>
            <th style="width: 5%; text-align: right;">Region</th>
            <th style="width: 13%; border: 1px solid black;">{{$forms[0]->schoolinfo->regDesc}}</th>
            <th style="width: 8%; text-align: right;">Division</th>
            <th style="width: 21%; border: 1px solid black;" colspan="3">{{$forms[0]->schoolinfo->citymunDesc}}</th>
            <th style="width: 10%; text-align: right;">District</th>
            <th style="width: 20%; border: 1px solid black;">{{$forms[0]->schoolinfo->district}}</th>
        </tr>
    </thead>
    <tr style="font-size: 11px;">
        <th style="text-align: right;">School Name</th>
        <th colspan="3" style="border: 1px solid black;">{{$forms[0]->schoolinfo->schoolname}}</th>
        <th style="text-align: right;">School Year</th>
        <th style="border: 1px solid black;">{{$forms[0]->schoolyear}}</th>
        <th style="text-align: right;">Grade Level</th>
        <th style="border: 1px solid black;">{{$forms[0]->gradelevel}}</th>
        <th style="text-align: right;">Section</th>
        <th colspan="2" style="border: 1px solid black;">{{$forms[0]->section}}</th>
    </tr>
</table>
@php
    $countstudentmale = 0;
    $countstudentfemale = 0;
@endphp
<table style="width: 100%; border-collapse: collapse; table-layout: fixed;" border="1">
    <thead style="font-size: 9px;">
        <tr>
            <th rowspan="2" style="width: 2%;">

            </th>
            <th rowspan="2"  style="width: 6%;">
                LRN
            </th>
            <th rowspan="2" style="width: 16%;">
                NAME<br/>
                <span style="font-size: 7px;">(Last Name, First Name, Middle Name)</span>
            </th>
            <th rowspan="2" style="width: 2%;">
                Sex
                <br>
                <span style="font-size: 7px;">(M/F)</span>
            </th>
            <th rowspan="2" style="width: 5%;">
                BIRTH DATE  
                <span style="font-size: 7px;">(mm/dd/yyyy)</span>
            </th>
            <th rowspan="2" style="width: 3%;">
                AGE as<br>
                of 1st<br>
                Friday
                <br>
                June
            </th>
            <th rowspan="2" style="width: 5%;">
                MOTHER
                TONGUE
            </th>
            <th rowspan="2" style="width: 5%;">
                IP
                (Ethnic Group)
            </th>
            <th rowspan="2" style="width: 5%;">
                RELIGION
            </th>
            <th colspan="4">
                ADDRESS
            </th>
            <th colspan="2" style="width: 11%;">
                PARENTS
            </th>
            <th colspan="2" style="width: 10%;">
                GUARDIAN
                (If not Parent)
            </th>
            <th rowspan="2" style="width: 6%;">
                Contact Number of Parent or Guardian
            </th>
            <th>
                REMARKS
            </th>
        </tr>
        <tr>
            <th>
                House #/<br/>Street/<br/>Sitio/<br/>Purok
            </th>
            <th>
                Barangay
            </th>
            <th>
                Municipality/<br/>City
            </th>
            <th>
                Province
            </th>
            <th style="width: 5.5%;">
                Father's Name (Last Name,
                First Name, Middle Name)
            </th>
            <th style="width: 5.5%;">
                Mother's Maiden Name (Last
                Name, First Name, Middle
                Name)
            </th>
            <th style="width: 6% ;">
                Name
            </th>
            <th style="width: 4%;">
                Relation-ship
            </th>
            <th>
                (Please refer to the
                legend on last page)
            </th>
        </tr>
    </thead>
    <tbody style="font-size: 10px;">
        @foreach($forms[0]->students as $studentinfo)
            @if(strtolower($studentinfo->gender) == 'male')
                @php
                    $countstudentmale+=1;
                @endphp
                <tr>
                    <td style="text-align: center;">{{$countstudentmale}}</td>
                    <td>
                        {{$studentinfo->lrn}}
                    </td>
                    <td style="padding-left: 2px;">{{ucwords(strtolower($studentinfo->lastname.', '.$studentinfo->firstname.' '.$studentinfo->middlename.' '.$studentinfo->suffix))}}</td>
                    <td style="text-align: center;">{{$studentinfo->gender[0]}}</td>
                    <td style="text-align: center;">{{$studentinfo->dob}}</td>
                    <td style="text-align: center;">{{$studentinfo->age}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->mtname))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->egname))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->religionname))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->street))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->barangay))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->city))}}</td>
                    <td>{{ucwords(strtolower($studentinfo->province))}}</td>
                    <td style="padding-left: 2px; word-wrap: break-word;">
                        @if($studentinfo->fathername != ',')
                            {{ucwords(strtolower($studentinfo->fathername))}}
                        @endif
                    </td>
                    <td style="padding-left: 2px; word-wrap: break-word;">
                        @if($studentinfo->mothername != ',')
                            {{ucwords(strtolower($studentinfo->mothername))}}
                        @endif
                    </td>
                    <td style="padding-left: 2px; word-wrap: break-word;">
                        @if($studentinfo->guardianname != ',')
                            {{ucwords(strtolower($studentinfo->guardianname))}}
                        @endif
                    </td>
                    <td>{{ucwords(strtolower($studentinfo->guardianrelation))}}</td>
                    <td style="text-align: center;">
                        @php
                            $contactnumbermale = null;
                        @endphp
                        @if($studentinfo->fcontactno != null && $contactnumbermale == null)
                            @php
                                $contactnumbermale = $studentinfo->fcontactno;
                            @endphp
                        @endif
                        @if($studentinfo->mcontactno != null && $contactnumbermale == null)
                            @php
                                $contactnumbermale = $studentinfo->mcontactno;
                            @endphp
                        @endif
                        @if($studentinfo->gcontactno != null && $contactnumbermale == null)
                            @php
                                $contactnumbermale = $studentinfo->gcontactno;
                            @endphp
                        @endif
                        {{$contactnumbermale}}
                    </td>
                    <td style="text-align: center;">
                        @if($studentinfo->studstatus == 2) {{-- Late enrolled --}}
                            LE Date: {{$studentinfo->dateenrolled}}
                        @elseif($studentinfo->studstatus == 3) {{-- Dropped --}}
                            DRP
                        @elseif($studentinfo->studstatus == 4) {{-- Transferred In --}}
                            T/I Date: {{$studentinfo->dateenrolled}}
                        @elseif($studentinfo->studstatus == 5) {{-- Transferred Out --}}
                            T/O Date: {{$studentinfo->dateenrolled}}
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        <tr style="text-align: center;">
            <td></td>
            <td>{{$countstudentmale}}</td>
            <td>==TOTAL MALE</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($forms[0]->students as $studentinfo)
            @if(strtolower($studentinfo->gender) == 'female')
                @php
                    $countstudentfemale+=1;
                @endphp
                <tr>
                    <td style="text-align: center;">{{$countstudentfemale}}</td>
                    <td>
                        {{$studentinfo->lrn}}
                    </td>
                    <td style="padding-left: 2px;">{{ucwords(strtolower($studentinfo->lastname.', '.$studentinfo->firstname.' '.$studentinfo->middlename.' '.$studentinfo->suffix))}}</td>
                    <td style="text-align: center;">{{$studentinfo->gender[0]}}</td>
                    <td style="text-align: center;">{{$studentinfo->dob}}</td>
                    <td style="text-align: center;">{{$studentinfo->age}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->mtname))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->egname))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->religionname))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->street))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->barangay))}}</td>
                    <td style="text-align: center;">{{ucwords(strtolower($studentinfo->city))}}</td>
                    <td>{{ucwords(strtolower($studentinfo->province))}}</td>
                    <td style="padding-left: 2px; word-wrap: break-word;">
                        @if($studentinfo->fathername != ',')
                            {{ucwords(strtolower($studentinfo->fathername))}}
                        @endif
                    </td>
                    <td style="padding-left: 2px; word-wrap: break-word;">
                        @if($studentinfo->mothername != ',')
                            {{ucwords(strtolower($studentinfo->mothername))}}
                        @endif
                    </td>
                    <td style="padding-left: 2px; word-wrap: break-word;">
                        @if($studentinfo->guardianname != ',')
                            {{ucwords(strtolower($studentinfo->guardianname))}}
                        @endif
                    </td>
                    <td>{{ucwords(strtolower($studentinfo->guardianrelation))}}</td>
                    <td style="text-align: center;">
                        @php
                            $contactnumberfemale = null;
                        @endphp
                        @if($studentinfo->fcontactno != null && $contactnumberfemale == null)
                            @php
                                $contactnumberfemale = $studentinfo->fcontactno;
                            @endphp
                        @endif
                        @if($studentinfo->mcontactno != null && $contactnumberfemale == null)
                            @php
                                $contactnumberfemale = $studentinfo->mcontactno;
                            @endphp
                        @endif
                        @if($studentinfo->gcontactno != null && $contactnumberfemale == null)
                            @php
                                $contactnumberfemale = $studentinfo->gcontactno;
                            @endphp
                        @endif
                        {{$contactnumberfemale}}
                    </td>
                    <td style="text-align: center;">
                        @if($studentinfo->studstatus == 2) {{-- Late enrolled --}}
                            LE Date: {{$studentinfo->dateenrolled}}
                        @elseif($studentinfo->studstatus == 3) {{-- Dropped --}}
                            DRP
                        @elseif($studentinfo->studstatus == 4) {{-- Transferred In --}}
                            T/I Date: {{$studentinfo->dateenrolled}}
                        @elseif($studentinfo->studstatus == 5) {{-- Transferred Out --}}
                            T/O Date: {{$studentinfo->dateenrolled}}
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        <tr style="text-align: center;">
            <td></td>
            <td>{{$countstudentfemale}}</td>
            <td>==TOTAL FEMALE</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="text-align: center;">
            <td></td>
            <td>{{$countstudentfemale + $countstudentmale}}</td>
            <td>==COMBINED</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
</div>
<table style="width: 100%; border-collapse: collapse;">
    <tr style="font-size: 10px;">
        <th>List and Code of Indicators under REMARKS column</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <table style="width: 100%; border-collapse: collapse; font-size: 9px;"> 
                <tr>
                    <td style="border: 1px solid black;">Indicator</td>
                    <td style="border: 1px solid black; text-align: center; ">Code</td>
                    <td style="border: 1px solid black;">Required Information</td>
                    <td style="border: 1px solid black;">Indicator</td>
                    <td style="border: 1px solid black; text-align: center; ">Code</td>
                    <td style="border: 1px solid black;">Required Information</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Transferred Out</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center; ">T/O</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Name of Public (P) Private (PR) School & Effectivity Date</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">CCT Recipient</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center; ">CCT</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">CCT Control/reference number & Effectivity Date</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;"></td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;"></td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;"></td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Balik Aral</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center;">B/A</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Name of school last attended & Year</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Transferred In</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center;">T/I</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Name of Public (P) Private (PR) School & Effectivity Date</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Learner With Disability</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none; text-align: center; ">LWD</td>
                    <td style="border: 1px solid black; border-bottom: none; border-top: none;">Name of school last attended & Year</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; border-top: none;  ">Dropped</td>
                    <td style="border: 1px solid black; border-top: none; text-align: center; ">DRP</td>
                    <td style="border: 1px solid black; border-top: none;  ">Reason and Effectivity Date</td>
                    <td style="border: 1px solid black; border-top: none;">Accelerated</td>
                    <td style="border: 1px solid black; border-top: none; text-align: center; ">ACL</td>
                    <td style="border: 1px solid black; border-top: none;  ">Specify</td>
                </tr>
            </table>
        </td>
        <td style="width: 1%"></td>
        <td style="width: 10%; vertical-align: top;">
            <table style="width: 100%; border-collapse: collapse; font-size: 9px;" border="1"> 
                <tr>
                    <th style="padding: 2px;">REGISTERED</th>
                    <th style="padding: 2px;">BoSY</th>
                    <th style="padding: 2px;">EoSY</th>
                </tr>
                <tr>
                    <th style="padding: 5px;">MALE</th>
                    <th style="padding: 5px;">{{$countstudentmale}}</th>
                    <th style="padding: 5px;">{{$countstudentmale}}</th>
                </tr>
                <tr>
                    <th style="padding: 5px;">FEMALE</th>
                    <th style="padding: 5px;">{{$countstudentfemale}}</th>
                    <th style="padding: 5px;">{{$countstudentfemale}}</th>
                </tr>
                <tr>
                    <th style="padding: 5px;">TOTAL</th>
                    <th style="padding: 5px;">{{$countstudentmale+$countstudentfemale}}</th>
                    <th style="padding: 5px;">{{$countstudentmale+$countstudentfemale}}</th>
                </tr>
            </table>
        </td>
        <td style="width: 1%"></td>
        <td style="width: 18%; vertical-align: top;">
            <table style="width: 100%;">
                <tr>
                    <td style="font-size: 9px;">Prepared by:</td>
                </tr>
                <tr>
                    <td style="font-size: 9px; border-bottom: 1px solid black; text-align: center;">
                        <br/>
                        {{$forms[0]->preparedby->firstname}} {{$forms[0]->preparedby->middlename[0].'.'}} {{$forms[0]->preparedby->lastname}} {{$forms[0]->preparedby->suffix}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 8px; text-align: center;">
                        <sup>(Signature of Adviser over Printed Name)</sup>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black; font-size: 8px; font-weight: bold;">
                        <br/>
                        BoSY Date: {{date('F d, Y',strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->sdate))}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EoSY Date: {{date('F d, Y',strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->edate))}}
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 1%"></td>
        <td style="width: 20%">
            <table style="width: 100%; vertical-align: top;">
                <tr>
                    <td style="font-size: 9px;">Certified Correct:</td>
                </tr>
                <tr>
                    <td style="font-size: 9px; border-bottom: 1px solid black; text-align: center;">
                        <br/>
                        {{DB::table('schoolinfo')->first()->authorized}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 8px; text-align: center;">
                        <sup>(Signature of School Head over Printed Name)</sup>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black; font-size: 8px; font-weight: bold;">
                        <br/>
                        BoSY Date: {{date('F d, Y',strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->sdate))}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EoSY Date: {{date('F d, Y',strtotime(DB::table('sy')->where('id', $forms[0]->syid)->first()->edate))}}
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid black; font-size: 8px; font-weight: bold;">
                        <br/>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 8px; text-align: center; font-weight: bold;">
                        <sup>Generated thru LIS</sup>
                    </td>
                </tr>
            </table>        
        </td>
    </tr>
</table>
<div style="width: 100%; font-size: 9px;">
    Generated on: {{date('l, F d, Y')}}
</div>
{{-- <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
    <tr>
        <th colspan="5" style="text-align: center;">List and Code of Indicators under REMARKS column</th>
        <th colspan="4"></th>
        <th>&nbsp;&nbsp;&nbsp;</th>
        <th rowspan="2">Prepared by:</th>
        <th rowspan="6"> &nbsp;&nbsp;&nbsp;</th>
        <th rowspan="2">Certified Correct:</th>
    </tr>
    <tr>
        <td style="border: 1px solid black;">Indicator</td>
        <td style="text-align: center; border: 1px solid black;">Code</td>
        <td style="border: 1px solid black;">Required Information</td>
        <td style="text-align: center; border: 1px solid black;">Code</td>
        <td style="border: 1px solid black;">Required Information</td>
        <td rowspan="5" style="border-top: none; border-bottom: none; width: 1%;"></td>
        <td style="text-align: center; border: 1px solid black;">REGISTERED</td>
        <td style="text-align: center; border: 1px solid black;">BoSY</td>
        <td style="text-align: center; border: 1px solid black;">EoSy</td>
        <td rowspan="5" style="border: none;"></td>
    </tr>
    <tr>
        <td style="border-bottom: none; border: 1px solid black;">Transferred Out</td>
        <td style="border-bottom: none;text-align: center; border: 1px solid black;">T/O</td>
        <td style="border-bottom: none; border: 1px solid black;">Name of Public (P) Private (PR) School & Effectivity Date</td>
        <td style="border-bottom: none;text-align: center; border: 1px solid black;">CCT</td>
        <td style="border-bottom: none; border: 1px solid black;">CCT Control/reference number & Effectivity Date</td>
        <td style="text-align: center; border: 1px solid black;">MALE</td>
        <td style="text-align: center; border: 1px solid black;">{{$countstudentmale}}</td>
        <td style="border: 1px solid black;"></td>
        <td style="border: none; border-bottom: 1px solid black; text-transform: uppercase; text-align: center;">
            {{$forms[0]->preparedby->firstname}} {{$forms[0]->preparedby->middlename[0].'.'}} {{$forms[0]->preparedby->lastname}} {{$forms[0]->preparedby->suffix}}
        </td>
        <td style="border: none; border-bottom: 1px solid black; text-align: center;">
            {{$forms[0]->schoolinfo->authorized}}
        </td>
    </tr>
    <tr>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Transferred IN</td>
        <td style="border-top: none; border-bottom: none; text-align: center; border: 1px solid black;">T/I</td>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Name of Public (P) Private (PR) School & Effectivity Date</td>
        <td style="border-top: none; border-bottom: none;text-align: center; border: 1px solid black;">B/A</td>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Name of school last attended & Year</td>
        <td style="text-align: center; border: 1px solid black;">FEMALE</td>
        <td style="text-align: center; border: 1px solid black;">{{$countstudentfemale}}</td>
        <td style="border: 1px solid black;"></td>
        <td style="border: none; font-size: 8px; text-align: center; padding: 0px;">
            <sup>
                <em>
                    (Signature of Adviser over Printed Name)
                </em>
            </sup>
        </td>
        <td style="border: none; font-size: 8px; text-align: center;">
            <sup>
                <em>
                    (Signature of School Head over Printed Name)
                </em>
            </sup>
        </td>
    </tr>
    <tr>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">DROPPED</td>
        <td style="border-top: none; border-bottom: none;text-align: center; border: 1px solid black;">DRP</td>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Reason and Effectivity Date</td>
        <td style="border-top: none; border-bottom: none;text-align: center; border: 1px solid black;">LWD</td>
        <td style="border-top: none; border-bottom: none; border: 1px solid black;">Specify</td>
        <td rowspan="2" style="text-align: center; border: 1px solid black;">TOTAL</td>
        <td rowspan="2" style="text-align: center; border: 1px solid black;">{{$countstudentmale+$countstudentfemale}}</td>
        <td rowspan="2" style="border: 1px solid black;"></td>
        <td rowspan="2" style="border: none; border-bottom: 1px solid;">
            BoSy Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EoSY Date:
        </td>
        <td rowspan="2" style="border: none; border-bottom: 1px solid;">
            BoSy Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EoSY Date:
        </td>
    </tr>
    <tr>
        <td style="border-top: none; border: 1px solid black;">Late Enrollment</td>
        <td style="border-top: none;text-align: center; border: 1px solid black;">LE</td>
        <td style="border-top: none; border: 1px solid black;">Reason (Enrollment beyond 1st Friday of June)</td>
        <td style="border-top: none;text-align: center; border: 1px solid black;">ACL</td>
        <td style="border-top: none; border: 1px solid black;">Specify </td>
    </tr>
</table> --}}

  