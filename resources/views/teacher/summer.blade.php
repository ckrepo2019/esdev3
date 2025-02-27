@extends('teacher.layouts.app')
@section('content')
<style>
    .color-palette                  { display: block; height: 35px; line-height: 35px; text-align: left; padding-left: .75rem; }
    .color-palette.disabled         { text-align: center; padding-right: 0; display: block; }
    .color-palette-set              { margin-bottom: 15px; }
    .color-palette.disabled span    { display: block; text-align: left; padding-left: .75rem; }
    .color-palette-box h4           { position: absolute; left: 1.25rem; margin-top: .75rem; color: rgba(255, 255, 255, 0.8); font-size: 12px; display: block; z-index: 7; }
</style>

<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="active breadcrumb-item">Summer</li>
            <li class="breadcrumb-item"><a href="/summergrades/dashboard">Subjects</a></li>
            <li class="active breadcrumb-item" aria-current="page">Grades</li>
        </ol>
    </nav>
</div>
@if(isset($message))

    <div class="alert alert-warning alert-dismissible col-md-12">
        <h5><i class="icon fas fa-exclamation-triangle"></i> {{$message}}</h5>
    </div>
@endif
@if(isset($subjects))
    @if(count($subjects)==0)
        <div class="alert alert-info alert-dismissible col-md-12">
            <h5><i class="icon fas fa-info"></i> Alert!</h5>
            <li>No students enrolled</li>
        </div>
    @endif
    <div class="row">
                    @if(count($subjects)!=0)
                        @foreach ($subjects as $subject)      
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-info">
                                      <div class="inner">
                                        <h3>{{$subject->subjects->subjcode}}</h3>
                        
                                        <p>{{$subject->subjects->subjtitle}}<br/>{{$subject->levelname}}</p>
                                        
                                      </div>
                                      <div class="icon">
                                        <i class="ion ion-bag"></i>
                                      </div>                      
                                      <form action="/summergrades/showstudents" method="get" class="small-box-footer">
                                          @csrf
                                          <input type="hidden" name="subjid" value="{{$subject->subjects->id}}"/>
                                          <input type="hidden" name="levelid" value="{{$subject->levelid}}"/>
                                        <button type="submit" class="btn btn-sm btn-block">More info <i class="fas fa-arrow-circle-right"></i></button>
                                      </form>
                                    </div>
                                </div>
                        @endforeach
                    @endif
    </div>
@endif
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script>
    var $ = jQuery;
    
</script>
@endsection