@extends('registrarportal.layouts.app')



@section('content')


<div class="row">
    <div class="col-lg-5">
        <div class="main-card mb-3 card">
            <div class="card-body"><h5 class="card-title">Approved Students</h5>
                <div class="scroll-area-md">
                    <div class="scrollbar-container ps--active-y ps">
                        <table class="mb-0 table table-striped">
                                <thead>
                                <tr>
                                    <th width="80%">Student Name</th>
                                    <th width="20%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($approved as $value)
                                        <tr>
                                            <td>{{$value->firstname}} {{$value->middlename}} {{$value->lastname}}</td>
                                        <td><button id="{{$value->id}}" type="button" class="btn mr-2 mb-2 btn-sm btn-primary regBut" data-toggle="modal" data-target=".bd-example-modal-sm">Register</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 300px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 77px;"></div></div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="main-card mb-3 card">
            <div class="card-body"><h5 class="card-title">Registered Students</h5>
                <div class="scroll-area-md">
                    <div class="scrollbar-container ps--active-y ps">
                        <table class="mb-0 table table-striped">
                                <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($registered as $value)
                                        <tr>
                                            <td>{{$value->sid}}</td>
                                            <td>{{$value->firstname}} {{$value->middlename}} {{$value->lastname}}</td>
                                            <td>{{$value->levelname}}</td>
                                            <td>{{$value->sectionname}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 300px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 77px;"></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Pregistered Students</div>
                        {{-- <div class="widget-subheading">Students waiting to be preregistered</div> --}}
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-success">{{count($approved)}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content">
            <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left">
                        <div class="widget-heading">Registered Students</div>
                        {{-- <div class="widget-subheading">Approved preregistered students</div> --}}
                    </div>
                    <div class="widget-content-right">
                        <div class="widget-numbers text-success">{{count($registered)}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Student Registration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="">
                        <div class="position-relative form-group">
                            <label for="exampleEmail" class="">Grade Level</label>
                            <select id="gradeLevelOption" class="mb-2 form-control">
                                @foreach ($gradelevel as $item)
                                    <option value="{{$item->id}}">{{$item->levelname}}</option>
                                   
                                @endforeach
                          
                            </select>
                            <label for="exampleEmail" class="">Grade Level</label>
                            <select class="mb-2 form-control">
                                
                               
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Register Student</button>
                </div>
            </div>
        </div>
    </div>



@endsection
