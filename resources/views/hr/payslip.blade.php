

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
    .payslip-title {
    margin-bottom: 20px;
    text-align: center;
    text-decoration: underline;
    text-transform: uppercase;
}
h1, h2, h3, h4, h5, h6 {
    /* font-family: CircularStd; */
    margin-top: 0;
    font-weight: 500;
}
.h4, h4 {
    font-size: 1.5rem;
}
.m-b-20 {
    margin-bottom: 20px !important;
}

@media (min-width: 576px){
.col-sm-6 {
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
}
}
.invoice-details, .invoice-payment-details > li span {
    float: right;
    text-align: right;
}
.list-unstyled {
    padding-left: 0;
    list-style: none;
}
dl, ol, ul {
    margin-top: 0;
    margin-bottom: 1rem;
}

</style>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h3 class="page-title">Payslip</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/employeesalary/dashboard">Payslip</a></li>
                <li class="breadcrumb-item active">{{$payslip[0]->employee_info->firstname}} {{$payslip[0]->employee_info->middlename}} {{$payslip[0]->employee_info->lastname}}</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Holiday</a>
            </div> --}}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{-- <h4 class="payslip-title">Payslip for the month of Feb 2019</h4> --}}
                <div class="row">
                    <div class="col-sm-6 m-b-20">
                        <img src="{{asset('assets/images/broken_shire_logo.png')}}" class="inv-logo" alt="" width="30%">
                        <ul class="list-unstyled mb-0">
                            <li>{{$schoolinfo->schoolname}}</li>
                            <li>{{$schoolinfo->address}}</li>
                            {{-- <li>Sherman Oaks, CA, 91403</li> --}}
                        </ul>
                    </div>
                    <div class="col-sm-6 m-b-20">
                        <div class="invoice-details">
                            {{-- <h3 class="text-uppercase">Payslip #49029</h3>
                            <ul class="list-unstyled">
                                <li>Salary Month: <span>March, 2019</span></li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 m-b-20">
                        <ul class="list-unstyled">
                            <li><h5 class="mb-0"><strong>{{$payslip[0]->employee_info->firstname}} {{$payslip[0]->employee_info->middlename}} {{$payslip[0]->employee_info->lastname}}</strong></h5></li>
                            <li><span>{{$payslip[0]->employee_info->utype}}</span></li>
                            <li>Employee ID: {{$payslip[0]->employee_info->licno}}</li>
                            <li>Joining Date: </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <h4 class="m-b-10"><strong>Earnings</strong></h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Basic Salary</strong> <span class="float-right">&#8369; <span class="salary">{{$payslip[0]->basicsalary}}</span></span></td>
                                    </tr>
                                    @foreach($payrollearnings as $earning)
                                    @if($earning->type == 'earning')
                                    <tr>
                                        <td>{{$earning->description}}<span class="float-right">&#8369; <span class="salary">{{$earning->amount}}</span></span></td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    {{-- <tr>
                                        <td><strong>House Rent Allowance (H.R.A.)</strong> <span class="float-right">$55</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Conveyance</strong> <span class="float-right">$55</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Other Allowance</strong> <span class="float-right">$55</span></td>
                                    </tr> --}}
                                    <tr>
                                        <td><strong>Total Earnings</strong> <span class="float-right"><strong>&#8369; <span class="salary">{{$payslip[0]->totalearnings}}</span></strong></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div>
                            <h4 class="m-b-10"><strong>Deductions</strong></h4>
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($payslip[0]->deductions as $deduction)
                                        @if($deduction->status == '1')
                                            <tr>
                                                <td><strong>{{$deduction->description}}</strong> <span class="float-right">&#8369; <span class="salary">{{$deduction->amount}}</span></span></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($payrollearnings as $earning)
                                    @if($earning->type == 'deduction')
                                    <tr>
                                        <td>{{$earning->description}}<span class="float-right">&#8369; <span class="salary">{{$earning->amount}}</span></span></td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    {{-- <tr>
                                        <td><strong>Tax Deducted at Source (T.D.S.)</strong> <span class="float-right">$0</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Provident Fund</strong> <span class="float-right">$0</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>ESI</strong> <span class="float-right">$0</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Loan</strong> <span class="float-right">$300</span></td>
                                    </tr> --}}
                                    <tr>
                                        <td><strong>Total Deductions</strong> <span class="float-right"><strong>&#8369; <span class="salary">{{$payslip[0]->totaldeduction}}</span></strong></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <p><strong>Net Salary: &#8369; <span class="salary">{{$payslip[0]->netsalary}}</span></strong> ({{$payslip[0]->netsalarydescription}})</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
     $.fn.digits = function(){ 
            return this.each(function(){ 
                $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
            })
        }
        $("span.salary").digits();
  </script>
@endsection

