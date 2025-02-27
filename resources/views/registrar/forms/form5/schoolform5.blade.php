
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
                <div class="card-body p-1">
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
                        <div class="row p-0">
                            @foreach ($sections as $sectionkey => $students)
                                <div class="col-md-4 p-2">
                                    <div class="border p-2" style="width: 100%;">
                                        <h5 style="font-weight: bold;">{{$sectionkey}}</h5>
                                        <p>{{count($students)}} Students</p>
                                        <a type="button" class="btn btn-sm btn-default" href="/export/form5?action=export&selectedform={{$selectedform}}&syid={{$schoolyear}}&sectionid={{$students[0]->sectionid}}&levelid={{$students[0]->levelid}}&exporttype=pdf" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>
                                        <a type="button" class="btn btn-sm btn-default p-1" href="/export/form5?action=export&selectedform={{$selectedform}}&syid={{$schoolyear}}&sectionid={{$students[0]->sectionid}}&levelid={{$students[0]->levelid}}&exporttype=excel" target="_blank"><i class="fa fa-file-excel"></i> EXCEL</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
            {{-- <div id="accordion" class="col-12">
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
                                            <div class="col-md-4 border pt-3 pb-2" style="line-height: 5px;">
                                                        <h5 style="font-weight: bold;">{{$section->sectionname}}</h5>
                                                        <p>{{$section->students}} Students</p>
                                                        <p>{{$section->teacher_lastname}}, {{$section->teacher_firstname[0]}}. {{$section->teacher_middlename[0]}}</p>
                                                        <a type="button" class="btn btn-sm btn-default" href="/export/form5?action=export&selectedform={{$selectedform}}&syid={{$schoolyear}}&sectionid={{$section->sectionid}}&levelid={{$gradelevel->gradelevelid}}&exporttype=pdf" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a>
                                                        <a type="button" class="btn btn-sm btn-default p-1" href="/export/form5?action=export&selectedform={{$selectedform}}&syid={{$schoolyear}}&sectionid={{$section->sectionid}}&levelid={{$gradelevel->gradelevelid}}&exporttype=excel"><i class="fa fa-file-excel"></i> EXCEL</a>

                                            </div>
                                            @else
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div> --}}
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script>
        $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        });
        // $('a').on('click', function (){
        //     $(this).closest('form').submit();
        // });
    </script>
@endsection
