@extends('studentPortal.layouts.app2')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-image nav-icon"></i> CLASS SCHEDULE</h4>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>
    @if(Session::get('enrollmentstatus'))

        @if(Session::get('studentInfo')->acadprogid == 6)
            <section class="">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class=" font-sm table table-borderless" style="min-width: 900px">
                                <thead>
                                      <tr>
                                         
                                            <th rowspan="2" class="border-bottom p-0 align-middle text-center" width="10%">CODE</th>
                                            <th rowspan="2" class="border-bottom p-0 align-middle" width="24%">DESCRITION</th>
                                            <th colspan="3" class="text-center p-0 align-middle" width="12%">UNIT</th>
                                            <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="39%">SCHEDULE / ROOM</th>
                                            <th  rowspan="2" class="border-bottom p-0 align-middle text-center" width="22%">FACULTY</th>
                                      </tr>
                                      <tr>
                                            <th  class="border-bottom p-0 align-middle text-center">Lec</th>
                                            <th class="border-bottom p-0 align-middle text-center">Lab</th>
                                            <th class="border-bottom p-0 align-middle text-center">Credit</th>
                                      </tr>
                                </thead>
                                <tbody>
                                      @foreach($sampleScheds as $itemclass)
                                            <tr style="font-size:15px !important">
                                                
                                                  <td class="text-center" >{{$itemclass[0]->subjCode}}</td>
                                                  <td>{{$itemclass[0]->subjDesc}}</td>
                                                  <td class="text-center ">{{number_format($itemclass[0]->lecunits,1)}}</td>
                                                  <td class="text-center ">{{number_format($itemclass[0]->labunits,1)}}</td>
                                                  <td class="text-center ">{{number_format($itemclass[0]->lecunits + $itemclass[0]->labunits,1) }}</td>
                                                  <td class="pl-4" >
                                                        @foreach($itemclass as $item)
                                                                    @if($item->scheddetialclass == 1)
                                                                          Lec.
                                                                    @elseif($item->scheddetialclass == 2)
                                                                          Lab.
                                                                    @endif
                          
                                                                    {{$item->description}}   
                          
                                                                    @if($item->stime!=null)
                                                                          {{\Carbon\Carbon::create($item->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($item->etime)->isoFormat('hh:mm A')}}  
                                                                    @endif
                          
                                                              
                                                                    {{$item->roomname}}   
                                                                  
                                                            
                                                                    <br>
                                                             
                                                        @endforeach
                                                  </td>
                                                  <td>
                                                        @if(isset($item->lastname))
                                                          {{$item->lastname}}, {{substr($item->firstname,0,1)}}.
                                                        @endif
                                                  </td>
                                                  
                                            </tr>
                                      
                                      @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </section>

        @else
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
                                                    <div class="col-md-12 text-muted subject"><i class="fa fa-book mr-2"> </i>{{$sampleSched->subjdesc}}</div>
                                                    <div class="col-md-12 text-primary subject font-weight-bold"><i class="fa fa-info-circle mr-2"></i>@if($sampleSched->schedclass==null)Regular Class @else{{$sampleSched->schedclass}}@endif</div>
                                                    <div class="col-md-12 text-info"><i class="fa fa-user mr-2"></i></i>{{$sampleSched->firstname}} {{$sampleSched->lastname}}
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
            </section>

        @endif
    @else
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mt-2">
                            <div class="card-body">
                                <p>You are not yet enrolled.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif             
@endsection
