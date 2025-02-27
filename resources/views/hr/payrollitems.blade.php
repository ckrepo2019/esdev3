

@extends('hr.layouts.app')
@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h3 class="page-title">Payroll Items</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Payroll Items</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Holiday</a>
            </div> --}}
        </div>
    </div>
</div>
<div class="row">
    @if(session()->has('messageAdded'))
        <div class="alert alert-success alert-dismissible col-12">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('messageAdded') }}
        </div>
    @endif
    @if(session()->has('messageExists'))
        <div class="alert alert-danger alert-dismissible col-12">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Alert!</h5>
            {{ session()->get('messageExists') }}
        </div>
    @endif
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="col-8 m-0">Designations</h3>
                <div class="col-4 float-right ml-auto">
                    {{-- <a href="#" class="btn btn-block m-0" data-toggle="modal" data-target="#add_position"><i class="fa fa-plus"></i> Add Position</a> --}}
                    <div class="modal fade" id="add_position" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <form action="/payrollitems/addposition" method="get">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Add Position</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label>Position</label>
                                        <input type="text" name="position_name" class="form-control" required/>
                                        <br>
                                        <div class="row">
                                        <div class="col-6">
                                            <label>Hourly Rate</label>
                                            <input type="number" name="hourly_rate" class="form-control" placeholder="Hourly Rate" required/>
                                        </div>
                                        <div class="col-6">
                                            <label>Daily Rate</label>
                                            <input type="number" name="daily_rate" class="form-control" placeholder="Daily Rate" required/>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-Success">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Hourly Rate</th>
                            <th>Daily Rate</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($job_positions_array)!=0)
                            @foreach ($job_positions_array as $position)
                                <tr>
                                    <td>{{$position->description}}</td>
                                    <td>{{$position->hourly_rate}}</td>
                                    <td>{{$position->daily_rate}}</td>
                                    <td width="5%;">
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit_rate{{$position->id}}">Edit</button>
                                        {{-- <button type="button" class="btn btn-sm btn-warning">Delete</button> --}}
                                        <div class="modal fade" id="edit_rate{{$position->id}}" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog modal-md">
                                                <form action="/payrollitems/editrate" method="get" name="saveedit">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Add Position</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label>Position</label>
                                                            <input type="text" name="position_name" value="{{$position->description}}" class="form-control" readonly/>
                                                            <input  name="id" value="{{$position->id}}" class="form-control" hidden/>
                                                            <br>
                                                            <div class="row">
                                                            <div class="col-6">
                                                                <label>Hourly Rate</label>
                                                                <input type="number" name="hourly_rate" class="form-control" placeholder="Hourly Rate" value="{{$position->hourly_rate}}"/>
                                                            </div>
                                                            <div class="col-6">
                                                                <label>Daily Rate</label>
                                                                <input type="number" name="daily_rate" class="form-control" placeholder="Daily Rate" value="{{$position->daily_rate}}"/>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-Success btnsaveedit" id="{{$position->id}}">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
    
        @if(session()->has('messageAdded'))
        <div class="alert alert-success alert-dismissible col-12">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('messageAdded') }}
        </div>
        @endif
        @if(session()->has('messageDeleted'))
        <div class="alert alert-success alert-dismissible col-12">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-trash"></i> Alert!</h5>
            {{ session()->get('messageDeleted') }}
        </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h3 class="col-8 m-0">Deductions</h3>
                <div class="col-4 float-right ml-auto">
                    <a href="#" class="btn btn-block m-0" data-toggle="modal" data-target="#add_deduction"><i class="fa fa-plus"></i> Add Deduction</a>
                    <div class="modal fade" id="add_deduction" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <form action="/payrollitems/adddeduction" method="get">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Add Deduction</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label>Description</label>
                                        <input type="text" name="deduction" class="form-control" placeholder="e.g. Pag-Ibig" required/>
                                        <br>
                                        <label>Amount</label>
                                        <input type="number" name="amount" class="form-control" placeholder="Amount" required/>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-Success">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($deductions)!=0)
                            @foreach($deductions as $deduction)
                                <tr>
                                    <td>{{$deduction->description}}</td>
                                    <td>&#8369; {{$deduction->amount}}</td>
                                    <td style="width:20%;">
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit{{$deduction->id}}"><i class="fa fa-edit"></i>&nbsp;Edit</button>
                                        <div class="modal fade" id="edit{{$deduction->id}}" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog modal-md">
                                                <form action="/payrollitems/editdeduction" method="get">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Deduction</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label>Description</label>
                                                            <input type="text" name="deduction" class="form-control" value="{{$deduction->description}}" placeholder="e.g. Pag-Ibig" required/>
                                                            <br>
                                                            <label>Amount</label>
                                                            <input type="number" name="amount" class="form-control" value="{{$deduction->amount}}" placeholder="Amount" required/>
                                                            <input type="hidden" name="deductionid" class="form-control" value="{{$deduction->id}}" placeholder="Amount" required/>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{$deduction->id}}"><i class="fa fa-trash"></i>&nbsp;Delete</button>
                                        <div class="modal fade" id="delete{{$deduction->id}}" style="display: none;" aria-hidden="true">
                                            <div class="modal-dialog modal-md">
                                                <form action="/payrollitems/deletededuction" method="get">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delete Deduction</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong class="text-danger">Are you sure you want to delete this deduction?</strong>
                                                            <br>
                                                            <br>
                                                            <h4><strong>Description: <span class="text-danger">{{$deduction->description}}</span></strong></h4>
                                                            <h4><strong>Amount: <span class="text-danger">{{$deduction->amount}}</span></strong></h4>
                                                            <input type="hidden" name="deductionid" class="form-control" value="{{$deduction->id}}" placeholder="Amount" required/>
                                                            <input type="hidden" name="deduction" class="form-control" value="{{$deduction->description}}" placeholder="Amount" required/>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-Success">Delete</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
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
   $(document).ready(function(){
       $('.btnsaveedit').on('click', function(){
        //    console.log('asd');
        $(this).closest('form[name=saveedit]').submit();
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
   })
  </script>
@endsection

