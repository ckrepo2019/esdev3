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
                <h1>Balance Forwarding Report</h1>
                <!-- <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <i class="fa fa-file-invoice nav-icon"></i> 
                    <b>STUDENT LEDGER</b></h4> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Balance Forwarding</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="row m-2">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{-- <button type="submit" class="btn btn-sm btn-primary float-right"><i class="fa fa-print"></i> Print</button> --}}
                <a href="/finance/reportbalanceforwarding/print" class="btn btn-sm btn-primary float-right" target="_blank"><i class="fa fa-print"></i> Print</a>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-sm text-sm">
                    <thead>
                        <tr>
                            <th>Name of Students</th> 
                            <th></th> 
                        </tr>
                    </thead>
                    <tbody class="studentscontainer text-uppercase">
                        @if(count($balforwardlist) > 0)
                            @foreach($balforwardlist as $list)
                                <tr>
                                    <td >
                                        <b>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</b>
                                        <br>{{$list->levelname}}
                                    </td>
                                    <td>
                                        <table class="table table-bordered">    
                                            <thead>
                                                <tr>
                                                    <th>PARTICULARS</th>
                                                    <th>AMOUNT</th>
                                                    <th>PAYMENT</th>
                                                    <th>BALANCE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{$list->particulars}}</td>
                                                    <td>{{number_format($list->amount, 2)}}</td>
                                                    <td>{{number_format($list->amountpay, 2)}}</td>
                                                    <td>{{number_format($list->balance, 2)}}</td>
                                                </tr>
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
            lengthMenu: [[5, 10, 20, 1], [5, 10, 20, 'Show All']],
			"bPaginate":false
        });
    });

</script>
@endsection