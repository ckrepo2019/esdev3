

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
</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Payroll</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            PAYROLL</h4>
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

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                @if(!$payrollperiod){{--if there is no active status (status == 1)--}}
                    <input type="text" class="form-control float-right input-payrolldates" id="reservation" readonly>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-sm btn-success" id="btn-payroll-dates-submit" data-action="new">
                            <i class="fa fa-share"></i>
                        </button>
                    </div>
                @else
                    @if(DB::table('hr_payrollv2history')->where('payrollid', $payrollperiod->id)->where('deleted','0')->count() == 0)
                        {{---if there's no existing employee computation, make it editable--}}
                        <input type="text" class="form-control float-right input-payrolldates" id="reservation" readonly value="{{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->datefrom))}} - {{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->dateto))}}" data-id="{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-warning" id="btn-payroll-dates-submit" data-action="update">
                                <i class="fa fa-share"></i>
                            </button>
                        </div>
                        @else
                            {{---if there's an existing employee computation, payroll period can no longer be edited--}}
                            <input type="text" class="form-control float-right input-payrolldates" readonly  data-id="{{$payrollperiod->id}}" value="{{date('m/d/Y', strtotime($payrollperiod->datefrom))}} - {{date('m/d/Y', strtotime($payrollperiod->dateto))}}">
                        @endif
                @endif  
                </div>
                <small><em class="text-bold">Note: When a payslip is released, you can no longer update the payroll period.</em></small>
                @if($payrollperiod)
                <small><a style="cursor: pointer;" href="#" id="a-close-payroll-period"><u>Close this Payroll Period</u></a></small>
                @endif
            </div>
            <div class="col-md-8 text-right">
                @if($payrollperiod)
                    {{--exporting this summary will only be visible if there is an active payroll period --}}
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-default" id="btn-print-summary"><i class="fa fa-file-pdf"></i> Export Summary</button>
                @endif
            </div>


        </div>

        <div class="row">
            <div class="col-md-12">
                @if($payrollperiod)
                    {{--display the selection of employees if there is an existing active payroll period --}}
                    <div class="row mt-2">
                        <div class="col-md-6">                    
                            <select class="form-control select2" id="select-employee">
                                <option value="0">Select employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 align-self-end text-right">
                            <h5><span id="numofreleased">{{DB::table('hr_payrollv2history')->where('payrollid', $payrollperiod->id)->where('deleted','0')->where('released','1')->count()}}</span>/{{count($employees)}} Released</h5>
                        </div>
                    </div>
                @endif
            </div>
        </div>

       

    </div>
</div>

<div id="div-container-salaryinfo">
    {{-- diri mugawas ang payroll details ni selected employee --}}
</div>
@endsection

@section('footerscripts')
<script>
    $(document).ready(function(){
        var payrollperiod = @json($payrollperiod);
        
        $('#reservation').daterangepicker({
            locale: {
            format: 'M/DD/YYYY'
            }
        })

        $('.select2').select2({
            theme: 'bootstrap4'
        })


        // EVENTS

        // pag click sa payroll period date nga button (kanang naay arrow dha)
        $('#btn-payroll-dates-submit').on('click', function(){
            var dataaction = $(this).attr('data-action')
            $.ajax({
                url: '/hr/payrollv3/payrolldates',
                type: 'get',
                data: {
                    action: dataaction,
                    dates   :   $('#reservation').val()
                },
                success: function(data){
                    if(data == 1)
                    {
                        toastr.success('Payroll date range is set!','Payroll')
                        window.location.reload();
                    }else{
                        toastr.error('Something went wrong!','Payroll')
                    }
                }
            })
        })


        $('#select-employee').on('change', function(){
            var employeeid = $(this).val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            if(employeeid == 0)
            {
                $('#div-container-salaryinfo').empty();
            }else{
                $.ajax({
                    url: '/hr/payrollv3/getsalaryinfo',
                    type: 'get',
                    data: {
                        payrollid    :   $('.input-payrolldates').attr('data-id'),
                        employeeid   :   employeeid
                    },
                    success: function(data){
                        $('#div-container-salaryinfo').empty()
                        $('#div-container-salaryinfo').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        // toastr.success('Payroll date range is set!','Payroll')
                        // window.location.reload();
                    }
                })
            }
        })

        
        // FUNCTIONS
    })
</script>
@endsection