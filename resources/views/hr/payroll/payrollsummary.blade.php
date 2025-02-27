@extends('hr.layouts.app')
@section('content')
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<style>
  .card{
    box-shadow: none !important;
    border: 1px solid #ddd;}
  }
</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Employees</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
          <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
          PAYROLL SUMMARY </h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">PAYROLL SUMMARY</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <div class="row mb-2">
      <div class="col-md-4">
          <label>Payroll Date</label>
          <select class="form-control" id="selectedpayrollid">
            @if(count($payrolldates)> 0)
                @foreach($payrolldates as $payrolldate)
                    <option value="{{$payrolldate->id}}"{{'1' == $payrolldate->status ? 'selected' : ''}}>{{$payrolldate->datefrom}} - {{$payrolldate->dateto}}</option>
                @endforeach
            @endif
          </select>
      </div>
      <div class="col-md-4">
        <label>Department</label>
        <select class="form-control text-uppercase" id="selecteddepartmentid">
          <option value="">All</option>
          @if(count($departments)> 0)
              @foreach($departments as $department)
                  <option value="{{$department->id}}">{{$department->department}}</option>
              @endforeach
          @endif
        </select>
      </div>
      <div class="col-md-4">
        <label>Employment Status</label>
        <select class="form-control text-uppercase" id="selectedemploymentstatusid">
          <option value="">All</option>
          <option value="1">Casual</option>
          <option value="2">Provisionary</option>
          <option value="3">Regular</option>
          <option value="4">Parttime</option>
          <option value="5">Substitute</option>
        </select>
      </div>
      <div class="col-md-12 mt-2">
        <label>Salary Type</label>
        <div class="form-group clearfix" id="selectedsalarytypeid">
          @foreach($basistypes as $basistype)
          <div class="icheck-primary d-inline mr-2">
            <input type="radio" id="basistype{{$basistype->id}}" name="basistype" @if($basistype->id == 4) checked @endif value="{{$basistype->id}}">
            <label for="basistype{{$basistype->id}}">
              {{$basistype->type}} ({{count(collect($employees->where('salarytypeid',$basistype->id)))}})
            </label>
          </div>
          @endforeach
        </div>
      </div>
  </div>
  <div class="row mb-2">
    <div class="col-md-8">
      <button type="button" class="btn btn-sm btn-info">Employees ({{count($employees)}})</button>
      <button type="button" class="btn btn-sm btn-warning">Unset basic salary info ({{count(collect($employees->where('salaryinfo',1)))}})</button>
      <button type="button" class="btn btn-sm btn-info">Released ({{count(collect($employees->where('salaryinfo',1)))}})</button>
      <button type="button" class="btn btn-sm btn-info">Configured ({{count(collect($employees->where('configured',1)))}})</button>
    </div>
    <div class="col-md-4 text-right">
      {{-- <button type="button" class="btn btn-primary btn-sm" id="generate"><i class="fa fa-sync"></i> Generate</button> --}}
      <button type="button" class="btn btn-primary btn-sm" id="generate"><i class="fa fa-sync"></i> Generate</button>
      @if(count($payrollsetup)>0)

      <button type="button" class="btn btn-primary btn-sm" id="btn-show-setup"><i class="fa fa-list"></i> Setup</button>
      @else
      <button type="button" class="btn btn-primary btn-sm" id="btn-add-setup"><i class="fa fa-list"></i> Add Setup</button>
      @endif

    </div>
  </div>
  <div id="employeescontainer"></div>

  <div class="modal fade" id="show-add-setup" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row" id="setup-container">
              
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" id="btn-create-setup" class="btn btn-primary">Create</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <div class="modal fade" id="show-setup" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              {{-- <em class="text-danger">Note: </em> --}}
            </div>
          </div>
            <div class="row" id="show-setup-container">
              
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" id="btn-delete-setup" class="btn btn-danger">Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script>
  $('body').addClass('sidebar-collapse')
  var selectedpayrollid             = $('#selectedpayrollid').val();
  var selecteddepartmentid          = null;
  var selectedemploymentstatusid    = null;
  var selectedsalarytypeid          = 4;

  $('#selectedpayrollid').on('change', function(){
    selectedpayrollid = $(this).val()
  });
  $('#selecteddepartmentid').on('change', function(){
    selecteddepartmentid = $(this).val()
  });
  $('#selectedemploymentstatusid').on('change', function(){
    selectedemploymentstatusid = $(this).val()
  });
  $('input[name="basistype"]').on('click', function(){
    selectedsalarytypeid = $(this).val()
  });

  $('#generate').on('click', function(){
    Swal.fire({
        title: 'Fetching data',
        onBeforeOpen: () => {
            Swal.showLoading()
        },
        allowOutsideClick: false
    })  
    $.ajax({
      url: '/hr/payrollsummary/filter',
      type: 'GET',
      data: {
        selectedpayrollid           : selectedpayrollid,
        selecteddepartmentid        : selecteddepartmentid,
        selectedemploymentstatusid  : selectedemploymentstatusid,
        selectedsalarytypeid        : selectedsalarytypeid
      }, success:function(data){
        $('#employeescontainer').empty()
        $('#employeescontainer').append(data)
        $('.paginate_button').addClass('btn btn-sm btn-default')
        $(".swal2-container").remove();
        $('body').removeClass('swal2-shown')
        $('body').removeClass('swal2-height-auto')
      }
    })
    // console.log(selectedpayrollid)
    // console.log(selecteddepartmentid)
    // console.log(selectedemploymentstatusid)
    // console.log(selectedsalarytypeid)
  })
  $('#btn-add-setup').on('click', function(){
    $('#show-add-setup').modal('show');
    $.ajax({
      url: '/hr/payrollsummary/setup',
      type: 'GET',
      data: {
        selectedpayrollid           : selectedpayrollid
      }, success:function(data){
        $('#setup-container').empty()
        $('#setup-container').append(data)
      }
    })
  })
  $('#btn-create-setup').on('click', function(){
    var particularsid = [];
    var particularsdesc = [];
    var particularstype = [];
    $('.particulars:checked').each(function(){
      particularsid.push($(this).attr('data-id'));
      particularsdesc.push($(this).attr('data-desc'));
      particularstype.push($(this).attr('data-type'));
    })

    $.ajax({
      url: '/hr/payrollsummary/setup-create',
      type: 'GET',
      data: {
      particularsid     : particularsid,
      particularsdesc   : particularsdesc,
      particularstype   : particularstype
      }, complete:function(data){
        window.location.reload()
      }
    })

  })
  $('#btn-show-setup').on('click', function(){
    $('#show-setup').modal('show')
    $.ajax({
      url: '/hr/payrollsummary/setup-show',
      type: 'GET',
      data: {
        selectedpayrollid           : selectedpayrollid
      }, success:function(data){
        $('#show-setup-container').empty()
        $('#show-setup-container').append(data)
      }
    })
  })
  $('#btn-delete-setup').on('click', function(){
    $.ajax({
      url: '/hr/payrollsummary/setup-delete',
      type: 'GET',
      data: {
        selectedpayrollid           : selectedpayrollid
      }, complete:function(data){
        window.location.reload()
      }
    })
  })
</script>
@endsection