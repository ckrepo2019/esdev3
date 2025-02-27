

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
@extends('hr.layouts.app')
@section('content')
<style>
    .mobile{
        display: none;
    }
    @media only screen and (max-width: 600px) {
        .mobile {
            display: block;
        }
        .web {
            display: none;
        }
    }
    .container {padding:20px;}
.popover {width:170px;max-width:170px;}
.popover-content h4 {
  color: #00A1FF;
}
.popover-content h4 small {
  color: black;
}
.popover-content button.btn-primary {
  color: #00A1FF;
  border-color:#00A1FF;
  background:white;
}

.popover-content button.btn-default {
  color: gray;
  border-color:gray;
}

.dataTables_wrapper .dataTables_info {
    clear:none;
    margin-left:10px;
    padding-top:0;
}
.swal2-header {
    border: hidden;
}
</style>

<div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
      <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-tabs-three-standard-tab" data-toggle="pill" href="#custom-tabs-three-standard" role="tab" aria-controls="custom-tabs-three-standard" aria-selected="true">Deductions</a>
            </li>
            {{-- <li class="nav-item">
              <a class="nav-link" id="custom-tabs-three-savings-tab" data-toggle="pill" href="#custom-tabs-three-savings" role="tab" aria-controls="custom-tabs-three-savings" aria-selected="false">Savings</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-three-other-tab" data-toggle="pill" href="#custom-tabs-three-other" role="tab" aria-controls="custom-tabs-three-other" aria-selected="false">Other Deductions</a>
            </li> --}}
            {{-- <li class="nav-item">
              <a class="nav-link" id="custom-tabs-three-tardiness-tab" data-toggle="pill" href="#custom-tabs-three-tardiness" role="tab" aria-controls="custom-tabs-three-tardiness" aria-selected="false">Tardiness Deduction Setup</a>
            </li> --}}
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-three-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-three-standard" role="tabpanel" aria-labelledby="custom-tabs-three-standard-tab">
                <div class="container-fluid">
                    <!-- START ALERTS AND CALLOUTS -->
                    <div class="row">
                        <div class="col-md-4">
                              <!-- Add the bg color to the header using any of the bg-* classes -->
                              <div class="row p-0">
                                  {{-- <div class="col-md-8">
                                    <select class="form-control form-control-sm" name="selectdeductiontype" id="selectdeductiontype">
                                        <option value="0">All</option>
                                        <option value="1">Standard Deduction</option>
                                        <option value="2">School Savings</option>
                                        <option value="3">Other Deductions</option>
                                    </select>
                                  </div> --}}
                                  <div class="col-md-6">
                                    <button type="button" class="btn btn-sm btn-primary float-right btn-block" id="adddeduction" clicked="0"><i class="fa fa-plus"></i>&nbsp; Deduction</button>
                                  </div>
                                  <div class="col-md-6" id="refreshtable">
                                    <button type="button" class="btn btn-sm btn-default float-right btn-block" id="adddeduction" clicked="0"><i class="fa fa-sync"></i>&nbsp; Refresh</button>
                                  </div>
                              </div>
                              <br>
                              <div class="row p-2">
                                    <table class="table">
                                        @foreach($deductiontypes as $deductiontype)
                                            <tr>
                                                <td class="text-uppercase">
                                                        <i class="fa fa-minus"></i> <a href="#" class="eachdeduction" deductionid="{{$deductiontype->id}}" deductiontype="{{$deductiontype->type}}" constant="{{$deductiontype->constant}}"> {{$deductiontype->description}} </a>
                                                </td>
                                                <td class="">
                                                    @if($deductiontype->constant == 0)
                                                        <a id="ded-popover{{$deductiontype->id}}" class="btn text-uppercase float-right popoverindividual" data-placement="right">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </a>             
                                                        <div id="popover_content_wrapper{{$deductiontype->id}}" class="popover_content_wrapper" style="display: none;">
                                                            <span id="ded_details"></span>
                                                                        
                                                            <div class="dedid{{$deductiontype->id}} deddes{{$deductiontype->description}} dedtypestandard"> 
                                                                {{-- <a href="#" class="btn btn-primary btn-sm text-white" id="check_out_ded">
                                                                <i class="fa fa-eye"></i> View
                                                                </a> --}}
                                                                <a href="#" class="btn btn-warning btn-sm text-white editdeductiontypebutton">
                                                                <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                                <a href="#" class="btn btn-danger btn-sm text-white deletedeductiontypebutton deductionid{{$deductiontype->id}}  deductiondescription{{$deductiontype->description}} deductiontypestandard">
                                                                <i class="fa fa-trash"></i> Clear
                                                                </a><span class="close-popover{{$deductiontype->id}} float-right text-danger "><i class="fas fa-times"></i></span>
                                                                
                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(document).ready(function(){
                                                                $('#ded-popover{{$deductiontype->id}}').popover({
                                                                html : true,
                                                                // placement: 'bottom',
                                                                content:function(){
                                                                    return $('#popover_content_wrapper{{$deductiontype->id}}').html();
                                                                }
                                                                
                                                                });
                                                                
                                                            });    
                                                            $(document).on('click','.close-popover{{$deductiontype->id}}',function(){
                                                                $('#ded-popover{{$deductiontype->id}}').popover('hide');
                                                            });
                                                        </script>
                                                    @else
                                                    
                                                        <form action="/bracketing" method="get">
                                                            <input type="hidden" class="form-control form-control-sm" name="type" value="{{$deductiontype->description}}"/>
                                                            <input type="hidden" class="form-control form-control-sm" name="id" value="{{$deductiontype->id}}"/>
                                                            <button type="submit" class="btn btn-default float-right btn-sm">
                                                                <i class="fa fa-cog text-muted"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                              </div>
                          <!-- /.card -->
                        </div>
                        <div class="col-md-8">
                            {{-- <div class="row">
                                <div class="col-md-2 text-right"><i class="fa fa-filter"></i> Filter by</div>
                                <div class="col-md-5">
                                    <label>
                                        Department
                                    </label>
                                    <select class="form-control form-control-sm" name="sadasdasd" >
                                        <option></option>
                                        <option></option>
                                        <option></option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label>
                                        Designation
                                    </label>
                                    <select class="form-control form-control-sm" name="sadasdasd" >
                                        <option></option>
                                        <option></option>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <br> --}}
                            <div class="row p-2">
                                <div class="col-md-12">
                                    <form action="/hrapplicationofdeduction" method="get" id="submitform">
                                        <input type="hidden" name="deductionid"/>
                                        <input type="hidden" name="deductionconstant"/>
                                        <input type="hidden" name="deductionstartdate"/>
                                        {{-- <input type="hidden" name="deductionid"/> --}}
                                    {{-- <div class="mailbox-messages"> --}}
                                        <table class="table table-hover" id="example1">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="employeescontainer">
                                                @if(count($employees) > 0)
                                                    @foreach($employees as $employee)
                                                        <tr>
                                                            <td style="width: 5%;">
                                                                <div class="icheck-primary">
                                                                    <input type="checkbox" value="" id="check{{$employee->employeeid}}" name="employeeids[]" employeename="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}">
                                                                    <label for="check{{$employee->employeeid}}"></label>
                                                                </div>
                                                            </td>
                                                            {{-- <td class="mailbox-star">
                                                                <a href="#">
                                                                    <i class="fas fa-star text-warning"></i>
                                                                </a>
                                                            </td> --}}
                                                            <td class="mailbox-name text-uppercase" style="width: 50%;">
                                                                <a href="#" id="">
                                                                    {{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}
                                                                </a>
                                                                <br>
                                                                <small class="text-muted">{{$employee->utype}}</small>
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">{{$employee->type}} : &#8369; {{number_format($employee->amount, 2, '.', ',')}}</small>
                                                                @if(count($employee->deductionsinfo) > 0)
                                                                    <br>
                                                                    <small class="text-muted">Deductions : <br/>
                                                                        @foreach($employee->deductionsinfo as $deductioninfo)
                                                                            @if($deductioninfo->status == 0)
                                                                                <span class="right badge badge-secondary">{{$deductioninfo->description}} - {{$deductioninfo->datestarted}}</span>
                                                                            @else
                                                                                <span class="right badge badge-warning">{{$deductioninfo->description}}</span>
                                                                            @endif
                                                                        @endforeach 
                                                                    </small>
                                                                @endif  
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </form>
                                    </div>
                                    {{-- </div> --}}
                                </div>
                            </div>
                        <!-- /.card -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <!-- END ALERTS AND CALLOUTS -->
                    <!-- END CUSTOM TABS -->
                    <!-- START PROGRESS BARS -->
                    <!-- /.row -->
                    <!-- END PROGRESS BARS -->
            
                    <!-- START ACCORDION & CAROUSEL-->
                    <!-- END ACCORDION & CAROUSEL-->
            
                    <!-- START TYPOGRAPHY -->
                    <!-- END TYPOGRAPHY -->
                  </div>
                  {{-- <div class="tab-pane fade" id="custom-tabs-three-tardiness" role="tabpanel" aria-labelledby="custom-tabs-three-tardiness-tab">
                      <input type="hidden" name="deleteid" value="{{Crypt::encrypt('deletedeductiontype')}}"/>
                      <input type="hidden" name="employee_deductionstandard.deduction_typeiddeductiondetail" value="{{Crypt::encrypt('adddeductiondetail')}}"/>
                      <hr>
                    <section class="content-header">
                        <div class="container-fluid">
                          <div class="row mb-2">
                            <div class="col-sm-6">
                              <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                                TARDINESS DEDUCTION SETUP</h4>
                            </div>
                          </div>
                        </div>
                      </section>
                      @if(count($tardinesstype)>0)
                      <section class="content">
                                        <div class="row p-2">
                                            <div class="col-md-8">
                                                <form action="/tardinessdeduction/changecomputationtype" method="get" name="changecomputationtype">
                                                    <input type="hidden" name="computationtypeid" />
                                                </form>
                                                <div class="form-group clearfix">
                                                    @if($tardinesstype[0]->status == 0)
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" id="radioPrimary1" name="tardinesssetuptype" value="{{$tardinesstype[0]->id}}">
                                                            <label for="radioPrimary1">
                                                                Standard Computation
                                                            </label>
                                                        </div>
                                                    @else
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" id="radioPrimary1" name="tardinesssetuptype" value="{{$tardinesstype[0]->id}}" checked>
                                                            <label for="radioPrimary1">
                                                                Standard Computation
                                                            </label>
                                                        </div>
                                                    @endif
                                                    <br>
                                                    <br>
                                                    @if($tardinesstype[1]->status == 0)
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" id="radioPrimary2" name="tardinesssetuptype" value="{{$tardinesstype[1]->id}}">
                                                            <label for="radioPrimary2">
                                                                Custom Computation
                                                            </label>
                                                        </div>
                                                    @else
                                                    
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" id="radioPrimary2" name="tardinesssetuptype" value="{{$tardinesstype[1]->id}}" checked>
                                                                <label for="radioPrimary2">
                                                                    Custom Computation
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 addcomputationbutton">
                                                                @if($tardinesstype[1]->status == 1)
                    
                                                                <a data-toggle="modal" data-target="#addcomputation" class="float-left">
                                                                    <i class="fa fa-plus text-success"></i> Add new custom computation
                                                                </a>
                                                                <div id="addcomputation" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h4 class="modal-title employeename">New Custom computation</h4>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">×</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form action="/addtardinesscomputation" method="get">
                                                                                    <label>Late allowance</label>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <input type="number" class="form-control form-control-sm" name="allowanceduration" placeholder="Time allowance duration" value="0"/>
                                                                                            <br>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group clearfix">
                                                                                                <div class="icheck-primary d-inline mr-3">
                                                                                                <input type="radio" id="allowanceduration1" name="allowancedurationtype" value="minutes" checked>
                                                                                                <label for="allowanceduration1">
                                                                                                    Minute/s
                                                                                                </label>
                                                                                                </div>
                                                                                                <div class="icheck-primary d-inline">
                                                                                                <input type="radio" id="allowanceduration2" name="allowancedurationtype" value="hours">
                                                                                                <label for="allowanceduration2">
                                                                                                        Hour/s
                                                                                                </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <label>Late duration</label>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <input type="number" class="form-control form-control-sm" name="timeduration" placeholder="Time duration" required/>
                                                                                            <br>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group clearfix">
                                                                                                <div class="icheck-primary d-inline mr-3">
                                                                                                <input type="radio" id="radioPrimary3" name="durationtype" value="minutes" checked>
                                                                                                <label for="radioPrimary3">
                                                                                                    Minute/s
                                                                                                </label>
                                                                                                </div>
                                                                                                <div class="icheck-primary d-inline">
                                                                                                <input type="radio" id="radioPrimary4" name="durationtype" value="hours">
                                                                                                <label for="radioPrimary4">
                                                                                                        Hour/s
                                                                                                </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row" hidden>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group clearfix">
                                                                                                <div class="icheck-primary d-inline mr-3">
                                                                                                <input type="radio" id="radioPrimary5" name="deductionbasis" value="fixedamount" checked>
                                                                                                <label for="radioPrimary5">
                                                                                                    Fixed Amount
                                                                                                </label>
                                                                                                </div>
                                                                                                <div class="icheck-primary d-inline">
                                                                                                <input type="radio" id="radioPrimary6" name="deductionbasis" value="dailyratepercentage">
                                                                                                <label for="radioPrimary6">
                                                                                                        Daily rate percentage
                                                                                                </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="basiscontainer">
                                                                                        <label>Amount</label>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <input type="text" class="form-control form-control-sm groupOfTexbox" name="amountdeducted" placeholder="Amount" required/>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            Applicable to:
                                                                                        </div>
                                                                                        <div class="col-md-9">
                                                                                            <select class="form-control form-control-sm" name="applicationtype">
                                                                                                <option value="all">All</option>
                                                                                                <option value="specific">Specific Departments</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="specificdepartmentscontainer"></div>
                                                                                    </div>
                                                                                    <div class="submit-section">
                                                                                        <button type="submit" class="btn btn-success submit-btn float-right addnewcomputation">Add</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                
                                                
                                            </div>
                                        </div>
                                        @if($tardinesstype[1]->status == 1)
                                        @if(count($tardinesscomputations) == 0)
                                        @else
                                            <div class="row text-uppercase">
                                                <div class="col-md-12">
                                                    <table class="table" style="table-layout: fixed;font-size:12px;">
                                                        <thead class="text-center">
                                                            <tr>
                                                                <th>Late<br/>Duration</th>
                                                                <th>Late<br/>Allowance</th>
                                                                <th>Amount<br/>Deducted</th>
                                                                <th>Department/s</th>
                                                                <th style="width: 10%;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-center">
                    
                                                            @foreach($tardinesscomputations as $tardinesscomputation)
                                                                <tr>
                                                                    <td>
                                                                        {{$tardinesscomputation->computationinfo->lateduration}}
                                                                        @if($tardinesscomputation->computationinfo->minutes == '1')
                                                                            Minute/s
                                                                        @endif
                                                                        @if($tardinesscomputation->computationinfo->hours == '1')
                                                                            Hour/s
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if(isset($tardinesscomputation->computationinfo->timeallowance))
                                                                        {{$tardinesscomputation->computationinfo->timeallowance}}
                                                                        @if($tardinesscomputation->computationinfo->timeallowancetype == '1')
                                                                         mins.
                                                                        @else
                                                                            hrs.
                                                                        @endif
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($tardinesscomputation->computationinfo->basisfixedamount == '1')
                                                                        &#8369; {{$tardinesscomputation->computationinfo->modifiedamount}}
                                                                        @else
                                                                        ------------
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($tardinesscomputation->computationinfo->specific == '1')
                                                                            @foreach($tardinesscomputation->computationdepartments as $computationdepartment)
                                                                                {{$computationdepartment->department}}<br>
                                                                            @endforeach
                                                                        @else
                                                                        All
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editcomputation{{$tardinesscomputation->computationinfo->id}}">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>
                                                                        <div id="editcomputation{{$tardinesscomputation->computationinfo->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title employeename">Edit Custom computation</h4>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">×</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <form action="/edittardinesscomputation" method="get" style="text-align: left !important;">
                                                                                            <input type="hidden" class="form-control form-control-sm" name="computationid" value="{{$tardinesscomputation->computationinfo->id}}" placeholder="Time duration" required/>
                                                                                            <label>Late duration</label>
                                                                                            <div class="row">
                                                                                                <div class="col-md-12">
                                                                                                    <input type="number" class="form-control form-control-sm" name="timeduration" value="{{$tardinesscomputation->computationinfo->lateduration}}" placeholder="Time duration" required/>
                                                                                                    <br>
                                                                                                </div>
                                                                                                <div class="col-md-12">
                                                                                                    @if($tardinesscomputation->computationinfo->minutes == '1')
                                                                                                        <div class="form-group clearfix">
                                                                                                            <div class="icheck-primary d-inline mr-3">
                                                                                                            <input type="radio" id="radioPrimary1{{$tardinesscomputation->computationinfo->lateduration}}" name="durationtype" value="minutes" checked>
                                                                                                            <label for="radioPrimary1{{$tardinesscomputation->computationinfo->lateduration}}">
                                                                                                                Minute/s
                                                                                                            </label>
                                                                                                            </div>
                                                                                                            <div class="icheck-primary d-inline">
                                                                                                            <input type="radio" id="radioPrimary2{{$tardinesscomputation->computationinfo->lateduration}}" name="durationtype" value="hours">
                                                                                                            <label for="radioPrimary2{{$tardinesscomputation->computationinfo->lateduration}}">
                                                                                                                    Hour/s
                                                                                                            </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                    @if($tardinesscomputation->computationinfo->hours == '1')
                                                                                                        <div class="form-group clearfix">
                                                                                                            <div class="icheck-primary d-inline mr-3">
                                                                                                            <input type="radio" id="radioPrimary1{{$tardinesscomputation->computationinfo->lateduration}}" name="durationtype" value="minutes">
                                                                                                            <label for="radioPrimary1{{$tardinesscomputation->computationinfo->lateduration}}">
                                                                                                                Minute/s
                                                                                                            </label>
                                                                                                            </div>
                                                                                                            <div class="icheck-primary d-inline">
                                                                                                            <input type="radio" id="radioPrimary2{{$tardinesscomputation->computationinfo->lateduration}}" name="durationtype" value="hours" checked>
                                                                                                            <label for="radioPrimary2{{$tardinesscomputation->computationinfo->lateduration}}">
                                                                                                                    Hour/s
                                                                                                            </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <br>
                                                                                            <div class="row" hidden>
                                                                                                <div class="col-md-12">
                                                                                                    @if($tardinesscomputation->computationinfo->basisfixedamount == '1')
                                                                                                        <div class="form-group clearfix">
                                                                                                            <div class="icheck-primary d-inline mr-3">
                                                                                                            <input type="radio" id="radioPrimary3{{$tardinesscomputation->computationinfo->lateduration}}" name="deductionbasis" value="fixedamount" checked>
                                                                                                            <label for="radioPrimary3{{$tardinesscomputation->computationinfo->lateduration}}">
                                                                                                                Fixed Amount
                                                                                                            </label>
                                                                                                            </div>
                                                                                                            <div class="icheck-primary d-inline">
                                                                                                            <input type="radio" id="radioPrimary4{{$tardinesscomputation->computationinfo->lateduration}}" name="deductionbasis" value="dailyratepercentage">
                                                                                                            <label for="radioPrimary4{{$tardinesscomputation->computationinfo->lateduration}}">
                                                                                                                    Daily rate percentage
                                                                                                            </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @else
                                                                                                        <div class="form-group clearfix">
                                                                                                            <div class="icheck-primary d-inline mr-3">
                                                                                                            <input type="radio" id="radioPrimary3{{$tardinesscomputation->computationinfo->lateduration}}" name="deductionbasis" value="fixedamount" >
                                                                                                            <label for="radioPrimary3{{$tardinesscomputation->computationinfo->lateduration}}">
                                                                                                                Fixed Amount
                                                                                                            </label>
                                                                                                            </div>
                                                                                                            <div class="icheck-primary d-inline">
                                                                                                            <input type="radio" id="radioPrimary4{{$tardinesscomputation->computationinfo->lateduration}}" name="deductionbasis" value="dailyratepercentage" checked>
                                                                                                            <label for="radioPrimary4{{$tardinesscomputation->computationinfo->lateduration}}">
                                                                                                                    Daily rate percentage
                                                                                                            </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="basiscontainer">
                                                                                                @if($tardinesscomputation->computationinfo->basisfixedamount == '1')
                                                                                                    <label>Amount</label>
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12">
                                                                                                            <input type="text" class="form-control form-control-sm groupOfTexbox" name="amountdeducted" value="{{$tardinesscomputation->computationinfo->amount}}" placeholder="Amount" required/>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <label>Percentage</label>
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12">
                                                                                                            <input type="text" class="form-control form-control-sm groupOfTexbox" name="percentage" value="{{$tardinesscomputation->computationinfo->dailyratepercentage}}" placeholder="Amount" required/>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                            <br/>
                                                                                            <div class="row" hidden>
                                                                                                <div class="col-3">
                                                                                                    REMAINING LATE DEDUCTION
                                                                                                </div>
                                                                                                <div class="col-9">
                                                                                                    @if($tardinesscomputation->computationinfo->deductfromrate == '0')
                                                                                                        <div class="form-group clearfix">
                                                                                                            <div class="icheck-primary d-inline mr-3">
                                                                                                            <input type="radio" id="deductfromrate1{{$tardinesscomputation->computationinfo->id}}" name="deductfromrate" value="0" checked>
                                                                                                            <label for="deductfromrate1{{$tardinesscomputation->computationinfo->id}}">
                                                                                                                NONE
                                                                                                            </label>
                                                                                                            </div>
                                                                                                            <div class="icheck-primary d-inline">
                                                                                                            <input type="radio" id="deductfromrate2{{$tardinesscomputation->computationinfo->id}}" name="deductfromrate" value="1">
                                                                                                            <label for="deductfromrate2{{$tardinesscomputation->computationinfo->id}}">
                                                                                                                    AUTOMATIC
                                                                                                            </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @else
                                                                                                        <div class="form-group clearfix">
                                                                                                            <div class="icheck-primary d-inline mr-3">
                                                                                                            <input type="radio" id="deductfromrate1{{$tardinesscomputation->computationinfo->id}}" name="deductfromrate" value="0" >
                                                                                                            <label for="deductfromrate1{{$tardinesscomputation->computationinfo->id}}">
                                                                                                                NONE
                                                                                                            </label>
                                                                                                            </div>
                                                                                                            <div class="icheck-primary d-inline">
                                                                                                            <input type="radio" id="deductfromrate2{{$tardinesscomputation->computationinfo->id}}" name="deductfromrate" value="1" checked>
                                                                                                            <label for="deductfromrate2{{$tardinesscomputation->computationinfo->id}}">
                                                                                                                    AUTOMATIC
                                                                                                            </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row">
                                                                                                <div class="col-md-3">
                                                                                                    Applicable to:
                                                                                                </div>
                                                                                                @if($tardinesscomputation->computationinfo->specific == '1')
                                                                                                    <div class="col-md-9">
                                                                                                        <select class="form-control form-control-sm" name="editapplicationtype">
                                                                                                            <option value="all">All</option>
                                                                                                            <option value="specific" selected="selected">Specific Departments</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <div class="col-md-9">
                                                                                                        <select class="form-control form-control-sm" name="editapplicationtype">
                                                                                                            <option value="all" selected="selected">All</option>
                                                                                                            <option value="specific">Specific Departments</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                            <br>
                                                                                            <div class="row">
                                                                                                <div class="editspecificdepartmentscontainer">
                                                                                                    @if($tardinesscomputation->computationinfo->specific == 1)
                                                                                                    @foreach($departments as $department)
                                                                                                        @php
                                                                                                            $match = 0;   
                                                                                                        @endphp
                                                                                                        @foreach($tardinesscomputation->computationdepartments as $computationdepartment)
                                                                                                            @if($department->id == $computationdepartment->departmentid)
                                                                                                                @php
                                                                                                                    $match = 1;   
                                                                                                                    $deptid = $computationdepartment->departmentid;   
                                                                                                                @endphp
                                                                                                            @endif
                                                                                                            
                                                                                                        @endforeach
                                                                                                        @if($match == 1)
                                                                                                            <div class="col-md-12 mb-3">
                                                                                                                <div class="icheck-primary d-inline">
                                                                                                                    <input type="checkbox" id="{{$department->id}}" checked="" value="{{$department->id}}" name="departments[]">
                                                                                                                    <label for="{{$department->id}}">
                                                                                                                        {{$department->department}}
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @else
                                                                                                            <div class="col-md-12 mb-3">
                                                                                                                <div class="icheck-primary d-inline">
                                                                                                                    <input type="checkbox" id="{{$department->id}}" value="{{$department->id}}" name="departments[]">
                                                                                                                    <label for="{{$department->id}}">
                                                                                                                        {{$department->department}}
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="submit-section">
                                                                                                <button type="submit" class="btn btn-warning submit-btn float-right addnewcomputation">Update</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#addcomputation{{$tardinesscomputation->computationinfo->id}}">
                                                                            <i class="fa fa-trash-alt"></i>
                                                                        </button>
                                                                        <div id="addcomputation{{$tardinesscomputation->computationinfo->id}}" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                                            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title employeename">Delete selected computation</h4>
                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                            <span aria-hidden="true">×</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body" style="text-align: none !important">
                                                                                        <form action="/deletetardinesscomputation" method="get">
                                                                                            <input type="hidden" name="tardinesscomputationid" value="{{$tardinesscomputation->computationinfo->id}}"/>
                                                                                            <label>Late duration</label>: <br>
                                                                                            {{$tardinesscomputation->computationinfo->lateduration}}
                                                                                            @if($tardinesscomputation->computationinfo->minutes == '1')
                                                                                                Minute/s
                                                                                            @endif
                                                                                            @if($tardinesscomputation->computationinfo->hours == '1')
                                                                                                Hour/s
                                                                                            @endif
                                                                                            <br>
                                                                                            <br>
                                                                                            @if($tardinesscomputation->computationinfo->basisfixedamount == '1')
                                                                                            <label>Amount</label>:<br>
                                                                                            &#8369; {{$tardinesscomputation->computationinfo->modifiedamount}}
                                                                                            <br>
                                                                                            <br>
                                                                                            @else
                                                                                            @endif
                                                                                            @if($tardinesscomputation->computationinfo->basispercentage == '1')
                                                                                            <label>Daily rate deduction </label>:<br>
                                                                                            {{$tardinesscomputation->computationinfo->modifiedpercentage}}
                                                                                            <br>
                                                                                            <br>
                                                                                            @else
                                                                                            @endif
                                                                                            <label>Department/s:</label><br>
                                                                                            @if($tardinesscomputation->computationinfo->specific == '1')
                                                                                                @foreach($tardinesscomputation->computationdepartments as $computationdepartment)
                                                                                                    {{$computationdepartment->department}}<br>
                                                                                                @endforeach
                                                                                            @else
                                                                                            All
                                                                                            @endif
                                                                                            <div class="submit-section">
                                                                                                <button type="submit" class="btn btn-danger submit-btn float-right addnewcomputation">Delete</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                        @endif
                    </section>
                    @endif
                  </div> --}}
            </div>
            {{-- <div class="tab-pane fade" id="custom-tabs-three-savings" role="tabpanel" aria-labelledby="custom-tabs-three-savings-tab">
            </div>
            <div class="tab-pane fade" id="custom-tabs-three-other" role="tabpanel" aria-labelledby="custom-tabs-three-other-tab">
            </div> --}}
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
  @endsection
  @section('footerscripts')
{{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script> --}}
<script>

    $(function () {
        $("#example1").DataTable({
            // pageLength : 10,
            // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
            "paging": false,
            // dom: 'lifrtp' 
            "dom": '<"toolbar">frtip'
        });
        $("#example1_filter").append(
            '<div class="mailbox-controls m-0 p-0 float-left">'+
                '<button type="button" class="btn btn-default btn-sm checkbox-toggle " id="selectallbutton"><i class="far fa-square"></i> '+
                    'Select All'+
                '</button>'+
                '<div class="btn-group">'+
                    '<button type="button" class="btn btn-default btn-sm" id="addbutton" disabled> <i class="fa fa-plus text-success"></i> Add</button>'+
                '</div>'+
                '<div class="btn-group">'+
                    '<button type="button" class="btn btn-default btn-sm" id="deletedbutton" disabled>&nbsp; <i class="far fa-trash-alt text-danger"></i> &nbsp;</button>'+
                '</div>'+
            '</div>'
        )
    });
  $(function () {
    //Enable check and uncheck all functionality
    $('.checkbox-toggle').click(function () {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('#employeescontainer input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
      } else {
        //Check all checkboxes
        $('#employeescontainer input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
      }
      $(this).data('clicks', !clicks)
    })
  })
    $(document).ready(function(){
        $('#refreshtable').hide();
        // $('#addbutton').hide();
        $('body').addClass('sidebar-collapse');
        var addrows = 0;
        $('#adddeduction').on('click', function(){
            Swal.fire({
                title: 'Fill in the fields below',
                // text: "You won't be able to revert this!",
                html:
                            '<select class="form-control" name="deductiontype" id="deductiontype">'+
                                '<option value="standard">Standard Deduction</option>'+
                                // '<option value="savings">School Savings</option>'+
                                // '<option value="other">Other Deductions</option>'+
                            '</select>'+
                            '<br>'+
                            '<input type="text"class="form-control name="deductiondescription" id="deductiondescription" style="display: inline-block; position:relative" placeholder="Deduction Type" required/>'+
                            '<br/>',
                // type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Submit!',
                allowOutsideClick: false,
                preConfirm: () => {
                    if($('#deductiondescription').val() == ""){
                        Swal.showValidationMessage(
                            "Please fill in the required section!"+
                            "<br>"+
                            "Description is required"
                        );
                    }else{
                        $.ajax({
                            url: '/newdeductionsetup/{{Crypt::encrypt("adddeduction")}}',
                            type:"GET",
                            dataType:"json",
                            data:{
                                type: $("#deductiontype").val(),
                                deductiondescription: $("#deductiondescription").val()
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == '0'){
                                    Swal.fire({
                                        text: "New deduction has been added successfully!",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK!',
                                        allowOutsideClick: false
                                    }).then((confirm) => {
                                        if (confirm.value) {
                                            window.location.reload();
                                        }
                                    })
                                }else{
                                    Swal.fire({
                                        text: "New deduction already exists!",
                                        type: 'danger',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK!',
                                        allowOutsideClick: false
                                    }).then((confirm) => {
                                        if (confirm.value) {
                                            window.location.reload();
                                        }
                                    })
                                }
                            }
                        })
                    }
                }
            })
        });
        $(document).on('click','.editdeductiontypebutton', function(){
            $('.popoverindividual').popover('hide');
            var deductionid = $(this).closest('div')[0].classList[0].replace("dedid", "");
            var description = $(this).closest('div')[0].classList[1].replace("deddes", "");
            var type        = $(this).closest('div')[0].classList[2].replace("dedtype", "");
            console.log(description)
            Swal.fire({
                title: 'Fill in the fields below',
                // text: "You won't be able to revert this!",
                html:
                            // '<select class="form-control" name="deductiontype" id="deductiontype">'+
                            //     '<option value="standard">Standard Deduction</option>'+
                            //     '<option value="savings">School Savings</option>'+
                            //     '<option value="other">Other Deductions</option>'+
                            // '</select>'+
                            // '<br>'+
                            '<input type="text"class="form-control name="deductiondescription" id="deductiondescription" style="display: inline-block; position:relative" placeholder="Title" required/>'+
                            '<br/>',
                // type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Submit!',
                allowOutsideClick: false,
                preConfirm: () => {
                    if($('#deductiondescription').val() == ""){
                        Swal.showValidationMessage(
                            "Please fill in the required section!"+
                            "<br>"+
                            "Title is required"
                        );
                    }else{
                        $.ajax({
                            url: '/newdeductionsetup/{{Crypt::encrypt("editdeduction")}}',
                            type:"GET",
                            dataType:"json",
                            data:{
                                deductionid: deductionid,
                                deductiondescription: $("#deductiondescription").val()
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(data){
                                Swal.fire({
                                    text: "Deduction updated successfully!",
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK!',
                                    allowOutsideClick: false
                                }).then((confirm) => {
                                    if (confirm.value) {
                                        window.location.reload();
                                    }
                                })
                            }
                        })
                    }
                }
            })
        });
        $(document).on('click','.deletedeductiontypebutton', function(){
            $('.popoverindividual').popover('hide');
            var deductionid = $(this).closest('div')[0].classList[0].replace("dedid", "");
            console.log(deductionid)
            Swal.fire({
                title: 'Are you sure you want to delete this deduction?',
                // type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm',
                allowOutsideClick: false,
                preConfirm: () => {
                    if($('#deductiondescription').val() == ""){
                        Swal.showValidationMessage(
                            "Please fill in the required section!"+
                            "<br>"+
                            "Title is required"
                        );
                    }else{
                        $.ajax({
                            url: '/newdeductionsetup/{{Crypt::encrypt("deletededuction")}}',
                            type:"GET",
                            dataType:"json",
                            data:{
                                deductionid: deductionid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data){
                                if(data == 0){
                                    Swal.fire({
                                        text: "Deduction deleted successfully!",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK!',
                                        allowOutsideClick: false
                                    }).then((confirm) => {
                                        if (confirm.value) {
                                            window.location.reload();
                                        }
                                    })
                                }else if(data == 1){
                                    Swal.fire({
                                        text: "There are still employees paying for this deduction!",
                                        type: 'success',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK!',
                                        allowOutsideClick: false
                                    }).then((confirm) => {
                                        if (confirm.value) {
                                            window.location.reload();
                                        }
                                    })
                                }
                            }
                        })
                    }
                }
            })
        });
        var selecteddeductionid;
        var selecteddeductionconstant;

        $(document).on('click','.eachdeduction', function(){
            $('#refreshtable').show();
            $('.mailbox-controls').find('button#addbutton').attr('disabled', true)
            $('td').removeClass('bg-primary')
            $(this).closest('td').addClass('bg-primary')
            var deductiontype = $(this).attr('deductiontype');
            var deductionid   = $(this).attr('deductionid');
            selecteddeductionid = deductionid;
            selecteddeductionconstant = $(this).attr('constant');
            $('input[name="deductionid"]').val(deductionid);
            $('input[name="deductiontypeselected"]').val(deductiontype);
            
            $.ajax({
                url: '/newdeductionsetup/{{Crypt::encrypt("getbydeduction")}}',
                type:"GET",
                dataType:"json",
                data:{
                    deductiontype: deductiontype,
                    deductionid: deductionid
                },
                // headers: { 'X-CSRF-TOKEN': token },,
                success: function(data){
                    console.log(data)
                    $('#employeescontainer').empty()
                    $.each(data, function(key, value){
                        if((value.deductionsinfo).length > 0){
                            // console.log(value.deductionsinfo[0])
                            if(value.deductionsinfo[0].status == 0){
                                var statusbadge = '<span class="right badge badge-secondary">'+value.deductionsinfo[0].description+' - '+value.deductionsinfo[0].datestarted+'</span>'
                            }else{
                                var statusbadge = '<span class="right badge badge-warning">'+value.deductionsinfo[0].description+'</span>'
                            }
                            $('#employeescontainer').append(
                                '<tr>'+
                                    '<td style="width: 5%;">'+
                                        '<div class="icheck-primary">'+
                                            '<input type="checkbox" value="'+value.employeeid+'" id="check'+value.employeeid+'" name="employeeids[]" employeename="'+value.lastname+', '+value.firstname+' '+value.middlename+' '+value.suffix+'">'+
                                            '<label for="check'+value.employeeid+'"></label>'+
                                        '</div>'+
                                    '</td>'+
                                    '<td class="mailbox-name text-uppercase" style="width: 50%;">'+
                                        '<a href="#">'+
                                            value.lastname+', '+value.firstname+' '+value.middlename+' '+value.suffix+
                                        '</a>'+
                                        '<br>'+
                                        '<small class="text-muted">'+value.utype+'</small>'+
                                    '</td>'+
                                    '<td>'+
                                        '<small class="text-muted">'+value.type+' : &#8369; '+value.amount+'</small>'+
                                            '<br>'+
                                            '<small class="text-muted">Deductions : '+
                                                statusbadge+
                                            '</small>'+
                                    '</td>'+
                                '</tr>'
                            )
                        }
                        // console.log(checkedstatus)
                        // $('input#check'+value.employeeid+'[name="employeeids[]"]').val(deductionstatus)
                        // $('input#check'+value.employeeid+'[name="employeeids[]"]').attr(checkedstatus)
                        // console.log($('input#check'+value.employeeid+'[name="employeeids[]"]'))
                    })
                }
            })

        })
        $(document).on('click','#refreshtable', function(){
            window.location.reload();
        })
        $(document).on('click', 'input[name="employeeids[]"]', function(){
            // $('.mailbox-controls').find('button#addbutton').attr('disabled', false)
            if(selecteddeductionid != null){
                $('.mailbox-controls').find('button#addbutton').attr('disabled', true)
                if($('#submitform').find('input[name="employeeids[]"]:checked').length == 0){
                    $('.mailbox-controls').find('button#deletedbutton').attr('disabled', true)
                }else{
                    $('.mailbox-controls').find('button#deletedbutton').attr('disabled', false)
                }
            }else{
                if($('#submitform').find('input[name="employeeids[]"]:checked').length == 0){
                    $('.mailbox-controls').find('button#addbutton').attr('disabled', true)
                    $('.mailbox-controls').find('button#deletebutton').attr('disabled', true)
                }else{
                    $('.mailbox-controls').find('button#addbutton').attr('disabled', false)
                    $('.mailbox-controls').find('button#deletebutton').attr('disabled', false)
                }
            }
            // console.log($(this).attr('id').replace('check,',''))
            if($(this).prop('checked') == true){
                $(this).val('1 - '+$(this).attr('id').replace('check',''))
            }else if($(this).prop('checked') == false){
                $(this).val('0 - '+$(this).attr('id').replace('check',''))
            }
        })
        $(document).on('click','#selectallbutton',function() {
            console.log(selecteddeductionid)
            if(selecteddeductionid != null){
                $('.mailbox-controls').find('button#addbutton').attr('disabled', true)
                if($('#submitform').find('input[name="employeeids[]"]:checked').length == 0){
                    $('.mailbox-controls').find('button#deletedbutton').attr('disabled', true)
                }else{
                    $('.mailbox-controls').find('button#deletedbutton').attr('disabled', false)
                }
            }else{
                if($('#submitform').find('input[name="employeeids[]"]:checked').length == 0){
                    $('.mailbox-controls').find('button#addbutton').attr('disabled', true)
                }else{
                    $('.mailbox-controls').find('button#addbutton').attr('disabled', false)
                }
            }
            $.each($('#submitform').find('input[name="employeeids[]"]'), function(){
                $(this).val('1 - '+$(this).attr('id').replace('check',''))
            })
        })
        $(document).on('click','#addbutton',function() {
            var selectedemployees = [];
            $.each($('#submitform').find('input:checkbox[name="employeeids[]"]:checked'),function(){
                selectedemployees.push($(this).attr('employeename'));
            });
            var html =  '<label class="text-left">Deduction type</label>'+
                        '<select id="selectdeductiontypeid" class="form-control">'+
                            '@foreach($deductiontypes as $deductiontype)'+
                                '<option value="{{$deductiontype->id}}">{{$deductiontype->description}}</option>'+
                            '@endforeach'+
                        '</select>'+
                        '<br/>'+
                        '<label class="text-left">Start date</label>'+
                        '<input type="date" class="form-control" id="selectstartdate"/>'+
                        '<br/>'+
                        '<div class="text-left text-uppercase" style="max-height: 500px;">';
                        $.each(selectedemployees,function(namekey,namevalue){
                            html+=namevalue;
                            html+='<br/>';
                        })
                        html+='</div>'
            Swal.fire({
                html:  html,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Submit',
                allowOutsideClick: false,
                preConfirm: () => {
                    if($('#selectstartdate').val() == ""){
                        Swal.showValidationMessage(
                            "Date is required"
                        );
                    }else{
                        $('#submitform').find('input[name="deductionid"]').val($('#selectdeductiontypeid').val())
                        $('#submitform').find('input[name="deductionstartdate"]').val($('#selectstartdate').val())
                        $('#submitform').submit();
                    }
                }
            })
        });
        $(document).on('click','#deletedbutton',function() {
            var selectedemployees = [];
            $.each($('#submitform').find('input:checkbox[name="employeeids[]"]:checked'),function(){
                selectedemployees.push($(this).attr('employeename'));
            });
            var html =  '<label class="text-left">Delete employee/s in the selected particular</label>'+
                        '<div class="text-left text-uppercase" style="max-height: 500px; border: 1px solid black; overflow-y: scroll;">';
                        $.each(selectedemployees,function(namekey,namevalue){
                            html+=namevalue;
                            html+='<br/>';
                        })
                        html+='</div>'
            Swal.fire({
                html:  html,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                allowOutsideClick: false,
                preConfirm: () => {
                    if($('#selectstartdate').val() == ""){
                        Swal.showValidationMessage(
                            "Date is required"
                        );
                    }else{
                        $('input[name=deductionid]').val(selecteddeductionid);
                        $('#submitform').attr('action','/hrapplicationdelete');
                        $('#submitform').submit();
                    }
                }
            })
        })
        // ===============================================================================================
        // ===============================================================================================
        // ===============================================================================================

        $('#adddeductiondetail').on('click', function(){
            var action = $('input[name=adddeductiondetail]').val();
           Swal.fire({
            title: 'Add new deduction detail',
            html:
                '<label style="text-align:none;">Select deduction type</label>' +
                '<select id="deductiontypeid" name="deductiontypeid" class="form-control form-control-sm">' +
                    @foreach($deductiontypes as $deductiontype)
                        '<option value="{{$deductiontype->id}}">{{$deductiontype->description}}</option>'+
                    @endforeach
                '</select>' +
                '<br>' +
                '<label style="text-align:none;">Deduction detail description</label>' +
                '<input id="deductiondetaildescription" name="deductiondetaildescription" type="text" class="form-control form-control-sm" required>'+
                '<br>' +
                '<div class="row">'+
                '<div class="col-md-6">'+
                '<label style="text-align:none;">Monthly amount</label>' +
                '<input id="deductiondetailmonthlyamount" name="deductiondetailmonthlyamount" type="number" class="form-control form-control-sm" required>'+
                '</div>'+
                '<div class="col-md-6">'+
                '<label style="text-align:none;">Percentage (%)</label>' +
                '<input id="deductiondetailpercentage" name="deductiondetailpercentage" type="number" class="form-control form-control-sm" required>'+
                '</div>'+
                '</div>',
            focusConfirm: false
            }).then((formValues) => {
                if (formValues.value) {
                    var deductiontypeid = $('#deductiontypeid').val();
                    var deductiondetaildescription = $('#deductiondetaildescription').val();
                    var deductiondetailmonthlyamount = $('#deductiondetailmonthlyamount').val();
                    var deductiondetailpercentage = $('#deductiondetailpercentage').val();
                    $.ajax({
                        url: '/deductiondetails/'+action,
                        type:"GET",
                        dataType:"json",
                        data:{
                            deductiontypeid: deductiontypeid,
                            deductiondetaildescription: deductiondetaildescription,
                            deductiondetailmonthlyamount: deductiondetailmonthlyamount,
                            deductiondetailpercentage: deductiondetailpercentage
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        complete: function(data){
                            Swal.fire({
                                title: 'Added Successfully!',
                                // text: "Your file has been deleted.",
                                type: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK!'
                            }).then((confirm) => {
                                if (confirm.value) {
                                    window.location.reload();
                                }
                            })
                        }
                    })
                }
            })
        });
        $(document).on('click','.editdeductiondetail', function(){
            console.log($(this).closest('tr'));
            $('select[name=deductiontypeid]').attr('disabled','disabled')
            $('input[name=deductiondetaildescription]').attr('readonly',true)
            $('input[name=monthlyamount]').attr('readonly',true)
            $('input[name=percentage]').attr('readonly',true)
            $('.editdeductiondetail').prop('type','button');
            $('.editdeductiondetail').removeClass('btn-success');
            $('.editdeductiondetail').addClass('btn-warning');
            $('.editdeductiondetail').text('Save');
            var detailid = $(this).attr('detailid');
            $('select#deductiontypeid'+detailid).removeAttr('disabled')
            $('input#deductiondetaildescription'+detailid).removeAttr('readonly')
            $('input#monthlyamount'+detailid).removeAttr('readonly')
            $('input#percentage'+detailid).removeAttr('readonly')
            $(this).prop('type','submit');
            $(this).removeClass('btn-warning');
            $(this).addClass('btn-success');
            $(this).text('Save');
            // var deductiontypeid = $(this).closest('tr')[0].cells[0].children[0].value;
            // var bracketname = $(this).closest('tr')[0].cells[1].children[0].value;
            // var monthlyamount = $(this).closest('tr')[0].cells[2].children[0].value;
            // var percentage = $(this).closest('tr')[0].cells[3].children[0].value;
        })
    })
    $(document).on('click','input[name=deductionbasis]', function(){
        $('.basiscontainer').empty();
        if($(this).val() == 'fixedamount'){
            $('.basiscontainer').append(
                '<label>Amount</label>'+
                '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<input type="text" class="form-control form-control-sm groupOfTexbox" name="amountdeducted" placeholder="Amount" required/>'+
                    '</div>'+
                '</div>'
            )
        }else{
            $('.basiscontainer').append(
                '<label>Daily Rate Deduction (%)</label>'+
                '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<input type="text" class="form-control form-control-sm groupOfTexbox" name="percentage" placeholder="Percentage " required/>'+
                    '</div>'+
                '</div>'
            )
        }
    });
        $(document).ready(function() {
        $('.groupOfTexbox').keypress(function (event) {
            return isNumber(event, this)
        });
    });
    // THE SCRIPT THAT CHECKS IF THE KEY PRESSED IS A NUMERIC OR DECIMAL VALUE.
    function isNumber(evt, element) {

        var charCode = (evt.which) ? evt.which : event.keyCode

        if (
            (charCode != 45 || $(element).val().indexOf('-') != -1) &&      // “-” CHECK MINUS, AND ONLY ONE.
            (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
            (charCode < 48 || charCode > 57))
            return false;

        return true;
    }  
    $(document).on('change','select[name=applicationtype]', function(){
        if($(this).val() == 'specific'){

            @foreach($departments as $department)
                $('.specificdepartmentscontainer').append(
                    '<div class="col-md-12 mb-3">'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="checkbox" id="a{{$department->id}}" checked="" value="{{$department->id}}" name="departments[]">'+
                            '<label for="a{{$department->id}}">'+
                                '{{$department->department}}'+
                            '</label>'+
                        '</div>'+
                    '</div>'
                )
            @endforeach
        }else{
            $('.specificdepartmentscontainer').empty();
        }
    });
    $(document).on('change','select[name=editapplicationtype]', function(){
        if($(this).val() == 'specific'){

            @foreach($departments as $department)
                $('.editspecificdepartmentscontainer').append(
                    '<div class="col-md-12 mb-3">'+
                        '<div class="icheck-primary d-inline">'+
                            '<input type="checkbox" id="a{{$department->id}}" checked="" value="{{$department->id}}" name="departments[]">'+
                            '<label for="a{{$department->id}}">'+
                                '{{$department->department}}'+
                            '</label>'+
                        '</div>'+
                    '</div>'
                )
            @endforeach
        }else{
            $('.editspecificdepartmentscontainer').empty();
        }
    });
    $(document).on('click','input[name=tardinesssetuptype]', function(){
        $('input[name=computationtypeid]').val($(this).val());
        $('form[name=changecomputationtype]').submit();
    });
    
  </script>
@endsection

