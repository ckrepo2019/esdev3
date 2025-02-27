@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Employees</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
          <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
          EMPLOYEEES </h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Employees</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  @php
  $refid = DB::table('usertype')
      ->where('id', Session::get('currentPortal'))
      ->first()->refid;
  @endphp

  <div class="card shadow" style="border: none;">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table width="100%" class="table table-bordered table-sm table-head-fixed " id="employee_datatable"  style="font-size: 20px;">
            <thead>
                <tr>
                    <th width="15%"></th>
                    <th width="70%" style="padding-left: 30px;">Employee Information</th>
                    <th width="15%" class="text-center">View</th>
                </tr>
            </thead>
            <tbody class="teachersched" style="font-size: 17px;">
              @foreach(collect($employees)->where('isactive','1')->values() as $employee)
                <tr>
                  <td class="text-center align-middle">
                    @php
                      $number = rand(1,3);
                      if(strtoupper($employee->gender) == 'FEMALE'){
                        $avatar = 'avatar/T(F) '.$number.'.png';
                      }
                      else{
                        $avatar = 'avatar/T(M) '.$number.'.png';
                      }
                    @endphp
                    <img class="img-circle elevation-2" src="{{asset($employee->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" 
                    onerror="this.onerror = null, this.src='{{asset($avatar)}}'"
                    alt="User Avatar" style="width: 70px !important; height: 70px;">
                  </td>
          
                  <td class="align-middle" style="padding-left: 30px;">
                    <h5 style="line-height: 1 !important;">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}} {{ucwords(strtolower($employee->suffix))}} <br> <small style="font-size: 14px; color: rgb(70, 70, 70);">{{$employee->tid}}</small> <br><small style="font-size: 14px;">{{$employee->utype}}</small></h5>
                    
                  </td>

                  <td class="align-middle text-center" style="">
                    @if($refid == 26)
                    <div class="col-md-12 p-1">
                        <a type="button" href="/hr/employees/profile/index?employeeid={{$employee->id}}" class="btn btn-primary text-light">View Profile</a>
                    </div>
                    @else
                    <div class="col-md-3 p-1">
                        <button type="button" class="btn btn-block btn-sm text-center btn-default text-success p-1" data-toggle="modal" data-target="#modal-status-{{$employee->id}}" ><i class="fa fa-check-circle m-0"></i></button>
                    </div>
                    <div class="col-md-9 p-1">
                        <a type="button" href="/hr/employees/profile/index?employeeid={{$employee->id}}" class="btn btn-primary text-light">View Profile</a>
                    </div>
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

  <div class="modal fade" id="modal-inactive">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Inactive Employees</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">                    
                @foreach(collect($employees)->where('isactive','0')->values() as $inactiveemp)
                <div class="col-12">
                    <div class="info-box">
                      <span class="info-box-icon bg-info">
                        <img class="img-circle elevation-2" src="{{asset($inactiveemp->picurl)}}" 
                        onerror="this.onerror = null, this.src='{{asset($avatar)}}'"
                        alt="User Avatar">
                      </span>
        
                      <div class="info-box-content">
                        <form action="/hr/employees/profile/changestatus" method="GET">
                          @csrf
                        <input type="hidden" name="status" value="1"/>
                        <input type="hidden" name="id" value="{{$inactiveemp->id}}"/>
                        <span class="info-box-text"><h6>{{ucwords(strtolower($inactiveemp->lastname))}}, {{ucwords(strtolower($inactiveemp->firstname))}} {{ucwords(strtolower($inactiveemp->suffix))}}</h6></span>
                        <span class="info-box-number">{{$inactiveemp->utype}}</span>
                        <span class="info-box-number text-right"><button type="submit" class="btn btn-success btn-sm">Mark Active</button></span>
                        </form>
                      </div>
                    </div>
                  </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
<script>
$(document).ready(function(){
  $('#employee_datatable').dataTable({
    destroy: true,
    lengthChange: false,
    scrollX: false,
    autoWidth: false,
    order: false,
  });

  var label_text = $($('#employee_datatable_wrapper')[0].children[0])[0].children[0]
  $(label_text)[0].innerHTML = '<div class="row"> '+
          '<div class="col-md-4 text-left"> '+
            '<a href="/hr/employees/index?action=export&exporttype=pdf" class="btn btn-primary btn-sm" style="color: white;" target="_blank"><i class="fa fa-file-pdf"></i> Export to PDF</a> '+
            '<a href="/hr/employees/index?action=export&exporttype=excel" class="btn btn-primary btn-sm" style="color: white;" target="_blank"><i class="fa fa-file-excel"></i> Export to Excel</a> '+
          '</div> '+
            '<div class="col-md-8 text-left"> '+
                '<button type="button" class="btn btn-sm btn-default">Total No. of Employees <span class="right badge badge-warning">{{count($employees)}}</span></button> '+
                '<button type="button" class="btn btn-sm btn-default">Active <span class="right badge badge-warning">{{collect($employees)->where('isactive','1')->count()}}</span></button> '+
                '<button type="button" class="btn btn-sm btn-default" @if(collect($employees)->where('isactive','0')->count()>0) data-toggle="modal" data-target="#modal-inactive" @endif>Inactive <span class="right badge badge-warning">{{collect($employees)->where('isactive','0')->count()}}</span></button> '+
                '@if($refid != 26) '+
                '<a href="/hr/employees/addnewemployee/index" class="btn btn-primary" style="color: white;"><i class="fa fa-plus"></i> Add New Employee</a> '+
                '@endif '+
              '</div> '+
          '</div>' 


});
</script>
<script>
    $(document).ready(function(){
        
    })
</script>
@endsection
