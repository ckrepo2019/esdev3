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

</style>
@php
    $countMale = 0;
    $countFemale = 0;
    $complete_twoyears_male = 0;
    $complete_abovetwoyears_male = 0;
    $complete_twoyears_female = 0;
    $complete_abovetwoyears_female = 0;
@endphp
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active"><a href="/forms/index/form5a">School Form 5B</a></li>
            @if(isset($gradeAndLevel))
                <li class="breadcrumb-item active">{{$gradeAndLevel[0]->levelname}} - {{$gradeAndLevel[0]->sectionname}}</li>
            @endif
            
        </ol>
    </nav>
</div>
<form action="/forms/form5b" method="GET" target="_blank">
    <input type="hidden" name="action" value="export"/>
    <input type="hidden" name="exporttype"/>
    <input type="hidden" name="strandid" value="{{$strandid}}"/>
    <input type="hidden" name="sectionid" value="{{$gradeAndLevel[0]->sectionid}}"/>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <strong>School Form 5B List of Learners with Complete SHS Requirements (SF5B-SHS) </strong>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-default btn-sm mr-1" id="btn-exportexcel"><i class="fa fa-file-excel"></i> Excel</button>
                            <button type="button" class="btn btn-default btn-sm" id="btn-exportpdf"><i class="fa fa-file-pdf"></i> PDF</button>
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
                                <th >Semester<br><input type="text" class="form-control form-control-sm" value="{{$semester->semester}}" readonly/></th>
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
            <div class="main-cardcard p-0">
                <div class="card-body p-0">
                    <table class="table table-bordered students">
                        <tr>
                            <th>No.</th>
                            <th>LRN</th>
                            <th>LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</th>
                            <th>Completed SHS in 2 SYs? (Y/N)</th>
                            <th>National<br>Certification Level Attained<br>(only if applicable)</th>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">MALE</th>
                        </tr>
                        @foreach ($filter as $student)
                            @if(strtoupper($student->studentdata->gender) == 'MALE')
                                @php
                                    $countMale+=1;   
                                @endphp
                                <tr>
                                    <td>{{$countMale}}</td>
                                    <td>{{$student->studentdata->lrn}}</td>
                                    <td>{{$student->studentdata->lastname.', '.$student->studentdata->firstname.' '.$student->studentdata->suffix.' '.$student->studentdata->middlename}}</td>
                                    <td>
                                        {{-- <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                              <input type="radio" id="radioPrimary1" name="r1" checked="">
                                              <label for="radioPrimary1">
                                                    Y
                                              </label>
                                            </div>
                                            <div class="icheck-primary d-inline">
                                              <input type="radio" id="radioPrimary2" name="r1">
                                              <label for="radioPrimary2">
                                                    N
                                              </label>
                                            </div>
                                          </div> --}}
                                        <center>
                                            @if ($student->status == 'COMPLETE')
                                                @php
                                                    $complete_twoyears_male+=1;   
                                                @endphp
                                                Y
                                            @elseif ($student->status == 'INCOMPLETE')
                                            @elseif ($student->status == 'OVERSTAYING')
                                                @php
                                                    $complete_abovetwoyears_male+=1;   
                                                @endphp
                                                N
                                            @else
                                                Y
                                            @endif
                                        </center>
                                    </td>
                                    <td class="p-0">
                                        <textarea name="{{$student->studentdata->id}}" type="text" class="form-control form-control-sm m-0 p-0" ></textarea>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <tr>
                            <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">FEMALE</th>
                        </tr>
                        @foreach ($filter as $student)
                            @if(strtoupper($student->studentdata->gender) == 'FEMALE')
                                @php
                                    $countFemale+=1;   
                                @endphp
                                <tr>
                                    <td>{{$countFemale}}</td>
                                    <td>{{$student->studentdata->lrn}}</td>
                                    <td>{{$student->studentdata->lastname.', '.$student->studentdata->firstname.' '.$student->studentdata->suffix.' '.$student->studentdata->middlename}}</td>
                                    <td>
                                        <center>
                                            @if ($student->status == 'COMPLETE')
                                                @php
                                                    $complete_twoyears_female+=1;   
                                                @endphp
                                                Y
                                            @elseif ($student->status == 'INCOMPLETE')
                                                {{-- y --}}
                                            @elseif ($student->status == 'OVERSTAYING')
                                                @php
                                                    $complete_abovetwoyears_female+=1;   
                                                @endphp
                                                N
                                                @else
                                                    Y
                                            @endif
                                        </center>
                                    </td>
                                    <td class="p-0">
                                        <textarea name="{{$student->studentdata->lastname.'-'.$student->studentdata->firstname}}" type="text" class="form-control form-control-sm m-0 p-0" ></textarea>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                    <div class="m-3">
                        <p>
                            <strong>
                                GUIDELINES:
                            </strong>
                            <ol>
                                <li>This form should be accomplished by the Class Adviser at End of School Year.</li>
                                <li>It should be compiled and checked by the School Head and passed to the Division Office before graduation.</li>
                            </ol>
                        </p>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="street" class="">Prepared by:</label>
                                    <input name="teacher" id="street" type="text" class="form-control form-control-sm" value="{{strtoupper($teachername->firstname.' '.$teachername->middlename.' '.$teachername->lastname.' '.$teachername->suffix)}}" readonly/>
                                    <small>Signature of Class Adviser over Printed Name</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="barangay" class="">Certified Correct by:</label>
                                    <input name="schoolhead" id="barangay" type="text" class="form-control form-control-sm" value="{{strtoupper($school->authorized)}}" readonly/>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="main-card card p-0">
                <div class="card-body p-0">
                    <table class="table table-bordered m-0 summary" style="width:">
                        <tr>
                            <th colspan="4">SUMMARY TABLE A<br>&nbsp;</th>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                        <tr>
                            <th>Learners who completed SHS Program within 2 SYs or 4 semesters</th>
                            <th>{{$complete_twoyears_male}}</th>
                            <th>{{$complete_twoyears_female}}</th>
                            <th>{{$complete_twoyears_male + $complete_twoyears_female}}</th>
                        </tr>
                        <tr>
                            <th>Learners who completed SHS Program in more than 2 SYs or 4 semesters</th>
                            <th>{{$complete_abovetwoyears_male}}</th>
                            <th>{{$complete_abovetwoyears_female}}</th>
                            <th>{{$complete_abovetwoyears_male + $complete_abovetwoyears_female}}</th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th>{{$complete_twoyears_male + $complete_abovetwoyears_male}}</th>
                            <th>{{$complete_twoyears_female + $complete_abovetwoyears_female}}</th>
                            <th>{{($complete_twoyears_male + $complete_twoyears_female) + ($complete_abovetwoyears_male + $complete_abovetwoyears_female)}}</th>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-bordered m-0 summary" style="width:">
                        <tr>
                            <th colspan="4">SUMMARY TABLE B</th>
                        </tr>
                        <tr>
                            <th>STATUS</th>
                            <th>MALE</th>
                            <th>FEMALE</th>
                            <th>TOTAL</th>
                        </tr>
                        <tr>
                            <th>NCIII</th>
                            <th class="p-0">
                                <input type="number" name="nciiimale" class="form-control"/>
                            </th>
                            <th class="p-0">
                                <input type="number" name="nciiifemale" class="form-control"/>
                            </th>
                            <th class="p-0">
                                <input type="number" name="nciiitotal" class="form-control"/>
                            </th>
                        </tr>
                        <tr>
                            <th>NC II</th>
                            <th class="p-0">
                                <input type="number" name="nciimale" class="form-control"/>
                            </th>
                            <th class="p-0">
                                <input type="number" name="nciifemale" class="form-control"/>
                            </th>
                            <th class="p-0">
                                <input type="number" name="nciitotal" class="form-control"/>
                            </th>
                        </tr>
                        <tr>
                            <th>NC I</th>
                            <th class="p-0">
                                <input type="number" name="ncimale" class="form-control"/>
                            </th>
                            <th class="p-0">
                                <input type="number" name="ncifemale" class="form-control"/>
                            </th>
                            <th class="p-0">
                                <input type="number" name="ncitotal" class="form-control"/>
                            </th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th class="p-0">
                                <input type="number" name="nctotalmale" class="form-control"/>
                            </th>
                            <th class="p-0">
                                <input type="number" name="nctotalfemale" class="form-control"/>
                            </th>
                            <th class="p-0">
                                <input type="number" name="nctotal" class="form-control"/>
                            </th>
                        </tr>
                    </table>
                    <small>Note: NCs are recorded here for documentation but is not a requirement for graduation.</small>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script>
    $('#btn-exportexcel').on('click', function(){
        $(this).closest('form').find('input[name="exporttype"]').val('excel')
        $(this).closest('form').find('input[name="strandid"]').val('{{$strandid}}')
        $(this).closest('form').submit();
    })
    $('#btn-exportpdf').on('click', function(){
        $(this).closest('form').find('input[name="exporttype"]').val('pdf')
        $(this).closest('form').find('input[name="strandid"]').val('{{$strandid}}')
        $(this).closest('form').submit();
    })
    $('input[type=text]').bind('keyup blur',function(){ 
    var node = $(this);
    node.val(node.val().replace(/[^a-z]/g,'') ); }
);
</script>
@endsection
