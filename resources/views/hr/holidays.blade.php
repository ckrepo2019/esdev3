

@extends('hr.layouts.app')
@section('content')
<style>
    .dot {
        height: 15px;
        width: 15px;
        border-radius: 50%;
        display: inline-block;
    }
</style>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <!-- <h3 class="page-title">Holidays</h3> -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            HOLIDAYS</h4>
            <ul class="breadcrumb col-md-10 float-left">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Holidays</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Holiday</a>
            </div> --}}
        </div>
    </div>
</div>
@if(session()->has('messageDelete'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageDelete') }}
    </div>
@endif
<div class="row">
    <div class="col-5 col-sm-2">
        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link" id="vert-tabs-holidays-tab" data-toggle="pill" href="#vert-tabs-holidays" role="tab" aria-controls="vert-tabs-holidays" aria-selected="false">Holidays</a>
            <a class="nav-link active" id="vert-tabs-holidayrates-tab" data-toggle="pill" href="#vert-tabs-holidayrates" role="tab" aria-controls="vert-tabs-holidayrates" aria-selected="true">Holiday Rates</a>
            <a class="nav-link" data-toggle="modal" href="#addholidayrates" ><i class="fa fa-plus"></i> Holiday Type</a>
            <div id="addholidayrates" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title employeename">Add Holiday Type</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="/addholidaytypes" method="get">
                                <label>Title</label>
                                <input type="text" name="typename" class="form-control form-control-sm text-uppercase" required/>
                                <br>
                                <div class="input-group ">
                                    <div class="input-group-append">
                                    <span class="input-group-text p-1"><small>NO WORK : Percentage (%)</small></span>
                                    </div>
                                    <input type="number" name="newnowork" class="form-control form-control-sm" placeholder="" required>
                                </div>
                                <br>
                                <div class="input-group ">
                                    <div class="input-group-append">
                                    <span class="input-group-text p-1"><small>WORK ON : Percentage (%)</small></span>
                                    </div>
                                    <input type="number" name="newworkon" class="form-control form-control-sm" placeholder="" required>
                                </div>
                                <br>
                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn float-right">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-7 col-sm-10">
        <div class="tab-content" id="vert-tabs-tabContent">
            <div class="tab-pane text-left fade " id="vert-tabs-holidays" role="tabpanel" aria-labelledby="vert-tabs-holidays-tab">
                <div class="row">
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Holiday Date</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($holidays as $holiday)
                                @if($holiday->noclass == 1)
                                <tr class="bg-warning">
                                @else
                                <tr>
                                @endif
                                    <td>{{$holiday->description}}</td>
                                    <td>{{$holiday->datefrom}} to {{$holiday->dateto}}</td>
                                    <td>{{$holiday->typename}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                <strong>Legends</strong>
                            </div>
                            <div class="card-body">
                                <span class="dot bg-warning"></span>    No Class
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show active" id="vert-tabs-holidayrates" role="tabpanel" aria-labelledby="vert-tabs-holidayrates-tab">
                <form action="/updateholidayrates" method="get"  class="p-0 m-0">
                    @if(count($holidaytypes) == 0)
                        <div class="row">
                            <div class="col-sm-12">
                            <!-- checkbox -->
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                        <input name="fixedholidaydescription[]" style="font-size:25px"  value="Regular holiday" class="form-control form-control-sm" readonly/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">No work</div>
                                    <div class="col-md-5">
                                        <div class="input-group ">
                                            <div class="input-group-append">
                                            <span class="input-group-text p-1"><small>Percentage (%)</small></span>
                                            </div>
                                            <input type="number" name="fixedratepercentagenowork[]" class="form-control form-control-sm" placeholder="" disabled required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3">Work on</div>
                                    <div class="col-md-5">
                                        <div class="input-group ">
                                            <div class="input-group-append">
                                            <span class="input-group-text p-1"><small>Percentage (%)</small></span>
                                            </div>
                                            <input type="number" name="fixedratepercentageworkon[]" class="form-control form-control-sm" placeholder="" disabled required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-sm-4">
                                            <input name="fixedholidaydescription[]" style="font-size:25px"  value="Special Non-working holiday" class="form-control form-control-sm" readonly/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><h6>No work</h6></div>
                                    <div class="col-md-5">
                                        <div class="input-group ">
                                            <div class="input-group-append">
                                            <span class="input-group-text p-1"><small>Percentage (%)</small></span>
                                            </div>
                                            <input type="number" name="fixedratepercentagenowork[]" class="form-control form-control-sm" placeholder="" disabled required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3">Work on</div>
                                    <div class="col-md-5">
                                        <div class="input-group ">
                                            <div class="input-group-append">
                                            <span class="input-group-text p-1"><small>Percentage (%)</small></span>
                                            </div>
                                            <input type="number" name="fixedratepercentageworkon[]" class="form-control form-control-sm" placeholder="" disabled required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 fixedbuttoncontainer mt-3">

                                <button type="button" class="btn btn-warning float-right editfixedrates">Edit</button>
                            </div>
                        </div>
                    @else
                            {{-- <div class="row"> --}}
                        @foreach($holidaytypes as $holidaytype)
                                <div class="col-sm-12">
                                <!-- checkbox -->
                                    <div class="row mb-2">
                                        {{-- <div class="col-sm-1">
                                            
                                        </div> --}}
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-sm btn-danger deletebutton" style="display inline-block;" data-toggle="modal" data-target="#deleteholidaytype"><i class="fa fa-trash-alt"></i></button>
                                            <input type="hidden" name="fixedholidayids[]" style="font-size:20px;background-color: white; border: none;" value="{{$holidaytype->id}}" class="form-control form-control-sm" readonly/>
                                            <input name="fixedholidaydescription[]" style="font-size:20px;background-color: white; border: none;width: 80%; display: inline;" value="{{$holidaytype->typename}}" class="form-control form-control-sm" readonly/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">No work</div>
                                        <div class="col-md-5">
                                            <div class="input-group ">
                                                <div class="input-group-append">
                                                <span class="input-group-text p-1"><small>Percentage (%)</small></span>
                                                </div>
                                                <input type="number" name="fixedratepercentagenowork[]" class="form-control form-control-sm" value="{{$holidaytype->ratepercentagenowork}}" placeholder="" disabled required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3">Work on</div>
                                        <div class="col-md-5">
                                            <div class="input-group ">
                                                <div class="input-group-append">
                                                <span class="input-group-text p-1"><small>Percentage (%)</small></span>
                                                </div>
                                                <input type="number" name="fixedratepercentageworkon[]" class="form-control form-control-sm" value="{{$holidaytype->ratepercentageworkon}}" placeholder="" disabled required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                            <div class="col-md-12 fixedbuttoncontainer mt-3">

                                <button type="button" class="btn btn-warning float-right editfixedrates">Edit</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<div id="deleteholidaytype" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title employeename">Delete Holiday Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/deleteholidaytype" method="get">
                    <label>Title</label>
                    <input type="hidden" name="deletetypeid" class="form-control form-control-sm" readonly/>
                    <input type="text" name="deletetypename" class="form-control form-control-sm" readonly/>
                    <br>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-danger submit-btn float-right">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script>
    $(document).on('click','.editfixedrates', function(){
        $('input[name="fixedholidays[]"]').prop('disabled',false);
        $('input[name="fixedratepercentagenowork[]"]').prop('disabled',false);
        $('input[name="fixedratepercentageworkon[]"]').prop('disabled',false);
        // $('input[name="hourlyratepercentage[]"]').prop('disabled',false);
        $('input[name="fixedholidaydescription[]"]').prop('readonly',false);
        $('.fixedbuttoncontainer').empty();
        $('.fixedbuttoncontainer').append(
            '<button type="submit" class="btn btn-success float-right">Update</button>'
        )
    })
    $(document).on('click','.deletebutton', function(){
        $('input[name=deletetypeid]').val($(this).next().val());
        $('input[name=deletetypename]').val($(this).next().next().val());
    })
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        window.setTimeout(function () {
            $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
  </script>
@endsection

