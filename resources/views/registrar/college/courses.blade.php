
@extends('registrar.layouts.app')
@section('content')

<script src="{{asset('plugins/jquery/jquery-3-3-1.min.js')}}"></script>

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
    <div class="col-sm-6">
      <!-- <h1>Student Information</h1> -->
      <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
        <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
        <b>ADDING / DROPPING</b></h4>
    </div>
    <div class="col-sm-6 pt-0">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><a href="/college/adddrop/index">Adding / Dropping</a></li>
        <li class="breadcrumb-item active">{{$collegename}} COURSES</li>
      </ol>
    </div>
  </div>
</div>
</section>
<div class="row mb-2">
    <div class="col-md-4">
        <input class="filter form-control" placeholder="Search course" />
    </div>
</div>
<div class="row d-flex align-items-stretch text-uppercase">
    @foreach($courses as $course)
        <div class="card col-md-4 text-center " style="border: none !important;box-shadow: none !important;" data-string="{{$course->courseDesc}}<">
            <div class="card-body " style="border: 3px dashed #ddd;background:#ddfcff;">
                {{-- <small></small> --}}
                <p class="card-text text-center m-0">
                    <div class="widget-user-image">
                        <a href="/college/adddrop/viewstudents?courseid={{$course->id}}&coursename={{$course->courseDesc}}&collegename={{$collegename}}">
                            <h3>{{$course->courseabrv}}</h3>
                            <h6>{{$course->courseDesc}}</h6>
                        </a>
                    </div>
                </p>
            </div>
        </div>
    @endforeach
</div>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- fullCalendar 2.2.5 -->
@endsection
