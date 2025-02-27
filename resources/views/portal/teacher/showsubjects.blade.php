@extends('teacher.layouts.app')

@section('content')
    <div>
        <nav class="" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/grades">Sections</a></li>
                <li class=" active breadcrumb-item"  aria-current="page">Subjects</li>
            </ol>
        </nav>
    </div>
    @if(isset($subjects))
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card ">
                <div class="card-body">
                    <h5 class="card-title"><strong>{{$subjects[0]->sectionname}}</strong></h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-deck">
        @foreach($subjects as $subject)
            <a href="/subjects/{{$subject->subjectid}}/{{$subject->syid}}/{{$subject->glevelid}}/{{$subject->sectionid}}">
                <div class="card mb-3" style="border: 2px solid orange;">
                    {{-- <img class="card-img-top" src="..." alt="Card image cap"> --}}
                    
                    <div class="card-body">
                        <h5 class="card-title text-muted"><strong>{{$subject->subjdesc}}</strong></h5>
                        {{-- <p class="card-text"></p> --}}
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
        {{-- Possible reasons:
        <ul>
            <li>No students enrolled</li>
            <li>No assigned schedule</li>
        </ul> --}}
    </div>
    @endif
    <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('assets/scripts/jquery-3.3.1.min.js')}}"></script> --}}
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
@endsection