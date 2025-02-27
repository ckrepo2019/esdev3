@extends(''.$extends.'')
@section('content')
<script src="{{asset('plugins/jquery/jquery-3-3-1.min.js')}}"></script>

<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
    })
</script>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-money-bill nav-icon"></i> Payroll Details</h4>
                <!-- <h1>Employee  Profile</h1> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Payroll Details</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content-body">
    <div class="row mb-2">
        <div class="col-sm-12">
            <div class="alert alert-warning alert-dismissible">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                Currently working on this page.
              </div>
        </div>
        <div class="col-md-4">
            <input class="filter form-control" placeholder="Search" />
        </div>
    </div>
    <div class="row d-flex align-items-stretch text-uppercase">
        @foreach($pays as $pay)
            @php
                $datefrom=date_create($pay->payrolldatefrom);
                $dateto=date_create($pay->payrolldateto);
                $datefrom = date_format($datefrom,"m/d/Y");
                $dateto = date_format($dateto,"m/d/Y");
            @endphp
            <div class="col-md-12">
                <div class="card" data-string="{{$datefrom}} {{$dateto}}<">
                    <div class="card-header bg-info">
                        {{$datefrom}} - {{$dateto}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Salary Type</label>
                                <br/>
                                {{$pay->ratetype}}
                            </div>
                            <div class="col-md-3">
                                <label>Basic Pay</label>
                                <br/>
                                {{$pay->basicpay}}
                            </div>
                            <div class="col-md-3">
                                <label>Overtime Pay</label>
                                <br/>
                                {{$pay->overtimepay}}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label>Holiday Pay</label>
                                <br/>
                                {{$pay->holidaypay}}
                            </div>
                            <div class="col-md-3">
                                <label>Holiday Overtime Pay</label>
                                <br/>
                                {{$pay->holidayovertimepay}}
                            </div>
                        </div>
                        <div class="row mt-2">
                            @if(count($pay->details)>0)
                                <div class="col-md-6">
                                    <table class="table table-hover table bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="2">
                                                    Deductions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pay->details as $deduction)
                                                @if($deduction->deductionid != 0)
                                                    <tr>
                                                        <td>
                                                            {{$deduction->description}}
                                                        </td>
                                                        <td>
                                                            {{$deduction->amount}}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-hover table bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="2">
                                                    Allowances
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pay->details as $allowance)
                                                @if($allowance->allowanceid != 0)
                                                    <tr>
                                                        <td>
                                                            {{$allowance->description}}
                                                        </td>
                                                        <td>
                                                            {{$allowance->amount}}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label>Total Earnings</label>
                                <br/>
                                {{$pay->totalearnings}}
                            </div>
                            <div class="col-md-3">
                                <label>Total Deductions</label>
                                <br/>
                                {{$pay->totaldeductions}}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label>Net Pay</label>
                                <br/>
                                {{$pay->netpay}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
@endsection