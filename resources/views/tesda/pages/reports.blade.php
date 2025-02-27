@extends('tesda.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
<div class="content bg-white" style="min-height:90vh">
    <!-- Content Header (Page header) -->
    <section class="content-header border-bottom pb-0">
      <div class="container-fluid">
        <div class="row mb-0 p-0">
          <div class="col-sm-8">
            <h3>Student Management</h3>
            <p style="font-size: 13px;" class="text-muted">Click the cards below to navigate through <b>"Reports"</b> to view and generate Reports.</p>
          </div>
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Reports Menu</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="main-content">
        <div class="container-fluid mt-3">

            <div class="row p-3">
                              <div class="container-fluid">

                <h6 class="text-muted">Reports</h6>
                                <div class="row mt-3">
                                    <!-- Card 1 -->
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card card-box">
                                            <div class="card-body d-flex align-items-top" onclick="window.location.href='/tesda/cor'" style="cursor: pointer;">
                                                <i class="fas fa-book fa-2x  mr-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Certificate of Registration</h6>
                                                    <small class="text-muted">View and Generate Student's Certificate of Registration.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card 2 -->
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card card-box">
                                            <div class="card-body d-flex align-items-top" onclick="window.location.href='/tesda/tor' " style="cursor: pointer;">
                                                <i class="fas fa-book fa-2x  mr-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Transcript of Records</h6>
                                                    <small class="text-muted">View and Generate Student's Transcript of Records.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card 3 -->
                                    <div class="col-md-4 col-lg-3">
                                        <div style="cursor: pointer;" class="card card-box" onclick="window.location.href='/tesda/attendanceSummary'">
                                            <div class="card-body d-flex align-items-top">
                                                <i class="fas fa-table fa-2x  mr-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Attendance Summary</h6>
                                                    <small class="text-muted">View and Generate Attendance Summary.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card 4 -->
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card card-box" onclick="window.location.href='/tesda/enrollmentSummary'"  style="cursor: pointer;">
                                            <div class="card-body d-flex align-items-top">
                                                <i class="fas fa-layer-group fa-2x  mr-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Enrollment Summary</h6>
                                                    <small class="text-muted">
                                                        View and Generate Enrollment Summary.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card 5 -->
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card card-box" onclick="window.location.href='/tesda/applicationForGraduation'"  style="cursor: pointer;">
                                            <div class="card-body d-flex align-items-top">
                                                <i class="fas fa-layer-group fa-2x  mr-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Application for Graduation</h6>
                                                    <small class="text-muted">
                                                        View and Generate Application for Graduation.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Card 5 -->
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card card-box" onclick="window.location.href='/tesda/certifications'"  style="cursor: pointer;">
                                            <div class="card-body d-flex align-items-top">
                                                <i class="fas fa-file fa-2x  mr-3"></i>
                                                <div>
                                                    <h6 class="mb-0">Certifications</h6>
                                                    <small class="text-muted">
                                                        Manage and Print Certifications.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


    </section>
    <!-- /.content -->
  </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

<style>
    .card-box {
            transition: transform 0.2s, box-shadow 0.2s;
            height: 130px;
        }

        .card-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
</style>
    <script>
    </script>
@endsection
