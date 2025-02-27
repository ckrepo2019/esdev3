@extends('teacher.layouts.app')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Summaries</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Summaries</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="row">
    <div class="col-md-4">
        <a href="/summaryattendancepersubject/dashboard" >
            <div class="col-12">
                <div class="small-box bg-warning teacherd">
                    <div class="inner">
                        <h5><i style="color: #ffc107" class="fa fa-list"></i> <b>Attendance</b></h5>
                        <ul>
                            <li class="nav-item">
                                By Subject
                            </li>
                            <li class="nav-item">
                                By Date
                            </li>
                        </ul>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/summaryofloads/dashboard" >
            <div class="col-12">
                <div class="small-box bg-warning teacherd">
                    <div class="inner">
                        <h5><i style="color: #ffc107" class="fa fa-list"></i> <b>Loads</b></h5>
                        <ul>
                            <li class="nav-item">
                                &nbsp;
                            </li>
                            <li class="nav-item">
                                &nbsp;
                            </li>
                        </ul>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
@endsection