
@extends('registrar.layouts.app')
@section('content')
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
                    <li class="breadcrumb-item active">School Forms</a></li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12 mb-2">
            <form action="/reports/selectSy"  method="GET">
                <div class="info-box bg-warning m-0">
                    <span class="info-box-icon"><i class="fa fa-file"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"></span>
                        <span class="info-box-number">Student Masterlist</span>
                    </div>
                    <input type="hidden" value="Student Masterlist" name="selectedform"/>
                    <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                <!-- /.info-box-content -->
                </div>
                <button type="submit" class="btn btn-block btn-success m-0">Select</button>
            </form>
            <!-- /.info-box -->
        </div>
        @if($academicprogram == 'elementary' || $academicprogram == 'juniorhighschool')
            <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET">
                    <div class="info-box bg-warning m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 5</span>
                        </div>
                        <input type="hidden" value="School Form 5" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <!-- /.info-box-content -->
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div>
        @endif
        
        @if($academicprogram == 'seniorhighschool')
            <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET">
                    <div class="info-box bg-warning m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 5A</span>
                        </div>
                        <input type="hidden" value="School Form 5A" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div>
            <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET">
                    <div class="info-box bg-warning m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 5B</span>
                        </div>
                        <input type="hidden" value="School Form 5B" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <!-- /.info-box-content -->
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div>
        @endif
        @if($academicprogram == 'elementary' || $academicprogram == 'juniorhighschool' || $academicprogram == 'seniorhighschool')
            <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET">
                    <div class="info-box bg-warning m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 6</span>
                        </div>
                        <input type="hidden" value="School Form 6" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <!-- /.info-box-content -->
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div>
        @endif
        @if($academicprogram == 'elementary' || $academicprogram == 'juniorhighschool')
            <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET" class="m-0">
                    <div class="info-box bg-warning m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 10</span>
                        </div>
                        <input type="hidden" value="School Form 10" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <!-- /.info-box-content -->
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div>
        @endif
        @if($academicprogram == 'seniorhighschool')
            <div class="col-md-3 col-sm-6 col-12 mb-2">
                <form action="/reports/selectSy"  method="GET" class="m-0">
                    <div class="info-box bg-warning m-0">
                        <span class="info-box-icon"><i class="fa fa-file"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"></span>
                            <span class="info-box-number">School Form 10</span>
                        </div>
                        <input type="hidden" value="School Form 10" name="selectedform"/>
                        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                    <!-- /.info-box-content -->
                    </div>
                    <button type="submit" class="btn btn-block btn-success m-0">Select</button>
                </form>
                <!-- /.info-box -->
            </div>
        @endif
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- fullCalendar 2.2.5 -->
@endsection
