

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
@extends('hr.layouts.app')
@section('content')
<style>
    .mobile{
        display: none;
    }
    @media only screen and (max-width: 600px) {
        .mobile {
            display: block;
        }
        .web {
            display: none;
        }
    }
    .container {padding:20px;}
.popover {width:170px;max-width:170px;}
.popover-content h4 {
  color: #00A1FF;
}
.popover-content h4 small {
  color: black;
}
.popover-content button.btn-primary {
  color: #00A1FF;
  border-color:#00A1FF;
  background:white;
}

.popover-content button.btn-default {
  color: gray;
  border-color:gray;
}

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
          <!-- <h1>Standard Deductions Setup</h1> -->
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
        <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
        Bracketing</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Bracketing</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
<div class="row">
</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>

    $(function () {
        $("#example1").DataTable({
            // pageLength : 10,
            // lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
            "paging": false,
            dom: 'lifrtp' 
            // "dom": '<"toolbar">frtip'
        });
    });
  </script>
@endsection

