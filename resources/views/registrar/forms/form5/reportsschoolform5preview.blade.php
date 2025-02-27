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
        <input type="hidden" value="{{$schoolyear ?? ''}}" name="syid"/>
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
        <input type="hidden" value="School Form 5" name="selectedform"/>
    </form>
    <form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear ?? ''}}" name="syid"/>
        <input type="hidden" value="School Form 5" name="selectedform"/>
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
    </form>
    <section class="content-header">
        <div class="col-12">
            @if($academicprogram == 'elementary')
                <h4>Elementary</h4>
            @elseif($academicprogram == 'juniorhighschool')
                <h4>Junior High School</h4>
            @elseif($academicprogram == 'seniorhighschool')
                <h4>Senior High School</h4>
            @endif
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/reports/{{$academicprogram}}">{{$selectedform}}</a></li>
                    <li class="breadcrumb-item"><a id="selectschoolyear" class="text-info">{{$schoolyeardesc}}</a></li>
                    <li class="breadcrumb-item"><a id="selectsection" class="text-info">{{$selectedsection}}</a></li>
                    <li class="breadcrumb-item active">School Form 5</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <form action="/export/form5" method="get" target="_blank">
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-9">
                                <strong>School Form 5 (SF5) Report on Promotion and Learning Progress & Achievement</strong>
                            </div>
                            <div class="col-md-3 text-right">
                                @if(isset($gradelevelid))
                                
                                    <input type="hidden" id="action" name="action" value="export"/>
                                    <input type="hidden" id="selectedform" name="selectedform" value="{{$selectedform}}"/>
                                    <input type="hidden" id="syid" name="syid" value="{{$schoolyear}}"/>
                                    <input type="hidden" id="sectionid" name="sectionid" value="{{$sectionid}}"/>
                                    <input type="hidden" id="levelid" name="levelid" value="{{$gradelevelid}}"/>
                                    <input type="hidden" id="exporttype" name="exporttype" value=""/>
                                    </a>
                                    <button type="button" class="btn btn-default btn-sm" id="btn-exportexcel">
                                        <i class="fa fa-file-excel"></i>
                                        Excel
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" id="btn-exportpdf">
                                        <i class="fa fa-upload"></i>
                                        PDF
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Curriculum</label><br/>
                                <input type="text" name="curriculum" class="form-control" required/>
                            </div>
                            <div class="col-md-6">
                                <label>Division Representative</label><br/>
                                <input type="text" name="divrep" class="form-control" required/>
                            </div>
                        </div>
                    </form>
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
                                            @if(strtolower($student->gender)=="male")
                                                <tr>
                                                    <td>{{$student->lrn}}</td>
                                                    <td>
                                                        {{$student->lastname}}, {{$student->firstname}} 
                                                        @if($student->middlename != null)
                                                        {{$student->middlename[0]}}. 
                                                        @endif
                                                        {{$student->suffix}} 
                                                    </td>
                                                    <td>
                                                        @if($student->generalaverage>0)
                                                            @if($student->generalaverage>=90)
                                                            <center>{{round($student->generalaverage)}}</center>
                                                            @else
                                                            <center>{{round($student->generalaverage, 2)}}</center>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{$student->promotionstat}}
                                                    </td>
                                                    <td class="text-center">
                                                        @if(collect($student->grades)->where('failed','1')->count()>0)
                                                            
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
                                            @if(strtolower($student->gender)=="male")
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
                                {{-- <div class="bottom"> --}}
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
                                                @if(strtolower($student->gender)=="male")
                                                    @if($student->promotionstat == "PROMOTED")
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
                                                @if(strtolower($student->gender)=="female")
                                                    @if($student->promotionstat == "PROMOTED")
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
                                                @if(strtolower($student->gender)=="male")
                                                    @if($student->promotionstat == "CONDITIONAL")
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
                                                @if(strtolower($student->gender)=="female")
                                                    @if($student->promotionstat == "CONDITIONAL")
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
                                                @if(strtolower($student->gender)=="male")
                                                    @if($student->promotionstat == "RETAINED")
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
                                                @if(strtolower($student->gender)=="female")
                                                    @if($student->promotionstat == "RETAINED")
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
                                                @if(strtolower($student->gender)=="male")
                                                    @if($student->generalaverage <= 74.99 && $student->generalaverage!=0)
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
                                                @if(strtolower($student->gender)=="female")
                                                    @if($student->generalaverage <= 74.99  && $student->generalaverage!=0)
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
                                                @if(strtolower($student->gender)=="male")
                                                    @if($student->generalaverage>= 75 && $student->generalaverage<= 79.99 && $student->generalaverage!=0)
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
                                                @if(strtolower($student->gender)=="female")
                                                    @if($student->generalaverage>= 75 && $student->generalaverage<= 79.99 && $student->generalaverage!=0)
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
                                                @if(strtolower($student->gender)=="male")
                                                    @if($student->generalaverage>= 80 && $student->generalaverage<= 84.99 && $student->generalaverage!=0)
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
                                                @if(strtolower($student->gender)=="female")
                                                    @if($student->generalaverage>= 80 && $student->generalaverage<= 84.99 && $student->generalaverage!=0)
                                                        @php
                                                            $satisfactoryFemale+=1;
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
                                                @if(strtolower($student->gender)=="male")
                                                    @if($student->generalaverage>= 85 && $student->generalaverage<= 89.99 && $student->generalaverage!=0)
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
                                                @if(strtolower($student->gender)=="female")
                                                    @if($student->generalaverage>= 85 && $student->generalaverage<= 89.99 && $student->generalaverage!=0)
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
                                                @if(strtolower($student->gender)=="male")
                                                    @if($student->generalaverage>= 90 && $student->generalaverage<= 100 && $student->generalaverage!=0)
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
                                                @if(strtoupper($student->gender)=="female")
                                                    @if($student->generalaverage>= 90 && $student->generalaverage<= 100 && $student->generalaverage!=0)
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
                                {{-- </div> --}}
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
                                        @if(strtolower($student->gender)=="female")
                                        <tr>
                                            <td>{{$student->lrn}}</td>
                                            <td>
                                                {{$student->lastname}}, {{$student->firstname}} 
                                                @if($student->middlename != null)
                                                    {{$student->middlename[0]}}. 
                                                @endif
                                                {{$student->suffix}} 
                                            </td>
                                            <td class="text-center">
                                                @if($student->generalaverage>0)
                                                    @if($student->generalaverage>=90)
                                                    <center>{{round($student->generalaverage)}}</center>
                                                    @else
                                                    <center>{{round($student->generalaverage, 2)}}</center>
                                                    @endif
                                                @endif
                                                {{-- {{$student->Final}} --}}
                                            </td>
                                            <td><center>
                                                {{$student->promotionstat}}</center></td>
                                            <td>
                                                {{-- @if(isset($student[3]))
                                                @foreach($student[3] as $failedSubject)
                                                {{$failedSubject[0]}}<br>
                                                @endforeach
                                                @endif --}}
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
                                            @if(strtolower($student->gender)=="female")
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
                                    {{-- <div class="bottom"> --}}
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
                                                &nbsp;
                                                <hr style="margin:0px;background-color:black;"/>
                                                <p>Division Representative<br>(Name and Signature)</p>
                                            </td>
                                        </tr>
                                    </table>
                                    {{-- <div > --}}
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
    $('#btn-exportexcel').on('click', function(){
        $('#exporttype').val('excel')
        $(this).closest('form').submit();
    })
    $('#btn-exportpdf').on('click', function(){
        $('#exporttype').val('pdf')
        $(this).closest('form').submit();
    })
    </script>
@endsection