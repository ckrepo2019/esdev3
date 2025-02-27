

@extends('superadmin.layouts.pdf')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }

        .font-20 {
            font-size: 20px !
        }
    </style>
@endsection



@section('content')

@php
    $semester = DB::table('semester')->get();
    $yearlevel1 = DB::table('gradelevel')->where('acadprogid',6)->where('deleted',0)->get();
    $yearlevel2 = DB::table('gradelevel')->where('acadprogid',8)->where('deleted',0)->get();
    $schoolinfo = DB::table('schoolinfo')->first();
@endphp
<div class="p-5">
    <table width="100%" class="">
        <tr>
            <td width="35%" class="text-right"><img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}" alt="school" width="70px"></td>
            <td width="30%" class="text-center">
                <div class="font-weight-bold">{{$schoolinfo->schoolname}}</div>
                <div class="">{{$schoolinfo->address}}</div>
            </td>               
            <td width="35%"><img src="{{asset($studinfo->picurl)}}" alt="{{ asset('images/avatar/1.jpg') }}" width="70px"></td>
        </tr>
    </table>
    
    <table width="100%" class="mt-4">
        <tr>
            <td width="100%" class="text-center">
                <div class="font-weight-bold" style="font-size: 17px!important">{{$course->collegeDesc}}</div>
                <div class="" style="font-size: 20px!important">{{$course->courseDesc}}</div>
            </td>
        </tr>
    </table>
    
    <table width="100%" class="mt-4">
        <tr>
            <td width="100%" class="font-weight-bold text-center">GRADE EVALUATION</td>
        </tr>
    </table>
    
    <table width="100%" class="mt-4">
        <tr>
            <td width="15%"><span class="font-weight-bold">ID NO.: </span>{{$studinfo->sid}}</td>
            <td width="40%"><span class="font-weight-bold">Name: </span> {{$studinfo->lastname}},  {{$studinfo->firstname}}  {{$studinfo->middlename}}</td>
            <td width="25%"><span class="font-weight-bold">Year: </span>{{$student_course->levelname}}</td>
            <td width="20%" class="text-right"><span class="font-weight-bold">Date Now: </span>{{ \Carbon\Carbon::now()->format('m/d/Y') }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="font-weight-bold">Address: </span>{{$studinfo->full_address}}</td>
            <td ><span class="font-weight-bold">Date of Birth: </span>{{ \Carbon\Carbon::create($studinfo->dob)->format('m/d/Y') }}</td>
            <td class="text-right"><span class="font-weight-bold">Curriculum: </span>{{$student_course->curriculumname}}</td>
        </tr>
    </table>

    <hr style="margin-top: 20px; margin-bottom: 20px">
    @foreach($gradelevel as $level)
        @foreach($level->semesters as $sem)
            <div>
                <div>{{$level->levelname}} - {{$sem->semester}}</div>
                <table width="100%" class="table table-sm table-bordered table-striped" >
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Subject Description</th>
                            <th>Pre-requisite</th>
                            <th>Lecture</th>
                            <th>Laboratory</th>
                            <th>Credited Units</th>
                            <th>GPA</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sem->student_grades_prospectus as $grade)
                            <tr>
                                <td>{{$grade->subjCode}}</td>
                                <td>{{$grade->subjDesc}}</td>
                                <td>
                                    @foreach($grade->prereq as $prereq)
                                        <span>{{$prereq->subjCode}} - {{$prereq->subjDesc}} </span><br>
                                    @endforeach
                                </td>
                                <td class="text-center">{{$grade->lecunits}}</td>
                                <td class="text-center">{{$grade->labunits}}</td>
                                <td class="text-center">{{$grade->credunits}}</td>
                                <td class="text-center">{{$grade->fg}}</td>
                                <td class="text-center">{{$grade->remarks}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endforeach
    
    
</div>
            

    
    
@endsection

@section('footerscript')
    
@endsection