@extends('registrar.layouts.app')

@section('content')
    <style>
        table,th,td                     { font-size: 12px; border:1px solid black !important; /* text-align: center; */ }
        #header td                      { padding-left: 1px; }
        #header, #header th, #header td { font-size: 12px; border: none !important; /* border:1px solid black !important; */ padding:2px; text-align: right; }
        th                              { text-align: center; /* table-layout: fixed; */ }
        input[type=text]                { text-align: center; width:100%; }
        .bottom                         { position: absolute; bottom: 0; }
        .male td, .female td            { text-transform: uppercase; }
    </style>
    <form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="School Form 5A" name="selectedform"/>
    </form>
    <form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="School Form 5A" name="selectedform"/>
    </form>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/reports/selectForms">{{$selectedform}}</a></li>
                        <li class="breadcrumb-item"><a id="selectschoolyear" class="text-info">{{$schoolyeardesc}}</a></li>
                        <li class="breadcrumb-item"><a id="selectsection" class="text-info">{{$selectedsection}}</a></li>
                        <li class="breadcrumb-item active">Student Masterlist</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>School Form 5A End of Semester and School Year Status of Learners for Senior High School (SF5A-SHS)</strong>
                    </h3>
                    @if(isset($gradelevelid))
                        <a href="/reports_schoolform5A/print/{{$schoolyear ?? ''}}/{{$sectionid}}/{{$gradelevelid}}/{{$teacherid}}" target="_blank" class="btn btn-success btn-sm text-white float-right">
                            <i class="fa fa-upload"></i>
                            Print
                        </a>
                    @endif
                </div>
            </div>
        </div>
        {{-- <div class="row mb-2"> --}}
        @if(isset($message))
            <div class="col-sm-12">
                <div class="alert alert-warning alert-dismissible">
                    {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    {{$message}}
                </div>
            </div>
        @endif
        {{-- </div> --}}
        {{-- <div class="row"> --}}
        <div class="col-12">
            <div class="card card-default">
                @if(isset($students))
                    <div class="card-body">
                        <table id="header" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="padding:10px;">
                                        <center><img src="{{asset('assets/images/department_of_Education.png')}}" alt="school" width="70px"></center>
                                    </th>
                                    <th style="padding:0px 2px 0px 20px;">Region</th>
                                    <th><input type="text" value="{{$school[0]->region}}" readonly/></th>
                                    <th>Division</th>
                                    <th colspan="2"><input type="text" value="{{$school[0]->division}}" readonly/></th>
                                    <th>District</th>
                                    <th colspan="2"><input type="text" value="{{$school[0]->district}}" readonly/></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>School ID</th>
                                    <th colspan="2"><input type="text" value="{{$school[0]->schoolid}}" readonly/></th>
                                    <th style="padding:0px 2px 0px 40px;">School Year</th>
                                    <th><input type="text" value="{{$sy}}" readonly/></th>
                                    <th>Curiculum</th>
                                    <th colspan="2"><input type="text" readonly/></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="2">School Name</th>
                                    <th colspan="4"><input type="text" value="{{$school[0]->schoolname}}" readonly/></th>
                                    <th>Grade Level</th>
                                    <th><input type="text" value="{{$gradeAndLevel[0]->levelname}}" readonly/></th>
                                    <th>Section</th>
                                    <th><input type="text" value="{{$gradeAndLevel[0]->sectionname}}" readonly/></th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-bordered male">
                                    <thead>
                                        <tr>
                                            <th>LRN</th>
                                            <th width="40%">LEARNER'S NAME</th>
                                            <th>GENERAL<br>AVERAGE<br>(Whole numbers for non-honor)</th>
                                            <th>ACTION TAKEN:<br>PROMOTED,<br>CONDITIONAL, or<br>RETAINED</th>
                                            <th>Did Not Meet Expectations of the<br>ff. Learning Area/s as of end of<br>current School Year</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $countMale = 0;   
                                        @endphp
                                        @foreach ($students as $student)
                                            @if(strtoupper($student[1]->gender)=="MALE")
                                                <tr>
                                                    <td>{{$student[1]->lrn}}</td>
                                                    <td>{{$student[1]->lastname}}, {{$student[1]->firstname}} {{$student[1]->middlename[0]}}.</td>
                                                    <td>
                                                        @php
                                                            $maleFinal = 0;   
                                                            $maleCountSubj = 0;
                                                        @endphp
                                                        @foreach ($student[0]->grades as $getFinal)
                                                            @php
                                                                $maleFinal+=$getFinal->final;
                                                                $maleCountSubj+=1;
                                                            @endphp
                                                        @endforeach
                                                        @php
                                                            if($maleFinal!=0){
                                                                $maleAverage = $maleFinal / $maleCountSubj;
                                                                if($maleAverage > 90){
                                                                    $maleFinalAverage = number_format($maleAverage, 2);
                                                                }
                                                                else{
                                                                    $maleFinalAverage = round($maleAverage);
                                                                }
                                                            }
                                                            else{
                                                                $maleFinalAverage = "";
                                                            }
                                                        @endphp
                                                        <center>{{$maleFinalAverage}}</center>
                                                    </td>
                                                    <td><center>{{$student[2]}}</center></td>
                                                    <td>
                                                        @if(isset($student[3]))
                                                        @foreach($student[3] as $failedSubject)
                                                        {{$failedSubject[0]}}<br>
                                                        @endforeach
                                                        @else
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $countMale+=1
                                                @endphp
                                            @endif
                                        @endforeach
                                        @php
                                                $male=0;   
                                        @endphp
                                        @foreach ($students as $student)
                                            @if(strtoupper($student[1]->gender)=="MALE")
                                                    @php
                                                    $male+=1
                                                @endphp
                                            @endif
                                        @endforeach
                                        @while ($male <= 14)
                                            
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            @php
                                                $male+=1;   
                                            @endphp
                                        @endwhile
                                        <tr>
                                            <td></td>
                                            <th>TOTAL MALE</th>
                                            <th>
                                                <center>{{$countMale}}</center>
                                            </th>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="bottom">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="4">SUMMARY</th>
                                    </tr>
                                    <tr>
                                        <th>STATUS</th>
                                        <th>MALE</th>
                                        <th>FEMALE</th>
                                        <th>TOTAL</th>
                                    </tr>
                                    <tr>
                                        <th>PROMOTED</th>
                                        <td>
                                            @php
                                                $promotedMale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="MALE")
                                                    @if($student[2] == "PROMOTED")
                                                        @php
                                                            $promotedMale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach

                                            <center>{{$promotedMale}}</center>
                                        </td>
                                        <td>
                                            @php
                                                $promotedFemale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="FEMALE")
                                                    @if($student[2] == "PROMOTED")
                                                        @php
                                                            $promotedFemale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach

                                            <center>{{$promotedFemale}}</center>
                                        </td>
                                        <td><center>{{$promotedMale + $promotedFemale}}</center></td>
                                    </tr>
                                    <tr>
                                        <th>*Conditional</th>
                                        <td>
                                            @php
                                                $conditionalMale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="MALE")
                                                    @if($student[2] == "CONDITIONAL")
                                                        @php
                                                            $conditionalMale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach

                                            <center>{{$conditionalMale}}</center>
                                        </td>
                                        <td>
                                            @php
                                                $conditionalFemale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="FEMALE")
                                                    @if($student[2] == "CONDITIONAL")
                                                        @php
                                                            $conditionalFemale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach

                                            <center>{{$conditionalFemale}}</center>
                                        </td>
                                        <td><center>{{$conditionalFemale + $conditionalMale}}</center></td>
                                    </tr>
                                    <tr>
                                        <th>RETAINED</th>
                                        <td>
                                            @php
                                                $retainedMale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="MALE")
                                                    @if($student[2] == "RETAINED")
                                                        @php
                                                            $retainedMale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach

                                            <center>{{$retainedMale}}</center>
                                        </td>
                                        <td>
                                            @php
                                                $retainedFemale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="FEMALE")
                                                    @if($student[2] == "RETAINED")
                                                        @php
                                                            $retainedFemale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach

                                            <center>{{$retainedFemale}}</center>
                                        </td>
                                        <td><center>{{$retainedFemale + $retainedMale}}</center></td>
                                    </tr>
                                </table>
                                <table class="table table-bordered ">
                                    <tr>
                                        <th colspan="4">LEARNING PROCESS AND ACHIEVEMENT<br>(Based on Learner's General Average)</th>
                                    </tr>
                                    <tr>
                                        <th>Descriptors & Grading Scale</th>
                                        <th>MALE</th>
                                        <th>FEMALE</th>
                                        <th>TOTAL</th>
                                    </tr>
                                    <tr>
                                        <th>Did Not Meet Expectations<br>(74 and below)</th>
                                        <td>
                                            @php
                                                $didNotMale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="MALE")
                                                    @if($student[0]->grades <= 74 )
                                                        @php
                                                            $didNotMale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$didNotMale}}</center>
                                        </td>
                                        <td>
                                            @php
                                                $didNotFemale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="FEMALE")
                                                    @if($student[0]->grades <= 74 )
                                                        @php
                                                            $didNotFemale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$didNotFemale}}</center>
                                        </td>
                                        <td><center>{{$didNotFemale + $didNotMale}}</center></td>
                                    </tr>
                                    <tr>
                                        <th>Fairly Satisfactory<br>(75-79)</th>
                                        <td>
                                            @php
                                                $fairlyMale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="MALE")
                                                    @if($student[0]->grades>= 75 && $student[0]->grades<= 79)
                                                        @php
                                                            $fairlyMale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$fairlyMale}}</center>
                                        </td>
                                        <td>
                                            @php
                                                $fairlyFemale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="FEMALE")
                                                    @if($student[0]->grades>= 75 && $student[0]->grades<= 79)
                                                        @php
                                                            $fairlyFemale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$fairlyFemale}}</center>
                                        </td>
                                        <td><center>{{$fairlyFemale + $fairlyMale}}</center></td>
                                    </tr>
                                    <tr>
                                        <th>Satisfactory<br>(80-84)</th>
                                        <td>
                                            @php
                                                $satisfactoryMale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="MALE")
                                                    @if($student[0]->grades>= 80 && $student[0]->grades<= 84)
                                                        @php
                                                            $satisfactoryMale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$satisfactoryMale}}</center>
                                        </td>
                                        <td>
                                            @php
                                                $satisfactoryFemale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="FEMALE")
                                                    @if($student[0]->grades>= 80 && $student[0]->grades<= 84)
                                                        @php
                                                            $fairlyFemale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$satisfactoryFemale}}</center>
                                        </td>
                                        <td><center>{{$satisfactoryFemale + $satisfactoryMale}}</center></td>
                                    </tr>
                                    <tr>
                                        <th>Very Satisfactory<br>(85-89)</th>
                                        <td>
                                            @php
                                                $verySatisfactoryMale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="MALE")
                                                    @if($student[0]->grades>= 85 && $student[0]->grades<= 89)
                                                        @php
                                                            $verySatisfactoryMale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$verySatisfactoryMale}}</center>
                                        </td>
                                        <td>
                                            @php
                                                $verySatisfactoryFemale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="FEMALE")
                                                    @if($student[0]->grades>= 85 && $student[0]->grades<= 89)
                                                        @php
                                                            $verySatisfactoryFemale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$verySatisfactoryFemale}}</center>
                                        </td>
                                        <td><center>{{$verySatisfactoryFemale + $verySatisfactoryMale}}</center></td>
                                    </tr>
                                    <tr>
                                        <th>Outstanding<br>(90-100)</th>
                                        <td>
                                            @php
                                                $outstandingMale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="MALE")
                                                    @if($student[0]->grades>= 90 && $student[0]->grades<= 100)
                                                        @php
                                                            $outstandingMale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$outstandingMale}}</center>
                                        </td>
                                        <td>
                                            @php
                                                $outstandingFemale = 0;
                                            @endphp
                                            @foreach ($students as $student)
                                                @if(strtoupper($student[1]->gender)=="FEMALE")
                                                    @if($student[0]->grades>= 90 && $student[0]->grades<= 100)
                                                        @php
                                                            $outstandingFemale+=1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            <center>{{$outstandingFemale}}</center>
                                        </td>
                                        <td><center>{{$outstandingFemale + $outstandingMale}}</center></td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-bordered female">
                                    <thead>
                                        <tr>
                                            <th>LRN</th>
                                            <th width="40%">LEARNER'S NAME</th>
                                            <th>GENERAL<br>AVERAGE</th>
                                            <th>ACTION TAKEN:<br>PROMOTED,<br>CONDITIONAL, or<br>RETAINED</th>
                                            <th>Did Not Meet Expectations of the<br>ff. Learning Area/s as of end of<br>current School Year</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $countFemale = 0;   
                                        @endphp
                                        @foreach ($students as $student)
                                        @if(strtoupper($student[1]->gender)=="FEMALE")
                                        <tr>
                                            <td>{{$student[1]->lrn}}</td>
                                            <td>{{$student[1]->lastname}}, {{$student[1]->firstname}} {{$student[1]->middlename[0]}}.</td>
                                            <td>
                                                @php
                                                    $femaleFinal = 0;   
                                                    $femaleCountSubj = 0;
                                                @endphp
                                                @foreach ($student[0]->grades as $getFinal)
                                                    @php
                                                        $femaleFinal+=$getFinal->final;
                                                        $femaleCountSubj+=1;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    if($femaleFinal!=0){
                                                        $femaleAverage = $femaleFinal / $femaleCountSubj;
                                                        if($femaleAverage > 90){
                                                            $femaleFinalAverage = number_format($femaleAverage, 2);
                                                        }
                                                        else{
                                                            $femaleFinalAverage = round($femaleAverage);
                                                        }
                                                    }
                                                    else{
                                                        $femaleFinalAverage = "";
                                                    }
                                                @endphp
                                                <center>{{$femaleFinalAverage}}</center>
                                            </td>
                                            <td><center>{{$student[2]}}</center></td>
                                            <td>
                                                @if(isset($student[3]))
                                                @foreach($student[3] as $failedSubject)
                                                    {{$failedSubject}}<br>
                                                @endforeach
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $countFemale+=1
                                        @endphp
                                        @endif
                                        @endforeach
                                        @php
                                                $female=0;   
                                        @endphp
                                        @foreach ($students as $student)
                                            @if(strtoupper($student[1]->gender)=="MALE")
                                                    @php
                                                    $female+=1
                                                @endphp
                                            @endif
                                        @endforeach
                                        @while ($female <= 25)
                                            
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            @php
                                                $female+=1;   
                                            @endphp
                                        @endwhile
                                        <tr>
                                            <td></td>
                                            <th>TOTAL FEMALE</th>
                                            <th>
                                                <center>{{$countFemale}}</center>
                                            </th>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4" >
                                {{-- <div class="mb-0 pb-0"> --}}
                                    <div class="bottom">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td style="border:hidden !important;">PREPARED BY:</td>
                                        </tr>
                                        <tr>
                                            <td style="border: hidden !important;text-align:center;">
                                                {{$teachername[0]->firstname}} {{$teachername[0]->middlename[0]}}. {{$teachername[0]->lastname}} {{$teachername[0]->suffix}}
                                                <hr style="margin:0px;background-color:black;"/>
                                                <p>Class Adviser<br>(Name and Signature)</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td style="border:hidden !important;">CERTIFIED CORRECT & SUBMITTED:</td>
                                        </tr>
                                        <tr>
                                            <td style="border: hidden !important;text-align:center;">
                                                {{$principalname[0]->firstname}} {{$principalname[0]->middlename[0]}}. {{$principalname[0]->lastname}} {{$principalname[0]->suffix}}
                                                <hr style="margin:0px;background-color:black;"/>
                                                <p>School Head<br>(Name and Signature)</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td style="border:hidden !important;">REVIEWED BY:</td>
                                        </tr>
                                        <tr>
                                            <td style="border: hidden !important;text-align:center;">
                                                &nbsp;
                                                <hr style="margin:0px;background-color:black;"/>
                                                <p>Division Representative<br>(Name and Signature)</p>
                                            </td>
                                        </tr>
                                    </table>
                                    <div >
                                        <div>
                                            <p><strong>GUIDELINES:</strong></p>
                                            <p><small><em><strong>1.Do not include Dropouts and Transfered Out (D.O.4, 2014)</strong></em></small></p>
                                            
                                            <p><small>2. To be prepared by the Adviser. The Adviser should indicate the General Average based on the learner's Form 138.</small></p>
        
                                            <p><small>3. On the summary table, reflect the total number of learners PROMOTED (Final Grade of at least <strong>75 in ALL learning areas</strong>), RETAINED (Did not Meet Expectations in <strong>three (3) or more learning areas</strong>) and *CONDITIONAL (*Did Not Meet Expectations in <strong>not more than two (2) learning areas</strong>) and the Learning Progress and Achievements accoding to the individual General Average. All provisions on classroom assessment and the grading system in the said Order shall be in effect for all grade levels - Deped Order 29, s. 2015.</small></p>
                                            
                                            <p><small>4. Did Not Meet Expectations of the Learning Areas. This refers to learning area/s that the learner had failed as of end of curent SY. The learner may be for remediation or retention.</small></p>
                                            
                                            <p><small>5. Potocols of validation & submission is under the discretion of the Schools Division Superintendent.</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                </div> --}}
                            {{-- &nbsp;
                                <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                </div> --}}
                        </div>
                    </div>
                 @endif
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

    <script>
        $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        });
        $('#selectsection').on('click', function (){
            document.getElementById('submitSelectSection').submit();
        });
    </script>
@endsection