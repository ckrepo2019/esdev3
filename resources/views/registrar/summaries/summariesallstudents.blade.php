
@extends('registrar.layouts.app')
@section('pagespecificscripts')

<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: .31rem;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }
    .alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeeba;
}
.alert {
    position: relative;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                <h1 class="m-0 text-dark">Enrolled Student List</h1>
                @else
                <h1 class="m-0 text-dark">Students Summary</h1>
                @endif
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
                    <li class="breadcrumb-item active">Enrolled Student List</li>
                    @else
                    <li class="breadcrumb-item active">Students Summary</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>

@php
$studentcount = 0;
$school = DB::table('schoolinfo')->first()->abbreviation;

$syid = DB::table('sy')->where('isactive','1')->first()->id;
$teacherid = DB::table('teacher')
                                                ->where('tid',auth()->user()->email)
                                                ->select('id')
                                                ->first()
                                                ->id;

$teacheradprogid = DB::table('teacheracadprog')
                                                    ->where('teacherid',$teacherid)
													->where('syid',$syid)
													->whereIn('acadprogutype',[3,8])
													->where('deleted',0)
                                                    ->get();
													
								$isjs = collect($teacheradprogid)->where('acadprogid',4)->count() > 0 ? true :false;
								$issh = collect($teacheradprogid)->where('acadprogid',5)->count() > 0 ? true :false;
								$iscollege = collect($teacheradprogid)->where('acadprogid',6)->count() > 0 ? true :false;
								$isgs = collect($teacheradprogid)->where('acadprogid',3)->count() > 0 ? true :false;
								$isps = collect($teacheradprogid)->where('acadprogid',3)->count() > 0 ? true :false;

                        $acadprogs = [];
                        $acadpoverall = 0;
                        if($isjs)
                        {
                            array_push($acadprogs ,4);
                        }
                        if($issh)
                        {
                            array_push($acadprogs ,5);
                        }
                        if($iscollege)
                        {
                            array_push($acadprogs ,6);
                        }
                        if($isgs)
                        {
                            array_push($acadprogs ,3);
                        }
                        if($isps)
                        {
                            array_push($acadprogs ,3);
                        }
                        if(count($acadprogs) == 0)
                        {
                            $acadpoverall = 0;
                        }elseif(count($acadprogs) == 1){
                            $acadpoverall = 0;
                        }else{
                            if($isjs && $issh && $isgs && $isps)
                            {
                            $acadpoverall = 1;
                            }
                        }
                        $academicprogram = collect($academicprogram)->whereIn('id', $acadprogs)->values();
@endphp
<div class="card">
    <div class="card-header">
        <div class="row mb-4">
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select School Year</sub>
                <select class="form-control form-control-sm mt-1" name="selectedschoolyear" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    @foreach($schoolyears as $schoolyear)
                        <option value="{{$schoolyear->id}}" @if($schoolyear->isactive == 1) selected @endif>{{$schoolyear->sydesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Semester</sub>
                <select class="form-control form-control-sm mt-1" name="selectedsemester" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    @foreach($semesters as $semester)
                        <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Department</sub>
                <select class="form-control form-control-sm mt-1" name="selectedacadprog" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    @if($acadpoverall == 1)
                    <option value="basiced">All Basic Ed</option>
                    @endif
                    @foreach($academicprogram as $acadprog)
                        
                        <option value="{{$acadprog->id}}">{{$acadprog->progname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Grade Level</sub>
                <select class="form-control form-control-sm mt-1" name="selectedgradelevel" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">All</option>
                    {{-- @foreach($gradelevels as $gradelevel)
                        <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                    @endforeach --}}
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Section</sub>
                <select class="form-control form-control-sm mt-1" name="selectedsection" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Track</sub>
                <select class="form-control form-control-sm mt-1" name="trackid" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    @foreach($tracks as $track)
                        <option value="{{$track->id}}">{{$track->trackname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Strand</sub>
                <select class="form-control form-control-sm mt-1" name="strandid" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    @foreach($strands as $strand)
                        <option value="{{$strand->id}}">{{$strand->strandname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select College</sub>
                <select class="form-control form-control-sm mt-1" name="collegeid" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    @foreach($colleges as $college)
                        <option value="{{$college->id}}">{{$college->collegeDesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Course</sub>
                <select class="form-control form-control-sm mt-1" name="courseid" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    @foreach($courses as $course)
                        <option value="{{$course->id}}">{{$course->courseDesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Mode of Learning</sub>
                <select class="form-control form-control-sm mt-1" name="selectedmode" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    <option value="unspecified">UNSPECIFIED</option>
                    @foreach ($modeoflearnings as $modeoflearning)
                    <option value="{{$modeoflearning->id}}">{{$modeoflearning->description}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Grantee</sub>
                <select class="form-control form-control-sm mt-1" name="selectedgrantee" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    @foreach ($grantees as $grantee)
                        <option value="{{$grantee->id}}">{{$grantee->description}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Student Type</sub>
                <select class="form-control form-control-sm mt-1" name="studenttype" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    <option value="new">NEW</option>
                    <option value="old">OLD</option>
					<option value="returnee">RETURNEE</option>
                    <option value="transferee">TRANSFEREE</option>
                </select>
            </div>
            {{-- <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Enrollment Period</sub>
                <input class="form-control form-control-sm mt-1" name="selecteddate" style="border-left: hidden;border-right: hidden;border-top: hidden;"/>
            </div> --}}
            <div class="col-md-6">
                <sub style="background-color: white;z-index: 99;font-weight: bold;">Select Admission Status</sub>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sjaes')
                <select class="select2 form-control mt-1" multiple="multiple" name="studentstatus" style="wdth: 100%;border-left: hidden;border-right: hidden;border-top: hidden;" data-placeholder="ALL">
                    @foreach ($studentstatus as $studstatus)
                        <option value="{{$studstatus->id}}">{{$studstatus->description}}@if($studstatus->id == 6)N @endif</option>
                    @endforeach
                </select>
                @else
                <select class="select2 form-control mt-1" multiple="multiple" name="studentstatus" style="wdth: 100%;border-left: hidden;border-right: hidden;border-top: hidden;" data-placeholder="ALL">
                    @foreach (collect($studentstatus)->where('id','!=','6')->values() as $studstatus)
                        <option value="{{$studstatus->id}}">{{$studstatus->description}}</option>
                    @endforeach
                </select>
                @endif
            </div>
            <div class="col-md-3">
                <sub style="background-color: white;font-weight: bold;">Select Gender</sub>
                <select class="form-control form-control-sm mt-1" name="selectedgender" style="border-left: hidden;border-right: hidden;border-top: hidden;">
                    <option value="0">ALL</option>
                    <option value="male">MALE</option>
                    <option value="female">FEMALE</option>
                </select>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkbox-daterange" >
                        <label for="checkbox-daterange">
                            Date Range : &nbsp;&nbsp;&nbsp;&nbsp;
                        </label>
                        </div>
                    </div>
                    <input class="form-control form-control-sm mt-1" style="border: 1px solid black;" name="selecteddate"  disabled/>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-sm btn-primary float-right" id="filtergenerate"><i class="fa fa-sync"></i> Generate</button>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-warning" role="alert" id="alert-warning">
    No students shown!
  </div>
<div class="card card-primary card-outline" id="card-results">
    <div class="card-header">
        {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi') --}}
            <div class="row mb-2 align-items-end">
                <div class="col-md-3">
                    <label>Prepared by:</label>
                    <input type="text" class="form-control form-control-sm" id="preparedby"/>
                </div>
                <div class="col-md-3">
                    <label>Generated by:</label>
                    <input type="text" class="form-control form-control-sm" id="generatedby"/>
                </div>
                <div class="col-md-3">
                    {{-- <label>&nbsp;</label><br/> --}}
                    <button type="button" class="btn btn-sm btn-default mt-2" id="btn-savesignatories"><i class="fa fa-share"></i> Save Changes</button>
                </div>
            </div>
        {{-- @endif --}}
        <div class="row">
            <div class="col-md-8">
                <button type="button" class="btn btn-sm btn-warning" id="noofstudents"><i class="fa fa-users"></i> &nbsp;</button>
            </div>
            <div class="col-md-4 text-right">
                <button type="button" class="btn btn-sm btn-default" id="btn-student-directory-excel"><i class="fa fa-file-excel"></i> STUDENT DIRECTORY</button>
            </div>
        </div>
    </div>
    <div class="card-body">
      <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
        <li class="nav-item p-1">
          <a class="nav-link p-2 active" id="custom-content-above-students-tab" data-toggle="pill" href="#custom-content-above-students" role="tab" aria-controls="custom-content-above-students" aria-selected="true">Students</a>
        </li>
        <li class="nav-item p-1">
          <a class="nav-link p-2" id="custom-content-above-studentsinfo-tab" data-toggle="pill" href="#custom-content-above-studentsinfo" role="tab" aria-controls="custom-content-above-studentsinfo" aria-selected="true">Students Info</a>
        </li>
        <li class="nav-item p-1">
          <a class="nav-link p-2" id="custom-content-above-gradelevel-tab" data-toggle="pill" href="#custom-content-above-gradelevel" role="tab" aria-controls="custom-content-above-gradelevel" aria-selected="false">By Grade Level</a>
        </li>
        <li class="nav-item p-1">
          <a class="nav-link p-2" id="custom-content-above-track-tab" data-toggle="pill" href="#custom-content-above-track" role="tab" aria-controls="custom-content-above-track" aria-selected="false">By Track</a>
        </li>
        <li class="nav-item p-1">
          <a class="nav-link p-2" id="custom-content-above-strand-tab" data-toggle="pill" href="#custom-content-above-strand" role="tab" aria-controls="custom-content-above-strand" aria-selected="false">By Strand</a>
        </li>
        <li class="nav-item p-1">
          <a class="nav-link p-2" id="custom-content-above-college-tab" data-toggle="pill" href="#custom-content-above-college" role="tab" aria-controls="custom-content-above-college" aria-selected="false">By College</a>
        </li>
        <li class="nav-item p-1">
          <a class="nav-link p-2" id="custom-content-above-course-tab" data-toggle="pill" href="#custom-content-above-course" role="tab" aria-controls="custom-content-above-course" aria-selected="false">By Course</a>
        </li>
        {{-- <li class="nav-item p-1">
          <a class="nav-link p-2" id="custom-content-above-section-tab" data-toggle="pill" href="#custom-content-above-section" role="tab" aria-controls="custom-content-above-section" aria-selected="false">By Track</a>
        </li> --}}
        <li class="nav-item p-1">
          <a class="nav-link p-2" id="custom-content-above-section-tab" data-toggle="pill" href="#custom-content-above-section" role="tab" aria-controls="custom-content-above-section" aria-selected="false">By Section</a>
        </li>
        {{-- <li class="nav-item">
          <a class="nav-link" id="custom-content-above-section-tab" data-toggle="pill" href="#custom-content-above-section" role="tab" aria-controls="custom-content-above-section" aria-selected="false">By Section</a>
        </li> --}}
      </ul>
      {{-- <div class="tab-custom-content">
        <p class="lead mb-0">Custom Content goes here</p>
      </div> --}}
      <div class="tab-content" id="custom-content-above-tabContent">
        <div class="tab-pane fade show active" id="custom-content-above-students" role="tabpanel" aria-labelledby="custom-content-above-students-tab">
            <div class="row mt-2 mb-2" id="container-students">
                <div class="col-md-6 mb-2 text-left">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="student" data-otherid="list"><i class="fa fa-file-pdf"></i> &nbsp;&nbsp;List of Students</button>
                </div>
                <div class="col-md-6 mb-2 text-right">
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="student"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
                </div>
                <div class="col-md-12" style="overflow-x: scroll;">
                    <table id="studentstable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>    
                                <th>SID</th>      
                                <th>LRN</th>       
                                <th>Last Name</th>    
                                <th>First Name</th>      
                                <th>Middle Name</th>       
                                <th>Suffix</th>      
                                <th>Gender</th>      
                                <th>DOB</th>      
                                <th>MOL</th>      
                                <th>Grade Level</th>    
                                <th>Section</th>    
                                <th>College/Track</th>    
                                <th>Course/Strand</th>    
                            </tr>
                        </thead>
                        <tbody class="studentscontainer"  style="font-size: 12px; ">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-content-above-studentsinfo" role="tabpanel" aria-labelledby="custom-content-above-studentsinfo-tab">
            <div class="row mt-2 mb-2" id="container-studentsinfo">
                <div class="col-md-12 mb-2 text-right">
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="studentinfo"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
                </div>
                <div class="col-md-12">
                    <table id="studentsinfotable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>    
                                <th>First Name</th>      
                                <th>Last Name</th>      
                            </tr>
                        </thead>
                        <tbody class="studentsinfocontainer"  style="font-size: 12px; ">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-content-above-gradelevel" role="tabpanel" aria-labelledby="custom-content-above-gradelevel-tab">
            <div class="row mt-2 mb-2" id="container-gradelevel">
                <div class="col-md-6 mb-2 text-left">
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="gradelevel" data-otherid="4ps"><i class="fa fa-file-excel"></i> &nbsp;&nbsp;4Ps Students</button>
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="gradelevel" data-otherid="list"><i class="fa fa-file-pdf"></i> &nbsp;&nbsp;List of Students</button>
                </div>
                <div class="col-md-6 mb-2 text-right">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="gradelevel"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="gradelevel"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
                </div>
                <div class="col-md-12">
                    {{-- <input type="text" id="myInput" placeholder="Search" class="form-control"> --}}
                    <table id="gradelevelstable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>    
                                <th>Grade Level</th>     
                                <th>Male</th>      
                                <th>Female</th>    
                                <th>Total</th>    
                            </tr>
                        </thead>
                        <tbody class="gradelevelscontainer"  style="font-size: 12px; ">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-content-above-track" role="tabpanel" aria-labelledby="custom-content-above-track-tab">
            <div class="row mt-2 mb-2" id="container-track">
                <div class="col-md-6 mb-2 text-left">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="track" data-otherid="list"><i class="fa fa-file-pdf"></i> &nbsp;&nbsp;List of Students</button>
                </div>
                <div class="col-md-6 mb-2 text-right">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="track"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="track"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
                </div>
                <div class="col-md-12">
                    {{-- <input type="text" id="myInput" placeholder="Search" class="form-control"> --}}
                    <table id="trackstable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>    
                                <th>Track</th>     
                                <th>Male</th>      
                                <th>Female</th>    
                                <th>Total</th>    
                            </tr>
                        </thead>
                        <tbody class="trackscontainer"  style="font-size: 12px; ">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-content-above-strand" role="tabpanel" aria-labelledby="custom-content-above-strand-tab">
            <div class="row mt-2 mb-2" id="container-strand">
                <div class="col-md-6 mb-2 text-left">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="strand" data-otherid="list"><i class="fa fa-file-pdf"></i> &nbsp;&nbsp;List of Students</button>
                </div>
                <div class="col-md-6 mb-2 text-right">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="strand"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="strand"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
                </div>
                <div class="col-md-12">
                    {{-- <input type="text" id="myInput" placeholder="Search" class="form-control"> --}}
                    <table id="strandstable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>    
                                <th>Strand Name</th>     
                                <th>Strand Code</th>     
                                <th>Male</th>      
                                <th>Female</th>    
                                <th>Total</th>    
                            </tr>
                        </thead>
                        <tbody class="strandscontainer"  style="font-size: 12px; ">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-content-above-college" role="tabpanel" aria-labelledby="custom-content-above-college-tab">
            <div class="row mt-2 mb-2" id="container-college">
                <div class="col-md-6 mb-2 text-left">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="college" data-otherid="list"><i class="fa fa-file-pdf"></i> &nbsp;&nbsp;List of Students</button>
                </div>
                <div class="col-md-6 mb-2 text-right">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="college"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="college"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
                </div>
                <div class="col-md-12">
                    {{-- <input type="text" id="myInput" placeholder="Search" class="form-control"> --}}
                    <table id="collegestable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>    
                                <th>College</th>     
                                <th>Male</th>      
                                <th>Female</th>    
                                <th>Total</th>    
                            </tr>
                        </thead>
                        <tbody class="collegescontainer"  style="font-size: 12px; ">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-content-above-course" role="tabpanel" aria-labelledby="custom-content-above-course-tab">
            <div class="row mt-2 mb-2" id="container-course">
                <div class="col-md-6 mb-2 text-left">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="course" data-otherid="list"><i class="fa fa-file-pdf"></i> &nbsp;&nbsp;List of Students</button>
                </div>
                <div class="col-md-6 mb-2 text-right">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="course" data-otherid="distribution"><i class="fa fa-file-pdf"></i> Enrollment Distribution</button>
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="course"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="course"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
                </div>
                <div class="col-md-12">
                    {{-- <input type="text" id="myInput" placeholder="Search" class="form-control"> --}}
                    <table id="coursestable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>    
                                <th>Course</th>     
                                <th>Male</th>      
                                <th>Female</th>    
                                <th>Total</th>    
                            </tr>
                        </thead>
                        <tbody class="coursescontainer"  style="font-size: 12px; ">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="custom-content-above-section" role="tabpanel" aria-labelledby="custom-content-above-section-tab">
            <div class="row mt-2 mb-2" id="container-section">
                <div class="col-md-6 mb-2 text-left">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="section" id="allsections"><i class="fa fa-file-pdf"></i> List of students</button>
                </div>
                <div class="col-md-6 mb-2 text-right">
                    <button type="button" class="btn btn-default btn-sm export-pdf" exporttype="section"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                    <button type="button" class="btn btn-default btn-sm export-excel" exporttype="section"><i class="fa fa-file-excel"></i> Export to EXCEL</button>
                </div>
                <div class="col-md-12">
                    {{-- <input type="text" id="myInput" placeholder="Search" class="form-control"> --}}
                    <table id="sectionstable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20px;">#</th>    
                                <th>Section</th>     
                                <th>Male</th>      
                                <th>Female</th>    
                                <th>Total</th>    
                            </tr>
                        </thead>
                        <tbody class="sectionscontainer"  style="font-size: 12px; ">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- /.card -->
  </div>
@endsection

@section('footerjavascript')   

<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script>
    var selectedschoolyear  = $('select[name=selectedschoolyear]').val();
    var selectedsemester    = $('select[name=selectedsemester]').val();
    var selectedacadprog    = 0;
    var studenttype         = 0;
    var studentstatus       = null;
    var selecteddate        = null;
    var selectedgender      = 0;
    var selectedgradelevel  = 0;
    var selectedsection     = 0;
    var trackid             = 0;
    var strandid            = 0;
    var selectedcollege     = 0;
    var selectedcourse      = 0;
    var selectedmode        = 0;
    var selectedgrantee     = 0;
    var lastselection       = '';
    $(document).ready(function(){
        $('#alert-warning').hide();
        $('#checkbox-daterange').on('click', function(){
            if($(this).is(":checked"))
            {
                selecteddate = '{{date('Y-m-d - Y-m-d')}}';
                $('input[name="selecteddate"]').val(selecteddate)
                $('input[name="selecteddate"]').removeAttr('disabled');
            }else{
                $('input[name="selecteddate"]').attr('disabled');
                selecteddate = null;
                $('input[name="selecteddate"]').val(selecteddate)
            }
        })
        $('#card-results').hide()
        $('#custom-content-above-tabContent').hide()
        $('.export').hide();
        $('body').addClass('sidebar-collapse')
        $('input[name=selecteddate]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoUpdateInput: false
        },function(start, end){
            // $('input[name=selecteddate]').on('change',function(e){
                // e.stopImmediatePropagation()
                $('input[name=selecteddate]').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'))
                selecteddate        = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');
                lastselection = 'selecteddate';
            }
        
        )
        
        function filterstudents ()
        { 
            

            Swal.fire({
                title: 'Generating results...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            
            $.ajax({
        
                url: "/reportssummariesallstudentsnew/filter",

                type: "GET",

                data: {
                        selectedschoolyear      : selectedschoolyear,
                        selectedsemester        : selectedsemester,
                        selectedacadprog        : selectedacadprog,
                        studenttype             : studenttype,
                        selectedstudentstatus   : studentstatus,
                        selecteddate            : selecteddate,
                        selectedgender          : selectedgender,
                        selectedgradelevel      : selectedgradelevel,
                        selectedsection         : selectedsection,
                        trackid                 : trackid,
                        strandid                : strandid,
                        selectedcollege         : selectedcollege,
                        selectedcourse          : selectedcourse,
                        selectedmode            : selectedmode,
                        selectedgrantee         : selectedgrantee
                    },
                    dataType: 'json',

                success: function (data) {
                    
                    getsignatories()
                    $('#alert-warning').hide();
                    $('#card-results').hide()
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                    $('.export').show();
                    $('#noofstudents').empty();
                    var studentcounter = 0;
                    $('.studentscontainer').empty()
                    $('.studentsinfocontainer').empty()
                    // if(data.length == 0){

                    //         $('#card-results').hide()
                    //         $('#noofstudents').append(
                    //             '<i class="fa fa-users"></i> &nbsp;<strong>0</strong> Student(s)'
                    //         );
                    //     // $('.studentscontainer').append(
                    //     //     '<tr>'+
                    //     //         '<td>0</td>'+
                    //     //         '<td colspan="6">'+
                    //     //             '<center>No students found!</center>'+
                    //     //         '</td>'+
                    //     //     '</tr>'
                    //     // )
                    // }
                    if(data.length == 0){
                            $('#card-results').show()
                            $('#noofstudents').append(
                                '<i class="fa fa-users"></i> &nbsp;<strong>0</strong> Student(s)'
                            );

                    }else{
                        if(data[0].students.length == 0)
                        {
                            $('#alert-warning').show();
                            $('#card-results').hide()
                            $('#noofstudents').append(
                                '<i class="fa fa-users"></i> &nbsp;<strong>0</strong> Student(s)'
                            );
                                
                            // $('.studentscontainer').append(
                            //     '<tr>'+
                            //         '<td>0</td>'+
                            //         '<td colspan="6" class="text-center">No students found!</td>'+
                            //     '</tr>'
                            // )

                        }else{
                            $('#alert-warning').hide();
                            $('#card-results').show()
                            $('#noofstudents').append(
                                '<i class="fa fa-users"></i> &nbsp;<strong>'+data[0].students.length+'</strong> Student(s)'
                            );
                            $.each(data[0].students,function(key, value){
                                studentcounter += 1;
                                if(value.suffix == ""){
                                    value.suffix = "";
                                }
                                if(value.lrn == null || value.lrn == ""){
                                    value.lrn = " ";
                                }
                                if(value.studinfostatus == '0')
                                {
                                    var badgestatusstudinfo = '<span class="badge badge-danger">S</span>';
                                }
                                else if(value.studinfostatus == '1')
                                {
                                    var badgestatusstudinfo = '<span class="badge badge-success">S</span>';
                                }else{
                                    var badgestatusstudinfo = '<span class="badge badge-warning">S</span>';
                                }
                                if(value.enrolledstudstatus == '0')
                                {
                                    var badgestatusenrolledstud = '<span class="badge badge-danger">E</span>';
                                }
                                else if(value.enrolledstudstatus == '1')
                                {
                                    var badgestatusenrolledstud = '<span class="badge badge-success">E</span>';
                                }else{
                                    var badgestatusenrolledstud = '<span class="badge badge-warning">E</span>';
                                }
                                $('.studentscontainer').append(
                                    '<tr>'+
                                        '<td style="width: 10%;">'+badgestatusstudinfo+' '+badgestatusenrolledstud+' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+studentcounter+'</td>'+
                                        '<td> '+value.sid+'</td>'+
                                        '<td> '+value.lrn+'</td>'+
                                        '<td> '+value.lastname+'</td>'+
                                        '<td> '+value.firstname+'</td>'+
                                        '<td> '+value.middlename+'</td>'+
                                        '<td> '+value.suffix+'</td>'+
                                        '<td>'+value.gender+'</td>'+
                                        '<td>'+value.dob+'</td>'+
                                        '<td>'+value.mol+'</td>'+
                                        '<td>'+value.levelname+'</td>'+
                                        '<td>'+value.sectionname+'</td>'+
                                        '<td>'+value.trackname+'</td>'+
                                        '<td>'+value.strandname+'</td>'+
                                    '</tr>'
                                )
                                
                                // '<td style="width: 10%;">'+badgestatusstudinfo+' '+badgestatusenrolledstud+' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+studentcounter+'</td>'+
                                $('.studentsinfocontainer').append(
                                    '<tr>'+
                                        '<td style="width: 10%;">'+studentcounter+'</td>'+
                                        '<td>'+value.firstname+'</td>'+
                                        '<td>'+value.lastname+'</td>'+
                                    '</tr>'
                                )
                            })
                        }
                        var gradelevelcounter = 0;
                        $('.gradelevelscontainer').empty()
                        if(data[0].gradelevels.length == 0)
                        {
                                
                            // $('.gradelevelscontainer').append(
                            //     '<tr>'+
                            //         '<td>0</td>'+
                            //         '<td colspan="4" class="text-center">No grade levels found!</td>'+
                            //     '</tr>'
                            // )
                        }else{
                            var gradelevelmaletotal = 0;
                            var gradelevelfemaletotal = 0;
                            var gradeleveltotal = 0;
                            $.each(data[0].gradelevels,function(levelkey, levelvalue){
                                gradelevelcounter += 1;
                                
                                $('.gradelevelscontainer').append(
                                    '<tr>'+
                                        '<td style="width: 10%;">'+gradelevelcounter+'</td>'+
                                        '<td> '+levelvalue.levelname+'</td>'+
                                        '<td class="text-center">'+levelvalue.countmale+'</td>'+
                                        '<td class="text-center">'+levelvalue.countfemale+'</td>'+
                                        '<td class="text-center">'+levelvalue.total+'</td>'+
                                    '</tr>'
                                )
                                gradelevelmaletotal+=levelvalue.countmale;
                                gradelevelfemaletotal+=levelvalue.countfemale;
                                gradeleveltotal+=levelvalue.total;
                            })
                            $('.gradelevelscontainer').append(
                                '<tr>'+
                                    '<td style="width: 10%;"></td>'+
                                    '<td class="text-center">TOTAL</td>'+
                                    '<td class="text-center">'+gradelevelmaletotal+'</td>'+
                                    '<td class="text-center">'+gradelevelfemaletotal+'</td>'+
                                    '<td class="text-center">'+gradeleveltotal+'</td>'+
                                '</tr>'
                            )
                        }
                        var trackcounter = 0;
                        $('.trackscontainer').empty()
                        if(data[0].tracks.length == 0)
                        {
                                
                            // $('.trackscontainer').append(
                            //     '<tr>'+
                            //         '<td>0</td>'+
                            //         '<td colspan="4" class="text-center">No tracks found!</td>'+
                            //     '</tr>'
                            // )
                        }else{
                            var trackmaletotal = 0;
                            var trackfemaletotal = 0;
                            var tracktotal = 0;
                            $.each(data[0].tracks,function(trackkey, trackvalue){
                                trackcounter += 1;
                                
                                $('.trackscontainer').append(
                                    '<tr>'+
                                        '<td style="width: 10%;">'+trackcounter+'</td>'+
                                        '<td> '+trackvalue.trackname+'</td>'+
                                        '<td class="text-center">'+trackvalue.countmale+'</td>'+
                                        '<td class="text-center">'+trackvalue.countfemale+'</td>'+
                                        '<td class="text-center">'+trackvalue.total+'</td>'+
                                    '</tr>'
                                )
                                trackmaletotal+=trackvalue.countmale;
                                trackfemaletotal+=trackvalue.countfemale;
                                tracktotal+=trackvalue.total;
                            })
                            $('.trackscontainer').append(
                                '<tr>'+
                                    '<td style="width: 10%;"></td>'+
                                    '<td class="text-center">TOTAL</td>'+
                                    '<td class="text-center">'+trackmaletotal+'</td>'+
                                    '<td class="text-center">'+trackfemaletotal+'</td>'+
                                    '<td class="text-center">'+tracktotal+'</td>'+
                                '</tr>'
                            )
                        }
                        var strandcounter = 0;
                        $('.strandscontainer').empty()
                        if(data[0].strands.length == 0)
                        {
                                
                            // $('.strandscontainer').append(
                            //     '<tr>'+
                            //         '<td>0</td>'+
                            //         '<td colspan="5" class="text-center">No strands found!</td>'+
                            //     '</tr>'
                            // )
                        }else{
                            var strandmaletotal = 0;
                            var strandfemaletotal = 0;
                            var strandtotal = 0;
                            $.each(data[0].strands,function(strandkey, strandvalue){
                                strandcounter += 1;
                                
                                $('.strandscontainer').append(
                                    '<tr>'+
                                        '<td style="width: 10%;">'+strandcounter+'</td>'+
                                        '<td> '+strandvalue.strandname+'</td>'+
                                        '<td> '+strandvalue.strandcode+'</td>'+
                                        '<td class="text-center">'+strandvalue.countmale+'</td>'+
                                        '<td class="text-center">'+strandvalue.countfemale+'</td>'+
                                        '<td class="text-center">'+strandvalue.total+'</td>'+
                                    '</tr>'
                                )
                                strandmaletotal+=strandvalue.countmale;
                                strandfemaletotal+=strandvalue.countfemale;
                                strandtotal+=strandvalue.total;
                            })
                            $('.strandscontainer').append(
                                '<tr>'+
                                    '<td style="width: 10%;"></td>'+
                                    '<td class="text-center" colspan="2">TOTAL</td>'+
                                    '<td class="text-center">'+strandmaletotal+'</td>'+
                                    '<td class="text-center">'+strandfemaletotal+'</td>'+
                                    '<td class="text-center">'+strandtotal+'</td>'+
                                '</tr>'
                            )
                        }
                        var collegecounter = 0;
                        $('.collegescontainer').empty()
                        if(data[0].colleges.length == 0)
                        {
                                
                            // $('.collegescontainer').append(
                            //     '<tr>'+
                            //         '<td>0</td>'+
                            //         '<td colspan="4" class="text-center">No colleges found!</td>'+
                            //     '</tr>'
                            // )
                        }else{
                            var collegemaletotal = 0;
                            var collegefemaletotal = 0;
                            var collegetotal = 0;
                            $.each(data[0].colleges,function(collegekey, collegevalue){
                                collegecounter += 1;
                                
                                $('.collegescontainer').append(
                                    '<tr>'+
                                        '<td style="width: 10%;">'+collegecounter+'</td>'+
                                        '<td> '+collegevalue.collegeDesc+'</td>'+
                                        '<td class="text-center">'+collegevalue.countmale+'</td>'+
                                        '<td class="text-center">'+collegevalue.countfemale+'</td>'+
                                        '<td class="text-center">'+collegevalue.total+'</td>'+
                                    '</tr>'
                                )
                                collegemaletotal+=collegevalue.countmale;
                                collegefemaletotal+=collegevalue.countfemale;
                                collegetotal+=collegevalue.total;
                            })
                            $('.collegescontainer').append(
                                '<tr>'+
                                    '<td style="width: 10%;"></td>'+
                                    '<td class="text-center">TOTAL</td>'+
                                    '<td class="text-center">'+collegemaletotal+'</td>'+
                                    '<td class="text-center">'+collegefemaletotal+'</td>'+
                                    '<td class="text-center">'+collegetotal+'</td>'+
                                '</tr>'
                            )
                        }
                        var coursecounter = 0;
                        $('.coursescontainer').empty()
                        if(data[0].courses.length == 0)
                        {
                                
                            // $('.coursescontainer').append(
                            //     '<tr>'+
                            //         '<td>0</td>'+
                            //         '<td colspan="4" class="text-center">No courses found!</td>'+
                            //     '</tr>'
                            // )
                        }else{
                            var coursemaletotal = 0;
                            var coursefemaletotal = 0;
                            var coursetotal = 0;
                            $.each(data[0].courses,function(coursekey, coursevalue){
                                coursecounter += 1;
                                
                                $('.coursescontainer').append(
                                    '<tr>'+
                                        '<td style="width: 10%;">'+coursecounter+'</td>'+
                                        '<td> '+coursevalue.courseDesc+'</td>'+
                                        '<td class="text-center">'+coursevalue.countmale+'</td>'+
                                        '<td class="text-center">'+coursevalue.countfemale+'</td>'+
                                        '<td class="text-center">'+coursevalue.total+'</td>'+
                                    '</tr>'
                                )
                                coursemaletotal+=coursevalue.countmale;
                                coursefemaletotal+=coursevalue.countfemale;
                                coursetotal+=coursevalue.total;
                            })
                            $('.coursescontainer').append(
                                '<tr>'+
                                    '<td style="width: 10%;"></td>'+
                                    '<td class="text-center">TOTAL</td>'+
                                    '<td class="text-center">'+coursemaletotal+'</td>'+
                                    '<td class="text-center">'+coursefemaletotal+'</td>'+
                                    '<td class="text-center">'+coursetotal+'</td>'+
                                '</tr>'
                            )
                        }
                        var sectioncounter = 0;
                        $('.sectionscontainer').empty()
                        if(data[0].sections.length == 0)
                        {
                                
                            // $('.coursescontainer').append(
                            //     '<tr>'+
                            //         '<td>0</td>'+
                            //         '<td colspan="4" class="text-center">No courses found!</td>'+
                            //     '</tr>'
                            // )
                        }else{
                            var sectionmaletotal = 0;
                            var sectionfemaletotal = 0;
                            var sectiontotal = 0;
                            $.each(data[0].sections,function(sectionkey, sectionvalue){
                                sectioncounter += 1;
                                
                                $('.sectionscontainer').append(
                                    '<tr>'+
                                        '<td style="width: 10%;">'+sectioncounter+'</td>'+
                                        '<td>'+sectionvalue.levelname+' - '+sectionvalue.sectionname+'</td>'+
                                        '<td class="text-center">'+sectionvalue.countmale+'</td>'+
                                        '<td class="text-center">'+sectionvalue.countfemale+'</td>'+
                                        '<td class="text-center">'+sectionvalue.total+'</td>'+
                                    '</tr>'
                                )
                                sectionmaletotal+=sectionvalue.countmale;
                                sectionfemaletotal+=sectionvalue.countfemale;
                                sectiontotal+=sectionvalue.total;
                            })
                            $('.sectionscontainer').append(
                                '<tr>'+
                                    '<td style="width: 10%;"></td>'+
                                    '<td class="text-center">TOTAL</td>'+
                                    '<td class="text-center">'+sectionmaletotal+'</td>'+
                                    '<td class="text-center">'+sectionfemaletotal+'</td>'+
                                    '<td class="text-center">'+sectiontotal+'</td>'+
                                '</tr>'
                            )
                        }
                    }
                    var $rows = $('.studentscontainer tr');
                    $('#myInput').on('keyup', function(){
                        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                        
                        $rows.show().filter(function() {
                            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                            return !~text.indexOf(val);
                        }).hide();
                    })
                    var table = $("#studentstable").DataTable({
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth":false,
                "columns": [
                                { "width": "25px", orderable: false },
                                { "width": "25px", orderable: false },
                                { "width": "700px", orderable: false },
                                { "width": "700px", orderable: false },
                                { "width": "700px", orderable: false },
                                { "width": "700px", orderable: false },
                                { "width": "20px", orderable: false },
                                { "width": "20px", orderable: false },
                                { "width": "20px", orderable: false },
                                { "width": "20px", orderable: false },
                                { "width": "20px", orderable: false },
                                { "width": "20px", orderable: false },
                                { "width": "20px", orderable: false },
                                { "width": "20px", orderable: false }
                        ]
                    });
                    table.on( 'order.dt search.dt', function () {
                        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();
                    // $("#studentstable").DataTable({
                    //     // pageLength : 10,
                    //     // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
                    //     "bPaginate": false,
                    //     "bInfo" : false,
                    //     "bFilter" : false,
                    //     "order": [[ 1, 'asc' ]]
                    // })
                    $('#custom-content-above-tabContent').show()
                }

            });
        }
        $('#filtergenerate').on('click',function(){
            filterstudents();
            $('#studentstable').DataTable().destroy();
        })
        $('.export-pdf').on('click', function(){
                var exporttype = 'pdf'
                if($(this).attr('id') == 'allsections')
                {
                    var allsections = 1;
                }else{
                    var allsections = 0;
                }
                var list = 0;
                if($(this).attr('data-otherid') == 'list')
                {
                    list = 1;
                }
                if($(this).attr('data-otherid') == 'distribution')
                {
                    list = 2;
                }
                var layout = $(this).attr('exporttype')
                var paramet = {
                    list : list,
                    allsections : allsections,
                    selectedschoolyear  : selectedschoolyear,
                    selectedsemester  : selectedsemester,
                    selectedacadprog   : selectedacadprog, 
                    studenttype  : studenttype, 
                    selectedstudentstatus  : studentstatus, 
                    selecteddate    : selecteddate, 
                    selectedgender     : selectedgender, 
                    selectedgradelevel     : selectedgradelevel,
                    selectedsection     : selectedsection,
                    trackid     : trackid,
                    strandid     : strandid,
                    selectedcollege     : selectedcollege,
                    selectedcourse     : selectedcourse,
                    selectedmode     : selectedmode,
                    selectedgrantee        : selectedgrantee 
                }
				window.open("/reportssummariesallstudentsnew/print?layout="+layout+"&exporttype="+exporttype+"&"+$.param(paramet));
        })
        $('.export-excel').on('click', function(){
                var fourps = 0;
                if($(this).attr('data-otherid') == '4ps')
                {
                    fourps = 1;
                }
                var exporttype = 'excel';
                var layout = $(this).attr('exporttype')
                var paramet = {
                    fourps  : fourps,
                    selectedschoolyear  : selectedschoolyear,
                    selectedsemester  : selectedsemester,
                    selectedacadprog   : selectedacadprog, 
                    studenttype  : studenttype, 
                    selectedstudentstatus  : studentstatus, 
                    selecteddate    : selecteddate, 
                    selectedgender     : selectedgender, 
                    selectedgradelevel     : selectedgradelevel,
                    selectedsection     : selectedsection,
                    trackid     : trackid,
                    strandid     : strandid,
                    selectedcollege     : selectedcollege,
                    selectedcourse     : selectedcourse,
                    selectedmode     : selectedmode,
                    selectedgrantee        : selectedgrantee 
                }
				window.open("/reportssummariesallstudentsnew/print?layout="+layout+"&exporttype="+exporttype+"&"+$.param(paramet));
        })
        $('#btn-student-directory-excel').on('click', function(){
                var fourps = 0;
                var exporttype = 'excel';
                var layout = 'studdirectoryexcel';
                var paramet = {
                    fourps  : fourps,
                    selectedschoolyear  : selectedschoolyear,
                    selectedsemester  : selectedsemester,
                    selectedacadprog   : selectedacadprog, 
                    studenttype  : studenttype, 
                    selectedstudentstatus  : studentstatus, 
                    selecteddate    : selecteddate, 
                    selectedgender     : selectedgender, 
                    selectedgradelevel     : selectedgradelevel,
                    selectedsection     : selectedsection,
                    trackid     : trackid,
                    strandid     : strandid,
                    selectedcollege     : selectedcollege,
                    selectedcourse     : selectedcourse,
                    selectedmode     : selectedmode,
                    selectedgrantee        : selectedgrantee 
                }
				window.open("/reportssummariesallstudentsnew/print?layout="+layout+"&exporttype="+exporttype+"&"+$.param(paramet));
        })
        $('#btn-savesignatories').on('click', function(){            
            var preparedby = $('#preparedby').val()
            var generatedby = $('#generatedby').val()
            $.ajax({
                url: '/reportssummariesallstudentsnew/updatesignatories',
                type: 'GET',
                dataType: 'json',
                data:{
                    preparedby: preparedby,
                    generatedby: generatedby,
                    // formid: 'summaryofallstudents',
                    syid: selectedschoolyear,
                    acadprogid: '0',
                    levelid: '0'
                    // dataid: dataid,
                    // title: title,
                    // name: name,
                    // label: label
                },
                success: function(data){
                    $('select[name=selectedgradelevel]').empty();
                    $('select[name=selectedgradelevel]').append(
                        '<option value="0">Select Level</option>'
                    );
                    if(data.length == 0)
                    {
                            $('select[name=selectedgradelevel]').append(
                                '<option value="0">All</option>'
                            );
                    }else{
                        $.each(data, function(key, value){
                            $('select[name=selectedgradelevel]').append(
                                '<option value="'+value.id+'">'+value.levelname+'</option>'
                            );
                        })
                    }
                    getsignatories()
                }
            })    

        })
        function getsignatories()
        {
            $.ajax({
                url: '/reportssummariesallstudentsnew/getsignatories',
                type: 'GET',
                dataType: 'json',
                data:{
                    // formid: 'summaryofallstudents',
                    syid: selectedschoolyear
                    // dataid: dataid,
                    // title: title,
                    // name: name,
                    // label: label
                },
                success: function(data){
                          
                    $('#preparedby').val(data.preparedby)
                    $('#generatedby').val(data.generatedby)
                }
            })    
        }
        
    })

    $(document).on('change','select[name=selectedschoolyear]', function(){
        $('#custom-content-above-tabContent').hide()

        selectedschoolyear  = $(this).val();
        lastselection = 'selectedschoolyear';

    })
    $(document).on('change','select[name=selectedsemester]', function(){
        $('#custom-content-above-tabContent').hide()

        selectedsemester  = $(this).val();
        lastselection = 'selectedsemester';

    })
    $(document).on('change','select[name=selectedacadprog]', function(){
        $('#custom-content-above-tabContent').hide()

        selectedacadprog    = $(this).val();
        lastselection = 'selectedacadprog';
        selectedgradelevel = 0;

        if(selectedacadprog == 2 || selectedacadprog == 3 || selectedacadprog == 4)
        {
            $('select[name="trackid"]').closest('div').hide()
            $('select[name="strandid"]').closest('div').hide()
            $('select[name="collegeid"]').closest('div').hide()
            $('select[name="courseid"]').closest('div').hide()
        }
        else if(selectedacadprog == 5)
        {
            $('select[name="trackid"]').closest('div').show()
            $('select[name="strandid"]').closest('div').show()
            $('select[name="collegeid"]').closest('div').hide()
            $('select[name="courseid"]').closest('div').hide()
        }
        else if(selectedacadprog == 6)
        {
            $('select[name="trackid"]').closest('div').hide()
            $('select[name="strandid"]').closest('div').hide()
            $('select[name="collegeid"]').closest('div').show()
            $('select[name="courseid"]').closest('div').show()
        }
        else{
            $('select[name="trackid"]').closest('div').show()
            $('select[name="strandid"]').closest('div').show()
            $('select[name="collegeid"]').closest('div').show()
            $('select[name="courseid"]').closest('div').show()
        }
        $.ajax({
            url: '/reportssummariesallstudentsnew/getgradelevels',
            type: 'GET',
            dataType: 'json',
            data:{
                selectedacadprog: selectedacadprog
            },
            success: function(data){
                $('select[name=selectedgradelevel]').empty();
                $('select[name=selectedgradelevel]').append(
                    '<option value="0">Select Level</option>'
                );
                if(data.length == 0)
                {
                        $('select[name=selectedgradelevel]').append(
                            '<option value="0">All</option>'
                        );
                }else{
                    $.each(data, function(key, value){
                        $('select[name=selectedgradelevel]').append(
                            '<option value="'+value.id+'">'+value.levelname+'</option>'
                        );
                    })
                }
            }
        })

    })
    $(document).on('change','select[name=studenttype]', function(){
        $('#custom-content-above-tabContent').hide()

        studenttype         = $(this).val();
        lastselection = 'studenttype';

    })
    $('.select2').select2().on('select2:select', function () {
        $('#custom-content-above-tabContent').hide()
        studentstatus       = $(this).val();
        lastselection = 'studentstatus';
    })
    $(document).on('change','select[name=selectedgender]', function(){
        $('#custom-content-above-tabContent').hide()
        selectedgender      = $(this).val();
        lastselection = 'selectedgender';

    })
    $(document).on('change','select[name=selectedgradelevel]', function(){
        $('#custom-content-above-tabContent').hide()
        selectedgradelevel  = $(this).val();
        lastselection = 'selectedgradelevel';
        selectedsection = null;
        $.ajax({
            url: '/reportssummariesallstudentsnew/getsections',
            type: 'GET',
            dataType: 'json',
            data:{
                selectedgradelevel: selectedgradelevel,
                selectedschoolyear: selectedschoolyear
            },
            success: function(data){
                $('select[name=selectedsection]').empty();
                $('select[name=selectedsection]').append(
                    '<option value="0">Select section</option>'
                );
                $.each(data, function(key, value){
                    $('select[name=selectedsection]').append(
                        '<option value="'+value.id+'">'+value.sectionname+'</option>'
                    );
                })
            }
        })

    })
    $(document).on('change','select[name=selectedsection]', function(){
        $('#custom-content-above-tabContent').hide()
        selectedsection     = $(this).val();
        lastselection = 'selectedsection';

    })
    $(document).on('change','select[name=trackid]', function(){
        $('#custom-content-above-tabContent').hide()
        trackid             = $(this).val();
        lastselection = 'trackid';

    })
    $(document).on('change','select[name=strandid]', function(){
        $('#custom-content-above-tabContent').hide()
        strandid            = $(this).val();
        lastselection = 'strandid';
    })
    $(document).on('change','select[name=collegeid]', function(){
        $('#custom-content-above-tabContent').hide()
        selectedcollege     = $(this).val();
        lastselection = 'selectedcollege';
        selectedcourse = null;
        $.ajax({
            url: '/reportssummariesallstudentsnew/getcourses',
            type: 'GET',
            dataType: 'json',
            data:{
                selectedcollege: selectedcollege
            },
            success: function(data){
                $('select[name=courseid]').empty();
                $('select[name=courseid]').append(
                    '<option value="0">Select course</option>'
                );
                $.each(data, function(key, value){
                    $('select[name=courseid]').append(
                        '<option value="'+value.id+'">'+value.courseDesc+'</option>'
                    );
                })
            }
        })

    })
    $(document).on('change','select[name=courseid]', function(){
        $('#custom-content-above-tabContent').hide()
        selectedcourse      = $(this).val();
        lastselection = 'selectedcourse';
    })

    $(document).on('change','select[name=selectedmode]', function(){
        $('#custom-content-above-tabContent').hide()
        selectedmode        = $(this).val();
        lastselection = 'selectedmode';
    })
    
    $(document).on('change','select[name=selectedgrantee]', function(){
        $('#custom-content-above-tabContent').hide()
        selectedgrantee     = $(this).val();
        lastselection = 'selectedgrantee';
    })
</script>
@endsection
