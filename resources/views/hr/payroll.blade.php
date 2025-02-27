

@extends('hr.layouts.app')
@section('content')
<style>
    table{
        font-size: 12px;;
    }
    table.table td h2.table-avatar {
    align-items: center;
    display: inline-flex;
    font-size: inherit;
    font-weight: 400;
    margin: 0;
    padding: 0;
    vertical-align: middle;
    white-space: nowrap;
}
.avatar {
    background-color: #aaa;
    border-radius: 50%;
    color: #fff;
    display: inline-block;
    font-weight: 500;
    height: 38px;
    line-height: 38px;
    margin: 0 10px 0 0;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    vertical-align: middle;
    width: 38px;
    position: relative;
    white-space: nowrap;
}
table.table td h2 span {
    color: #888;
    display: block;
    font-size: 12px;
    margin-top: 3px;
}
.avatar > img {
    border-radius: 50%;
    display: block;
    overflow: hidden;
    width: 100%;
}
img {
    vertical-align: middle;
    border-style: none;
}
* {
    box-sizing: border-box
} 

.container {
    /* background-color: #ddd; */
    padding: 10px;
    margin: 0 auto;
    max-width: 500px;
}

.button {
    /* background-color: #bbb; */
    display: block;
    margin: 10px 0;
    padding: 10px;
    width: 100%;
}
</style>
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h3 class="page-title">Payroll</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Payroll</li>
            </ul>
            {{-- <div class="col-md-2 float-right ml-auto">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Holiday</a>
            </div> --}}
        </div>
    </div>
</div>

@if(session()->has('messageAdded'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('messageAdded') }}
    </div>
@endif
@if(session()->has('messageExists'))
    <div class="alert alert-danger alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
        {{ session()->get('messageExists') }}
    </div>
@endif
@if(session()->has('messageAdded'))
<div class="alert alert-success alert-dismissible col-12">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Alert!</h5>
    {{ session()->get('messageAdded') }}
</div>
@endif
@if(session()->has('messageDeleted'))
<div class="alert alert-success alert-dismissible col-12">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-trash"></i> Alert!</h5>
    {{ session()->get('messageDeleted') }}
</div>
@endif
<div class="row">
    <div class="col-12">
    {{-- <div class="card">
        <div class="card-body"> --}}
        {{-- <div class="col-md-12"> --}}
        <label>Set Pay Date</label>
        <form action="/payroll/setpaydate" method="get">
        @if(count($payroll) == 0)
        <input type="date" name="date" class="form-control form-control-sm col-md-6" required/>
        <br>
        <button type="submit"class="btn btn-sm btn-success">Generate</button>
        @else
        <input type="date" name="date" class="form-control form-control-sm col-md-6" id="currentpaydate" value="{{$payroll[0]->payrolldate}}" required/>
        <br>
        <button type="button"class="btn btn-sm btn-secondary" id="generatepaydate" >Generate</button>
        @endif
        </form>
        {{-- </div>
    </div>
    </div> --}}
    <br>
    <br>
    
    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
        <div class="row">
            <div class="col-sm-12">
                {{-- <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Salary</th>
                            <th>Deductions</th>
                            <th>Payslip</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($employees)!=0)
                            @foreach($employees as $employee)
                                <tr>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="#" class="avatar">
                                                @if (File::exists($employee->employee_info->picurl))
                                                    <img src="{{ asset('uploads/' . $post->image) }}" alt="" />
                                                @else 
                                                    <img src="{{ asset('assets/images/avatars/256-512.png') }}" alt="" />
                                                @endif
                                            <a href="/payslip/{{Crypt::encrypt($employee->employee_info->id)}}">  {{$employee->employee_info->firstname}} {{$employee->employee_info->middlename}} {{$employee->employee_info->lastname}} {{$employee->employee_info->suffix}} <span>{{$employee->employee_info->utype}}</span></a>
                                        </h2>
                                    </td>
                                    <td>&#8369; <span class="salary">{{$employee->salary}}</span></td>
                                    <td>
                                        <form action="/employeesalary/deductions" method="get" name="editdeductions">
                                            <div class="form-group">
                                                <input type="hidden" name="employee_id" value="{{$employee->employee_info->id}}"/>
                                                <input type="hidden" name="status" value=""/>
                                                <input type="hidden" name="deductionid" value=""/>
                                                @php
                                                    $unique = 0;   
                                                @endphp
                                                @foreach($employee->deductions as $deduction)
                                                    @php
                                                        $unique+=1;   
                                                    @endphp
                                                    <div class="custom-control custom-checkbox">
                                                        @if($deduction->status == '1')
                                                            <input class="custom-control-input" name="deductions" value="{{$deduction->deductionid}}" type="checkbox" id="{{$employee->employee_info->id}}{{$unique}}" checked="">
                                                        @elseif($deduction->status == '0')
                                                            <input class="custom-control-input" name="deductions" value="{{$deduction->deductionid}}" type="checkbox" id="{{$employee->employee_info->id}}{{$unique}}">
                                                        @endif
                                                        <label for="{{$employee->employee_info->id}}{{$unique}}" class="custom-control-label">{{$deduction->description}}</label>
                                                        <br>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="/payslip/generate" method="get">
                                            <input type="hidden" name="empid" value="{{Crypt::encrypt($employee->employee_info->id)}}"/>
                                            <input type="hidden" name="netpay" value="{{$employee->salary}}"/>
                                            <div class="container bg-warning">
                                                <button class="btn button bg-warning" type="submit"><strong>Generate Slip</strong></button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table> --}}
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
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script>
    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        $('#reservation').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            }
        })
    });
   $(document).ready(function(){
       
    $('body').addClass('sidebar-collapse')
       $('.btnsaveedit').on('click', function(){
        //    console.log('asd');
        $(this).closest('form[name=saveedit]').submit();
       })
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
   })
  </script>
@endsection

