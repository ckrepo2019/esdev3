
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
    }
    .mytextwithicon {
    position:relative;
}    
.mytextwithicon:before {
    content: "\25AE";  /* this is your text. You can also use UTF-8 character codes as I do here */
    font-family: FontAwesome;
    left:-5px;
    position:absolute;
    top:0;
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
</style>
<table class="header">
    <tr>
        <td width="10%" style="text-align:right;"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px"></td>
        <td>
            <center>
                <small style="font-size: 10px">Republic of the Philippines</small>
                <br>
                <small style="font-size: 10px">Department of Education</small>
                <br>
                <strong style="font-size: 15px">Learner's Permanent Academic Record for Junior High School (SF10-JHS)</strong>
                <br>
                <small style="font-size: 10px"><em>(Formerly Form 137)</em></small>
            </center>
        </td>
        <td width="10%" style="text-align:right;"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="70px"></td>
    </tr>
</table>
&nbsp;
<div style="width: 100%; background-color:#d6d6c2; text-align:center;font-size: 13px"><strong>LEARNER'S INFORMATION</strong></div>
<table class="student_info" style="text-align: center">
    <tr>
        <td style="width:9%;">LAST NAME :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->lastname}}</td>
        <td style="width:10%;">FIRST NAME :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->firstname}}</td>
        <td style="width:15%;">NAME EXTN. (Jr,I,II) :</td>
        <td style="border-bottom: 1px solid black;width:5%;">{{$student_info[0]->suffix}}</td>
        <td style="width:12%;">MIDDLE NAME :</td>
        <td style="border-bottom: 1px solid black;width:10%;">{{$student_info[0]->middlename}}</td>
    </tr>
</table>
<table class="student_info" style="text-align: none;">
    <tr>
        <td style="text-transform: none;width:23%">Learner Reference Number (LRN) :</td>
        <td style="border-bottom: 1px solid black;width:20%">{{$student_info[0]->lrn}}</td>
        <td style="width:20%">Birthdate (mm/dd/yyyy) :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->dob}}</td>
        <td >Sex :</td>
        <td style="border-bottom: 1px solid black;">{{$student_info[0]->gender}}</td>
    </tr>
</table>
<br>
{{-- {{$eligibility}} --}}
<div style="width: 100%; background-color:#d6d6c2; text-align:center;font-size: 13px"><strong>ELIGIBILITY FOR JHS ENROLMENT</strong></div>
<div style="width: 100%;border: 1px solid black;margin-top: 2px;">
    <table class="student_info" style="text-align: none; text-transform:none;">
        <tr>
            <td style="width:25%">
                @if(count($eligibility)!=0)
                    <label>
                        <input type="checkbox" name="check-1" checked>Elementary School Completer
                    </label>
                @else
                    <label>
                        <input type="checkbox" name="check-1">Elementary School Completer
                    </label>
                @endif
            </td>
            {{-- <td style="border-bottom: 1px solid; width:10%"></td> --}}
            <td style="width:15%">General Average:</td>
            <td style="border-bottom: 1px solid">
                @if(count($eligibility)!=0)
                    <center>{{$eligibility[0]->gen_ave}}</center>
                @endif
            </td>
            <td style="width: 15%">Citation: (If Any)</td>
            <td style="border-bottom: 1px solid; width: 30%">
                @if(count($eligibility)!=0)
                    <center>{{$eligibility[0]->citation}}</center>
                @endif
            </td>
        </tr>
    </table>
    <table class="student_info" style="text-align: none; text-transform:none;">
        <tr>
            <td style="width:20%">Name of Elementary School:</td>
            <td style="width:20%">
                @if(count($eligibility)!=0)
                    <center>{{$eligibility[0]->schoolname}}</center>
                @endif
            </td>
            <td style="width:15%">School ID:</td>
            <td>
                @if(count($eligibility)!=0)
                    <center>{{$eligibility[0]->schoolid}}</center>
                @endif
            </td>
            <td style="width: 15%">Address of School:</td>
            <td style="width: 30%">
                @if(count($eligibility)!=0)
                    <center>{{$eligibility[0]->schooladdress}}</center>
                @endif
            </td>
        </tr>
    </table>
</div>
<table class="student_info" style="text-align: none; text-transform:none;">
    <tr>
        <td colspan="6">Other Credential Presented</td>
    </tr>
    <tr>
        <td style="width:25%">
            @if(count($eligibility)!=0)
                @if($eligibility[0]->passer == 'pept')
                    <label>
                        <input type="checkbox" name="check-1" checked>PEPT Passer  &nbsp; &nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$eligibility[0]->rating}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                    </label>
                @else
                    <label>
                        <input type="checkbox" name="check-1">PEPT Passer  &nbsp; &nbsp;Rating:
                    </label>
                @endif
            @endif
        </td>
        {{-- <td style="border-bottom: 1px solid;">
            @if(count($eligibility)!=0)
                
            @endif
        </td> --}}
        <td style="width:30%">
            @if(count($eligibility)!=0)
                @if($eligibility[0]->passer == 'als')
                    <label>
                        <input type="checkbox" name="check-1" checked>ALS A & E Passer &nbsp; &nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$eligibility[0]->rating}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                    </label>
                @else
                    <label>
                        <input type="checkbox" name="check-1">ALS A & E Passer &nbsp; &nbsp;Rating: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
                    </label>
                @endif
            @endif
        </td>
        {{-- <td style="border-bottom: 1px solid; "></td> --}}
        <td style="width: 15%">Others (Pls. Specify):</td>
        <td style="border-bottom: 1px solid; width: 30%"></td>
    </tr>
</table>
<table class="student_info" style="text-align: none; text-transform:none;">
    <tr>
        <td style="width:25%">Date of Examination/Assessment (mm/dd/yyyy):</td>
        <td style="border-bottom: 1px solid;width: 10%;">
            @if(count($eligibility)!=0)
                {{$eligibility[0]->exam_date}}
            @endif
        </td>
        <td style="width:20%">Name and Address of Testing Center:</td>
        <td style="border-bottom: 1px solid; width:25%">
            @if(count($eligibility)!=0)
                {{$eligibility[0]->learning_center_name}} / {{$eligibility[0]->learning_center_address}}
            @endif
        </td>
    </tr>
</table>
<br>
<div style="width: 100%; background-color:#d6d6c2; text-align:center;font-size: 13px"><strong>SCHOLASTIC RECORD</strong></div>
<br>
<div style="width: 100%; border: 2px solid;">
@foreach($res_data as $data )
    <table width="100%" class="student_info" style="padding-bottom: 2px;font-size:8px;text-transform: none;">
        <tr>
            <td style="width: 1%;">School:</td>
            <td style="width: 20%;"><center>{{$data->schoolname}}</center></td>
            <td style="">School ID:</td>
            <td style="width: 10%;"><center>1152356</center></td>
            <td style="">District:</td>
            <td style=""><center>{{$data->district}}</center></td>
            <td style="">Division:</td>
            <td style=""><center>{{$data->division}}</center></td>
            <td style="">Region:</td>
            <td style=""><center>{{$data->region}}</center></td>
        </tr>
    </table>
    <table width="100%" class="student_info" style="padding-bottom: 1px;font-size:8px;text-transform: none;">
        <tr>
            <td style="">Classified as Grade:</td>
            <td style="">{{$data->levelname}}</td>
            <td style="">Section:</td>
            <td style=""><center></center></td>
            <td style="">School Year:</td>
            <td style="">{{$data->sy}}</td>
            <td style="">Name of Adviser/Teacher:</td>
            <td style=""><center>{{$data->teacher}}</center></td>
            <td style="">Signature:</td>
            <td style=""><center></center></td>
        </tr>
    </table>
    <table width="100%" class="student_data">
        <tr>
            <th style="width:30%;">LEARNING AREAS</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>FINAL RATING</th>
            <th>REMARKS</th>
            {{-- <th>CREDITS EARNED</th> --}}
        </tr>
        @foreach ($data->grades as $datagrades)
            @if($datagrades->subj_desc!='General Average')
            <tr>
                <td><center>{{$datagrades->subj_desc}}</center></td>
                <td><center>{{$datagrades->quarter1}}</center></td>
                <td><center>{{$datagrades->quarter2}}</center></td>
                <td><center>{{$datagrades->quarter3}}</center></td>
                <td><center>{{$datagrades->quarter4}}</center></td>
                <td><center>{{$datagrades->finalrating}}</center></td>
                <td><center>{{$datagrades->action}}</center></td>
                {{-- <td><center>{{$datagrades->credits}}</center></td> --}}
            </tr>
            @endif
        @endforeach
        <tr>
            <th style="width:30%;">&nbsp;</th>
            <th colspan="4"><strong><em>General Average</em></strong></th>
            <th>
                @foreach ($data->grades as $datagrades)
                    @if($datagrades->subj_desc=='General Average')
                        {{$datagrades->finalrating}}
                    @endif
                @endforeach
            </th>
            <th>&nbsp;</th>
            {{-- <th>CREDITS EARNED</th> --}}
        </tr>
    </table>
    <br>
@endforeach
</div>
<br>
<div style="width: 100%; border: 2px solid;font-size:10px; padding: 2px;">
<strong style="font-size:11px"><center>CERTIFICATION</center></strong>
<br>
I CERTIFY that this is a true record of <strong>{{$student_info[0]->lastname}}, {{$student_info[0]->firstname}} {{$student_info[0]->middlename[0]}}. {{$student_info[0]->suffix}}</strong> with LRN: {{$student_info[0]->lrn}}
<br>
<br>
<br>
<table style="width: 100%">
    <tr>
        <td>
            <strong>REMARKS</strong>
        </td>
        <td>
            Copy for <strong><u>DCAA Purposes Only</u></strong>
        </td>
    </tr>
</table>
<br>
<br>
<table style="width: 100%">
    <tr>
        <td style="text-align:center;padding-left: 10px;padding-right: 10px;">
            <div style="width: 100%;border-bottom: 1px solid;">
                <strong>{{strtoupper($getTeacher[0]->lastname)}}, {{strtoupper($getTeacher[0]->firstname)}} {{strtoupper($getTeacher[0]->middlename[0].'.')}} {{strtoupper($getTeacher[0]->suffix)}}</strong>
            </div>
        </td>
        <td style="text-align:center;padding-left: 10px;padding-right: 10px;">
            <div style="width: 100%;border-bottom: 1px solid;">
                <strong>&nbsp;</strong>
            </div>
        </td>
        <td style="text-align:center;padding-left: 10px;padding-right: 10px;">
            <div style="width: 100%;border-bottom: 1px solid;">
                <strong>{{$schoolinfo[0]->authorized}}</strong>
            </div>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;padding-left: 10px;padding-right: 10px;">
            <div style="width: 100%;">
                <strong>Class Adviser</strong>
            </div>
        </td>
        <td style="text-align:center;padding-left: 10px;padding-right: 10px;">
            <div style="width: 100%;">
                <strong>Records In-Charge</strong>
            </div>
        </td>
        <td style="text-align:center;padding-left: 10px;padding-right: 10px;">
            <div style="width: 100%;">
                <strong>School Head</strong>
            </div>
        </td>
    </tr>
</table>
<br>
<br>
<table style="width: 100%">
    <tr>
        <td style="">
            <strong>SCHOOL SEAL</strong>
        </td>
        <td  style="width:50%">
            <center><u><strong>{{$currentDate}}</strong></u></center>
        </td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
        <td >
            <center>Date</center>
        </td>
    </tr>
</table>
{{-- <span style="margin-right: 20px;"></span><span></span> --}}
</div>