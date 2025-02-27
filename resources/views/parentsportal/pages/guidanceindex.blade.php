@extends('parentsportal.layouts.app2')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Guidance</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Enrollment</li>
                </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content pt-0">
        <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 form-group mb-0">
                                        <label for="">School Year</label>
                                    </div>
                                    <div class="col-md-2 form-group mb-0" id="filter_sem_holder" hidden>
                                        <label for="">Semester</label>
                                        <select name="" id="filter_sem" class="form-control select2">
                                        </select>
                                    </div>
                                    <div class="col-md-10" hidden>
                                        <button class="btn btn-primary btn-sm float-right" id="view_fees_modal">View Fees</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row with_enrollment" >
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h3 class="card-title">
                                    <i class="fas fa-info"></i>
                                    Guidance Information
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">
                                                School Year 
                                            </div>
                                            <div class="col-md-8">
                                                : <b><span class="enrollment_schoolyear"></span></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                Enrollment Status 
                                            </div>
                                            <div class="col-md-8">
                                                : <b><span id="enrollment_status"></span></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                Grade Level
                                            </div>
                                            <div class="col-md-8">
                                                : <b><span id="enrollment_gradelevel"></span></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                Section
                                            </div>
                                            <div class="col-md-8">
                                                : <b><span id="enrollment_section"></span></b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    
                @foreach($records as $item)
                    <div class="timeline" >
                        <div class="time-label">
                            <span class="bg-info">{{date('d M. Y', strtotime($item->createddatetime))}} </span>
                        </div>
                        <div>
                            <i class="fas fa-share bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-exclamation-triangle"></i> {{$item->card}}</span>
                                <h3 class="timeline-header"><a href="#">Guidance Record</a> </h3>
                                
                                <div class="timeline-body @if($item->card == "Red") bg-danger @else  bg-success  @endif " >
                                    <label>Description:</label>
                                        <small>Your Presence has been requested in the Guidance Office</small>
                                    <br/>
                                    <label>Message:</label>
                                    <small>{{$item->message}}</small>
                                    <br/>
                                    <label>Date:</label>
                                    <small>{{date('d M. Y', strtotime($item->scheddate))}} </small>
                                    <br/>
                                    <label>Status:</label>
                                    @if($item->resolve == '1')
                                        <small>Resolve &nbsp; &nbsp; <i class="fas fa-check" style="color: #1eff00;"></i>  </small>
                                    @else
                                        <small>Unresolve &nbsp; &nbsp; <i class="fas fa-times" style="color: #ff0000;"></i>  </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            
            
            
            
            </div>
    </section>

    </div>

@endsection


