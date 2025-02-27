

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h3 class="page-title">Taxes</h3>
            <ul class="breadcrumb col-md-12">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Taxes</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Overtime</a>
            </div> --}}
        </div>
    </div>
</div>

@if(session()->has('messageApproved'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageApproved') }}
    </div>
@endif
@if(session()->has('messageDispproved'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageDispproved') }}
    </div>
@endif
<div class="card">
    {{-- <div class="card-header">
        <div class="float-right">
            <p class="text-muted"  >Settings
                <button class="btn btn-sm btn-light"  data-toggle="modal" data-target="#settings">
                    <i class="fa fa-cogs text-muted"></i>
                </button>
                <div class="modal fade" id="settings" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <form action="/overtime/overtimesettings" method="get">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Overtime Settings</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <span class="text-muted">Rate per hour</span>
                                    <br>
                                    &nbsp;
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-Success">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </p>
        </div>
    </div> --}}
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
            <div class="row">
                <div class="col-sm-12">
                    <table id="example1" style="table-layout: fixed;font-size: 12px" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Employee</th>
                                <th>OT Date</th>
                                <th style="width: 10%;">No. of hours</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Approved By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            
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
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
    })
  </script>
@endsection

