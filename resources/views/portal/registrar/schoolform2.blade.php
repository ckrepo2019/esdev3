
@extends('registrar.layouts.app')
@section('content')
    <div class="row">
        <!-- /.card-header -->
        <div id="accordion" class="col-12">
            @foreach($grade_levels as $gradelevel)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne{{$gradelevel['id']}}">
                            {{$gradelevel['levelname']}}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne{{$gradelevel['id']}}" class="panel-collapse collapse in">
                        <div class="card-body">
                            <div class="row">
                                @foreach ($sections as $section)
                                    @if($section->gradelevelid == $gradelevel['id'])
                                        <div class="col-md-3">
                                            <a href="/preview_school_form_2/preview/{{$gradelevel['id']}}/{{$section->sectionid}}">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-info elevation-1">
                                                        
                                                    </span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">{{$section->sectionname}}</span>
                                                        <span class="info-box-number">
                                                            @php
                                                                $count = count($students->where('sectionid',$section->sectionid));
                                                            @endphp
                                                            {{$count}}
                                                            <small>Students</small>
                                                        </span>
                                                        <sub>{{$section->teacher_lastname}}, {{$section->teacher_firstname[0]}}. {{$section->teacher_middlename[0]}}</sub>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                            </a>
                                            <!-- /.info-box -->
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
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
@endsection
