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
    @if(count($students) == 0)
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning alert-dismissible col-12">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> No records available!</h5>
                </div>
            </div>
        </div>
    @else
        @foreach($students as $key => $student)
            <div class="card collapsed-card">
                <div class="card-header">
                    <a data-toggle="collapse" href="#" data-card-widget="collapse" >
                        <div class="row">
                            <div class="col-md-6">
                                {{$key}}
                            </div>
                            <div class="col-md-6 text-right">
                                <span class="right badge badge-warning">{{count($student)}}</span> 
                                <span class="right badge badge-info">Students</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $sections = collect($student)->sortBy('sectionname')->groupBy('sectionname');
                    @endphp
                    @if(count($sections) == 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-warning alert-dismissible col-12">
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> No records available!</h5>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            @foreach ($sections as $sectionkey => $students)
                                <div class="col-md-4">
                                        <a href="/reports_studentmasterlist/preview/{{$schoolyear}}/{{$students[0]->sectionid}}?selectedform={{$selectedform}}&schoolyeardesc={{$schoolyeardesc}}&academicprogram={{$academicprogram}}&levelid={{$students[0]->levelid}}" class="eachsection">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info elevation-1">
                                                    <i class="fa fa-door-open"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">{{$sectionkey}}</span>
                                                    <span class="info-box-number">
                                                        {{count($students)}}
                                                        <small>Students</small>
                                                    </span>
                                                    <span class="info-box-number">
                                                        
                                                        @if(count($students)>0)
                                                        <small>{{$students[0]->teachername}}</small>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
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
