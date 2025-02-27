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

<form action="/shs_form5b/print" method="GET" target="_blank">
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>School Form 5B List of Learners with Complete SHS Requirements (SF5B-SHS) </strong>
                    </h3>
                    <button type="submit" class="btn btn-primary btn-sm text-white float-right" >
                        <i class="fa fa-upload"></i>
                    Print
                    
                    </button>
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
                                <th >Semester<br><input type="text" class="form-control form-control-sm" value="" readonly/></th>
                                <th>Grade Level<br><input type="text" id="curriculum" class="form-control form-control-sm" name="curriculum" style="text-transform: uppercase" value="{{$gradeAndLevel[0]->levelname}}" readonly/></th>
                                <th>Section<br><input type="text" id="curriculum" class="form-control form-control-sm" name="curriculum" style="text-transform: uppercase" value="{{$gradeAndLevel[0]->sectionname}}" readonly/></th>
                            </tr>
                            <tr>
                                <th colspan="2">Track and Strand<br><input type="text" class="form-control form-control-sm" value="" /></th>
                                <th colspan="2">Course/s (only for TVL)<br><input type="text" class="form-control form-control-sm" value="" /></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>Learners who completed SHS Program in more than 2 SYs or 4 semesters</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>NC II</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>NC I</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </table>
                    <small>Note: NC's are recorded here for documentation but is not a requirement for graduation.</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="main-card mb-3 card p-0">
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
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="6" style="text-align: left;" class="p-2 bg-secondary">FEMALE</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
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
                                    <input name="street" id="street" type="text" class="form-control form-control-sm" readonly/>
                                    <small>Signature of Class Adviser over Printed Name</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="barangay" class="">Certified Correct by:</label>
                                    <input name="barangay" id="barangay" type="text" class="form-control form-control-sm" readonly/>
                                    <small>Signature of School Head over Printed Name</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="city" class="">Reviewed by:</label>
                                    <input name="city" id="city" type="text" class="form-control form-control-sm" readonly/>
                                    <small>Signature of Division Representative over Printed Name</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

@endsection
