
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
                    <li class="breadcrumb-item"><a href="/reports/{{$academicprogram}}">{{$selectedform}}</a></li>
                    <li class="breadcrumb-item active">Select School Year</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        @foreach ($schoolyear as $sy)
            <div class="col-lg-3 col-6">
                <!-- small card -->
                <form action="/reports/selectSection"  method="GET">
                    @if($sy->isactive == '1')
                    <div class="small-box bg-success">
                    @else
                    <div class="small-box bg-warning">
                    @endif
                        <div class="inner">
                            <h3>{{$sy->sydesc}}</h3>
                            <input type="hidden" value="{{$sy->id}}" name="syid"/>
                            <input type="hidden" value="{{$selectedform}}" name="selectedform"/>
                            <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
                        </div>
                        <div class="icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <a class="small-box-footer">
                        <button type="submit" class="btn btn-block">
                            Select <i class="fas fa-arrow-circle-right"></i>
                        </button>
                        </a>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- fullCalendar 2.2.5 -->
@endsection
