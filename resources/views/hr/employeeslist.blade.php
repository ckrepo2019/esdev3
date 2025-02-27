

@extends('hr.layouts.app')
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
        <div class="col-sm-6">
          <!-- <h1>Employees</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
          <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
          EMPLOYEEES </h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Employees</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  
  <div class="row mb-3 text-uppercase" >
      <div class="col-12">
        <a href="/addnewemployee/{{Crypt::encrypt('dashboard')}}" class="float-right"><i class="fa fa-plus"></i> Add New Employee</a>
      </div>
  </div>
  <div class="row mb-2">
    <input class="filter form-control col-md-4" placeholder="Search employee" />
  </div>
    <div class="row d-flex align-items-stretch text-uppercase">
        @foreach($employees as $employee)
            <div class="card col-md-4 text-center " style="border: none !important;box-shadow: none !important;" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}} {{$employee->utype}}<">
                <div class="card-body p-0" style="border: 1px solid#ddd;background: #e8e8e8;">
                    <small>{{$employee->utype}}</small>
                    <p class="card-text text-center m-0">
                    <div class="widget-user-image">
            
                        @php
                                $number = rand(1,3);
                                if(strtoupper($employee->gender) == 'FEMALE'){
                                    $avatar = 'avatar/T(F) '.$number.'.png';
                                }
                                else{
                                    $avatar = 'avatar/T(M) '.$number.'.png';
                                }
                            @endphp
            
                        <img class="img-circle elevation-2" src="{{asset($employee->picurl)}}" 
                            onerror="this.onerror = null, this.src='{{asset($avatar)}}'"
                            alt="User Avatar" style="width: 40% !important"
                            >
                  
                            <a href="/hr/employeeprofile?employeeid={{$employee->id}}">
                                <h6>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}</h6>
                            </a>
                    </div>
                    </p>
                </div>
            </div>
        @endforeach
    </div>

{{-- @foreach($employees as $employee)
<div class="col-md-4 col-12 col-lg-4 col-xl-4 ">
    <a href="/hr/employeeprofile?employeeid={{$employee->id}}">
    <div class="card card-widget widget-user">
        @if($employee->isactive == '1')
            <div class="widget-user-header bg-success">
                <h5 class="widget-user-desc">{{$employee->utype}}</h5>
            </div>
        @elseif($employee->isactive == '0')
            <div class="widget-user-header bg-secondary">
                <h5 class="widget-user-desc">{{$employee->utype}}</h5>
            </div>
        @endif
        <div class="widget-user-image">

            @php
                    $number = rand(1,3);
                    if(strtoupper($employee->gender) == 'FEMALE'){
                        $avatar = 'avatar/T(F) '.$number.'.png';
                    }
                    else{
                        $avatar = 'avatar/T(M) '.$number.'.png';
                    }
                @endphp

            <img class="img-circle elevation-2" src="{{asset($employee->picurl)}}" 
                onerror="this.onerror = null, this.src='{{asset($avatar)}}'"
                alt="User Avatar" style="width: 65% !important"
                >
      
        </div>
        <div class="card-footer" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">
            <small style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}</small>
        </div>
    </div>
    </a>
</div>
@endforeach --}}
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
@endsection
