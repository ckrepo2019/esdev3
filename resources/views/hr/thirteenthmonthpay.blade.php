

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<style>
    .dataTables_wrapper .dataTables_info {
    clear:none;
    margin-left:10px;
    padding-top:0;
}
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">13<sup>th</sup> Month Pay</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">13<sup>th</sup> Month Pay</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="card">
    <div class="card-header">
        <i class="fa fa-exclamation-triangle text-warning"></i> <small>13th month pay is only available during the month of December</small>
        @if($currentmonthstatus == 1)
            <button type="button" class="btn btn-primary btn-sm float-right" id="printall">
                <i class="fa fa-print"></i> Print
            </button>
        @endif
    </div>
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" >
            <div class="row">
                <div class="col-sm-12">
                    <table id="example1" style="font-size: 12px" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date Hired</th>
                                <th>Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($employees) == 0)
                                <tr>
                                    <td colspan="3">No employees shown</td>
                                </tr>
                            @else
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>
                                            {{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}}
                                        </td>
                                        <td>{{$employee->datehired}}</td>
                                        <td>
                                            @if($employee->currentmonthstatus == 0)
                                                        &#8369; {{number_format($employee->pay,2,'.',',')}}
                                            @else
                                                @if($employee->paystatus == 1)
                                                    <button type="button" class="btn btn-secondary btn-sm btn-block paybutton" employeename="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename[0]}}. {{$employee->suffix}}" employeeid="{{$employee->id}}" amountpay="{{number_format($employee->pay,2,'.',',')}}" paystatus="{{$employee->paystatus}}">
                                                        <span class="right badge badge-warning">Paid</span> &#8369; {{number_format($employee->pay,2,'.',',')}}
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-light btn-sm btn-block btn-outline-primary paybutton" employeename="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename[0]}}. {{$employee->suffix}}" employeeid="{{$employee->id}}" amountpay="{{number_format($employee->pay,2,'.',',')}}"  paystatus="{{$employee->paystatus}}" disabled>
                                                        &#8369; {{number_format($employee->pay,2,'.',',')}}
                                                    </button>
                                                @endif
                                            @endif
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
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    
    $("#example1").DataTable({
        // pageLength : 10,
        // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
        paging: false,
        dom: 'lifrtp' 
    });

    $(document).on('click','#printall', function() {
        Swal.fire({
            title: "Generating summary...",
            // text: "You won't be able to revert this!",
            html:
                '<form id="printallform" action="/hrreports/thirteenthmonth/{{Crypt::encrypt("print")}}" method="get" target="_blank">'+
                    '<input type="hidden" name="printview" value="all"/>'+
                '</form>',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue!',
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $('#printallform').submit();
                // window.location.reload();
            }
        })
    });

    $(document).on('click','.paybutton', function() {
        var employeeid      = $(this).attr('employeeid');
        var employeename    = $(this).attr('employeename');
        var amountpay       = $(this).attr('amountpay');
        if($(this).attr('paystatus') == 0){
            var confirmbuttontext =  'Pay';
        }else{
            var confirmbuttontext =  'Print';
        }
        Swal.fire({
            title: employeename.toUpperCase(),
            html:
                "Amount: &#8369; "+amountpay+
                '<br/>'+
                '<form id="payform" action="/hrreports/thirteenthmonthpayslip" method="get" target="_blank">'+
                    '<input type="hidden" name="employeeid" value="'+employeeid+'"/>'+
                    '<input type="hidden" name="amountpay" value="'+amountpay+'"/>'+
                '</form>',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmbuttontext,
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                $('#payform').submit();
                window.location.reload();
            }
        })
    });
</script>
@endsection

