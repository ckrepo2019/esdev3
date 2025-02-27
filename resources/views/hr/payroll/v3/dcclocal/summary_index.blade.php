

@extends('hr.layouts.app')
@section('content')
<style>
    .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
td, th{
    padding: 1px !important;
}
.info-box{
    min-height: unset;
}
        
        .select2-container .select2-selection--single {
            height: 40px !important;
        }
/* [class*=icheck-]>input:first-child+input[type=hidden]+label::before, [class*=icheck-]>input:first-child+label::before{
    width: 18px;
    height: 18px;
} */
.alert-primary {
    color: #004085;
    background-color: #cce5ff;
    border-color: #b8daff;
}
.alert-secondary {
    color: #383d41;
    background-color: #e2e3e5;
    border-color: #d6d8db;
}
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeeba;
}
</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Payroll</h1> -->
          <h4>PAYROLL HISTORY</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Payroll</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
</section>
<div class="card" style="border: 1px solid #ddd; box-shadow: 0 0 1px rgb(0 0 0 / 13%) !important;">
    <div class="card-header">
        <div class="row">
            <div class="col-md-5">
                <label>Payroll Period</label>
                <select class="form-control" id="payrollid">
                    @foreach($payrollperiods as $payrollperiod)
                        <option value="{{$payrollperiod->id}}" @if($payrollperiod->status == 1) selected @endif>{{date('M d, Y',strtotime($payrollperiod->datefrom))}} - {{date('M d, Y',strtotime($payrollperiod->dateto))}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-7 text-right">
                <label for="">TOTAL NET SALARY THIS PAYROLL PERIOD</label><br>
                <span style="font-size: 20px;"><i class="fas fa-ruble-sign"></i> <b><span class="text-success" id="netsalary"></span></b> &nbsp;&nbsp;<a href="javascript:void(0)" class="text-primary" id="payrollhistory" style="font-size: 15px;"><i class="fas fa-print"></i> Print Summary</a></span>
            </div>
        </div>
    </div>
</div>
<div class="row" id="container-result">
    {{-- <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" id="container-result"></div> --}}
</div>
{{-- <div id="container-result">

</div> --}}
  <!-- Bootstrap 4 -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- SweetAlert2 -->
  <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
  <!-- ChartJS -->
  <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
  <!-- DataTables -->
  <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
  <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
  <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
  <!-- Toastr -->
  <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  <!-- date-range-picker -->
  <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <script>
      $(document).ready(function(){
        
        gethistory()

        function gethistory(){
            var payrollid =  $('#payrollid').val();
            totalnetsalary(payrollid)
            
            $.ajax({
                url: '/hr/payrollv3/payrollhistory',
                type: 'get',
                data: {
                    action: 'gethistory',
                    payrollid   :   $('#payrollid').val()
                },
                success: function(data){
                    $('#container-result').empty()
                    $('#container-result').append(data)
                }
            })
        }
        
        $('#payrollid').on('change', function(){
            var payrollid =  $('#payrollid').val();
            gethistory()
            totalnetsalary(payrollid)
        })

        $(document).on('click', '#payrollhistory', function(){
            var payrollid =  $('#payrollid').val();
            window.open('/hr/payrollv3/export?exporttype=2&payrollid='+payrollid,'_blank');
        })

        $(document).on('click', '.btn-getdetails', function(){
            var historyid = $(this).attr('data-id');
            
            $.ajax({
                url: '/hr/payrollv3/payrollhistory',
                type: 'get',
                data: {
                    action: 'getdetails',
                    historyid   :   historyid
                },
                success: function(data){
                    $('#container-id-'+historyid).empty()
                    $('#container-id-'+historyid).append(data)
                }
            })
        })
          
        $(document).on('click','.btn-print-slip', function(){
            var container = $(this).closest('.col-md-12');

            // Retrieve the employee ID and payroll ID from the container
            var empid = container.find('input[data-id]').data('id');
            var payrollid =  $('#payrollid').val();

            window.open('/hr/payrollv3/export?exporttype=1&payrollid='+payrollid+'&employeeid='+empid,'_blank')
        })

        
        function totalnetsalary(payrollid){
            $.ajax({
                type: "GET",
                url: "/hr/payrollv3/loadnetsalary",
                data: {
                    payrollid : payrollid
                },
                success: function (data) {
                    if (data) {
                        $('#netsalary').text(data);
                    } else {
                        $('#netsalary').text(0);
                    }
                }
            });
        }

        
      })
  </script>
@endsection

