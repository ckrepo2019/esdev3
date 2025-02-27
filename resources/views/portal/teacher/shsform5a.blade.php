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

<form action="/shs_form5a/print" method="GET" target="_blank">
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>School Form 5A End of Semester and School Year Status of Learners for Senior High School (SF5A-SHS) </strong>
                    </h3>
                    <button type="submit" class="btn btn-primary btn-sm text-white float-right">
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>INCOMPLETE</th>
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
        <div class="col-md-4">
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
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>INCOMPLETE</th>
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
        <div class="col-md-4">
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
                            <th>COMPLETE</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th>INCOMPLETE</th>
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
                            <th>BACK SUBJECTS<br>List down subjects where learner obtain a rating below 75%</th>
                            <th>END OF<br>SEMESTER STATUS<br>Complete/Incomplete</th>
                            <th>END OF<br>SCHOOL YEAR<br>STATUS<br>(Regular/Irregular)</th>
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
                            <td></td>
                        </tr>
                    </table>
                    <div class="m-3">
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
    </div>
</form>

<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>

@endsection


{{-- <div class="row">
    <div class="col-12">
        <div class="card card-default color-palette-box">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa fa-file"></i>
                    <strong>School Form 5A End of Semester and School Year Status of Learners for Senior High School (SF5A-SHS) </strong>
                </h3>
                <button type="submit" class="btn btn-primary btn-sm text-white float-right">
                    <i class="fa fa-upload"></i>
                Print
                
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="main-card mb-3 card ">
            <div class="card-body">
                <table id="header" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="padding:0px 2px 0px 20px;width: 10%">School Name</th>
                            <th><input type="text" value="{{$school[0]->schoolname}}" readonly/></th>
                            <th style="width: 10%">School ID</th>
                            <th ><input type="text" value="{{$school[0]->schoolid}}" readonly/></th>
                            <th>District</th>
                            <th ><input type="text" value="{{$school[0]->district}}" readonly/></th>
                            <th>Division</th>
                            <th ><input type="text" value="{{$school[0]->division}}" readonly/></th>
                            <th>Region</th>
                            <th ><input type="text" value="{{$school[0]->region}}" readonly/></th>
                        </tr>
                        <tr>
                            <th >Semester</th>
                            <th colspan="2"><input type="text" value="" readonly/></th>
                            <th style="padding:0px 2px 0px 40px;">School Year</th>
                            <th><input type="text" value="{{$sy}}" readonly/></th>
                            <th>Grade Level</th>
                            <th ><input type="text" id="curriculum" name="curriculum" style="text-transform: uppercase" value="{{$gradeAndLevel[0]->levelname}}"/></th>
                            <th>Section</th>
                            <th ><input type="text" id="curriculum" name="curriculum" style="text-transform: uppercase" value="{{$gradeAndLevel[0]->sectionname}}"/></th>
                        </tr>
                        <tr>
                            <th colspan="2">Track and Strand</th>
                            <th colspan="3"><input type="text" value="" readonly/></th>
                            <th colspan="2">Course/s (only for TVL)</th>
                            <th colspan="3"><input type="text" value="" readonly/></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="main-card mb-3 card ">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No.</th>
                        <th>LRN</th>
                        <th>LEARNER'S NAME<br>(Last Name, First Name, Name Extension, Middle Name)</th>
                        <th>BACK SUBJECTS<br>List down subjects where learner obtain a rating below 75%</th>
                        <th>END OF<br>SEMESTER STATUS<br>Complete/Incomplete</th>
                        <th>END OF<br>SCHOOL YEAR<br>STATUS<br>(Regular/Irregular)</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div> --}}