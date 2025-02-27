@extends('registrar.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <!-- <h1>Standard Deductions Setup</h1> -->
                <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
                GOOD MORAL CERTIFICATE</h4>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Good Moral Certificate</li>
                </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="table-layout: fixed;font-size: 12px" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Grade Level</th>
                                    <th width="20%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>
                                            {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                        </td>
                                        <td>
                                            {{$student->levelname}}
                                        </td>
                                        <td>
                                            {{-- <input type="hidden" name="studid" --}}
                                            @if($student->promotionstatus == 1)
                                                <button type="submit" class="btn btn-sm btn-block btn-info"><i class="fa fa-print"></i> Print</button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-block btn-secondary">Incomplete</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
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
                paging: false,
                
                // pageLength : 10,
                // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
                "dom": "lifrtp"
            });
            
        })
    </script>
@endsection