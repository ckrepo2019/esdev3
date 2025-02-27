
@extends('registrar.layouts.app')
@section('content')

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
        <input type="hidden" value="School Form 1" name="selectedform"/>
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
                                            <button type="button" class="btn btn-sm border btn-block dropdown-toggle dropdown-icon small-box-footer" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                                                <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item" href="#" id="exportpdf{{$students[0]->sectionid}}">PDF</a>
                                                <a class="dropdown-item" href="#" id="exportexcel{{$students[0]->sectionid}}">EXCEL</a>
                                                </div>
                                            </button>
                                            <script>
                                                $('#exportpdf{{$students[0]->sectionid}}').on('click', function(){
                                                    window.open("/registrar/forms/schoolform1/export?selectedform={{$selectedform}}&schoolyear={{$schoolyear}}&sectionid={{$students[0]->sectionid}}&levelid={{$students[0]->levelid}}&exporttype=pdf");
                                                })
                                                $('#exportexcel{{$students[0]->sectionid}}').on('click', function(){
                                                    window.open("/registrar/forms/schoolform1/export?selectedform={{$selectedform}}&schoolyear={{$schoolyear}}&sectionid={{$students[0]->sectionid}}&levelid={{$students[0]->levelid}}&exporttype=excel");
                                                })
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
    {{-- <div class="row">  
        @if(isset($messageWarning))
            @if($messageWarning == true)
                <div class="alert alert-warning alert-dismissible col-12">
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
            @else
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
                                        @if(count($grade_sections_lower) > 0)
                                            @foreach ($grade_sections_lower as $section)
                                                @if($section->gradelevelid == $gradelevel->gradelevelid)
                                                <div class="col-lg-3 col-3">
                                                    <!-- small box -->
                                                    <div class="small-box bg-warning">
                                                    <div class="inner">
                                                        <h3>{{$section->students}}</h3>
                                        
                                                        <p class="mb-0">{{$section->sectionname}}</p>
                                                        <p class="mt-0">{{$section->teacher_lastname}}, {{$section->teacher_firstname[0]}}. {{$section->teacher_middlename[0]}}</p>
                                                    </div>
                                                    <div class="icon">
                                                            <i class="fas fa-door-open"></i>
                                                    </div>
                                                    <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon small-box-footer" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                                                        <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a class="dropdown-item" href="#" id="exportpdf{{$section->sectionid}}">PDF</a>
                                                        <a class="dropdown-item" href="#" id="exportexcel{{$section->sectionid}}">EXCEL</a>
                                                        </div>
                                                    </button>
                                                    <script>
                                                            $('#exportpdf{{$section->sectionid}}').on('click', function(){
                                                                window.open("/registrar/forms/schoolform1/export?selectedform={{$selectedform}}&schoolyear={{$schoolyear}}&sectionid={{$section->sectionid}}&levelid={{$gradelevel->gradelevelid}}&exporttype=pdf");
                                                            })
                                                            $('#exportexcel{{$section->sectionid}}').on('click', function(){
                                                                window.open("/registrar/forms/schoolform1/export?selectedform={{$selectedform}}&schoolyear={{$schoolyear}}&sectionid={{$section->sectionid}}&levelid={{$gradelevel->gradelevelid}}&exporttype=excel");
                                                            })
                                                        </script>
                                                    </div>
                                                </div>
                                                    @else
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
            @else
                <div id="accordion" class="col-12">
                    @foreach($grade_levels_higher as $gradelevel)
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
                                        @if(count($grade_sections_higher) > 0)
                                            @foreach ($grade_sections_higher as $section)
                                                @if($section->gradelevelid == $gradelevel->gradelevelid)
                                                <div class="col-lg-3 col-6">
                                                    <!-- small box -->
                                                    <div class="small-box bg-warning">
                                                    <div class="inner">
                                                        <h3>{{$section->students}}</h3>
                                        
                                                        <p class="mb-0">{{$section->sectionname}}</p>
                                                        <p class="mt-0">{{$section->teacher_lastname}}, {{$section->teacher_firstname[0]}}. {{$section->teacher_middlename[0]}}</p>
                                                    </div>
                                                    <div class="icon">
                                                            <i class="fas fa-door-open"></i>
                                                    </div>
                                                    <button type="button" class=" btn btn-sm btn-block dropdown-toggle dropdown-icon small-box-footer" data-toggle="dropdown" aria-expanded="false">Export As <span class="sr-only">Toggle Dropdown</span>
                                                        <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <a class="dropdown-item" href="#" id="exportpdf{{$section->sectionid}}">PDF</a>
                                                        <a class="dropdown-item" href="#" id="exportexcel{{$section->sectionid}}">EXCEL</a>
                                                        </div>
                                                    </button>
                                                    <script>
                                                            $('#exportpdf{{$section->sectionid}}').on('click', function(){
                                                                window.open("/registrar/forms/schoolform1/export?selectedform={{$selectedform}}&schoolyear={{$schoolyear}}&sectionid={{$section->sectionid}}&levelid={{$gradelevel->gradelevelid}}&exporttype=pdf");
                                                            })
                                                            $('#exportexcel{{$section->sectionid}}').on('click', function(){
                                                                window.open("/registrar/forms/schoolform1/export?selectedform={{$selectedform}}&schoolyear={{$schoolyear}}&sectionid={{$section->sectionid}}&levelid={{$gradelevel->gradelevelid}}&exporttype=excel");
                                                            })
                                                        </script>
                                                    </div>
                                                </div>
                                                    @else
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>--}}
    <!-- jQuery -->
@endsection
