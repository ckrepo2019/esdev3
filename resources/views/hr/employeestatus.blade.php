

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h3 class="page-title">Employee Status</h3>
            <ul class="breadcrumb col-md-12">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Employee Status</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Overtime</a>
            </div> --}}
        </div>
    </div>
</div>
<div class="card" style="border: unset;">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <table class="table" style="table-layout: fixed;">
                    <tbody>
                        <tr>
                            <td class="p-0">
                                <div class="info-box">
                                  <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                        
                                  <div class="info-box-content">
                                    <span class="info-box-text">Casual</span>
                                    <span class="info-box-number">{{$employeescasual}}</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                            </td>
                            <td class="p-0">
                                <div class="info-box">
                                  <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                        
                                  <div class="info-box-content">
                                    <span class="info-box-text">Provisionary</span>
                                    <span class="info-box-number">{{$employeesprovisionary}}</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                            </td>
                            <td class="p-0">
                                <div class="info-box">
                                  <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                        
                                  <div class="info-box-content">
                                    <span class="info-box-text">Regular</span>
                                    <span class="info-box-number">{{$employeesregular}}</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                            </td>
                            <td class="p-0">
                                <div class="info-box">
                                  <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                        
                                  <div class="info-box-content">
                                    <span class="info-box-text">Part-time</span>
                                    <span class="info-box-number">{{$employeesparttime}}</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                            </td>
                            <td class="p-0">
                                <div class="info-box">
                                  <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                        
                                  <div class="info-box-content">
                                    <span class="info-box-text">Substitute</span>
                                    <span class="info-box-number">{{$employeessubstitute}}</span>
                                  </div>
                                  <!-- /.info-box-content -->
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
            <div class="row">
                <div class="col-sm-12">
                    <table id="example1" style="font-size: 12px" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date Hired</th>
                                <th></th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}}</td>
                                <td>{{$employee->datehiredmodified}}</td>
                                <td>{{$employee->updatedperiod}}</td>
                                <td>
                                    <form action="/employeestatus/{{Crypt::encrypt('update')}}" method="get" name="changeemploymentstatus">
                                        <input type="hidden" name="employeeid"  value="{{$employee->id}}"/>
                                        <select class="form-control form-control-sm" name="employmentstatus">
                                            <option value="1" {{'1' == $employee->employmentstatus ? 'selected' : ''}}>Casual</option>
                                            <option value="2" {{'2' == $employee->employmentstatus ? 'selected' : ''}}>Provisionary</option>
                                            <option value="3" {{'3' == $employee->employmentstatus ? 'selected' : ''}}>Regular</option>
                                            <option value="4" {{'4' == $employee->employmentstatus ? 'selected' : ''}}>Part-time</option>
                                            <option value="5" {{'5' == $employee->employmentstatus ? 'selected' : ''}}>Substitute</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
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
   $(document).on('change', 'select[name="employmentstatus"]', function(){
       $(this).closest('form[name=changeemploymentstatus]').submit();
   })
    $(document).ready(function(){
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        window.setTimeout(function () {
            $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        $(document).on('click', '.disapproved', function(){
            //    console.log($(this).closest('form[name=changestatus]'));
            $(this).prev('input').val($(this)[0].innerText);
            $(this).closest('form[name=changestatus]').submit();

        })
        $(document).on('click', '.approved', function(){
            //    console.log($(this).closest('form[name=changestatus]'));
            $(this).prev('input').val($(this)[0].innerText);
            $(this).closest('form[name=changestatus]').submit();

        })
   })
  </script>
@endsection

