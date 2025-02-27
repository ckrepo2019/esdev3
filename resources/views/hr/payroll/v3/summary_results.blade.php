{{-- <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                
            </div>
        </div>
    </div>
</div> --}}
@if(count($histories) == 0)
<div class="col-12">
    <div class="alert alert-danger" role="alert">
        No payroll history!
    </div>
</div>
@else
    <div class="col-md-12">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Search employee</span>
                </div>
                <input type="text" class="form-control" placeholder="Employee" id="employeeSearch">
            </div>        
         </div>
    </div>
    <div class="col-md-12 d-flex align-items-stretch flex-column">
        <div class="card card-primary collapsed-card" style="border: 1px solid #ddd; box-shadow: 0 0 1px rgb(0 0 0 / 13%) !important; margin-bottom: 1px!important">
          
                <div class="card-header">
                    <div class="row p-0">
                        <div class="col-1"></div>
                        <div class="col-11">
                            <div class="row">
                                <div class="col-3 text-left">
                                    <small class="text-bold text-muted" style="font-size: 14px;">NAME</small>
                                </div>
                                <div class="col-2 text-left">
                                    <small class="text-bold text-muted" style="font-size: 14px;">TOTAL EARNING</small>
                                </div>
                                <div class="col-2 text-left">
                                    <small class="text-bold text-muted" style="font-size: 14px;">TOTAL DEDUCTION</small>
                                </div>
                                <div class="col-1 text-left">
                                    <small class="text-bold text-muted" style="font-size: 14px;">NET PAY</small>
                                </div>
                                <div class="col-4 text-left mt-2">
                                    <!-- Add button headers here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="row" id="newdata">

    </div>
    @foreach($histories as $history)
    <div class="col-md-12 d-flex align-items-stretch flex-column">
        <div class="card card-primary collapsed-card  employee-card" style="border: 1px solid #ddd; box-shadow: 0 0 1px rgb(0 0 0 / 13%) !important; margin-bottom: 1px!important">
                <div class="card-header">
                    <div class="row">
                        <div class="col-1" style="float: center;">
                            @php
                                $number = rand(1,3);
                                if(strtoupper($history->gender) == 'FEMALE'){
                                    $avatar = 'avatar/T(F) '.$number.'.png';
                                }
                                else{
                                    $avatar = 'avatar/T(M) '.$number.'.png';
                                }
                            @endphp
                            <img src="{{ asset($history->picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'"  class="img-circle img-fluid" style="width: 50px;">
                        </div>
                        <div class="col-11">
                            <input type="hidden" id="employeeid" value="{{$history->id}}" data-id="{{$history->employeeid}}">
                            <div class="row" style="padding-top: 6px;">
                                <div class="col-3" style="font-size: 18px !important;">
                                    <small class="text-bold text-muted">{{$history->lastname}}, {{$history->firstname}}</small>
                                    {{-- <small>{{$history->utype}} / {{$history->tid}}</small> --}}
                                </div>
                                <div class="col-2 text-left" style="font-size: 20px !important;">
                                    <small class="text-muted">{{number_format($history->totalearning,2,'.',',')}}</small>
                                </div>
                                <div class="col-2 text-left" style="font-size: 20px !important;">
                                    <small class="text-muted">{{number_format($history->totaldeduction,2,'.',',')}}</small>
                                </div>
                                <div class="col-2 text-left" style="font-size: 20px !important;">
                                    <small class="text-muted">{{number_format($history->netsalary,2,'.',',')}}</small>
                                </div>
                                <div class="col-3 text-right">
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-print-slip" data-id="{{$history->id}}"><i class="fas fa-print"></i> View Payslip
                                    </button>

                                    <button type="button" class="btn btn-tool text-secondary btn-outline-warning btn-getdetails" data-id="{{$history->id}}"  data-card-widget="collapse"><i class="fas fa-plus"></i> View details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="card-body pt-0" style="font-size: 12.5px;" id="container-id-{{$history->id}}"></div>
        </div>
    </div>
    @endforeach
    {{-- @foreach($histories as $history)
        <div class="col-md-12 d-flex align-items-stretch flex-column">
            <div class="card card-primary collapsed-card" style="border: 1px solid #ddd; box-shadow: 0 0 1px rgb(0 0 0 / 13%) !important;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-1" style="float: center;">
                                @php
                                $number = rand(1,3);
                                if(strtoupper($history->gender) == 'FEMALE'){
                                    $avatar = 'avatar/T(F) '.$number.'.png';
                                }
                                else{
                                    $avatar = 'avatar/T(M) '.$number.'.png';
                                }
                            @endphp
                            <img src="{{ asset($history->picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'"  class="img-circle img-fluid" style="width: 60px;">
                        </div>
                    
                        <div class="col-11">
                            <input type="hidden" id="employeeid" value="{{$history->id}}" data-id="{{$history->employeeid}}">
                            <div class="row">
                                <div class="col-2">
                                    <p class="text-bold text-muted m-0">{{$history->lastname}}, {{$history->firstname}}</p>
                                    <small>{{$history->utype}} / {{$history->tid}}</small>
                                </div>
                                <div class="col-6 text-right">
                                    <p class="text-muted m-0"><small>{{$history->utype}} / {{$history->tid}}</small></p>
                                </div>
                                <div class="col-2 text-right" style="font-size: 20px !important;">
                                    <small class="text-muted">Total Earning: <span class="text-bold">{{number_format($history->totalearning,2,'.',',')}}</span></small>
                                </div>
                                <div class="col-2 text-right" style="font-size: 20px !important;">
                                    <small class="text-muted">Total Deduction:  <span class="text-bold">{{number_format($history->totaldeduction,2,'.',',')}}</span></small>
                                </div>
                                <div class="col-2 text-right" style="font-size: 20px !important;">
                                    <small class="text-muted">Net Pay:  <span class="text-bold">{{number_format($history->netsalary,2,'.',',')}}</span></small>
                                </div>
                                <div class="col-4 text-muted text-bold text-right"><small>Pay Released: </b>{{date('M d, Y', strtotime($history->releaseddatetime))}}</small></div>
                                <div class="col-4 text-right mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-print-slip" data-id="{{$history->id}}"><i class="fas fa-print"></i> Export Payslip
                                    </button>

                                    <button type="button" class="btn btn-tool text-secondary btn-outline-warning btn-getdetails" data-id="{{$history->id}}"  data-card-widget="collapse"><i class="fas fa-plus"></i> View details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>
                <div class="card-body pt-0" style="font-size: 12.5px;" id="container-id-{{$history->id}}">
                </div>
            </div>
        </div>
    @endforeach --}}
@endif
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Listen for changes in the search input
        $("#employeeSearch").on("input", function () {
            // Get the search value
            var searchValue = $(this).val().toLowerCase();

            // Hide all employee cards
            $(".employee-card").hide();

            // Show only the cards that match the search criteria
            $(".employee-card").filter(function () {
                var employeeName = $(this).find(".text-bold").text().toLowerCase();
                return employeeName.includes(searchValue);
            }).show();
        });
    });
</script>