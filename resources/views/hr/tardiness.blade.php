

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
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

</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Tardiness Setup</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Tardiness Setup</li>
          </ol>
        </div>
      </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-2">
                    <div class="row p-2">
                        <div class="col-md-8">
                            {{-- {{$tardinesstype}} --}}
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
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="radioPrimary2" name="tardinesssetuptype" value="{{$tardinesstype[1]->id}}" checked>
                                        <label for="radioPrimary2">
                                            Custom Computation
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            
                            @if($tardinesstype[1]->status == 1)

                            <a data-toggle="modal" data-target="#addcomputation" class="float-right">
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
                                                <label>Late duration</label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input type="number" class="form-control form-control-sm" name="timeduration" placeholder="Time duration" required/>
                                                    </div>
                                                    <div class="col-md-8">
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
                                                <label>Deduction Basis</label>
                                                <div class="row">
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
                                                <br>
                                                <div class="basiscontainer">
                                                    <label>Amount</label>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="number" class="form-control form-control-sm" name="amountdeducted" placeholder="Amount" required/>
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
                    
                    @if($tardinesstype[1]->status == 1)
                    @if(count($tardinesscomputations) == 0)
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table" style="table-layout: fixed;font-size:13px;">
                                    <thead class="text-center">
                                        <tr>
                                            <th style="width: 15%;">Late Duration</th>
                                            <th style="width: 15%;">Amount Deducted</th>
                                            <th style="width: 15%;">Daily rate deduction (%)</th>
                                            <th style="width: 35%;">Department/s</th>
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
                                                    {{-- <select class="form-control form-control-sm" name="durationtype">
                                                        <option value="minutes" {{$tardinesscomputation->minutes == '1' ? 'selected' : ''}}>Minute/s</option>
                                                        <option value="hours" {{$tardinesscomputation->hours == '1' ? 'selected' : ''}}>Hour/s</option>
                                                    </select> --}}
                                                <td>
                                                    @if($tardinesscomputation->computationinfo->basisfixedamount == '1')
                                                    &#8369; {{$tardinesscomputation->computationinfo->modifiedamount}}
                                                    @else
                                                    ------------
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($tardinesscomputation->computationinfo->basispercentage == '1')
                                                        {{$tardinesscomputation->computationinfo->modifiedpercentage}}
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
                </div>
            </div>
        </div>
    </div>
</section>
<input type="hidden" name="deleteid" value="{{Crypt::encrypt('deletedeductiontype')}}"/>
<input type="hidden" name="adddeductiondetail" value="{{Crypt::encrypt('adddeductiondetail')}}"/>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(document).on('click','input[name=deductionbasis]', function(){
        $('.basiscontainer').empty();
        if($(this).val() == 'fixedamount'){
            $('.basiscontainer').append(
                '<label>Amount</label>'+
                '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<input type="number" class="form-control form-control-sm" name="amountdeducted" placeholder="Amount" required/>'+
                    '</div>'+
                '</div>'
            )
        }else{
            $('.basiscontainer').append(
                '<label>Daily Rate Deduction (%)</label>'+
                '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<input type="number" class="form-control form-control-sm" name="percentage" placeholder="Percentage " required/>'+
                    '</div>'+
                '</div>'
            )
        }
    });
    $(document).on('change','select[name=applicationtype]', function(){
        if($(this).val() == 'specific'){

            @foreach($departments as $department)
                $('.specificdepartmentscontainer').append(
                    '<div class="icheck-primary d-inline">'+
                        '<input type="checkbox" id="{{$department->id}}" checked="" value="{{$department->id}}" name="departments[]">'+
                        '<label for="{{$department->id}}">'+
                            '{{$department->department}}'+
                        '</label>'+
                    '</div>'+
                    '<br>'
                )
            @endforeach
        }
    });
    $(document).on('click','input[name=tardinesssetuptype]', function(){
        $('input[name=computationtypeid]').val($(this).val());
        $('form[name=changecomputationtype]').submit();
    });
    


</script>
@endsection

