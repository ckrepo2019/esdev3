
@extends('registrar.layouts.app')
@section('content')

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })
</script>
<section class="content-header">
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-12">
      <!-- <h1>Student Information</h1> -->
      <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
        <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
        <b>ADDING / DROPPING</b></h4>
    </div>
    <div class="col-sm-12 pt-0">
      <ol class="breadcrumb" style="font-size: 13px;">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><a href="/college/adddrop/index">ADDING / DROPPING</a></li>
        <li class="breadcrumb-item active">{{$collegename}}</li>
        <li class="breadcrumb-item active">{{$coursename}}</li>
      </ol>
    </div>
  </div>
</div>
</section>
<div class="row mb-2">
    <div class="col-md-4">
        <input class="filter form-control" placeholder="Search student" />
    </div>
</div>
<div class="row d-flex align-items-stretch text-uppercase">
    @foreach($students as $student)
        <div class="card col-md-4" style="border: none !important;box-shadow: none !important;" data-string="{{$student->lastname}} {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}<">
            <a href="/college/adddrop/viewschedule?courseid={{$courseid}}&studid={{$student->id}}" class="info-box" style="color: inherit;">
                <span class="info-box-icon bg-warning" style="">
                    @if(strtolower($student->gender) == 'male')
                    <i class="fa fa-male"></i>
                    @elseif(strtolower($student->gender) == 'female')
                    <i class="fa fa-female"></i>
                    @else
                    <i class="fa fa-user"></i>
                    @endif
                </span>

                <div class="info-box-content" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
                <span class="info-box-text"><strong>{{$student->lastname}}</strong>, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</span>
                <span class="info-box-number">
                    <small>Year Level : {{$student->yearDesc}}</small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </a>
        </div>
    @endforeach
</div>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- fullCalendar 2.2.5 -->
@endsection
