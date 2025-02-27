
<style>
    html{font-family: Arial, Helvetica, sans-serif !important;}
    .header{ width: 100%; table-layout: fixed;  /* border: 1px solid black; */ }

    .student_info{ width: 100%; font-size: 60%; text-transform:uppercase;  }

    .student_data{ width: 100%; font-size: 60%; text-transform:uppercase; border-collapse: collapse;}

    .student_data td{
        border: 1px solid black;
    }
    .student_data th{
        border: 1px solid black;
        text-align: center;
        background-color: #b3b3b3;
    }
label {
  cursor: pointer;
}

input[type="checkbox"] {
  /* position: relative; */
  top: 2px;
  box-sizing: content-box;
  width: 14px;
  height: 14px;
  margin: 2px 5px 0 0;
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
}.nobreak {
  page-break-inside: avoid;
}
</style>
<table class="header">
    <tr>
        <td width="10%" style="text-align:right;text-transform: uppercase;"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
        <td>
            <center>
                <small style="font-size: 10px">Republic of the Philippines</small>
                <br>
                <small style="font-size: 10px">Department of Education</small>
                <br>
                <strong style="font-size: 15px">LEARNER'S PERMANENT ACADEMIC RECORD FOR SENIOR HIGH SCHOOL</strong>
                {{-- <br>
                <small style="font-size: 10px"><em>(Formerly Form 137)</em></small> --}}
            </center>
        </td>
        <td width="10%" style="text-align:right;"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="70px"></td>
    </tr>
</table>
&nbsp;
<div style="width: 100%; background-color:#d9d9d9; text-align:center;font-size: 10px"><strong>LEARNER'S INFORMATION</strong></div>
<table class="student_info" style="text-align: center">
    <tr>
        <td style="width:9%;">LAST NAME :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->lastname}}</td>
        <td style="width:10%;">FIRST NAME :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->firstname}}</td>
        {{-- <td style="width:15%;">NAME EXTN. (Jr,I,II) :</td>
        <td style="border-bottom: 1px solid black;width:5%;">{{$student_info[0]->suffix}}</td> --}}
        <td style="width:12%;">MIDDLE NAME :</td>
        <td style="border-bottom: 1px solid black;width:10%;">{{$student_info[0]->middlename}}</td>
    </tr>
</table>
<table class="student_info" style="text-align: none; text-transform: none;">
    <tr>
        <td style="text-transform: none;">LRN :</td>
        <td style="border-bottom: 1px solid black;width:15%">{{$student_info[0]->lrn}}</td>
        <td style="width:20%">Date of Birth (MM/DD/YYYY) :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->dob}}</td>
        <td >Sex :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->gender}}</td>
        <td style="width: 25%">Date of SHS Admission (MM/DD/YYYY) :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->dob}}</td>
    </tr>
</table>
<br>
<div style="width: 100%; background-color:#d9d9d9; text-align:center;font-size: 10px"><strong>ELIGIBILITY FOR SHS ENROLMENT</strong></div>
<table class="student_info" style="text-align: none; text-transform:none;">
    <tr>
        <td style="width:30%">
            @if (count($eligibility)!=0)
                @if($eligibility[0]->completer == 'hs')
                    <label>
                        <input type="checkbox" name="check-1" checked>High School Completer* &nbsp;&nbsp;&nbsp;Gen. Ave:
                    </label>
                @else
                    <label>
                        <input type="checkbox" name="check-1">High School Completer* &nbsp;&nbsp;&nbsp;Gen. Ave:
                    </label>
                @endif
            @endif
        </td>
        <td style="border-bottom: 1px solid;">
            @if (count($eligibility)!=0)
                @if($eligibility[0]->completer == 'hs')
                    {{$eligibility[0]->gen_ave}}
                @endif
            @endif
        </td>
        <td style="width:30%">
            @if (count($eligibility)!=0)
                @if($eligibility[0]->completer == 'jhs')
                    <label>
                        <input type="checkbox" name="check-1" checked>Junior High School Completer &nbsp;&nbsp;&nbsp;Gen. Ave:
                    </label>
                @else
                    <label>
                        <input type="checkbox" name="check-1">Junior High School Completer &nbsp;&nbsp;&nbsp;Gen. Ave:
                    </label>
                @endif
            @endif
        </td>
        <td style="border-bottom: 1px solid">
            @if (count($eligibility)!=0)
                @if($eligibility[0]->completer == 'jhs')
                    {{$eligibility[0]->gen_ave}}
                @endif
            @endif
        </td>
    </tr>
</table>
<table class="student_info" style="text-align: none; text-transform:none;">
    <tr>
        <td style="width:30%">Date of Graduation/Completing (MM/DD/YYYY):</td>
        <td style="border-bottom: 1px solid;">
            @if (count($eligibility)!=0)
                {{$eligibility[0]->completion_date}}
            @endif
        </td>
        <td style="">Name of School:</td>
        <td style="border-bottom: 1px solid">
            @if (count($eligibility)!=0)
                {{$eligibility[0]->schoolname}}
            @endif
        </td>
        <td style="">School Address:</td>
        <td style="border-bottom: 1px solid">
            @if (count($eligibility)!=0)
                {{$eligibility[0]->schooladdress}}
            @endif
        </td>
    </tr>
</table>
<table class="student_info" style="text-align: none; text-transform:none;">
    <tr>
        <td style="width:30%">
            @if (count($eligibility)!=0)
                @if($eligibility[0]->passer == 'pept')
                    <label>
                        <input type="checkbox" name="check-1" checked>PEPT Passer** &nbsp;&nbsp;&nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;{{$eligibility[0]->rating}}&nbsp;&nbsp;&nbsp;</u>
                    </label>
                @else
                    <label>
                        <input type="checkbox" name="check-1">PEPT Passer** &nbsp;&nbsp;&nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                    </label>
                @endif
            @endif
        </td>
        <td style="width:30%">
            @if (count($eligibility)!=0)
                @if($eligibility[0]->passer == 'als')
                    <label>
                        <input type="checkbox" name="check-1" checked>ALS A&E Passer** &nbsp;&nbsp;&nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;{{$eligibility[0]->rating}}&nbsp;&nbsp;&nbsp;</u>
                    </label>
                @else
                    <label>
                        <input type="checkbox" name="check-1">ALS A&E Passer** &nbsp;&nbsp;&nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                    </label>
                @endif
            @endif
        </td>
        <td style="width:20%">Others (Pls. Specify):</td>
        <td style="border-bottom: 1px solid"></td>
    </tr>
</table>
<table class="student_info" style="text-align: none; text-transform:none;">
    <tr>
        <td style="width:35%">Date of Examination/Assessment (MM/DD/YYYY):</td>
        <td style="border-bottom: 1px solid;">
            @if (count($eligibility)!=0)
                {{$eligibility[0]->exam_date}}
            @endif
        </td>
        <td style="width:35%">Name and Address of Community Learning Center:</td>
        <td style="border-bottom: 1px solid">
            @if (count($eligibility)!=0)
                {{$eligibility[0]->learning_center_name.' / '.$eligibility[0]->learning_center_address}}
            @endif
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
<div style="width: 100%; background-color:#d9d9d9; text-align:center;font-size: 10px"><strong>SCHOLASTIC RECORD</strong></div>
@foreach($res_data as $data )
    <table class="student_info" style="text-align: none; text-transform:none;">
        <tr>
            <td style="width:5%">SCHOOL:</td>
            <td style="border-bottom: 1px solid;width:%">{{$data['first'][0]['schoolinfo']->schoolname}}</td>
            <td style="width:10%">SCHOOL ID:</td>
            <td style="border-bottom: 1px solid;width:%"></td>
            <td style="width:15%">GRADE LEVEL:</td>
            <td style="border-bottom: 1px solid;width:%">{{$data['first'][0]['schoolinfo']->levelname}}</td>
            <td style="width:5%">SY:</td>
            <td style="border-bottom: 1px solid;">{{$data['first'][0]['schoolinfo']->schoolyear}}</td>
            <td style="width:5%">SEM:</td>
            <td style="border-bottom: 1px solid;">1st</td>
        </tr>
        <tr>
            <td>TRACK/STRAND:</td>
            <td colspan="5" style="border-bottom: 1px solid;">{{$data['first'][0]['schoolinfo']->track.' - '.$data['first'][0]['schoolinfo']->strand}}</td>
            <td>SECTION</td>
            <td colspan="3" style="border-bottom: 1px solid;">{{$data['first'][0]['schoolinfo']->section}}</td>
        </tr>
    </table>
    {{-- @foreach($data['first'][0]['schoolinfo'] as $first) --}}
    <div class="nobreak">
        <table width="100%" class="student_data" style="text-transform:none;border: 2px solid;">
            <thead>
                <tr>
                    <th rowspan="2">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                    <th style="width:30%;" rowspan="2">SUBJECTS</th>
                    <th colspan="2">Quarter</th>
                    <th rowspan="2">SEM FINAL<br>GRADE</th>
                    <th rowspan="2">ACTION<br>TAKEN</th>
                </tr>
                <tr>
                    <th>1ST</th>
                    <th>2ND</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['first'][0]['grades'] as $datagrades)
                    @if($datagrades->subj_desc != 'General Average')
                    <tr>
                        <td><center>{{$datagrades->core}}<</center></td>
                        <td><center>{{$datagrades->subj_desc}}</center></td>
                        <td><center>{{$datagrades->quarter1}}</center></td>
                        <td><center>{{$datagrades->quarter2}}</center></td>
                        <td><center>{{$datagrades->finalrating}}</center></td>
                        <td><center>{{$datagrades->action}}</center></td>
                    </tr>
                    @endif
                @endforeach
                <tr>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                </tr>
                <tr>
                    <th colspan="4"><strong>General Ave. for the Semester:</strong></th>
                    <td>
                        @foreach ($data['first'][0]['grades'] as $datagrades)
                            @if($datagrades->subj_desc == 'General Average')
                            <center>{{$datagrades->finalrating}}</center>
                            @endif
                        @endforeach
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:10%">REMARKS: </td>
                <td colspan="2" style="border-bottom: 1px solid;"></td>
            </tr>
        </table>
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:35%">Prepared by: </td>
                <td style="width:35%">Certified True Correct:</td>
                <td style="width:30%">Date Checked (MM/DD/YYYY):</td>
            </tr>
            <tr>
                <td style="padding-right: 10%;">
                    <div style="width:100%; border-bottom: 1px solid black;"><center>{{$data['first'][0]['schoolinfo']->teacher}}</center></div>
                    <sup><center>Signature of Adviser over Printed Name</center></sup>
                </td>
                <td style="padding-right: 10%;">
                    <div style="width:100%; margin-left: 10%; border-bottom: 1px solid black;">&nbsp;</div>
                    <sup style="margin-left: 10%;"><center>SHS-School Record's In-charge</center></sup>
                </td>
                <td>
                    <div style="width:100%; border-bottom: 1px solid black;">&nbsp;</div>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div class="nobreak">
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:15%">REMEDIAL CLASSES</td>
                <td style="width:20%">Conducted from (MM/DD/YYYY):</td>
                <td style="border-bottom: 1px solid;width:5%">
                    @if(count($data['firstsum'])!=0 )
                        @if(count($data['firstsum'][0])!=0 )
                            {{-- {{$data['firstsum'][0]['grades']->conducted_from}} --}}
                        @endif
                    @endif
                </td>
                <td style="width:5%">,(MM/DD/YYYY):</td>
                <td style="border-bottom: 1px solid;width:5%">
                    @if(count($data['firstsum'])!=0)
                        @if(count($data['firstsum'][0])!=0 )
                            {{-- {{$data['firstsum'][0]['grades']->conducted_to}} --}}
                        @endif
                    @endif
                </td>
                <td style="width:5%">SCHOOL:</td>
                <td style="border-bottom: 1px solid;width:15%">
                    @if(count($data['firstsum'])!=0)
                        @if(count($data['firstsum'][0])!=0 )
                            {{$data['firstsum'][0]['schoolinfo']->schoolname}}
                        @endif
                    @endif
                </td>
                <td style="width:8%">SCHOOL ID:</td>
                <td style="border-bottom: 1px solid;width:15%">
                    @if(count($data['firstsum'])!=0)
                        @if(count($data['firstsum'][0])!=0 )
                            {{$data['firstsum'][0]['schoolinfo']->schoolid}}
                        @endif
                    @endif
                </td>
            </tr>
        </table>
        <table width="100%" class="student_data" style="text-transform:none;border: 2px solid;">
            <thead>
                <tr>
                    <th >Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                    <th style="width:30%;" >SUBJECTS</th>
                    <th >SEM FINAL GRADE</th>
                    <th >REMEDIAL<br>CLASS<br>MARK</th>
                    <th>RECOMPUTED<br>FINAL GRADE</th>
                    <th>ACTION<br>TAKEN</th>
                </tr>
            </thead>
            <tbody>
                @if(count($data['firstsum'])!=0)
                @foreach ($data['firstsum'][0]['grades'] as $datagrades)
                    <tr>
                        <td><center>{{$datagrades->core}}<</center></td>
                        <td><center>{{$datagrades->subjects}}</center></td>
                        <td><center>{{$datagrades->semfinal}}</center></td>
                        <td><center>{{$datagrades->classmark}}</center></td>
                        <td><center>{{$datagrades->recomputedgrade}}</center></td>
                        <td><center>{{$datagrades->action}}</center></td>
                    </tr>
                @endforeach
                @endif
                <tr>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                </tr>
            </tbody>
        </table>
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:20%">Name of Teacher/Adviser:</td>
                <td style="border-bottom: 1px solid;width:50%">
                    @if(count($data['firstsum'])!=0)
                        {{$data['firstsum'][0]['schoolinfo']->teacher}}
                    @endif
                </td>
                <td style="width:10%">Siganture:</td>
                <td style="border-bottom: 1px solid;"></td>
            </tr>
        </table>
    </div>
    <br>
    <div class="nobreak">
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:5%">SCHOOL:</td>
                <td style="border-bottom: 1px solid;width:%">
                    @if(count($data['secondsum'])!=0)
                        {{$data['second'][0]['schoolinfo']->schoolname}}
                    @endif
                </td>
                <td style="width:10%">SCHOOL ID:</td>
                <td style="border-bottom: 1px solid;width:%"></td>
                <td style="width:15%">GRADE LEVEL:</td>
                <td style="border-bottom: 1px solid;width:%">
                    @if(count($data['second'])!=0)
                        {{$data['second'][0]['schoolinfo']->levelname}}
                    @endif
                </td>
                <td style="width:5%">SY:</td>
                <td style="border-bottom: 1px solid;">
                    @if(count($data['second'])!=0)
                        {{$data['second'][0]['schoolinfo']->schoolyear}}
                    @endif
                </td>
                <td style="width:5%">SEM:</td>
                <td style="border-bottom: 1px solid;">2nd</td>
            </tr>
            <tr>
                <td>TRACK/STRAND:</td>
                <td colspan="5" style="border-bottom: 1px solid;">
                    @if(count($data['second'])!=0)
                        {{$data['second'][0]['schoolinfo']->track}} - {{$data['second'][0]['schoolinfo']->strand}}
                    @endif
                </td>
                <td>SECTION</td>
                <td colspan="3" style="border-bottom: 1px solid;">
                    @if(count($data['secondsum'])!=0)
                        {{$data['second'][0]['schoolinfo']->section}}
                    @endif
                </td>
            </tr>
        </table>
        <table width="100%" class="student_data" style="text-transform:none;border: 2px solid;">
            <thead>
                <tr>
                    <th rowspan="2">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                    <th style="width:30%;" rowspan="2">SUBJECTS</th>
                    <th colspan="2">Quarter</th>
                    <th rowspan="2">SEM FINAL<br>GRADE</th>
                    <th rowspan="2">ACTION<br>TAKEN</th>
                </tr>
                <tr>
                    <th>3rd</th>
                    <th>4th</th>
                </tr>
            </thead>
            <tbody>
                @if(count($data['second'])!=0)
                    @foreach ($data['second'][0]['grades'] as $datagrades)
                        @if($datagrades->subj_desc != 'General Average')
                        <tr>
                            <td><center>{{$datagrades->core}}<</center></td>
                            <td><center>{{$datagrades->subj_desc}}</center></td>
                            <td><center>{{$datagrades->quarter1}}</center></td>
                            <td><center>{{$datagrades->quarter2}}</center></td>
                            <td><center>{{$datagrades->finalrating}}</center></td>
                            <td><center>{{$datagrades->action}}</center></td>
                        </tr>
                        @endif
                    @endforeach
                @endif
                <tr>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                </tr>
                <tr>
                    <th colspan="4"><strong>General Ave. for the Semester:</strong></th>
                    <td>
                        @if(count($data['second'])!=0)
                            @foreach ($data['second'][0]['grades'] as $datagrades)
                                @if($datagrades->subj_desc == 'General Average')
                                <center>{{$datagrades->finalrating}}</center>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:10%">REMARKS:</td>
                <td colspan="2" style="border-bottom: 1px solid;"></td>
            </tr>
        </table>
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:35%">Prepared by:</td>
                <td style="width:35%">Certified True Correct:</td>
                <td style="width:30%">Date Checked (MM/DD/YYYY):</td>
            </tr>
            <tr>
                <td style="padding-right: 10%;">
                    <div style="width:100%; border-bottom: 1px solid black;">
                        @if(count($data['second'])!=0)
                            <center>{{$data['second'][0]['schoolinfo']->teacher}}</center>
                        @endif
                    </div>
                    <sup><center>Signature of Adviser over Printed Name</center></sup>
                </td>
                <td style="padding-right: 10%;">
                    <div style="width:100%; margin-left: 10%; border-bottom: 1px solid black;">&nbsp;</div>
                    <sup style="margin-left: 10%;"><center>SHS-School Record's In-charge</center></sup>
                </td>
                <td>
                    <div style="width:100%; border-bottom: 1px solid black;">&nbsp;</div>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div class="nobreak">
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:15%">REMEDIAL CLASSES</td>
                <td style="width:20%">Conducted from (MM/DD/YYYY):</td>
                <td style="border-bottom: 1px solid;width:5%">
                    @if(count($data['secondsum'])!=0)
                        {{-- {{$data['secondsum'][0]->conducted_from}} --}}
                    @endif
                </td>
                <td style="width:5%">,(MM/DD/YYYY):</td>
                <td style="border-bottom: 1px solid;width:5%">
                    @if(count($data['secondsum'])!=0)
                        {{-- {{$data[1]['summer'][0]->conducted_to}} --}}
                    @endif
                </td>
                <td style="width:5%">SCHOOL:</td>
                <td style="border-bottom: 1px solid;width:15%">
                    @if(count($data['secondsum'])!=0)
                        {{$data['secondsum'][0]['schoolinfo']->schoolname}}
                    @endif
                </td>
                <td style="width:8%">SCHOOL ID:</td>
                <td style="border-bottom: 1px solid;width:15%">
                    @if(count($data['secondsum'])!=0)
                    <center>{{$data['secondsum'][0]['schoolinfo']->schoolid}}</center>
                    @endif
                </td>
            </tr>
        </table>
        <table width="100%" class="student_data" style="text-transform:none;border: 2px solid;">
            <thead>
                <tr>
                    <th >Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                    <th style="width:30%;" >SUBJECTS</th>
                    <th >SEM FINAL GRADE</th>
                    <th >REMEDIAL<br>CLASS<br>MARK</th>
                    <th>RECOMPUTED<br>FINAL GRADE</th>
                    <th>ACTION<br>TAKEN</th>
                </tr>
            </thead>
            <tbody>
                @if(count($data['secondsum'])!=0)
                @foreach ($data['secondsum'][0]['grades'] as $datagrades)
                    <tr>
                        <td><center>{{$datagrades->core}}<</center></td>
                        <td><center>{{$datagrades->subjects}}</center></td>
                        <td><center>{{$datagrades->semfinal}}</center></td>
                        <td><center>{{$datagrades->classmark}}</center></td>
                        <td><center>{{$datagrades->recomputedgrade}}</center></td>
                        <td><center>{{$datagrades->action}}</center></td>
                    </tr>
                @endforeach
                @endif
                <tr>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                    <td><center>&nbsp;</center></td>
                </tr>
            </tbody>
        </table>
        <table class="student_info" style="text-align: none; text-transform:none;">
            <tr>
                <td style="width:20%">Name of Teacher/Adviser:</td>
                <td style="border-bottom: 1px solid;width:50%">
                    @if(count($data['secondsum'])!=0)
                        <center>{{$data['secondsum'][0]['schoolinfo']->teacher}}</center>
                    @endif
                </td>
                <td style="width:10%">Signature:</td>
                <td style="border-bottom: 1px solid;"></td>
            </tr>
        </table>
    </div>
    <br>
@endforeach
{{-- <br> --}}

<div style="width: 100%; background-color:#d9d9d9; text-align:center;font-size: 10px; margin-top: 5px;">&nbsp;</div>

<div class="nobreak">
    <table class="student_info" style="text-align: none; text-transform:none;">
        <tr>
            <td style="width:20%"><strong>Strand Accomplished:</strong></td>
            <td style="border-bottom: 1px solid;width:50%">

            </td>
            <td style="width:15%"><strong>SHS General Average:</strong></td>
            <td style="border-bottom: 1px solid;"></td>
        </tr>
    </table>
    <table class="student_info" style="text-align: none; text-transform:none;">
        <tr>
            <td style="width:20%"><strong>Awards/Honors Recieved:</strong></td>
            <td style="border-bottom: 1px solid;width:40%">

            </td>
            <td style="width:28%"><strong>Date of SHS Graduation (MM/DD/YYYY):</strong></td>
            <td style="border-bottom: 1px solid;"></td>
        </tr>
    </table>
    <table class="student_info" style="text-align: none; text-transform:none;">
        <tr>
            <td style="width: 50%;"><strong>Certified by:</strong></td>
            <td style="width: 50%;"><strong>Place School Seal Here:</strong></td>
        </tr>
        <tr>
            <td style="border-right: 1px solid;">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 60%;">
                            <div style="width: 90%; border-bottom: 1px solid;">
                                <strong>&nbsp;</strong>
                            </div>
                        </td>
                        <td style="width: 40%; ">
                            <div style="width: 90%; border-bottom: 1px solid;">
                                <center><strong>{{$currentDate}}</strong></center>
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
        <tr>
            <td colspan="2">
                <strong>REMARKS: Copy for</strong> <span style="font-size: 12px;"><strong>PMA TRAINING REQUIREMENT</strong></span>  <strong>Purposes Only.</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <br>
                <strong>Date Issued (MM/DD/YYYY):</strong>&nbsp;&nbsp;<span style="font-size: 12px;"><strong><u>{{$currentDate}}</u></strong></span>  
            </td>
        </tr>
    </table>
</div>
