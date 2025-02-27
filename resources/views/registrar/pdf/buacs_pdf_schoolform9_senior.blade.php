<style>
    * { font-family: Arial, Helvetica, sans-serif; line-height: 11px;}
    @page { margin: 20px; }

    #table1 td{
        padding: 5px;
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
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 20px;

                /** Extra personal styles **/
                background-color: #03a9f4;
                color: white;
                text-align: center;
                line-height: 5px;
            }
    .table-grades td{
        padding: 10px;
    }
</style>
<body>
    
{{-- <header>
    Our Code World
</header> --}}
<table style="width: 100%">
    <tr style="line-height:">
        <td width="20%" rowspan="3" style="text-align: right;"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
        <td style="text-align:center; font-size: 11px;">
            REPUBLIC OF THE PHILIPPINES<br/>
            DEPARTMENT OF EDUCATION
        </td>
        <td width="20%" style="text-align:right;"><sup style="font-size: 11px;">Form 9-SHS</sup></td>
        {{-- <td width="20%" style="text-align:right;" rowspan="3"><sup style="font-size: 11px;">Form 9-SHS</sup><br/><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="90px"></td> --}}
    </tr>
    {{-- <tr>
        <td style="text-align:center; font-size: 11px;">DEPARTMENT OF EDUCATION</td>
    </tr> --}}
    {{-- <tr>
        <td style="text-align:center; font-size: 15px; font-weight: bold; text-transform: uppercase;">SENIOR HIGH SCHOOL STUDENT PERMANENT RECORD</td>
    </tr> --}}
    <tr>
        <td style="text-align:center; font-size: 11px;">
            <div style="font-weight: bold; font-size: 13px;">SENIOR HIGH SCHOOL STUDENT PERMANENT RECORD</div>
        </td>
        <td width="20%" style="text-align:left;" rowspan="2"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="100px"></td>
    </tr>
    <tr>
        <td style="text-align:center; font-weight: bold;font-size: 12px;">
            {{ucwords(strtolower($schoolinfo->schoolname))}}<br/>
            {{ucwords(strtolower($schoolinfo->address))}}
        </td>
    </tr>
</table>
<br/>
<div style="width: 100%; line-height: 5px;">&nbsp;</div>
<table style="width: 100%" id="table2">
    <tr>
        <td colspan="8" style="text-align: center; font-size: 13px; font-weight: bold; background-color: #aba9a9;">LEARNER'S INFORMATION</td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 10%;">LAST NAME:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{$studinfo->lastname}}</td>
        <td style="width: 10%;">FIRST NAME:</td>
        <td style="width: 25%; border-bottom: 1px solid black;">{{$studinfo->firstname}}</td>
        <td style="width: 15%;">MIDDLE NAME</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{$studinfo->middlename}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;" id="table3">
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
        <td style="width: 5%;">LRN:</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{$studinfo->lrn}}</td>
        <td style="width: 20%; text-align: right;">Date of Birth (MM/DD/YYYY):</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{date('m/d/Y',strtotime($studinfo->dob))}}</td>
        <td style="width: 5%; text-align: right;">Sex:</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
        <td style="width: 28%;">Date of SHS Admission (MM/DD/YYYY):</td>
        <td style="width: 10%; border-bottom: 1px solid black;"></td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;" id="table4">
    <tr>
        <td style="background-color: #c9c8c5">
            ELIGIBILITY FOR SHS ENROLMENT
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 11px;">
    <tr>
        <td style="width:30%">
            @if($eligibility->completerhs == '1')
                <label>
                    <input type="checkbox" name="check-1" checked>High School Completer* &nbsp;&nbsp;&nbsp;Gen. Ave: 
                </label>
            @else
                <label>
                    <input type="checkbox" name="check-1">High School Completer* &nbsp;&nbsp;&nbsp;Gen. Ave:
                </label>
            @endif
        </td>
        <td style="border-bottom: 1px solid; width: 10%;">
            @if($eligibility->completerhs == '1')
                {{$eligibility->genavehs}}
            @endif
        </td>
        <td style="width:10%"></td>
        <td style="width:30%">
            @if($eligibility->completerjh == '1')
                <label>
                    <input type="checkbox" name="check-1" checked>Junior High School Completer &nbsp;&nbsp;&nbsp;Gen. Ave:
                </label>
            @else
                <label>
                    <input type="checkbox" name="check-1">Junior High School Completer &nbsp;&nbsp;&nbsp;Gen. Ave:
                </label>
            @endif
        </td>
        <td style="border-bottom: 1px solid; width: 10%;">
            @if($eligibility->completerjh == '1')
                {{$eligibility->genavejh}}
            @endif
        </td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;">
    <tr>
        <td style="width:35%">Date of Graduation/Completing (MM/DD/YYYY):</td>
        <td style="border-bottom: 1px solid;">
            {{$eligibility->graduationdate}}
        </td>
        <td style="">Name of School:</td>
        <td style="border-bottom: 1px solid">
            {{$eligibility->schoolname}}
        </td>
        <td style="">School Address:</td>
        <td style="border-bottom: 1px solid">
            {{$eligibility->schooladdress}}
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 4px;">&nbsp;</div>
<table style="width: 100%; font-size: 11px;">
    <tr>
        <td style="width:30%">
            @if($eligibility->peptpasser == '1')
                <label>
                    <input type="checkbox" name="check-1" checked>PEPT Passer** &nbsp;&nbsp;&nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;{{$eligibility->peptrating}}&nbsp;&nbsp;&nbsp;</u>
                </label>
            @else
                <label>
                    <input type="checkbox" name="check-1">PEPT Passer** &nbsp;&nbsp;&nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                </label>
            @endif
        </td>
        <td style="width:30%">
            @if($eligibility->alspasser == '1')
                <label>
                    <input type="checkbox" name="check-1" checked>ALS A&E Passer** &nbsp;&nbsp;&nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;{{$eligibility->alsrating}}&nbsp;&nbsp;&nbsp;</u>
                </label>
            @else
                <label>
                    <input type="checkbox" name="check-1">ALS A&E Passer** &nbsp;&nbsp;&nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                </label>
            @endif
        </td>
        <td style="width:15%">Others (Pls. Specify):</td>
        <td style="border-bottom: 1px solid">{{$eligibility->others}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;">
    <tr>
        <td style="width:35%">Date of Examination/Assessment (MM/DD/YYYY):</td>
        <td style="border-bottom: 1px solid;">
            @if($eligibility->examdate!=null) {{date('m/d/Y',strtotime($eligibility->examdate))}} @endif
        </td>
        <td style="width:35%">Name and Address of Community Learning Center:</td>
        <td style="border-bottom: 1px solid">
                {{$eligibility->centername}}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:7px"><em>*High School Completers are students who graduated from secondary school under the old curriculum</em></td>
        <td colspan="2" style="font-size:7px"><em>***ALS A&E - Alternative Learning System Accreditation and Equivalency Test for JHS</em></td>
    </tr>
    <tr>
        <td colspan="4" style="font-size:7px"><em>**PEPT - Philippine Educational Placement Test for JHS</em></td>
    </tr>
</table>
<table style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;" id="table9">
    <tr>
        <td style="background-color: #c9c8c5">
            SCHOLASTIC RECORD
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 4px;">&nbsp;</div>
@if(count($records)>0)
    @foreach($records as $record)

        <div style="width: 100%; line-height: 3px;">&nbsp;</div>
        
        <table style="width: 100%; table-layout: fixed; page-break-inside: avoid;">
            <thead>
                <tr>
                    <th>
                        
                        <table style="width: 100%; font-size: 10px;">
                            <tr>
                                <td style="width: 5%;">School:</td>
                                <td style="width: 38%; border-bottom: 1px solid black;">{{$record->schoolname}}</td>
                                <td style="width: 7%;">School ID:</td>
                                <td style="width: 7%; border-bottom: 1px solid black;">{{$record->schoolid}}</td>
                                <td style="width: 10%;">GRADE LEVEL:</td>
                                <td style="width: 8%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record->levelname)}}</td>
                                <td style="width: 2%;">SY:</td>
                                <td style="width: 13%; border-bottom: 1px solid black;">{{$record->sydesc}}</td>
                                {{-- <td style="width: 4%;">SEM:</td>
                                <td style="width: 6%; border-bottom: 1px solid black;">@if($record->semid == 1) 1st @elseif($record->semid == 2) 2nd @endif</td> --}}
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 10px;">
                            <tr>
                                <td style="width: 10%;">TRACK/STRAND:</td>
                                <td style="width: 60%; border-bottom: 1px solid black;">{{$record->trackname}} / {{$record->strandname}}</td>
                                <td style="width: 7%;">SECTION:</td>
                                <td style="width: 23%; border-bottom: 1px solid black;">{{$record->sectionname}}</td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </thead>
            <tr>
                <td>
                    <table class="table-grades" style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 10px;" border="1">
                        <thead>
                            {{-- <tr>
                                <td colspan="5">
                                </td>
                            </tr> --}}
                            <tr style="background-color: #c9c8c5">
                                <th style="width: 20%;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                                <th style="width: 50%;">SUBJECTS</th>
                                <th style="width: 10%;">Final Rating</th>
                                <th>ACTION<br/>TAKEN</th>
                                <th>No. of Hours Taken</th>
                            </tr>
                        </thead>
                        
                        @if(count(collect($record->grades)->where('semid',$record->semid))>0)
                            @php
                                $gen_ave_for_sem = 0;
                                $with_final_rating = true;
                            @endphp
                            @foreach(collect($record->grades)->where('semid',$record->semid)->unique('subjcode') as $grade)
                                @php
                                    $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                                    $average = $with_final_rating ? ($grade->q1 + $grade->q2 ) / 2 : '';
                                    $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                                @endphp
                                @if($record->type == 2)
                                    @if(strtolower($grade->subjdesc) != 'general average')
                                        <tr>
                                            <td class="text-center">{{$grade->subjcode}}</td>
                                            <td>{{$grade->subjdesc}}</td>
                                            <td class="text-center">{{$grade->finalrating}}</td>
                                            <td class="text-center">{{$grade->remarks}}</td>
                                            <td class="text-center"></td>
                                        </tr>
                                    @endif
                                @else
                                    @if(strtolower($grade->subjdesc) != 'general average')
                                        <tr>
                                            <td class="text-center">
                                                @if($grade->type == 1)
                                                    Core
                                                @elseif($grade->type == 2)
                                                    Specialized
                                                @elseif($grade->type == 3)
                                                    Applied
                                                @else
                                                    Other Subject
                                                @endif
                                            </td>
                                            <td>{{$grade->subjdesc}}</td>
                                            <td class="text-center">{{$grade->finalrating}}</td>
                                            <td class="text-center">{{$grade->remarks}}</td>
                                            <td class="text-center"></td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            
                            @if($record->type == 1)
                                <tr style="font-weight: bold;">
                                    @php
                                        $with_final = collect($record->grades)->where('semid',$record->semid)->where('q1','!=,',null)->count() == 0 && collect($record->grades)->where('semid',$record->semid)->where('q2','!=,',null)->count() == 0 ? true:false;
                                    @endphp
                                    <td colspan="4" style="text-align: right;background-color: #c9c8c5">General Average</td>
                                    @if(collect($record->grades)->where('semid',$record->semid)->count()>0)
                                    <td class="text-center">{{ $with_final ? number_format($gen_ave_for_sem / collect($record->grades)->where('semid',$record->semid)->count()) : '' }}</td>
                                    @else
                                    <td class="text-center">&nbsp;</td>
                                    @endif
                                </tr>
                            @elseif($record->type == 2)
                                @if(count($record->grades) > 1)
                                    @foreach($record->grades as $grade)
                                        @if(strtolower($grade->subjdesc) == 'general average')
                                            <tr style="font-weight: bold;">
                                                <td colspan="4" style="background-color: #c9c8c5">General Average</td>
                                                <td class="text-center">{{$grade->finalrating}}</td>
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
                                </tr>
                            @endfor
                        @endif
                    </table>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <br/>
    @endforeach
@endif
<table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
    <tr>
        <td style="width:20%"><strong>Track/Strand Accomplished:</strong></td>
        <td style="border-bottom: 1px solid;width:45%">

        </td>
        <td style="width:20%"><strong>SHS General Average:</strong></td>
        <td style="border-bottom: 1px solid;"></td>
    </tr>
</table>
<table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
    <tr>
        <td style="width:20%"><strong>Awards/Honors Recieved:</strong></td>
        <td style="border-bottom: 1px solid;width:40%">

        </td>
        <td style="width:28%"><strong>Date of SHS Graduation (MM/DD/YYYY):</strong></td>
        <td style="border-bottom: 1px solid;"></td>
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
                            {{strtoupper(DB::table('schoolinfo')->first()->authorized)}}
                        </div>
                    </td>
                    <td style="width: 40%; ">
                        <div style="width: 90%; border-bottom: 1px solid;">
                            <strong>&nbsp;</strong>
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
    <tr>
        <td colspan="2">
            <strong>REMARKS: </strong> 
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br>
            <strong>Date Issued (MM/DD/YYYY):</strong>
        </td>
    </tr>
</table>
</body>