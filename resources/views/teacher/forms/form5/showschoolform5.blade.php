@extends('teacher.layouts.app')

@section('content')
<style>
    
    table,th,td{ font-size: 12px; border:1px solid black !important; /* text-align: center; */ }

    #header td{ padding-left: 1px; }

    #header, #header th, #header td{ font-size: 12px; border: none !important; /* border:1px solid black !important; */ padding:2px; text-align: right; }

    th{ text-align: center; /* table-layout: fixed; */ }

    input[type=text]{ text-align: center; width:100%; }

    .bottom { position: absolute; bottom: 0; }

    td{text-transform: uppercase}

</style>
@if(count($gradeAndLevel)>0)
<form action="/forms/form5" method="GET" target="_blank">
    <input type="hidden" name="action" value="export"/>
    <input type="hidden" name="sectionid" value="{{$gradeAndLevel[0]->sectionid}}"/>
    <input type="hidden" name="exporttype" id="exporttype"/>
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h4>School Form 5 (SF5) Report on Promotion and Learning Progress & Achievement</h4>
                    <em>Revised to conform with the instructions of Deped Order 8, s. 2015</em>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-sm text-white " id="btn-exportpdf">
                            <i class="fa fa-file-pdf"></i>
                            Export to PDF
                        
                    </button>
                    <button type="button" class="btn btn-primary btn-sm text-white " id="btn-exportexcel">
                            <i class="fa fa-file-excel"></i>
                            Export to EXCEL
                        
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-xl-12">
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
                                <th><input type="text" value="{{$sy->sydesc}}" readonly/></th>
                                <th>Curriculum</th>
                                <th colspan="2"><input type="text" id="curriculum" name="curriculum" style="text-transform: uppercase" required/></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="2">School Name</th>
                                <th colspan="4"><input type="text" value="{{$school[0]->schoolname}}" readonly/></th>
                                <th>Grade Level</th>
                                <th><input type="text" value="@if(isset($gradeAndLevel)){{$gradeAndLevel[0]->levelname}}@endif" readonly/></th>
                                <th>Section</th>
                                <th><input type="text" value="@if(isset($gradeAndLevel)){{$gradeAndLevel[0]->sectionname}}@endif" readonly/></th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-bordered">
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
                                    @if(count($students)>0)
                                        @foreach ($students as $student)
                                            @if(($student->gender)=="MALE" || ($student->gender)=="Male")
                                                <tr>
                                                    <td>{{$student->lrn}}</td>
                                                    <td>{{$student->lastname}}, {{$student->firstname}} @if($student->middlename != null) {{$student->middlename[0]}}. @endif {{$student->suffix}}</td>
                                                    <td>
                                                        @if($student->info[0]->Final>=90)
                                                        <center>{{round($student->info[0]->Final)}}</center>
                                                        @else
                                                            @if(round($student->info[0]->Final, 2) == 0)
                                                            @else
                                                            <center>{{round($student->info[0]->Final, 2)}}</center>
                                                            @endif
                                                       
                                                        @endif
                                                        {{-- {{$student->info[0]->Final}} --}}
                                                    </td>
                                                    <td><center>{{$student->actiontaken}}</center></td>
                                                    <td>
                                                        @if(count($student->failedsubjects)>0)
                                                            @foreach($student->failedsubjects as $failedSubject)
                                                            {{$failedSubject}}<br>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $countMale+=1
                                                @endphp
                                            @endif
                                        @endforeach
                                    
                                    
                                    @endif
                                    @php
                                            $male=0;   
                                    @endphp
                                    @if(isset($students))
                                     @foreach ($students as $student)
                                        @if(($student->gender)=="MALE" || ($student->gender)=="Male")
                                                @php
                                                $male+=1
                                            @endphp
                                        @endif
                                    @endforeach
                                    @endif
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
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @if($student->actiontaken == "PROMOTED")
                                                    @php
                                                        $promotedMale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                        <center>{{$promotedMale}}</center>
                                    </td>
                                    <td>
                                        @php
                                            $promotedFemale = 0;
                                        @endphp
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="female")
                                                @if($student->actiontaken == "PROMOTED")
                                                    @php
                                                        $promotedFemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif

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
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @if($student->actiontaken == "CONDITIONAL")
                                                    @php
                                                        $conditionalMale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif

                                        <center>{{$conditionalMale}}</center>
                                    </td>
                                    <td>
                                        @php
                                            $conditionalFemale = 0;
                                        @endphp
                                        @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="female")
                                                @if($student->actiontaken == "CONDITIONAL")
                                                    @php
                                                        $conditionalFemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
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
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @if($student->actiontaken == "RETAINED")
                                                    @php
                                                        $retainedMale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
                                        <center>{{$retainedMale}}</center>
                                    </td>
                                    <td>
                                        @php
                                            $retainedFemale = 0;
                                        @endphp
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="female")
                                                @if($student->actiontaken == "RETAINED")
                                                    @php
                                                        $retainedFemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
                                        <center>{{$retainedFemale}}</center>
                                    </td>
                                    <td><center>{{$retainedFemale + $retainedMale}}</center></td>
                                </tr>
                            </table>
                            <table class="table table-bordered">
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
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @if($student->info[0]->Final <= 74.99 )
                                                    @php
                                                        $didNotMale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
                                        <center>{{$didNotMale}}</center>
                                    </td>
                                    <td>
                                        @php
                                            $didNotFemale = 0;
                                        @endphp
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="female")
                                                @if($student->info[0]->Final <= 74.99 )
                                                    @php
                                                        $didNotFemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
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
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @if($student->info[0]->Final >= 75 && $student->info[0]->Final <= 79.99)
                                                    @php
                                                        $fairlyMale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
                                        <center>{{$fairlyMale}}</center>
                                    </td>
                                    <td>
                                        @php
                                            $fairlyFemale = 0;
                                        @endphp
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="female")
                                                @if($student->info[0]->Final >= 75 && $student->info[0]->Final <= 79.99)
                                                    @php
                                                        $fairlyFemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
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
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @if($student->info[0]->Final >= 80 && $student->info[0]->Final <= 84.99)
                                                {{-- {{$student->info[0]->Final}} --}}
                                                    @php
                                                        $satisfactoryMale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
                                        <center>{{$satisfactoryMale}}</center>
                                    </td>
                                    <td>
                                        @php
                                            $satisfactoryFemale = 0;
                                        @endphp
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="female")
                                                @if($student->info[0]->Final >= 80 && $student->info[0]->Final <= 84.99)
                                                    @php
                                                        $satisfactoryFemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
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
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @if($student->info[0]->Final >= 85 && $student->info[0]->Final <= 89.99)
                                                    @php
                                                        $verySatisfactoryMale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
                                        <center>{{$verySatisfactoryMale}}</center>
                                    </td>
                                    <td>
                                        @php
                                            $verySatisfactoryFemale = 0;
                                        @endphp
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="female")
                                                @if($student->info[0]->Final >= 85 && $student->info[0]->Final <= 89)
                                                    @php
                                                        $verySatisfactoryFemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
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
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @if($student->info[0]->Final >= 90 && $student->info[0]->Final <= 100)
                                                    @php
                                                        $outstandingMale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
                                        <center>{{$outstandingMale}}</center>
                                    </td>
                                    <td>
                                        @php
                                            $outstandingFemale = 0;
                                        @endphp
                                        
                                    @if(isset($students))
                                        @foreach ($students as $student)
                                            @if($student->gender=="female")
                                                @if($student->info[0]->Final >= 90 && $student->info[0]->Final <= 100)
                                                    @php
                                                        $outstandingFemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @endif
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
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>LRN</th>
                                        <th>LEARNER'S NAME</th>
                                        <th>GENERAL<br>AVERAGE</th>
                                        <th>ACTION TAKEN:<br>PROMOTED,<br>CONDITIONAL, or<br>RETAINED</th>
                                        <th>Did Not Meet Expectations of the<br>ff. Learning Area/s as of end of<br>current School Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $countFemale = 0;   
                                    @endphp
                                    
                                    @if(isset($students))
                                    @foreach ($students as $student)
                                        @if($student->gender=="female")
                                    <tr>
                                        <td>{{$student->lrn}}</td>
                                        <td>{{$student->lastname}}, {{$student->firstname}} @if($student->middlename != null) {{$student->middlename[0]}}. @endif {{$student->suffix}}</td>
                                        <td>
                                            @if($student->info[0]->Final>=90)
                                            <center>{{round($student->info[0]->Final)}}</center>
                                            @else
                                                @if(round($student->info[0]->Final, 2) == 0)
                                                @else
                                                <center>{{round($student->info[0]->Final, 2)}}</center>
                                                @endif
                                           
                                            @endif
                                            {{-- {{$student->info[0]->Final}} --}}
                                        </td>
                                        <td><center>{{$student->actiontaken}}</center></td>
                                        <td>
                                            @if(count($student->failedsubjects)>0)
                                                @foreach($student->failedsubjects as $failedSubject)
                                                    {{$failedSubject}}<br>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $countFemale+=1
                                    @endphp
                                    @endif
                                    @endforeach
                                    
                                    @endif
                                    @php
                                            $female=0;   
                                    @endphp
                                    @if(isset($students))
                                     @foreach ($students as $student)
                                            @if($student->gender=="male")
                                                @php
                                                $female+=1
                                            @endphp
                                        @endif
                                    @endforeach
                                    @endif
                                    @while ($female <= 30)
                                        
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
                                            {{$teachername->firstname}} {{$teachername->middlename[0]}}. {{$teachername->lastname}} {{$teachername->suffix}}
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
                                            {{$school[0]->authorized}}
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
                                            <input type="text" class="form-control" name="divisionRep" style="text-transform: uppercase" required/>
                                            <hr style="margin:0px;background-color:black;"/>
                                            <p>Division Representative<br>(Name and Signature)</p>
                                        </td>
                                    </tr>
                                </table>
                                <div >
                                    <div>
                                        <p><strong>GUIDELINES:</strong></p>
                                        <p><small><em><strong>1.Do not include Dropouts and Transferred Out (D.O.4, 2014)</strong></em></small></p>
                                        
                                        <p><small>2. To be prepared by the Adviser. The Adviser should indicate the General Average based on the learner's Form 138.</small></p>
    
                                        <p><small>3. On the summary table, reflect the total number of learners PROMOTED (Final Grade of at least <strong>75 in ALL learning areas</strong>), RETAINED (Did not Meet Expectations in <strong>three (3) or more learning areas</strong>) and *CONDITIONAL (*Did Not Meet Expectations in <strong>not more than two (2) learning areas</strong>) and the Learning Progress and Achievements according to the individual General Average. All provisions on classroom assessment and the grading system in the said Order shall be in effect for all grade levels - Deped Order 29, s. 2015.</small></p>
                                        
                                        <p><small>4. Did Not Meet Expectations of the Learning Areas. This refers to learning area/s that the learner had failed as of end of current SY. The learner may be for remediation or retention.</small></p>
                                        
                                        <p><small>5. Protocols of validation & submission is under the discretion of the Schools Division Superintendent..</small></p>
                                    </div>
                                </div>
                        {{-- </div> --}}
                        </div>
                    </div>
                            {{-- <div class="row">
                            </div>
                            &nbsp;
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
                </div>
        </div>
    </div>
</form>
@endif
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#btn-exportpdf').on('click', function(){
            $('#exporttype').val('pdf')
            $(this).closest('form').submit();
        })
        $('#btn-exportexcel').on('click', function(){
            $('#exporttype').val('excel')
            $(this).closest('form').submit();
        })
    })
</script>
@endsection