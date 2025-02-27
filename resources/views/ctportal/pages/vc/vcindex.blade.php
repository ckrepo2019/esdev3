
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')

      <section class="content">
          <div class="row ">
            @if(count($classrooms) == 0)
            <div class="card col-md-6 container-fluid bg-primary">
                  <div class="card-body text-center ">
                        No schedules yet
                  </div>
            </div>
            @else
                @foreach($classrooms as $classroom)
                    <div class="col-lg-3 col-6">
                    <!-- small box -->
                        <div class="small-box ">
                            <div class="inner bg-primary ">
                            <h5>{{$classroom->classroomname}}</h5>
        
                            <p>{{$classroom->countstud}} Students</p>
                            </div>
                            <div class="icon">
                            <i class="ion ion-bag"></i>
                            </div>
                            <a href="/college/teacher/vc/visit?classroomid={{Crypt::encrypt($classroom->id)}}" class="small-box-footer bg-light">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
      </section>
@endsection

@section('footerscript')

@endsection

