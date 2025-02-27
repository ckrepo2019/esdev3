@extends('principalsportal.layouts.app2')
@section('content')

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Reports</h1>
                <h6>Retentions</h6>
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
                <form action="/summaryofretentions/filter" method="get">
                    <label>Select School Year</label>
                    <select name="selectedschoolyear" class="form-control form-control-sm">
                        @foreach($schoolyears as $schoolyear)
                            <option value="{{$schoolyear->id}}" {{$schoolyear->id == $selectedschoolyear ? 'selected' : ''}} >{{$schoolyear->sydesc}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                </form>
                <br>
                <form action="/summaryofretentions/filter" method="get">
                    <label>Select Grade Level</label>
                    <select name="selectedgradelevel" class="form-control form-control-sm">
                        {{-- <option value="all" {{"all" == $selectedgradelevel ? 'selected' : ''}} >All</option> --}}
                        <option>Grade Level</option>
                        @foreach($gradelevels as $gradelevel)
                            <option value="{{$gradelevel->id}}" {{$gradelevel->id == $selectedgradelevel ? 'selected' : ''}} >{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="selectedschoolyear" value="{{$selectedschoolyear}}"/>
                </form>
                <br>
                <form action="/summaryofretentions/print" method="get" target="_blank">
                    <input type="hidden" name="selectedschoolyear" value="{{$selectedschoolyear}}"/>
                    <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                    {{-- <input type="hidden" name="selectedschoolyear" value="{{$selectedschoolyear}}"/>
                    <input type="hidden" name="selectedperiod" value="{{$periodfrom}} - {{$periodto}}"/> --}}
                    <button type="submit" class="btn btn-block btn-sm btn-primary"><i class="fa fa-print"></i> Print</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                @foreach($schoolyears as $schoolyear)
                    @if($schoolyear->id == $selectedschoolyear)
                        <strong>S.Y {{$schoolyear->sydesc}}</strong>
                    @endif
                @endforeach
                <br>
                @foreach($gradelevels as $gradelevel)
                    @if($gradelevel->id == $selectedgradelevel)
                        <strong>{{$gradelevel->levelname}}</strong>
                    @endif
                @endforeach
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>Students</th>
                            <th>School Years Repeated</th>
                        </tr>
                    </thead>
                    <tbody class="studentscontainer text-uppercase">
                        @if(count($repeaters) > 0)
                            @foreach($repeaters as $repeater)
                                <tr>
                                    <td>
                                        {{$repeater->lastname.', '}} {{$repeater->firstname}} {{$repeater->middlename[0].'.'}} {{$repeater->suffix}}
                                    </td>
                                    <td  class="text-center">
                                        @foreach($repeater->schoolyears as $repeaterschoolyear)
                                            {{$repeaterschoolyear->sydesc}}
                                            <br/>
                                        @endforeach
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

    $(document).on('change','select[name="selectedgradelevel"]', function(){
        $(this).closest('form').submit();
    });
</script>
@endsection