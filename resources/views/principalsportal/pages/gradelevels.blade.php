@extends('principalsportal.layouts.app')

@section('content')
<div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-diamond icon-gradient bg-strong-bliss"> </i>
                </div>
                <div> AWARDS AND RECOGNITION FOR THE K TO 12 BASIC EDUCATION PROGRAM 
                    <div class="page-title-subheading">Click the link provided for policy guidelines: <a href="https://www.deped.gov.ph/wp-content/uploads/2016/06/DO_s2016_036.pdf">https://www.deped.gov.ph/wp-content/uploads/2016/06/DO_s2016_036.pdf</a>
                        </div>
                </div>
            </div>   
        </div>
    </div>
 


<div class="main-card mb-3 card">
    <div class="card-body">
        <h5 class="card-title">Quarter {{$quarter}} Classroom Awards </h5>
        <div class="row">
            <div class="col-lg-6 col-xl-3">
                <a href="#" class="nav-link p-0 ">
                    <div class="card  widget-content bg-arielle-smile w-100">
                        <div class="widget-content-wrapper text-white text-center">
                            <div class="widget-content-left">
                                <div class="text-white ">
                                    <span>Recognition for Perfect Attendance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-xl-3">
                <a href="/principalAcademicExcellenceAward/{{$quarter}}/" class="nav-link p-0 ">
                    <div class="card widget-content bg-arielle-smile w-100">
                        <div class="widget-content-wrapper text-white text-center">
                            <div class="widget-content-left">
                                <div class="text-white ">
                                    <span>Academic Excellence Award </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
