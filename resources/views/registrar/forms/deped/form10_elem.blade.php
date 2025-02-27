<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ELEM - School Form 10</title>
        <style>
    * { font-family: Arial, Helvetica, sans-serif; }
    @page { margin: 20px 20px 5px 20px; size: {{$papersize != null ? $papersize : '8.5in 14in'}};} /*8.5 14*/

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
    </head>
    <body>
<table style="width: 100%" id="table1">
    <tr>
        <td width="15%" rowspan="4">
            {{-- <sup style="font-size: 9px;">SF10-JHS</sup><br/> --}}
        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
        </td>
        <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
        <td width="15%" style="text-align:right;" rowspan="4"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="80px"></td>
    </tr>
    <tr>
        <td style="text-align:center; font-size: 11px;">Department of Education</td>
    </tr>
    <tr>
        <td style="text-align:center; font-size: 15px; font-weight: bold;">Learner's Permanent Academic Record for Elementary School<br/>(SF10-ES)</td>
    </tr>
    <tr style="line-height: 5px;font-size: 11px;">
        <td style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%" id="table2">
    <tr>
        <td colspan="8" style="text-align: center; font-size: 13px; font-weight: bold; background-color: #aba9a9; border: 1px solid black;">LEARNER'S PERSONAL INFORMATION</td>
    </tr>
    {{-- <tr>
        <td colspan="8">&nbsp;</td>
    </tr> --}}
    <tr>
        <td style="width: 10%;">LAST NAME:</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{$studinfo->lastname}}</td>
        <td style="width: 10%;">FIRST NAME:</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{$studinfo->firstname}}</td>
        <td style="width: 15%;">NAME EXTN. (Jr,I,II)</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{$studinfo->suffix}}</td>
        <td style="width: 10%;">MIDDLE NAME</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{$studinfo->middlename}}</td>
    </tr>
</table>
<table style="width: 100%; font-size: 11px;" id="table3">
    <tr>
        <td style="width: 25%;">Learner Reference Number (LRN):</td>
        <td style="width: 20%; border-bottom: 1px solid black;">{{$studinfo->lrn}}</td>
        <td style="width: 20%; text-align: right;">Birthdate (mm/dd/yyyy):</td>
        <td style="width: 15%; border-bottom: 1px solid black;">{{date('m/d/Y',strtotime($studinfo->dob))}}</td>
        <td style="width: 10%; text-align: right;">Sex:</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;" id="table4">
    <tr>
        <td style="border: 1px solid black; background-color: #bababa">
            ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLMENT
        </td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<div style="width: 100%; border: 1px solid black; padding-top: 4px;">
    <table style="width: 100%; font-size: 11px;" id="table5">
        <tr style="font-style: italic;">
            <td>Credential Presented for Grade 1:</td>
            <td><input type="checkbox" name="check-1"@if($eligibility->kinderprogreport == 1) checked @endif>Kinder Progress Report</td>
            <td><input type="checkbox" name="check-1"@if($eligibility->eccdchecklist == 1) checked @endif>ECCD Checklist</td>
            <td><input type="checkbox" name="check-1"@if($eligibility->kindergartencert == 1) checked @endif>Kindergarten Certificate of Completion</td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 11px;" id="table6">
        <tr>
            <td style="width: 13%;">Name of School:</td>
            <td style="width: 25%; border-bottom: 1px solid black;">{{$eligibility->schoolname}}</td>
            <td style="width: 10%;">School ID:</td>
            <td style="border-bottom: 1px solid black;">{{$eligibility->schoolid}}</td>
            <td style="width: 15%;">Address of School:</td>
            <td style="width: 20%; border-bottom: 1px solid black;">{{$eligibility->schooladdress}}</td>
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
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="check-1"@if($eligibility->pept == 1) checked @endif>PEPT Passer &nbsp;&nbsp;&nbsp;&nbsp;Rating:<u>&nbsp;&nbsp;&nbsp;&nbsp;{{$eligibility->peptrating}}&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
        <td style="width: 32%;">Date of Examination/Assessment (mm/dd/yyyy):</td>
        <td style="width: 10%; border-bottom: 1px solid black;">@if($eligibility->examdate != null) {{date('m/d/Y',strtotime($eligibility->examdate))}} @endif</td>
        <td style="width: 18%;"><input type="checkbox" name="check-1">Others (Pls. Specify):</td>
        <td style="width: 12%; border-bottom: 1px solid black;">{{$eligibility->specifyothers}}</td>
    </tr>
</table>

<table style="width: 100%; font-size: 11px;" id="table8" >
    <tr>
        <td style="width: 28%; "> &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;Name and Address of Testing Center:</td>
        <td style="border-bottom: 1px solid black;" colspan="2">{{$eligibility->centername}}</td>
        <td style="width: 5%;">Remark:</td>
        <td style="border-bottom: 1px solid black;">{{$eligibility->remarks}}</td>
    </tr>
</table>
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
<table style="width: 100%; font-size: 12px; font-weight: bold; text-align: center;" id="table9">
    <tr>
        <td style="border: 1px solid black; background-color: #bababa">
            SCHOLASTIC RECORD
        </td>
    </tr>
</table>
@php
    $tablescount = 4;
    $tablescount-= count($records);
@endphp
<div style="width: 100%; line-height: 3px;">&nbsp;</div>
@if(count($records)>0)
    @foreach($records as $key => $record)
        @php
            $columngrades   = 15;
            $columngrades00 = $columngrades;
            $columngrades01 = $columngrades;
            $columngrades10 = $columngrades;
            $columngrades11 = $columngrades;
        @endphp        
              
            @if($key == 2)
                <table style="width: 100%; page-break-before: always;" id="table1">
                    <tr>
                        <td width="15%" ><sup style="font-size: 9px;">SF10-ES</sup></td>
                        <td width="10%"></td>
                        <td></td>
                        <td width="25%" style="font-size: 9px; text-align: right; padding-bottom: 10px;">page 2 of 2 pages&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
                @endif
                <table style="width: 100%; table-layout: fixed; font-size: 9.5px;">
                  <tr>
                      <td colspan="7" style="padding: 0px; vertical-align: top;">
                          <div style="width: 100%; border: 1px solid black; margin: 0px;">
                              <table style="width: 100%;">
                                  <tr>
                                      <td style="width: 10%;">School:</td>
                                      <td style="width: 50%; border-bottom: 1px solid black;">{{$record[0]->schoolname}}</td>
                                      <td style="width: 15%;">School ID:</td>
                                      <td style="width: 15%; border-bottom: 1px solid black;">{{$record[0]->schoolid}}</td>
                                  </tr>
                              </table>
                              <table style="width: 100%;">
                                  <tr>
                                      <td style="width: 10%;">District:</td>
                                      <td style="width: 25%; border-bottom: 1px solid black;">{{$record[0]->schooldistrict}}</td>
                                      <td style="width: 10%;">Division:</td>
                                      <td style="width: 25%; border-bottom: 1px solid black;">{{$record[0]->schooldivision}}</td>
                                      <td style="width: 10%;">Region:</td>
                                      <td style="width: 20%; border-bottom: 1px solid black;">{{$record[0]->schoolregion}}</td>
                                  </tr>
                              </table>
                              <table style="width: 100%;">
                                  <tr>
                                      <td style="width: 30%;">Classified as Grade:</td>
                                      <td style="width: 10%; border-bottom: 1px solid black;">{{$acadprogid == 3 ? preg_replace('/\D+/', '', $record[0]->levelname) : $record[0]->levelname}}</td>
                                      <td style="width: 10%;">Section:</td>
                                      <td style="width: 20%; border-bottom: 1px solid black;">{{$record[0]->sectionname}}</td>
                                      <td style="width: 10%;">SchoolYear:</td>
                                      <td style="width: 20%; border-bottom: 1px solid black;">{{$record[0]->sydesc}}</td>
                                  </tr>
                              </table>
                              <table style="width: 100%;">
                                  <tr>
                                      <td style="width: 30%;">Name of Adviser/Teacher:</td>
                                      <td style="border-bottom: 1px solid black;">{{$record[0]->teachername}}</td>
                                      <td style="width: 10%;">Signature:</td>
                                      <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                  </tr>
                              </table>
                              <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                          </div>
                      </td>
                      <td style="width: 1.2%;">&nbsp;</td>
                      <td colspan="7" style="padding: 0px; vertical-align: top;">
                          <div style="width: 100%; border: 1px solid black; margin: 0px;">
                              <table style="width: 100%;">
                                  <tr>
                                      <td style="width: 10%;">School:</td>
                                      <td style="width: 50%; border-bottom: 1px solid black;">{{$record[1]->schoolname}}</td>
                                      <td style="width: 15%;">School ID:</td>
                                      <td style="width: 15%; border-bottom: 1px solid black;">{{$record[1]->schoolid}}</td>
                                  </tr>
                              </table>
                              <table style="width: 100%;">
                                  <tr>
                                      <td style="width: 10%;">District:</td>
                                      <td style="width: 25%; border-bottom: 1px solid black;">{{$record[1]->schooldistrict}}</td>
                                      <td style="width: 10%;">Division:</td>
                                      <td style="width: 25%; border-bottom: 1px solid black;">{{$record[1]->schooldivision}}</td>
                                      <td style="width: 10%;">Region:</td>
                                      <td style="width: 20%; border-bottom: 1px solid black;">{{$record[1]->schoolregion}}</td>
                                  </tr>
                              </table>
                              <table style="width: 100%;">
                                  <tr>
                                      <td style="width: 30%;">Classified as Grade:</td>
                                      <td style="width: 10%; border-bottom: 1px solid black;">{{$acadprogid == 3 ? preg_replace('/\D+/', '', $record[1]->levelname) : $record[1]->levelname}}</td>
                                      <td style="width: 10%;">Section:</td>
                                      <td style="width: 20%; border-bottom: 1px solid black;">{{$record[1]->sectionname}}</td>
                                      <td style="width: 10%;">SchoolYear:</td>
                                      <td style="width: 20%; border-bottom: 1px solid black;">{{$record[1]->sydesc}}</td>
                                  </tr>
                              </table>
                              <table style="width: 100%;">
                                  <tr>
                                      <td style="width: 30%;">Name of Adviser/Teacher:</td>
                                      <td style="border-bottom: 1px solid black;">{{$record[1]->teachername}}</td>
                                      <td style="width: 10%;">Signature:</td>
                                      <td style="width: 20%; border-bottom: 1px solid black;"></td>
                                  </tr>
                              </table>
                              <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                          </div>
                      </td>
                  </tr>   
                  <tr>
                      <th rowspan="2" style="width: 35%; border: 1px solid black;">LEARNING AREAS</th>
                      <th colspan="4" style=" border: 1px solid black;">Quarterly</th>
                      <th rowspan="2" style="width: 10%; border: 1px solid black;">Final<br/>Rating</th>
                      <th rowspan="2" style="width: 15%; border: 1px solid black;">Remarks</th>
                      <td>&nbsp;</td>
                      <th rowspan="2" style="width: 35%; border: 1px solid black;">LEARNING AREAS</th>
                      <th colspan="4" style=" border: 1px solid black;">Quarterly</th>
                      <th rowspan="2" style="width: 10%; border: 1px solid black;">Final<br/>Rating</th>
                      <th rowspan="2" style="width: 15%; border: 1px solid black;">Remarks</th>
                  </tr>
                  <tr>
                      <th style="width: 8%; border: 1px solid black;">1</th>
                      <th style="width: 8%; border: 1px solid black;">2</th>
                      <th style="width: 8%; border: 1px solid black;">3</th>
                      <th style="width: 8%; border: 1px solid black;">4</th>
                      <td>&nbsp;</td>
                      <th style="width: 8%; border: 1px solid black;">1</th>
                      <th style="width: 8%; border: 1px solid black;">2</th>
                      <th style="width: 8%; border: 1px solid black;">3</th>
                      <th style="width: 8%; border: 1px solid black;">4</th>
                  </tr>             
                    @for($x=0; $x<15; $x++)
                        <tr>
                            @if(count($record[0]->grades)>0)
                                @if(isset($record[0]->grades[$x]))
                                    @if(strtolower($record[0]->grades[$x]->subjtitle) != 'general average')
                                        <td style="border: 1px solid black;">@if($record[0]->grades[$x]->inMAPEH == 1 || $record[0]->grades[$x]->inTLE) &nbsp;&nbsp;&nbsp;@endif&nbsp;&nbsp;{{$record[0]->grades[$x]->subjtitle}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[0]->grades[$x]->quarter1}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[0]->grades[$x]->quarter2}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[0]->grades[$x]->quarter3}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[0]->grades[$x]->quarter4}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[0]->grades[$x]->finalrating > 0 ? $record[0]->grades[$x]->finalrating : ''}}</td>
                                        <td style="border: 1px solid black; text-align: center;">@if($record[0]->grades[$x]->finalrating > 0){{$record[0]->grades[$x]->finalrating >= 75? 'PASSED':'FAILED'}}@endif</td>
                                        @else
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                    @endif
                                @else
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                @endif
                            @else
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                                @if(collect($gradelevels)->where('levelid',$record[1]->levelid)->count()>0)
                                
                                    @if($x == 0)
                                    <td style="border: 1px solid black; font-weight: bold;">Christian Living Education			
                                    </td>
                                    @elseif($x == 1)
                                    <td style="border: 1px solid black; font-weight: bold;">English			
                                    </td>
                                    @elseif($x == 2)
                                    <td style="border: 1px solid black; font-weight: bold;">Mathematics			
                                    </td>
                                    @elseif($x == 3)
                                    <td style="border: 1px solid black; font-weight: bold;">Science</td>
                                    @elseif($x == 4)
                                    <td style="border: 1px solid black; font-weight: bold;">Livelihood Education</td>
                                    @elseif($x == 5)
                                    <td style="border: 1px solid black; font-weight: bold;">Filipino</td>
                                    @elseif($x == 6)
                                    <td style="border: 1px solid black; font-weight: bold;">Araling Panlipunan</td>
                                    @elseif($x == 7)
                                    <td style="border: 1px solid black; font-weight: bold;">MAPEH</td>
                                    @elseif($x == 8)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Music</em></td>
                                    @elseif($x == 9)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Arts</em></td>
                                    @elseif($x == 10)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Physical Education /Health</em></td>
                                    @elseif($x == 11)
                                    <td style="border: 1px solid black; font-weight: bold;">Computer</td>
                                    @else
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    @endif
                                @else
                                    @if($x == 0)
                                    <td style="border: 1px solid black; font-weight: bold;">Christian Living Education			
                                    </td>
                                    @elseif($x == 1)
                                    <td style="border: 1px solid black; font-weight: bold;">English			
                                    </td>
                                    @elseif($x == 2)
                                    <td style="border: 1px solid black; font-weight: bold;">Mathematics			
                                    </td>
                                    @elseif($x == 3)
                                    <td style="border: 1px solid black; font-weight: bold;">Science</td>
                                    @elseif($x == 4)
                                    <td style="border: 1px solid black; font-weight: bold;">Livelihood Education</td>
                                    @elseif($x == 5)
                                    <td style="border: 1px solid black; font-weight: bold;">Filipino</td>
                                    @elseif($x == 6)
                                    <td style="border: 1px solid black; font-weight: bold;">Araling Panlipunan</td>
                                    @elseif($x == 7)
                                    <td style="border: 1px solid black; font-weight: bold;">MAPEH</td>
                                    @elseif($x == 8)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Music</em></td>
                                    @elseif($x == 9)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Arts</em></td>
                                    @elseif($x == 10)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Physical Education /Health</em></td>
                                    @elseif($x == 11)
                                    <td style="border: 1px solid black; font-weight: bold;">Computer</td>
                                    @else
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    @endif
                                @endif
                            @else
                                @if(collect($gradelevels)->where('levelid',$record[1]->levelid)->count()>0)
                                
                                    @if($x == 0)
                                    <td style="border: 1px solid black; font-weight: bold;">Mother Tongue				
                                    </td>
                                    @elseif($x == 1)
                                    <td style="border: 1px solid black; font-weight: bold;">Filipino			
                                    </td>
                                    @elseif($x == 2)
                                    <td style="border: 1px solid black; font-weight: bold;">English			
                                    </td>
                                    @elseif($x == 3)
                                    <td style="border: 1px solid black; font-weight: bold;">Mathematics</td>
                                    @elseif($x == 4)
                                    <td style="border: 1px solid black; font-weight: bold;">Science</td>
                                    @elseif($x == 5)
                                    <td style="border: 1px solid black; font-weight: bold;">Araling Panlipunan</td>
                                    @elseif($x == 6)
                                    <td style="border: 1px solid black; font-weight: bold;">EPP / TLE</td>
                                    @elseif($x == 7)
                                    <td style="border: 1px solid black; font-weight: bold;">MAPEH</td>
                                    @elseif($x == 8)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Music</em></td>
                                    @elseif($x == 9)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Arts</em></td>
                                    @elseif($x == 10)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Physical Education</em></td>
                                    @elseif($x == 11)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Health</em></td>
                                    @elseif($x == 12)
                                    <td style="border: 1px solid black; font-weight: bold;">Eduk. sa Pagpapakatao</td>
                                    @elseif($x == 13)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;*Arabic Language</td>
                                    @elseif($x == 14)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;*Islamic Values Education</td>
                                    @else
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    @endif
                                @else
                                    @if($x == 0)
                                    <td style="border: 1px solid black; font-weight: bold;">Mother Tongue				
                                    </td>
                                    @elseif($x == 1)
                                    <td style="border: 1px solid black; font-weight: bold;">Filipino			
                                    </td>
                                    @elseif($x == 2)
                                    <td style="border: 1px solid black; font-weight: bold;">English			
                                    </td>
                                    @elseif($x == 3)
                                    <td style="border: 1px solid black; font-weight: bold;">Mathematics</td>
                                    @elseif($x == 4)
                                    <td style="border: 1px solid black; font-weight: bold;">Science</td>
                                    @elseif($x == 5)
                                    <td style="border: 1px solid black; font-weight: bold;">Araling Panlipunan</td>
                                    @elseif($x == 6)
                                    <td style="border: 1px solid black; font-weight: bold;">EPP / TLE</td>
                                    @elseif($x == 7)
                                    <td style="border: 1px solid black; font-weight: bold;">MAPEH</td>
                                    @elseif($x == 8)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Music</em></td>
                                    @elseif($x == 9)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Arts</em></td>
                                    @elseif($x == 10)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Physical Education</em></td>
                                    @elseif($x == 11)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Health</em></td>
                                    @elseif($x == 12)
                                    <td style="border: 1px solid black; font-weight: bold;">Eduk. sa Pagpapakatao</td>
                                    @elseif($x == 13)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;*Arabic Language</td>
                                    @elseif($x == 14)
                                    <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;*Islamic Values Education</td>
                                    @else
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    @endif
                                @endif
                            @endif
                            <td style="border: 1px solid black;">&nbsp;</td>
                            <td style="border: 1px solid black;">&nbsp;</td>
                            <td style="border: 1px solid black;">&nbsp;</td>
                            <td style="border: 1px solid black;">&nbsp;</td>
                            <td style="border: 1px solid black;">&nbsp;</td>
                            <td style="border: 1px solid black;">&nbsp;</td>
                            @endif
                            <td></td>
                            @if(count($record[1]->grades)>0)
                                @if(isset($record[1]->grades[$x]))
                                    @if(strtolower($record[1]->grades[$x]->subjtitle) != 'general average')
                                        <td style="border: 1px solid black;">@if($record[1]->grades[$x]->inMAPEH == 1 || $record[1]->grades[$x]->inTLE) &nbsp;&nbsp;&nbsp;@endif&nbsp;&nbsp;{{$record[1]->grades[$x]->subjtitle}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[1]->grades[$x]->quarter1}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[1]->grades[$x]->quarter2}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[1]->grades[$x]->quarter3}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[1]->grades[$x]->quarter4}}</td>
                                        <td style="border: 1px solid black; text-align: center;">{{$record[1]->grades[$x]->finalrating > 0 ? $record[1]->grades[$x]->finalrating : ''}}</td>
                                        <td style="border: 1px solid black; text-align: center;">@if($record[1]->grades[$x]->finalrating > 0){{$record[1]->grades[$x]->finalrating >= 75? 'PASSED':'FAILED'}}@endif</td>
                                    @else
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                        <td style="border: 1px solid black;">&nbsp;</td>
                                    @endif
                                @else
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                @endif
                            @else
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                                        @if(collect($gradelevels)->where('levelid',$record[1]->levelid)->count()>0)
                                        
                                            @if($x == 0)
                                            <td style="border: 1px solid black; font-weight: bold;">Christian Living Education			
                                            </td>
                                            @elseif($x == 1)
                                            <td style="border: 1px solid black; font-weight: bold;">English			
                                            </td>
                                            @elseif($x == 2)
                                            <td style="border: 1px solid black; font-weight: bold;">Mathematics			
                                            </td>
                                            @elseif($x == 3)
                                            <td style="border: 1px solid black; font-weight: bold;">Science</td>
                                            @elseif($x == 4)
                                            <td style="border: 1px solid black; font-weight: bold;">Livelihood Education</td>
                                            @elseif($x == 5)
                                            <td style="border: 1px solid black; font-weight: bold;">Filipino</td>
                                            @elseif($x == 6)
                                            <td style="border: 1px solid black; font-weight: bold;">Araling Panlipunan</td>
                                            @elseif($x == 7)
                                            <td style="border: 1px solid black; font-weight: bold;">MAPEH</td>
                                            @elseif($x == 8)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Music</em></td>
                                            @elseif($x == 9)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Arts</em></td>
                                            @elseif($x == 10)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Physical Education /Health</em></td>
                                            @elseif($x == 11)
                                            <td style="border: 1px solid black; font-weight: bold;">Computer</td>
                                            @else
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            @endif
                                        @else
                                            @if($x == 0)
                                            <td style="border: 1px solid black; font-weight: bold;">Christian Living Education			
                                            </td>
                                            @elseif($x == 1)
                                            <td style="border: 1px solid black; font-weight: bold;">English			
                                            </td>
                                            @elseif($x == 2)
                                            <td style="border: 1px solid black; font-weight: bold;">Mathematics			
                                            </td>
                                            @elseif($x == 3)
                                            <td style="border: 1px solid black; font-weight: bold;">Science</td>
                                            @elseif($x == 4)
                                            <td style="border: 1px solid black; font-weight: bold;">Livelihood Education</td>
                                            @elseif($x == 5)
                                            <td style="border: 1px solid black; font-weight: bold;">Filipino</td>
                                            @elseif($x == 6)
                                            <td style="border: 1px solid black; font-weight: bold;">Araling Panlipunan</td>
                                            @elseif($x == 7)
                                            <td style="border: 1px solid black; font-weight: bold;">MAPEH</td>
                                            @elseif($x == 8)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Music</em></td>
                                            @elseif($x == 9)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Arts</em></td>
                                            @elseif($x == 10)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Physical Education /Health</em></td>
                                            @elseif($x == 11)
                                            <td style="border: 1px solid black; font-weight: bold;">Computer</td>
                                            @else
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            @endif
                                        @endif
                                    @else
                                        @if(collect($gradelevels)->where('levelid',$record[1]->levelid)->count()>0)
                                        
                                            @if($x == 0)
                                            <td style="border: 1px solid black; font-weight: bold;">Mother Tongue				
                                            </td>
                                            @elseif($x == 1)
                                            <td style="border: 1px solid black; font-weight: bold;">Filipino			
                                            </td>
                                            @elseif($x == 2)
                                            <td style="border: 1px solid black; font-weight: bold;">English			
                                            </td>
                                            @elseif($x == 3)
                                            <td style="border: 1px solid black; font-weight: bold;">Mathematics</td>
                                            @elseif($x == 4)
                                            <td style="border: 1px solid black; font-weight: bold;">Science</td>
                                            @elseif($x == 5)
                                            <td style="border: 1px solid black; font-weight: bold;">Araling Panlipunan</td>
                                            @elseif($x == 6)
                                            <td style="border: 1px solid black; font-weight: bold;">EPP / TLE</td>
                                            @elseif($x == 7)
                                            <td style="border: 1px solid black; font-weight: bold;">MAPEH</td>
                                            @elseif($x == 8)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Music</em></td>
                                            @elseif($x == 9)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Arts</em></td>
                                            @elseif($x == 10)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Physical Education</em></td>
                                            @elseif($x == 11)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Health</em></td>
                                            @elseif($x == 12)
                                            <td style="border: 1px solid black; font-weight: bold;">Eduk. sa Pagpapakatao</td>
                                            @elseif($x == 13)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;*Arabic Language</td>
                                            @elseif($x == 14)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;*Islamic Values Education</td>
                                            @else
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            @endif
                                        @else
                                            @if($x == 0)
                                            <td style="border: 1px solid black; font-weight: bold;">Mother Tongue				
                                            </td>
                                            @elseif($x == 1)
                                            <td style="border: 1px solid black; font-weight: bold;">Filipino			
                                            </td>
                                            @elseif($x == 2)
                                            <td style="border: 1px solid black; font-weight: bold;">English			
                                            </td>
                                            @elseif($x == 3)
                                            <td style="border: 1px solid black; font-weight: bold;">Mathematics</td>
                                            @elseif($x == 4)
                                            <td style="border: 1px solid black; font-weight: bold;">Science</td>
                                            @elseif($x == 5)
                                            <td style="border: 1px solid black; font-weight: bold;">Araling Panlipunan</td>
                                            @elseif($x == 6)
                                            <td style="border: 1px solid black; font-weight: bold;">EPP / TLE</td>
                                            @elseif($x == 7)
                                            <td style="border: 1px solid black; font-weight: bold;">MAPEH</td>
                                            @elseif($x == 8)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Music</em></td>
                                            @elseif($x == 9)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Arts</em></td>
                                            @elseif($x == 10)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Physical Education</em></td>
                                            @elseif($x == 11)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;<em>Health</em></td>
                                            @elseif($x == 12)
                                            <td style="border: 1px solid black; font-weight: bold;">Eduk. sa Pagpapakatao</td>
                                            @elseif($x == 13)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;*Arabic Language</td>
                                            @elseif($x == 14)
                                            <td style="border: 1px solid black;">&nbsp;&nbsp;&nbsp;*Islamic Values Education</td>
                                            @else
                                            <td style="border: 1px solid black;">&nbsp;</td>
                                            @endif
                                        @endif
                                    @endif
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                                    <td style="border: 1px solid black;">&nbsp;</td>
                            @endif
                        </tr>
                    @endfor
                    <tr style="font-weight: bold;">
                        <td style="border: 1px solid black;">General Average</td>
                        <td style="border: 1px solid black;">&nbsp;</td>
                        <td style="border: 1px solid black;">&nbsp;</td>
                        <td style="border: 1px solid black;">&nbsp;</td>
                        <td style="border: 1px solid black;">&nbsp;</td>
                            <td style="border: 1px solid black; text-align: center;">@if(isset($record[0]->generalaverage)){{collect($record[0]->generalaverage)->first()->finalrating ?? ''}}@endif</td>
                            <td style="border: 1px solid black; text-align: center;">@if(isset($record[0]->generalaverage))@if(collect($record[0]->generalaverage)->count()>0)@if(collect($record[0]->generalaverage)->first()->finalrating > 0){{collect($record[0]->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif @endif @endif</td>
                
                        <td></td>
                        <td style="border: 1px solid black;">General Average</td>
                        <td style="border: 1px solid black;">&nbsp;</td>
                        <td style="border: 1px solid black;">&nbsp;</td>
                        <td style="border: 1px solid black;">&nbsp;</td>
                        <td style="border: 1px solid black;">&nbsp;</td>
                        <td style="border: 1px solid black; text-align: center;">@if(isset($record[1]->generalaverage)){{collect($record[1]->generalaverage)->first()->finalrating ?? ''}}@endif</td>
                        <td style="border: 1px solid black; text-align: center;">@if(isset($record[1]->generalaverage))@if(collect($record[1]->generalaverage)->count()>0)@if(collect($record[1]->generalaverage)->first()->finalrating > 0){{collect($record[1]->generalaverage)->first()->finalrating >= 75 ? 'PASSED' : 'FAILED'}}@endif @endif @endif</td>
                    </tr>

                    
                    <tr>
                        <td colspan="7" class="p-0">
                            @if(collect($eachgradesignatories)->where('levelid', $record[0]->levelid)->isNotEmpty())
                                @foreach(collect($eachgradesignatories)->where('levelid', $record[0]->levelid)->values() as $eachsign)
                                    <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 3px;">
                                        <tr>
                                            <td>REMARKS:</td>
                                            <td style="border-bottom: 1px solid black;" colspan="4"> {{$eachsign->remarks}}</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                                        <tr>
                                            <td style="vertical-align: top">Prepared by:</td>
                                            <td></td>
                                            <td style="vertical-align: top">Certified True and Correct:</td>
                                            <td></td>
                                            <td style="vertical-align: top">Date Checked (MM/DD/YYYY)</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$eachsign->certncorrectname}}</td>
                                            <td style="width: 5%;"></td>
                                            <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$eachsign->certncorrectname}}</td>
                                            <td style="width: 5%;"></td>
                                            <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{ $eachsign->datechecked ? date('m/d/Y', strtotime($eachsign->datechecked)) : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Signature of Adviser over Printed Name</td>
                                            <td></td>
                                            <td class="text-center">&nbsp;{{$eachsign->certncorrectdesc}}</td>
                                            <td></td>
                                            <td class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"></td>
                                        </tr>
                                    </table>
                                @endforeach
                            @else
                                <!-- Display the blank format if there is no data -->
                                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 3px;">
                                    <tr>
                                        <td>REMARKS:</td>
                                        <td style="border-bottom: 1px solid black;" colspan="4"></td>
                                    </tr>
                                </table>
                                <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                                    <tr>
                                        <td style="vertical-align: top">Prepared by:</td>
                                        <td></td>
                                        <td style="vertical-align: top">Certified True and Correct:</td>
                                        <td></td>
                                        <td style="vertical-align: top">Date Checked (MM/DD/YYYY)</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;</td>
                                        <td style="width: 5%;"></td>
                                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;</td>
                                        <td style="width: 5%;"></td>
                                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Signature of Adviser over Printed Name</td>
                                        <td></td>
                                        <td class="text-center">&nbsp;</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                        <td></td>
                        <td colspan="7" class="p-0">
                            @if(collect($eachgradesignatories)->where('levelid', $record[1]->levelid)->isNotEmpty())
                                @foreach(collect($eachgradesignatories)->where('levelid', $record[0]->levelid)->values() as $eachsign)
                                    <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 3px;">
                                        <tr>
                                            <td>REMARKS:</td>
                                            <td style="border-bottom: 1px solid black;" colspan="4"></td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                                        <tr>
                                            <td style="vertical-align: top">Prepared by:</td>
                                            <td></td>
                                            <td style="vertical-align: top">Certified True and Correct:</td>
                                            <td></td>
                                            <td style="vertical-align: top">Date Checked (MM/DD/YYYY)</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$eachsign->certncorrectname}}</td>
                                            <td style="width: 5%;"></td>
                                            <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$eachsign->certncorrectname}}</td>
                                            <td style="width: 5%;"></td>
                                            <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{ $eachsign->datechecked ? date('m/d/Y', strtotime($eachsign->datechecked)) : '' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Signature of Adviser over Printed Name</td>
                                            <td></td>
                                            <td class="text-center">&nbsp;{{$eachsign->certncorrectdesc}}</td>
                                            <td></td>
                                            <td class="text-center"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"></td>
                                        </tr>
                                    </table>
                                @endforeach
                            @else
                                <!-- Display the blank format if there is no data -->
                                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 3px;">
                                    <tr>
                                        <td>REMARKS:</td>
                                        <td style="border-bottom: 1px solid black;" colspan="4"></td>
                                    </tr>
                                </table>
                                <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                                    <tr>
                                        <td style="vertical-align: top">Prepared by:</td>
                                        <td></td>
                                        <td style="vertical-align: top">Certified True and Correct:</td>
                                        <td></td>
                                        <td style="vertical-align: top">Date Checked (MM/DD/YYYY)</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;</td>
                                        <td style="width: 5%;"></td>
                                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;</td>
                                        <td style="width: 5%;"></td>
                                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Signature of Adviser over Printed Name</td>
                                        <td></td>
                                        <td class="text-center">&nbsp;</td>
                                        <td></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="15">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="7" style="padding: 0px;">
                            <table style="width: 100%; table-layout: fixed; border: 1px solid black; margin: 0px;" border="1">
                                    @if(collect($record[0]->remedials)->contains('type','2'))
                                        @foreach(collect($record[0]->remedials)->where('type','2')->values() as $remedial)
                                            @if($remedial->type == 2)
                                                <tr>
                                                    <td style="width: 30%;">Remedial Classes</td>
                                                    <td colspan="4">Conducted from:&nbsp;&nbsp;{{$remedial->datefrom != null ? date('m/d/Y', strtotime($remedial->datefrom)) : ''}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to:&nbsp;&nbsp;{{$remedial->dateto != null ? date('m/d/Y', strtotime($remedial->dateto)) : ''}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="width: 30%;">Remedial Classes</td>
                                            <td colspan="4">Conducted from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Learning Ares</th>
                                        <th>Final Rating</th>
                                        <th>Remedial Class Mark</th>
                                        <th>Recomputed Final</th>
                                        <th>Remarks</th>
                                    </tr>
                                    @if(collect($record[0]->remedials)->contains('type','1'))
                                        @foreach(collect($record[0]->remedials)->where('type','1')->values() as $remedial)
                                            @if($remedial->type == 1)
                                                <tr>
                                                    <td>{{$remedial->subjectname}}</td>
                                                    <td class="text-center">{{$remedial->finalrating}}</td>
                                                    <td class="text-center">{{$remedial->remclassmark}}</td>
                                                    <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                                    <td class="text-center">{{$remedial->remarks}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif  
                                    @for($x = collect($record[0]->remedials)->where('type','1')->count(); $x<3; $x++)
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    @endfor
                            </table>
                        </td>
                        <td></td>
                        <td colspan="7" style="padding: 0px; vertical-align:top;">
                            <table style="width: 100%; table-layout: fixed; border: 1px solid black; margin: 0px;" border="1">
                                    @if(collect($record[1]->remedials)->contains('type','2'))
                                        @foreach($record[1]->remedials as $remedial)
                                            @if($remedial->type == 2)
                                                <tr>
                                                    <td style="width: 30%;">Remedial Classes</td>
                                                    <td colspan="4">Conducted from:&nbsp;&nbsp;{{$remedial->datefrom != null ? date('m/d/Y', strtotime($remedial->datefrom)) : ''}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to:&nbsp;&nbsp;{{$remedial->dateto != null ? date('m/d/Y', strtotime($remedial->dateto)) : ''}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="width: 30%;">Remedial Classes</td>
                                            <td colspan="4">Conducted from:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Learning Ares</th>
                                        <th>Final Rating</th>
                                        <th>Remedial Class Mark</th>
                                        <th>Recomputed Final</th>
                                        <th>Remarks</th>
                                    </tr>
                                    @if(collect($record[1]->remedials)->contains('type','1'))
                                        @foreach($record[1]->remedials as $remedial)
                                            @if($remedial->type == 1)
                                                <tr>
                                                    <td>{{$remedial->subjectname}}</td>
                                                    <td class="text-center">{{$remedial->finalrating}}</td>
                                                    <td class="text-center">{{$remedial->remclassmark}}</td>
                                                    <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                                    <td class="text-center">{{$remedial->remarks}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    @for($x = collect($record[1]->remedials)->where('type','1')->count(); $x<3; $x++)
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    @endfor
                            </table>
                        </td>
                    </tr>
                    @if($key == 1)
                    <tr>
                        <td colspan="15" style="text-align: right;"><em>SFRT 2017</em>		
                        </td>
                    </tr>
                  @endif
                  {{-- @endif --}}
              </table>
              
              <br/>   
            <div style="width: 100%; line-height: 4px;">&nbsp;</div>
        @endforeach
    @endif
    <div style="width: 100%; line-height: 4px;">&nbsp;</div>
    <div style="width: 100%;page-break-inside: avoid;">
        <div style="width: 100%; font-size: 11px;">For Transfer Out /Elementary School Completer Only</div>
        @php
            $y = 1;
            // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
            // {
            // $y = 1;
            // }
        @endphp
        @for($x=0; $x<$y; $x++)
            <table style="width: 100%; font-size: 10px; border: 1px solid black;">
                <tr>
                    <td colspan="10" style="border: 1px solid black; border-bottom: hidden; background-color: #d6d0d0; font-weight: bold;" class="text-center">
                        CERTIFICATION
                    </td>
                </tr>
                <tr>
                    <td style="width: 3%;"></td>
                    <td colspan="8" style="text-align: justify;">
                        @if($x == 0 )
                        I CERTIFY that this is a true record of <u>{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{isset($studinfo->middlename[0]) ? $studinfo->middlename[0].'. ' : ''}} </u> with LRN <u>{{$studinfo->lrn}}</u> and that he/she is eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;{{$footer->admissiontograde}}&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                        @else
                        I CERTIFY that this is a true record of <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> with LRN <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> and that he/she is eligible for admission to Grade <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>.
                        @endif
                    </td>
                    <td style="width: 3%;"></td>
                </tr>
                @if($x == 0 )
                <tr>
                    <td style="width: 3%;"></td>
                    <td style="width: 10%;">School Name:</td>
                    <td style="width: 18%; border-bottom: 1px solid black;">{{$schoolinfo->schoolname}}</td>
                    <td style="width: 7%;">School ID:</td>
                    <td style="width: 10%; border-bottom: 1px solid black;">{{$schoolinfo->schoolid}}</td>
                    <td style="width: 6%;">Division:</td>
                    <td style="width: 14%; border-bottom: 1px solid black;">{{$schoolinfo->division}}</td>
                    <td style="width: 18%;">Last School Year Attended:</td>
                    <td style="width: 9%; border-bottom: 1px solid black;">{{$footer->lastsy}}</td>
                    <td style="width: 3%;"></td>
                </tr>
                @else
                <tr>
                    <td style="width: 3%;"></td>
                    <td style="width: 10%;">School Name:</td>
                    <td style="width: 18%; border-bottom: 1px solid black;">&nbsp;</td>
                    <td style="width: 7%;">School ID:</td>
                    <td style="width: 10%; border-bottom: 1px solid black;">&nbsp;</td>
                    <td style="width: 6%;">Division:</td>
                    <td style="width: 14%; border-bottom: 1px solid black;">&nbsp;</td>
                    <td style="width: 18%;">Last School Year Attended:</td>
                    <td style="width: 9%; border-bottom: 1px solid black;">&nbsp;</td>
                    <td style="width: 3%;"></td>
                </tr>
                @endif
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'mci')
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
                @endif
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
                    <td style="border-bottom: 1px solid black; text-align: center;">@if($x == 0 ) {{date('M d, Y')}}@endif</td>
                    <td>&nbsp;</td>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                    <td colspan="3" style="border-bottom: 1px solid black; text-align: center;">{{$x == 0 ? $footer->recordsincharge : ''}}</td>
                    @else
                    <td colspan="3" style="border-bottom: 1px solid black; text-align: center;">
                        @php
                            $principalname = DB::table('academicprogram')
                                ->join('teacher','academicprogram.principalid','=','teacher.id')
                                ->first();
                        @endphp
                        @if($principalname)
                           @if($x == 0 ) {{$principalname->firstname}} {{$principalname->middlename != null ? $principalname->middlename[0].'.' : ''}} {{$principalname->lastname}} {{$principalname->suffix}}@endif
                        @endif
                    </td>
                    @endif
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-center">Date</td>
                    <td>&nbsp;</td>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                    <td colspan="3" class="text-center" style="font-size: 10px;">Name of School Registrar over Printed Name</td>
                    @else
                    <td colspan="3" class="text-center" style="font-size: 10px;">Name of Principal/School Head over Printed Name</td>
                    @endif
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
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
            <tr style="font-weight: bold; font-size: 12px !important;">
                <td>&nbsp;</td>
                <td>Copy for:</td>
                <td colspan="8">{{$footer->purpose}}</td>
            </tr>
            @endif
            </table>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'cisat')
            @break
            @endif
        @endfor
        <table style="width: 100%;table-layout: fixed;">
            <tr>
                <td style="font-size: 11px;">May add Certification Box if needed</td>
                <td style="font-size: 11px; text-align: right; font-style: italic;">SFRT Revised 2017</td>
            </tr>
        </table>
    </div>
</body>
</html>