
@extends('registrar.layouts.app')
@section('content')
    <form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
        <input type="hidden" value="School Form 5" name="selectedform"/>
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
                        <li class="breadcrumb-item active">Select Section</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">  
        @if(isset($messageWarning))
            @if($messageWarning == true)
                <div class="alert alert-warning alert-dismissible col-12">
                    {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    {{$messageWarning}}
                </div>
            @endif
        @endif
        {{-- @if(isset($grade_levels_higher)) --}}
        @if (count($grade_levels_lower)==0)
            <div class="col-sm-12">
                <div class="alert alert-warning alert-dismissible col-12">
                    {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                    <h5><i class="icon fas fa-exclamation-triangle"></i> No records available!</h5>
                </div>
            </div>
        @endif
        {{-- @endif --}}
        @if($selectedform == 'School Form 5A' || $selectedform == 'School Form 5B')
            <div id="accordion" class="col-12">
                @foreach($grade_levels_higher as $gradelevelHigh)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{$gradelevelHigh->gradelevelid}}">
                                {{$gradelevelHigh->gradelevelname}}
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne{{$gradelevelHigh->gradelevelid}}" class="panel-collapse collapse in">
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($grade_sections_higher as $sectionHigher)
                                        @if($sectionHigher->gradelevelid == $gradelevelHigh->gradelevelid)
                                            <div class="col-md-4">
                                                <form action="/reports_schoolform5/preview/{{$selectedform}}/{{$schoolyear}}/{{$sectionHigher->sectionid}}/{{$gradelevelHigh->gradelevelid}}/{{$sectionHigher->teacherid}}" method="get">
                                                    <a>
                                                        <div class="info-box">
                                                            <span class="info-box-icon bg-info elevation-1">
                                                                <div class="icon">
                                                                        <i class="fas fa-door-open"></i>
                                                                </div>
                                                            </span>
                                                            <div class="info-box-content">
                                                                <span class="info-box-text">{{$sectionHigher->sectionname}}</span>
                                                                <span class="info-box-number">
                                                                    {{$sectionHigher->students}}
                                                                    <small>Students</small>
                                                                </span>
                                                                <sub>{{$sectionHigher->teacher_lastname}}, {{$sectionHigher->teacher_firstname[0]}}. {{$sectionHigher->teacher_middlename[0]}}</sub>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" value="{{$schoolyeardesc}}" name="schoolyeardesc"/>
                                                        <input type="hidden" value="School Form 5" name="selectedform"/>
                                                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                                                    </a>
                                                </form>
                                            </div>
                                            @else
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($selectedform == 'School Form 5')
            <div id="accordion" class="col-12">
                @foreach($grade_levels_lower as $gradelevel)
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{$gradelevel->gradelevelid}}">
                                {{$gradelevel->gradelevelname}}
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne{{$gradelevel->gradelevelid}}" class="panel-collapse collapse in">
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($grade_sections_lower as $section)
                                        @if($section->gradelevelid == $gradelevel->gradelevelid)
                                            <div class="col-md-4">
                                                <form action="/reports_schoolform5/preview/{{$selectedform}}/{{$schoolyear}}/{{$section->sectionid}}/{{$gradelevel->gradelevelid}}" method="get">
                                                    <a>
                                                        <div class="info-box">
                                                            <span class="info-box-icon bg-info elevation-1">
                                                                <div class="icon">
                                                                        <i class="fas fa-door-open"></i>
                                                                </div>
                                                            </span>
                                                            <div class="info-box-content">
                                                                <span class="info-box-text">{{$section->sectionname}}</span>
                                                                <span class="info-box-number">
                                                                    {{$section->students}}
                                                                    <small>Students</small>
                                                                </span>
                                                                <sub>{{$section->teacher_lastname}}, {{$section->teacher_firstname[0]}}. {{$section->teacher_middlename[0]}}</sub>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" value="{{$schoolyeardesc}}" name="schoolyeardesc"/>
                                                        <input type="hidden" value="School Form 5" name="selectedform"/>
                                                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                                                    </a>
                                                </form>
                                            </div>
                                            @else
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script>
        $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        });
        $('a').on('click', function (){
            $(this).closest('form').submit();
        });
    </script>
@endsection
