@extends('finance.layouts.app')

@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
    /* .widget-user .widget-user-image > img {
        border: hidden;
    }
    .donutTeachers{
        margin-top: 90px;
        margin: 0 auto;
        background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  50% 80%;
        background-size: 30%;
    }
    .donutStudents{
        margin-top: 90px;
        margin: 0 auto;
        background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  50% 80%;
        background-size: 30%;
    } */
</style>
<br>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Online Payments Report</h1>
                <!-- <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <i class="fa fa-file-invoice nav-icon"></i>
                    <b>STUDENT LEDGER</b></h4> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Online Payments</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="row m-2">
    {{-- <div class="col-md-3">
        <div class="card">
            <div class="card-header"><i class="fa fa-filter"></i> Filter</div>
            <div class="card-body">
            </div>
        </div>
    </div> --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <label>Status</label>
                        <form action="/finance/reportonlinepayments/changestatus" method="get">
                            <input type="hidden" name="action" value="0"/>
                            <select class="form-control form-control-sm" name="status">
                                <option value="all" {{"all" == $status ? 'selected' : ''}}>ALL</option>
                                <option value="0" {{"0" == $status ? 'selected' : ''}}>PENDING</option>
                                <option value="1" {{"1" == $status ? 'selected' : ''}}>APPROVED</option>
                                <option value="2" {{"2" == $status ? 'selected' : ''}}>DISAPPROVED</option>
                                <option value="5" {{"5" == $status ? 'selected' : ''}}>PAID</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3">
                        &nbsp;
                        <form action="/finance/reportonlinepayments/changestatus" method="get" target="_blank">
                            <input type="hidden" name="action" value="1"/>
                            <input type="hidden" name="status" value="{{$status}}"/>
                            <button type="submit" class="btn btn-sm btn-primary float-right"><i class="fa fa-print"></i> Print</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name of Students</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="studentscontainer text-uppercase">
                        @if(count($studentonlinepayments) > 0)
                            {{-- @dd($studentonlinepayments) --}}

                            @foreach($studentonlinepayments as $studentonlinepayment)
                                <tr>
                                    <td >
                                        {{$studentonlinepayment->studinfo->lastname}}, {{$studentonlinepayment->studinfo->firstname}} {{$studentonlinepayment->studinfo->middlename}} {{$studentonlinepayment->studinfo->suffix}}
                                    </td>
                                    <td class="p-0">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>AMOUNT</th>
                                                    <th>PAYMENT TYPE</th>
                                                    <th>TRANSACTION DATE</th>
                                                    <th>PAYMENT DATE</th>
                                                    <th>REMARKS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($studentonlinepayment->paymentinfo as $paymentinfo)
                                                    <tr>
                                                        <td>{{$paymentinfo->amount}}</td>
                                                        <td>{{$paymentinfo->TransDate}}</td>
                                                        <td>{{$paymentinfo->TransDate}}</td>
                                                        <td>{{$paymentinfo->paymentDate}}</td>
                                                        <td>{{$paymentinfo->remarks}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>

    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
    });
    $(document).ready(function(){
        $('body').addClass('sidebar-collapse');


    })
    $(document).on('change','select[name=status]', function(){
        $(this).closest('form').submit();
    })

</script>
@endsection
