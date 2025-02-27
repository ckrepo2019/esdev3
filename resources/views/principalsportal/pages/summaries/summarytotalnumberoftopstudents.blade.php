@extends('principalsportal.layouts.app2')
@section('content')

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Reports</h1>
                <h6>Top Students</h6>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Top Students</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-filter"></i> Filter Students
            </div>
            <div class="card-body">
                <form action="/summarytotalnumberofdropped/filter" method="get">
                    <label>Select Gradelevel</label>
                    <select name="selectedgradelevel" class="form-control form-control-sm">
                        <option></option>
                        @foreach($gradelevels as $gradelevel)
                            <option value="{{$gradelevel->id}}" {{-- {{$gradelevel->id == $selectedschoolyear ? 'selected' : ''}} --}} >{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                    {{-- <input type="hidden" name="selectedperiod" value="{{$periodfrom}} - {{$periodto}}"/> --}}
                </form>
                <br>
                <form action="/summarytotalnumberofdropped/filter" method="get">
                    {{-- <input type="hidden" name="selectedschoolyear" value="{{$selectedschoolyear}}"/>
                    <label>Period</label>
                    <input type="text" name="selectedperiod" class="form-control form-control-sm p-1" id="selectedperiod" value="{{$periodfrom}} - {{$periodto}}"> --}}
                </form>
                <br>
                <form action="/summarytotalnumberofdropped/print" method="get" target="_blank">
                    {{-- <input type="hidden" name="selectedschoolyear" value="{{$selectedschoolyear}}"/>
                    <input type="hidden" name="selectedperiod" value="{{$periodfrom}} - {{$periodto}}"/> --}}
                    <button type="submit" class="btn btn-block btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Students</th>
                            <th>Gender</th>
                        </tr>
                    </thead>
                    <tbody class="studentscontainer text-uppercase">
                        {{-- @if(count($droppedstudents) > 0)
                            @foreach($droppedstudents as $droppedstudent)
                                <tr>
                                    <td>
                                        {{$droppedstudent->lastname.', '}} {{$droppedstudent->firstname}} {{$droppedstudent->middlename[0].'.'}} {{$droppedstudent->suffix}}
                                    </td>
                                    <td>
                                        {{$droppedstudent->gender}}
                                    </td>
                                    <td>
                                        {{$droppedstudent->levelname}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>

    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });

        $('#selectedperiod').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        })
    });

    $(document).on('change','select[ name="selectedschoolyear"]', function(){
        $(this).closest('form').submit();
    });

    $(document).on('change','input[ name="selectedperiod"]', function(){
        $(this).closest('form').submit();
    });
</script>
@endsection