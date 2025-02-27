
@extends('registrar.layouts.app')
@section('content')
    <style>
        .table tr{ border-bottom: 1px solid #ddd; }
        .card-header{ border-bottom: hidden; }
        td{ text-transform: uppercase; }
    </style>
    <form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear ?? ''}}" name="syid"/>
        <input type="hidden" value="Student Masterlist" name="selectedform"/>
    </form>
    <form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear ?? ''}}" name="syid"/>
        <input type="hidden" value="Student Masterlist" name="selectedform"/>
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
                        <li class="breadcrumb-item active">Student Masterlist</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    @if(isset($message))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning alert-dismissible">
                    {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    {{$message}}
                </div>
            </div>
        </div>
    @endif
    @if(isset($data[0]))
        <div class="row">
            <div class="col-12">
                <div class="card card-default color-palette-box">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-file"></i>
                            <strong>{{$data[0]->gradelevelname}} - {{$data[0]->sectionname}}</strong>
                        </h3>
                        <br>
                        <small>Teacher Incharged: <u>{{$data[0]->teacher_lastname}}, {{$data[0]->teacher_firstname}} {{$data[0]->teacher_middlename[0]}}. {{$data[0]->teacher_suffix}}</u></small>
                        <a href="/reports_studentmasterlist/print/{{$schoolyear ?? ''}}/{{$data[0]->sectionid}}" target="_blank" class="btn btn-primary btn-sm float-right" style="color: white;"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(isset($data))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-male"></i>
                            <strong>Male</strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            @php
                                $male = 0;
                            @endphp
                            @foreach ($data as $student)
                                @if (strtoupper($student->student_gender)=="MALE")
                                    @php
                                        $male+=1;   
                                    @endphp
                                    <tr>
                                        <td width="45%">
                                            {{$male}}.  {{$student->student_lastname}},
                                        </td>
                                        <td>
                                            {{$student->student_firstname}}
                                        </td>
                                        <td width="5%">
                                            {{$student->student_middlename[0]}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-female"></i>
                            <strong>Female</strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            @php
                                $female = 0;
                            @endphp
                            @foreach ($data as $student)
                                @if (strtoupper($student->student_gender)=="FEMALE")
                                    @php
                                        $female+=1;   
                                    @endphp
                                    <tr>
                                        <td width="45%">
                                            {{$female}}. {{$student->student_lastname}},
                                        </td>
                                        <td>
                                            {{$student->student_firstname}}
                                        </td>
                                        <td width="5%">
                                            {{$student->student_middlename[0]}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- </div> --}}
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script>submitSelectSchoolyear
        $('#selectsection').on('click', function (){
            document.getElementById('submitSelectSection').submit();
        })
        $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        })
    </script>
@endsection
