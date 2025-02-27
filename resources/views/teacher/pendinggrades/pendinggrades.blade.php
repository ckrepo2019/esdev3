
@extends('teacher.layouts.app')

@section('content')

<div class="row">

      @foreach ($gradeSchoolPendingGrades as $item)
            <div class="col-lg-3 col-6">
                  <div class="small-box bg-info">
                  <div class="inner">
                        <p>{{$item->levelname}} - {{$item->sectionname}} </p>
                        <p>{{$item->subjdesc}}</p>
                        @if($item->quarter == 1)
                              <p>1st Quarter</p>
                        @elseif($item->quarter == 1)
                              <p>2nd Quarter</p>
                        @elseif($item->quarter == 1)
                               <p>3rd Quarter</p>
                        @elseif($item->quarter == 1)
                              <p>4th Quarter</p>
                        @endif
                  </div>
                  
            <a href="/subjects/{{$item->subjid}}/{{$item->syid}}/{{$item->levelid}}/{{$item->sectionid}}?quarter={{$item->quarter}}" class="bg-warning btn-warning btn btn-sm small-box-footer btn-block"> More info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
            </div>


          
      @endforeach
    
  </div>


@endsection