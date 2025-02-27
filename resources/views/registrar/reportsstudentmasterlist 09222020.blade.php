@extends('registrar.layouts.app')
@section('content')
    <form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$selectedform}}" name="selectedform"/>
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
    </form>
    <section class="content-header">
        <div class="col-12">
            @if($academicprogram == 'preschool')
                <h4>Pre-school</h4>
            @elseif($academicprogram == 'elementary')
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
        @if(isset($grade_levels_lower))
            @if (count($grade_levels_lower)==0)
                <div class="col-sm-12">
                    <div class="alert alert-warning alert-dismissible col-12">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> No records available!</h5>
                    </div>
                </div>
            @endif
        @endif
        @if(isset($grade_levels_higher))
            @if (count($grade_levels_higher)==0)
                <div class="col-sm-12">
                    <div class="alert alert-warning alert-dismissible col-12">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> No records available!</h5>
                    </div>
                </div>
            @endif
        @endif
        @if(isset($grade_levels_lower))
            @foreach($grade_levels_lower as $gradelevel)
                <div class="col-md-12">
                    <div class="card collapsed-card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a data-toggle="collapse" href="#" data-card-widget="collapse" >
                                {{$gradelevel->gradelevelname}}
                                </a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- {{$academicprogram}} --}}
                                @foreach ($grade_sections_lower as $section)
                                    @if($section->gradelevelid == $gradelevel->gradelevelid)
                                        <div class="col-md-4">
                                                <a href="/reports_studentmasterlist/preview/{{$schoolyear}}/{{$section->sectionid}}?selectedform={{$selectedform}}&schoolyeardesc={{$schoolyeardesc}}&academicprogram={{$academicprogram}}" class="eachsection">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-info elevation-1">
                                                            <i class="fa fa-door-open"></i>
                                                        </span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">{{$section->sectionname}}</span>
                                                            <span class="info-box-number">
                                                                {{$section->students}}
                                                                <small>Students</small>
                                                            </span>
                                                            <sub>{{$section->teacher_lastname}}, {{$section->teacher_firstname[0]}}. {{$section->teacher_middlename[0]}}</sub>
                                                            {{-- <input type="hidden" value="{{$selectedform}}" name="selectedform"/>
                                                            <input type="hidden" value="{{$schoolyeardesc}}" name="schoolyeardesc"/>
                                                            <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/> --}}
                                                        </div>
                                                    </div>
                                                </a>
                                        </div>
                                        @else
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        @if(isset($grade_levels_higher))
            @foreach($grade_levels_higher as $gradelevelHigh)
                <div class="col-md-12">
                    <div class="card collapsed-card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a data-toggle="collapse" href="#" data-card-widget="collapse" >
                                {{$gradelevelHigh->gradelevelname}}
                                </a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($grade_sections_higher as $sectionHigher)
                                    @if($sectionHigher->gradelevelid == $gradelevelHigh->gradelevelid)
                                        <div class="col-md-4">
                                                <a href="/reports_studentmasterlist/preview/{{$schoolyear}}/{{$sectionHigher->sectionid}}?selectedform={{$selectedform}}&schoolyeardesc={{$schoolyeardesc}}&academicprogram={{$academicprogram}}" class="eachsection">
                                                    <div class="info-box">
                                                        <span class="info-box-icon bg-info elevation-1">
                                                            <i class="fa fa-door-open"></i>
                                                        </span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">{{$sectionHigher->sectionname}}</span>
                                                            <span class="info-box-number">
                                                                {{$sectionHigher->students}}
                                                                <small>Students</small>
                                                            </span>
                                                            <sub>{{$sectionHigher->teacher_lastname}}, {{$sectionHigher->teacher_firstname[0]}}. {{$sectionHigher->teacher_middlename[0]}}</sub>
                                                            {{-- <input type="hidden" value="{{$selectedform}}" name="selectedform"/>
                                                            <input type="hidden" value="{{$schoolyeardesc}}" name="schoolyeardesc"/>
                                                            <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/> --}}
                                                        </div>
                                                    </div>
                                                </a>
                                        </div>
                                        @else
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script>
        $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        });
    </script>
@endsection
