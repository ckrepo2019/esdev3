

@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
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
            <div class="col-md-4">
                <label>Payroll Period</label>
                <div class="form-group" style="" id="earningselect2">
                    <select class="form-control form-control-sm select2" id="payrollid"></select>
                </div>
                {{-- <select class="form-control" id="payrollid">
                    @foreach($payrollperiods as $payrollperiod)
                        <option value="{{$payrollperiod->id}}" @if($payrollperiod->status == 1) selected @endif>{{date('M d, Y',strtotime($payrollperiod->datefrom))}} - {{date('M d, Y',strtotime($payrollperiod->dateto))}}</option>
                    @endforeach
                </select> --}}
            </div>
            <div class="col-md-3">
                <label>Department</label>
                <div class="form-group" style="" id="earningselect2">
                    <select class="form-control form-control-sm select2" id="select-department"></select>
                </div>
            </div>
            <div class="col-md-5 text-right">
                <label for="">TOTAL NET SALARY THIS PAYROLL PERIOD</label><br>
                <span style="font-size: 20px;"><b><a href="javascript:void(0)" class="text-primary" id="printallpayslip" style="font-size: 15px;"><i class="fas fa-print"></i> Print all Payslip</a> &nbsp;&nbsp;<a href="javascript:void(0)" class="text-primary" id="payrollhistory" style="font-size: 15px;"><i class="fas fa-print"></i> Print Summary</a> &nbsp;&nbsp;<i class="fas fa-ruble-sign"></i> <span class="text-success" id="netsalary"></span></b> </span>
            </div>
        </div>
    </div>
</div>
<div class="row" id="container-result">
    {{-- <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" id="container-result"></div> --}}
</div>
{{-- <div id="container-result">

</div> --}}
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
  <script>
      $(document).ready(function(){
        var departments = [];
        select_departments()
        payrollperiods()

        

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
        
        $(document).on('click', '#payrollhistory', function(){
            var payrollid =  $('#payrollid').val();
            window.open('/hr/payrollv3/export?exporttype=2&payrollid='+payrollid,'_blank');
        })

        $(document).on('click', '#printallpayslip', function(){
            var payrollid =  $('#payrollid').val();
            var depid =  $('#select-department').val();
            window.open('/hr/payrollv3/exportpayslipbydepartment?exporttype=2&payrollid='+payrollid+'&depid='+depid,'_blank');
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

        $(document).on('change', '#select-department', function(){
            loadempbydepartment()
        })

        $('#payrollid').on('select2:select', function (e) {
            // Check if an item is selected
            if (e.params.data) {
                $('#select-department').prop('disabled', false);
            } else {
                $('#select-department').prop('disabled', false);
            }
        });

        $('#payrollid').on('select2:unselect', function (e) {
            console.log('mmmmmmmmmmmmmmm');
            gethistory()
            $('#select-department').prop('disabled', true);
        });
          
        $('#select-department').on('select2:unselect', function (e) {
            gethistory()
        });
        $(document).on('click','.btn-print-slip', function(){
            var container = $(this).closest('.col-md-12');
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
        // Gian Additional
        function payrollperiods() {
            
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/loadpayrollperiods",
                success: function (data) {
                    payrollperiodss = data;

                    $('#payrollid').empty();
                    $('#payrollid').append('<option value="">Select Payroll Date</option>');

                    if (payrollperiodss.length > 0) {
                        // Assuming data is an array of objects with 'active' property
                        var activePayroll = data.find(item => item.active === true);
                        if (activePayroll) {
                            // If an active payroll is found, set it as selected
                            $('#payrollid').append('<option value="' + activePayroll.id + '" selected>' + activePayroll.text + '</option>');
                        } else {
                            // If no active payroll is found, set the first one as selected
                            $('#payrollid').append('<option value="' + data[0].id + '" selected>' + data[0].text + '</option>');
                        }
                    }

                    $('#payrollid').select2({
                        data: data,
                        allowClear: true,
                        placeholder: {
                            id: '',
                            text: 'Select Payroll Date',
                            template: function (data) {
                                // Customize the placeholder style here
                                return '<span style="font-size: 9px; font-weight: normal;">' + data.text + '</span>';
                            }
                        }
                    });

                    // Trigger the change event after loading payroll periods
                    $('#payrollid').trigger('change');
                    gethistory()
                }
            });
        }

        // Event listener for #payrollid change
        $('#payrollid').on('change', function () {
            var payrollid = $('#payrollid').val();
            gethistory();
            totalnetsalary(payrollid);
        });

        // function loaddepartmentwithcount(){
        //     var payrollid = $('#payrollid').val();
        //     $.ajax({
        //         type: "GET",
        //         url: "/hr/payrollv3/payrollhistorybydepartment",
        //         data: {
        //             payrollid : payrollid
        //         },
        //         success: function (data) {
        //             departments = data
        //             select_departments()
        //         }
        //     });
        // }

        function select_departments(){
            $.ajax({
            type: "GET",
            url: "/payrollclerk/employees/profile/select_departments",
            success: function (data) {
                    $('#select-department').empty()
                    $('#select-department').append('<option value="">Select Department</option>')
                    $('#select-department').select2({
                        data: data,
                        allowClear : true,
                        placeholder: 'Select Department'
                    });
                }
            });
        }

        function loadempbydepartment(){
            var depid = $('#select-department').val()
            var payrollid = $('#payrollid').val();

            $.ajax({
                type: "GET",
                url: "/hr/payrollv3/loademployeebydepartment",
                data: {
                    depid : depid,
                    payrollid : payrollid
                },
                success: function (data) {
                    console.log(data);
                    $('#container-result').empty()
                    $('#container-result').append(data)
                }
            });
        }
      })
  </script>
@endsection

