@extends('teacher.layouts.app')

@section('content')

    <style>
        .ribbon-wrapper .ribbon {
            box-shadow: 0 0 3px rgb(0 0 0 / 30%);
            font-size: 0.7rem;
            line-height: 100%;
            padding: 0.375rem 0;
            position: relative;
            right: -1px;
            text-align: center;
            text-shadow: 0 -1px 0 rgb(0 0 0 / 40%);
            text-transform: uppercase;
            top: 2px;
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
            width: 100px;
        }
    </style>
    
    <section class="content-header">
        <div class="container-fluid">
              <div class="row mb-2">
                    <div class="col-sm-6">
                          <h1>Subjects</h1>
                    </div>
                    <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/grades/index">Sections</a></li>
                        <li class=" active breadcrumb-item"  aria-current="page">Subjects</li>
                    </ol>
                    </div>
              </div>
        </div>
    </section>
    
    @if(isset($subjects))
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card shadow">
                    <div class="card-body bg-warning">
                        <h5 class="card-title"><strong>{{$subjects[0]->sectionname}}</strong></h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-deck">
            @foreach($subjects as $subject)
                <a href="/subjects/{{$subject->subjectid}}/{{$subject->syid}}/{{$subject->glevelid}}/{{$subject->sectionid}}/{{$selectedsemester}}">
                    <div class="card mb-3 shadow" style="border: 2px solid orange;">
                        <div class="card-body">
                            @if($subject->with_pending)
                                <div class="ribbon-wrapper">
                                    <div class="ribbon bg-warning">
                                    With <br>Pending
                                    </div>
                                </div>
                            @endif
                            <h5 class="card-title text-muted"><strong>{{$subject->subjectname}}</strong></h5>
                            <p class="card-text"><small class="text-muted">Select</small></p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
    @if(isset($message))
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> {{$message}}</h5>
        </div>
    @endif

@endsection