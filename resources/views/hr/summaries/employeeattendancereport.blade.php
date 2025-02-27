@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Summaries</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Absentees</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-filter"></i> Filter
            </div>
            <div class="card-body">
                <label>Select status</label>
                <form action="/summaryofattendance/filter" method="get">
                    <input type="hidden" class="form-control form-control-sm" name="selecteddate" value="{{$selecteddate}}"/>
                    <select name="selectedstatus" class="form-control form-control-sm">
                        <option value="all" {{"all" == $selectedstatus ? 'selected' : ''}}>All</option>
                        <option value="present" {{"present" == $selectedstatus ? 'selected' : ''}}>Present</option>
                        <option value="tardy" {{"tardy" == $selectedstatus ? 'selected' : ''}}>Tardy</option>
                        <option value="absent" {{"absent" == $selectedstatus ? 'selected' : ''}}>Absent</option>
                    </select>
                </form>
                <label>Select date</label>
                <form action="/summaryofattendance/filter" method="get">
                    <input type="hidden" class="form-control form-control-sm" name="selectedstatus" value="{{$selectedstatus}}"/>
                    <input type="date" class="form-control form-control-sm" name="selecteddate" value="{{$selecteddate}}"/>
                </form>
                <br>
                <form action="/summaryofattendance/print" method="get" target="_blank">
                    <input type="hidden" class="form-control form-control-sm" name="selectedstatus" value="{{$selectedstatus}}"/>
                    <input type="hidden" class="form-control form-control-sm" name="selecteddate" value="{{$selecteddate}}"/>
                    <button type="submit" class="btn btn-sm btn-primary btn-block"><i class="fa fa-print"></i> Print</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h4>
                    <strong>{{strtoupper($selectedstatus)}} EMPLOYEES</strong>
                </h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Designation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($present))
                            @foreach($present as $presentemployee)
                                <tr>
                                    <td><span class="right badge badge-success">PRESENT</span> {{$presentemployee->firstname}} {{$presentemployee->middlename[0].'.'}} {{$presentemployee->lastname}} {{$presentemployee->suffix}}</td>
                                    <td>{{$presentemployee->designation}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($tardy))
                            @foreach($tardy as $tardyemployee)
                                <tr>
                                    <td><span class="right badge badge-warning">TARDY</span> {{$tardyemployee->firstname}} {{$tardyemployee->middlename[0].'.'}} {{$tardyemployee->lastname}} {{$tardyemployee->suffix}}</td>
                                    <td>{{$tardyemployee->designation}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(isset($absent))
                            @foreach($absent as $absentemployee)
                                <tr>
                                    <td><span class="right badge badge-danger">ABSENT</span> {{$absentemployee->firstname}} {{$absentemployee->middlename[0].'.'}} {{$absentemployee->lastname}} {{$absentemployee->suffix}}</td>
                                    <td>{{$absentemployee->designation}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
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

    $(document).on('change','select[name=selectedstatus]', function(){

        $(this).closest('form').submit();

    });

    $(document).on('change','input[name=selecteddate]', function(){

        $(this).closest('form').submit();

    });

</script>
@endsection