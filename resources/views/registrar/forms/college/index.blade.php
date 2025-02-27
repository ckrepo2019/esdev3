
@extends('registrar.layouts.app')
@section('content')
    <section class="content-header">
        <div class="col-12">
            <h4>College</h4>
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">School Forms</a></li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    @php
         
         $rand = array('B54647', 'B68C4C', 'E0D29D', '54B0B9', '2F977A');
         $studentmasterlistcolor = $rand[rand(0,4)];
         $form1color = $rand[rand(0,4)];

    @endphp
    <div class="row">
        <div class="col-md-4 ">
            <div class="small-box bg-info">
                <div class="inner">
                <h3>SPR</h3>

                <p>Student Permanent Record</p>
                </div>
                <div class="icon">
                <i class="fa fa-file"></i>
                </div>
                <a href="/schoolforms/college/permanentrecordindex" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- fullCalendar 2.2.5 -->
@endsection
