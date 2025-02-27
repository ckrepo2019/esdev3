
@extends('teacher.layouts.app')


@section('content')
<style>
    
    #header td                          { padding-left: 1px; }

    #header, #header th, #header td     { font-size: 12px; border: none !important; /* border:1px solid black !important; */ padding:2px; text-align: right; }

    th                                  { text-align: center; /* table-layout: fixed; */ }

    input[type=text]                    { text-align: center; width:100%; }

    .bottom                             { position: absolute; bottom: 0; }

    td                                  {text-transform: uppercase}

    .header                             { border: hidden; font-size: 13px;}

    .header td                          { border: hidden;}

    .summary, .students, .prepared      {font-size: 13px;}

    ol                                  { padding: 2px}
</style>
@php
    $countMale = 0;
    $countFemale = 0;
    $firstsemcompletemale = 0;
    $firstsemcompletefemale = 0;
    $firstsemincompletemale = 0;   
    $firstsemincompletefemale = 0;   
    $secondsemcompletemale = 0;
    $secondsemcompletefemale = 0;
    $secondsemincompletemale = 0;  
    $secondsemincompletefemale = 0;  
@endphp
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active"><a href="/forms/index/form5a">School Form 5A</a></li>
            @if(isset($gradeAndLevel))
                <li class="breadcrumb-item active">{{$gradeAndLevel[0]->levelname}} - {{$gradeAndLevel[0]->sectionname}}</li>
            @endif
            
        </ol>
    </nav>
</div>
<form action="/forms/form5a" method="GET" target="_blank">
    <input type="hidden" name="action" value="export"/>
    <input type="hidden" name="exporttype"/>
    <input type="hidden" name="semid"/>
    <input type="hidden" name="strandid" value="{{$strandid}}"/>
    <input type="hidden" name="sectionid" value="{{$gradeAndLevel[0]->sectionid}}"/>
    <input type="hidden" name="levelid" value="{{$gradeAndLevel[0]->levelid}}"/>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <strong>School Form 5A End of Semester and School Year Status of Learners for Senior High School (SF5A-SHS) </strong>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="button" id="btn-exportexcel" class="btn btn-default"><i class="fa fa-file-excel"></i> Excel </button>
                            <button type="button" id="btn-exportpdf" class="btn btn-default"><i class="fa fa-file-pdf"></i> Print </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table header" style="border:none">
                        <thead>
                            {{-- <tr>
                                <th>School Name<br><input type="text" class="form-control" value="{{$school[0]->schoolname}}" readonly/></th>
                                <th>School ID<br><input type="text" class="form-control" value="{{$school[0]->schoolid}}" readonly/></th>
                                <th>District<br><input type="text" class="form-control" value="{{$school[0]->district}}" readonly/></th>
                                <th>Division<br><input type="text" class="form-control" value="{{$school[0]->division}}" readonly/></th>
                            </tr> --}}
                            <tr>
                                {{-- <th>Region<br><input type="text" class="form-control" value="{{$school[0]->region}}" readonly/></th> --}}
                                <th >School Year<br><input type="text" class="form-control form-control-sm" value="{{$sy}}" readonly/></th>
                                <th >Semester<br>
                                    <select id="select-semester" class="form-control form-control-sm" >
                                        <option value="1" @if($semester->id == 1) selected @endif>1st</option>
                                        <option value="2" @if($semester->id == 2) selected @endif>2nd</option>
                                    </select>
                                </th>
                                <th>Grade Level<br><input type="text" id="curriculum" class="form-control form-control-sm" name="curriculum" style="text-transform: uppercase" value="{{$gradeAndLevel[0]->levelname}}" readonly/></th>
                                <th>Section<br><input type="text" id="curriculum" class="form-control form-control-sm" name="curriculum" style="text-transform: uppercase" value="{{$gradeAndLevel[0]->sectionname}}" readonly/></th>
                            </tr>
                            <tr>
                                <th colspan="2">Track and Strand
                                    @if(isset($trackAndStrands))
                                    <br>
                                    @foreach ($trackAndStrands as $track)
                                        {{$track['track'].' - '.$track['strand']}}
                                        <br>
                                    @endforeach
                                    @else
                                    <input type="text" class="form-control form-control-sm" value="" />
                                    @endif
                                </th>
                                @if(isset($tvl))
                                <th colspan="2">Course/s (only for TVL)<br><input type="text" class="form-control form-control-sm" value="" /></th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="main-card card p-0">
                <div class="card-body p-0">
                    <table class="table table-bordered students" style="table-layout: fixed;">
                        <tr>
                            <th style="width: 5%; padding: 0px" >No.</th>
                            <th>LRN</th>
                            <th>LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</th>
                            <th>BACK SUBJECT/S<br>List down subjects where learner obtain a rating below 75%</th>
                            <th>END OF<br>SEMESTER STATUS<br>Complete/Incomplete</th>
                            <th>END OF<br>SCHOOL YEAR<br>STATUS<br>(Regular/Irregular)</th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">MALE</th>
                        </tr>
                        @if(count($students)>0)
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='MALE')
                                    @php
                                        $countMale+=1;   
                                    @endphp
                                    <tr>
                                        <td class="p-0"><center>{{$countMale}}</center></td>
                                        <td>{{$student->lrn}}</td>
                                        <td>{{$student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix}}</td>
                                        <td style="text-align: left;">
                                            <ol style="text-align: left;">
                                            @if(collect($student->backsubjects)->where('semid',$semester->id)->count()>0)
                                                @foreach (collect($student->backsubjects)->where('semid',$semester->id) as $backsubjects)
                                                    <li style="text-align: left;">{{$backsubjects->subjectcode}}</li>
                                                @endforeach
                                            @endif
                                            </ol>
                                        </td>
                                        <td>
                                            @if (collect($student->backsubjects)->where('semid',$semester->id)->count() == 0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                            @endif
                                        </td>
                                        <td>
                                            @if($semester->id == 2)
                                                @if (collect($student->backsubjects)->where('semid',$semester->id)->count() == 0)
                                                    <center>REGULAR</center>
                                                @else
                                                    <center>IRREGULAR</center>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        <tr>
                            <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">FEMALE</th>
                        </tr>
                        @if(count($students)>0)
                            @foreach ($students as $student)
                                @if (strtoupper($student->gender)=='FEMALE')
                                    @php
                                        $countFemale+=1;   
                                    @endphp
                                    <tr>
                                        <td class="p-0"><center>{{$countFemale}}</center></td>
                                        <td>{{$student->lrn}}</td>
                                        <td>{{$student->lastname.', '.$student->firstname.' '.$student->middlename.' '.$student->suffix}}</td>
                                        <td style="text-align: left;">
                                            <ol style="text-align: left;">
                                            @if(collect($student->backsubjects)->where('semid',$semester->id)->count()>0)
                                                @foreach (collect($student->backsubjects)->where('semid',$semester->id) as $backsubjects)
                                                    <li style="text-align: left;">{{$backsubjects->subjectcode}}</li>
                                                @endforeach
                                            @endif
                                            </ol>
                                        </td>
                                        <td>
                                            @if (collect($student->backsubjects)->where('semid',$semester->id)->count() == 0)
                                                <center>COMPLETE</center>
                                            @else
                                                <center>INCOMPLETE</center>
                                            @endif
                                        </td>
                                        <td>
                                            @if($semester->id == 2)
                                                @if (collect($student->backsubjects)->where('semid',$semester->id)->count() == 0)
                                                    <center>REGULAR</center>
                                                @else
                                                    <center>IRREGULAR</center>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </table>
                    <div class="m-3">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="street" class="">Prepared by:</label>
                                    <input name="street" id="street" type="text" class="form-control form-control-sm" value="{{strtoupper($teachername->firstname.' '.$teachername->middlename.' '.$teachername->lastname.' '.$teachername->suffix)}}" readonly/>
                                    <small>Signature of Class Adviser over Printed Name</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="barangay" class="">Certified Correct by:</label>
                                    <input name="barangay" id="barangay" type="text" class="form-control form-control-sm" value="{{strtoupper($school->authorized)}}" readonly/>
                                    <small>Signature of School Head over Printed Name</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="city" class="">Reviewed by:</label>
                                    <input name="divisionrep" id="city" type="text" class="form-control form-control-sm text-uppercase" required/>
                                    <small>Signature of Division Representative over Printed Name</small>
                                </div>
                            </div>
                        </div>
                        <p>
                            <strong>
                                GUIDELINES:
                            </strong>
                            <br>
                            <em>
                                This form shall be accomplished after each semester in a school year,  leaving the End of School Year Status Column and Summary Table for End of School Year Status blank/unfilled at the end of the 1st Semester.  These data elements shall be filled up only after the 2nd semester or at the end of the School Year. 
                            </em>
                        </p>
                        <br>
                        <p>
                            <strong>
                                INDICATORS:
                            </strong>
                            <br>
                            <em>
                                <strong>
                                    End of Semester Status
                                </strong>
                            </em>
                            <br>
                            <span class="ml-5"> 
                                <strong>Complete</strong> - number of learners who completed/satisfied the requirements in all subject areas (with grade of at least 75%)
                            </span>
                            <br>
                            <span class="ml-5"> 
                                <strong>Incomplete</strong> - number of learners who did not meet expectations in one or more subject areas, regardless of number of subjects failed (with grade less than 75%)
                            </span>
                            <br>
                            <span class="ml-5"> 
                                <em>
                                    <strong>Note:</strong> Do not include learners who are No Longer in School (<strong>NLS</strong>)
                                </em>
                            </span>
                            <br>
                            <em>
                                <strong>
                                    End of School Year Status
                                </strong>
                            </em>
                            <br>
                            <span class="ml-5"> 
                                <strong>Regular</strong> - number of learners who completed/satisfied requirements in all subject areas  both in the 1st and 2nd semester
                            </span>
                            <br>
                            <span class="ml-5"> 
                                <strong>Irregular</strong> - number of learners who were not able to satisfy/complete requirements in one or both semesters
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
                <div class="main-card card p-0">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0 summary" style="width:">
                            <tr>
                                <th colspan="4">SUMMARY TABLE 1ST SEM</th>
                            </tr>
                            <tr>
                                <th>STATUS</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th>COMPLETE</th>
                                <th>
                                    @php
                                        $firstsemcompletemale = 0;
                                    @endphp
                                    @foreach ($students as $student)
                                        @if (strtoupper($student->gender)=='MALE')
                                            @if(collect($student->backsubjects)->where('semid',1)->count() == 0)
                                                @php
                                                    $firstsemcompletemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    {{$firstsemcompletemale}}
                                </th>
                                <th>
                                    @php
                                        $firstsemcompletefemale = 0;
                                    @endphp
                                    @foreach ($students as $student)
                                        @if (strtoupper($student->gender)=='FEMALE')
                                            @if(collect($student->backsubjects)->where('semid',1)->count() == 0)
                                                @php
                                                    $firstsemcompletefemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    {{$firstsemcompletefemale}}
                                </th>
                                <th>{{$firstsemcompletemale + $firstsemcompletefemale}}</th>
                            </tr>
                            <tr>
                                <th>INCOMPLETE</th>
                                <th>
                                    @php
                                        $firstsemincompletemale = 0;
                                    @endphp
                                    @foreach ($students as $student)
                                        @if (strtoupper($student->gender)=='MALE')
                                            @if(collect($student->backsubjects)->where('semid',1)->count() > 0)
                                                @php
                                                    $firstsemincompletemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    {{$firstsemincompletemale}}
                                </th>
                                <th>
                                    @php
                                        $firstsemincompletefemale = 0;
                                    @endphp
                                    @foreach ($students as $student)
                                        @if (strtoupper($student->gender)=='FEMALE')
                                            @if(collect($student->backsubjects)->where('semid',1)->count() > 0)
                                                @php
                                                    $firstsemincompletefemale+=1;
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    {{$firstsemincompletefemale}}
                                </th>
                                <th>{{$firstsemincompletemale + $firstsemincompletefemale}}</th>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <th>{{$firstsemcompletemale + $firstsemincompletemale}}</th>
                                <th>{{$firstsemcompletefemale + $firstsemincompletefemale}}</th>
                                <th>{{($firstsemcompletemale + $firstsemcompletefemale) + ($firstsemincompletemale + $firstsemincompletefemale)}}</th>
                            </tr>
                        </table>
                    </div>
                </div> 
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0 summary" style="width:">
                            <tr>
                                <th colspan="4">SUMMARY TABLE 2ND SEM</th>
                            </tr>
                            <tr>
                                <th>STATUS</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th>COMPLETE</th>
                                <th>
                                    @php
                                        $secondsemcompletemale = 0;
                                    @endphp
                                    @foreach ($students as $student)
                                        @if (strtoupper($student->gender)=='MALE')
                                            @if(collect($student->backsubjects)->where('semid',2)->count() == 0)
                                                    @php
                                                        $secondsemcompletemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$secondsemcompletemale}}
                                </th>
                                <th>
                                    @php
                                        $secondsemcompletefemale = 0;
                                    @endphp
                                    @foreach ($students as $student)
                                        @if (strtoupper($student->gender)=='FEMALE')
                                            @if(collect($student->backsubjects)->where('semid',2)->count() == 0)
                                                    @php
                                                        $secondsemcompletefemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$secondsemcompletefemale}}
                                </th>
                                <th>{{$secondsemcompletemale + $secondsemcompletefemale}}</th>
                            </tr>
                            <tr>
                                <th>INCOMPLETE</th>
                                <th>
                                    @php
                                        $secondsemincompletemale = 0;
                                    @endphp
                                    @foreach ($students as $student)
                                        @if (strtoupper($student->gender)=='MALE')
                                            @if(collect($student->backsubjects)->where('semid',2)->count() > 0)
                                                    @php
                                                        $secondsemincompletemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$secondsemincompletemale}}
                                </th>
                                <th>
                                    @php
                                        $secondsemincompletefemale = 0;
                                    @endphp
                                    @foreach ($students as $student)
                                        @if (strtoupper($student->gender)=='FEMALE')
                                            @if(collect($student->backsubjects)->where('semid',2)->count() > 0)
                                                    @php
                                                        $secondsemincompletefemale+=1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                    {{$secondsemincompletefemale}}
                                </th>
                                <th>{{$secondsemincompletemale + $secondsemincompletefemale}}</th>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <th>{{$secondsemcompletemale + $secondsemincompletemale}}</th>
                                <th>{{$secondsemcompletefemale + $secondsemincompletefemale}}</th>
                                <th>{{($secondsemcompletemale + $secondsemcompletefemale) + ($secondsemincompletemale + $secondsemincompletefemale)}}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            {{-- </div>
            <div class="col-md-12"> --}}
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered m-0 summary" style="width:">
                            <tr>
                                <th colspan="4">SUMMARY TABLE (End of the School Year Only)</th>
                            </tr>
                            <tr>
                                <th>STATUS</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th>REGULAR</th>
                                <th>
                                    @if($semester->id == 2)
                                        {{$firstsemcompletemale + $secondsemcompletemale}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester->id == 2)
                                        {{$firstsemcompletefemale + $secondsemcompletefemale}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester->id == 2)
                                        {{($firstsemcompletemale + $secondsemcompletemale) + ($firstsemcompletefemale + $secondsemcompletefemale)}}
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th>IRREGULAR</th>
                                <th>
                                    @if($semester->id == 2)
                                        {{$firstsemincompletemale + $secondsemincompletemale}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester->id == 2)
                                        {{$firstsemincompletefemale + $secondsemincompletefemale}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester->id == 2)
                                        {{($firstsemincompletemale + $secondsemincompletemale) + ($firstsemincompletefemale + $secondsemincompletefemale)}}
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <th>
                                    @if($semester->id == 2)
                                        {{($firstsemcompletemale + $secondsemcompletemale) + ($firstsemincompletemale + $secondsemincompletemale)}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester->id == 2)
                                        {{($firstsemcompletefemale + $secondsemcompletefemale) + ($firstsemincompletefemale + $secondsemincompletefemale)}}
                                    @endif
                                </th>
                                <th>
                                    @if($semester->id == 2)
                                        {{(($firstsemcompletemale + $secondsemcompletemale) + ($firstsemincompletemale + $secondsemincompletemale)) + (($firstsemcompletefemale + $secondsemcompletefemale) + ($firstsemincompletefemale + $secondsemincompletefemale))}}
                                    @endif
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            {{-- </div> --}}
        </div>
    </div>
</form>

@endsection
@section('footerjavascript')
<script>
    $('input').bind('keyup blur',function(){ 
        var node = $(this);
        node.val(node.val().replace(/[^a-z]/g,'') ); }
    );
    $('#btn-exportexcel').on('click', function(){
        $(this).closest('form').find('input[name="exporttype"]').val('excel')
        $(this).closest('form').find('input[name="semid"]').val($('#select-semester').val())
        $(this).closest('form').find('input[name="strandid"]').val('{{$strandid}}')
        $(this).closest('form').submit();
    })
    $('#btn-exportpdf').on('click', function(){
        $(this).closest('form').find('input[name="exporttype"]').val('pdf')
        $(this).closest('form').find('input[name="semid"]').val($('#select-semester').val())
        $(this).closest('form').find('input[name="strandid"]').val('{{$strandid}}')
        $(this).closest('form').submit();
    })
    $('#select-semester').on('change', function(){
        $('input[name="semid"]').val($(this).val())
        var semid = $(this).val();
        window.location.replace('/forms/form5a?semid='+semid+'&action=show&sectionid={{$gradeAndLevel[0]->sectionid}}&levelid={{$gradeAndLevel[0]->levelid}}&strandid={{$strandid}}')
    })
    
</script>
@endsection