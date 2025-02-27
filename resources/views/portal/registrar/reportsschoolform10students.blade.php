
@extends('registrar.layouts.app')
@section('content')
    <style>
        td  { border-bottom: hidden; text-transform: uppercase; }
    </style>
    <form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="School Form 10" name="selectedform"/>
    </form>
    <form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="School Form 10" name="selectedform"/>
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
                    <li class="breadcrumb-item"><a id="selectsection" class="text-info">{{$section}}</a></li>
                    <li class="breadcrumb-item active">School Form 10 (Form 137)</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    @if(count($data) == 0)
        <div class="col-sm-12">
            <div class="alert alert-warning alert-dismissible col-12">
                {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                <h5><i class="icon fas fa-exclamation-triangle"></i> No records available!</h5>
                <p>Possible reason/s:</p>
                <ul>
                    <li>No students enrolled yet</li>
                </ul>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-6">
                @php
                    $male = 0;
                @endphp
                <div class="card">
                    <table class="table">
                        <tr>
                            <th>Male</th>
                        </tr>
                        @foreach ($data as $studentMale)
                        <tr>
                            @if (strtoupper($studentMale->gender)=="MALE")
                            <td class="pb-0">
                                @php
                                    $male+=1;   
                                @endphp
                                <form action="/reports_schoolform10/students_preview/{{$schoolyear}}/{{$sectionid}}/{{$gradelevelid}}/{{$studentMale->id}}" method="get" class="m-0">
                                    <a class="btn btn-block btn-default m-0" style="text-align: left;">
                                        {{$male}}. {{$studentMale->lastname}}, {{$studentMale->firstname}} {{$studentMale->middlename[0]}}. {{$studentMale->suffix}}
                                        <input type="hidden" value="{{$schoolyeardesc}}" name="schoolyeardesc"/>
                                        <input type="hidden" value="{{$section}}" name="selectedsection"/>
                                        <input type="hidden" value="School Form 10" name="selectedform"/>
                                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                                    </a>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                @php
                    $female = 0;
                @endphp
                <div class="card">
                    <table class="table">
                        <tr>
                            <th>Female</th>
                        </tr>
                        @foreach ($data as $studentFemale)
                        <tr>
                            @if (strtoupper($studentFemale->gender)=="FEMALE")
                            <td class="pb-0">
                                @php
                                    $female+=1;   
                                @endphp
                                <form action="/reports_schoolform10/students_preview/{{$schoolyear}}/{{$sectionid}}/{{$gradelevelid}}/{{$studentFemale->id}}" method="get" class="m-0">
                                    <a class="btn btn-block btn-default" style="text-align: left;">
                                        {{$female}}. {{$studentFemale->lastname}}, {{$studentFemale->firstname}} {{$studentFemale->middlename[0]}}. {{$studentFemale->suffix}}
                                        <input type="hidden" value="{{$schoolyeardesc}}" name="schoolyeardesc"/>
                                        <input type="hidden" value="{{$section}}" name="selectedsection"/>
                                        <input type="hidden" value="School Form 10" name="selectedform"/>
                                    </a>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endif
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script>
        $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        });
        $('#selectsection').on('click', function (){
            document.getElementById('submitSelectSection').submit();
        });
        $('a').on('click', function (){
            $(this).closest('form').submit();
        });
    </script>
@endsection
