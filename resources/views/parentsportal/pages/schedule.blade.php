@extends('parentsportal.layouts.app2')

@section('content')
    @if(Session::get('enrollmentstatus'))
    <section class="content-header">
        <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-image nav-icon"></i> CLASS SCHEDULE</h4>
            </div>
            <div class="col-sm-6">
            <!-- <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ol> -->
            </div>
        </div>
        </div>
        </section>
        <section class="">
            <div class="row p-1">
                @foreach ($scheds as $key=>$sched)
                    <div class="col-md-12">
                        <div class="main-card mb-1 card collapsed-card ">
                            <div class="card-header text-light {{$sched->color}} rounded-0 ">
                                {{$sched->description}}
                                <div class="card-tools">
                                    <button type="button"
                                            class="btn text-white btn-sm"
                                            data-card-widget="collapse"
                                            data-toggle="tooltip"
                                            title="Collapse">
                                    <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            
                            </div>
                            <div class="card-body">
                                <div class="row ">
                                    @foreach($sampleScheds as $sampleSched)
                                        @if($sched->description== $sampleSched->description)
                                            <div class="col-md-6 mb-3">
                                                <div class="col-md-12 text-muted subject"><i class="fa fa-book mr-2"> </i>{{$sampleSched->subjdesc}}
                                                    </div>
                                                <div class="col-md-12 text-primary subject font-weight-bold"><i class="fa fa-info-circle mr-2"> </i>@if($sampleSched->schedclass==null)Regular Class @else{{$sampleSched->schedclass}}@endif}</div>
                                                <div class="col-md-12 text-info"><i class="fa fa-user mr-2"></i></i>@if(Session::get('studentInfo')->acadprogid == '5'){{$sampleSched->firstname}} {{$sampleSched->lastname}}@else{{$sampleSched->firstname}} {{$sampleSched->lastname}}@endif
                                                </div>
                                                <div class="col-md-12 text-success"><i class="fa fa-clock mr-2"> </i>{{\Carbon\Carbon::create($sampleSched->stime)->isoFormat('hh:mm a')}} - {{\Carbon\Carbon::create($sampleSched->etime)->isoFormat('hh:mm a')}}</div>
                                                <div class="col-md-12 text-danger"><i class="fa fa-door-open mr-2"> {{$sampleSched->roomname}}</i></div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        <section>
    @else
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <p>Your student is not yet enrolled for {{App\Models\Principal\SPP_SchoolYear::getActiveSchoolYear()->sydesc}} School Year.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
