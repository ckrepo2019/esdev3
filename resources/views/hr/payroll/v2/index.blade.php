

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
/* [class*=icheck-]>input:first-child+input[type=hidden]+label::before, [class*=icheck-]>input:first-child+label::before{
    width: 18px;
    height: 18px;
} */
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
  {{-- <div class="row">
      <div class="col-md-5">
          <
      </div>
  </div> --}}
  <div class="row">
      <div class="col-md-5">
        <div class="card" style="border: none;">
            <div class="card-header p-2">
                <label>Payroll Period</label>
                
                <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() == 0)
                        <input type="text" class="form-control form-control-sm float-right" id="reservation">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-success" id="btn-payroll-dates-submit">
                                <i class="fa fa-share"></i>
                            </button>
                        </div>
                    @else
                        @if(DB::table('hr_payrollv2history')->where('payrollid', DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id)->where('deleted','0')->count() == 0)
                            <input type="text" class="form-control form-control-sm float-right" id="reservation" value="{{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->datefrom))}} - {{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->dateto))}}" data-id="{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-warning" id="btn-payroll-dates-submit">
                                    <i class="fa fa-share"></i>
                                </button>
                            </div>
                        @else
                            <input type="text" class="form-control form-control-sm float-right" id="reservation" readonly  data-id="{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}">
                            {{-- <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-default" id="btn-payroll-dates-submit">
                                    <i class="fa fa-share"></i>
                                </button>
                            </div> --}}
                        @endif
                    @endif
                  </div>
            </div>
        </div>
        <div class="card card-success" style="border: none;">
          <div class="card-header p-1 pl-2">
            <h3 class="card-title p-0"><input type="text" class="form-control m-0" id="input-filter-employee" placeholder="Search Employee"/></h3>

            <div class="card-tools p-2">
              <button type="button" class="btn btn-tool text-secondary" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body" style="max-height:500px; overflow:scroll;">
              @if(count($employees) == 0)
              @else
              <div class="row">
                  @foreach ($employees as $employee)
                  <div class="col-md-12 mb-2 div-each-employee" data-empid="{{$employee->id}}" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}} {{$employee->designation}}<" style="border-radius: 5px; border: 1px solid green; cursor: pointer">
                      <label>{{strtoupper($employee->lastname)}}</label>, {{ucwords(strtolower($employee->firstname))}}
                      <br/>
                      <sup class="text-bold text-muted">{{$employee->designation}}</sup>
                  </div>
                  @endforeach
              </div>
              @endif
          </div>
          <!-- /.card-body -->
        </div>
      </div>
      <div class="col-md-7">
          <div class="card" style="border: none;">
              <div class="card-header">
                  <small>Note: Please select an employee by clicking their name cards.</small>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                    </button>
                  </div>
              </div>
              <div class="card-body p-2" id="div-container-salaryinfo"  style="height:550px; overflow: scroll;">
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <i class="far fa-hand-point-left fa-4x text-muted"></i>
                        </div>                    
                    </div> --}}
              </div>
              <div class="card-footer p-1 pr-3">                  
                    <div class="row">
                        <div class="col-md-5">
                            <button type="button" class="btn btn-sm btn-primary" id="btn-compute">Compute</button>
                            <button type="button" class="btn btn-sm btn-primary" id="btn-addparticular"><i class="fa fa-plus"></i> Add</button>
                        </div>   
                        <div class="col-md-3">
                            <h6>Net Salary</h6>
                        </div>   
                        <div class="col-md-4 text-right">
                            <h4><span id="netsalary"></span><span id="newsalary"></span></h4>
                        </div>                     
                    </div>
              </div>
          </div>
      </div>
  </div>
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
        @if(DB::table('hr_payrollv2')->where('deleted','0')->count() == 0)
            toastr.warning('Please select payroll period!','Payroll')
        @else

        @endif
        $('#reservation').daterangepicker({
            
        locale: {
        format: 'M/DD/YYYY'
        }
        })
        $("#input-filter-employee").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".div-each-employee").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });
        $('#btn-payroll-dates-submit').on('click', function(){
            
            $.ajax({
                url: '/hr/payrollv2/payrolldates',
                type: 'get',
                data: {
                    action: 'updatepayrolldate',
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
        $('.div-each-employee').on('click', function(){
            $('.div-each-employee').removeClass('bg-success')
            $('.div-each-employee').removeClass('text-white')
            $('.div-each-employee').find('sup').removeClass('text-white')
            $('.div-each-employee').find('sup').addClass('text-muted')
            $(this).addClass('bg-success')
            $(this).addClass('text-white')
            $(this).find('sup').removeClass('text-muted')
            $(this).find('sup').addClass('text-white')
            var employeeid = $(this).attr('data-empid');
            $.ajax({
                url: '/hr/payrollv2/getsalaryinfo',
                type: 'get',
                data: {
                    payrollid    :   $('#reservation').attr('data-id'),
                    employeeid   :   employeeid
                },
                success: function(data){
                    $('#div-container-salaryinfo').empty()
                    $('#div-container-salaryinfo').append(data)
                    $('#btn-compute').attr('data-employeeid', employeeid)
                    // toastr.success('Payroll date range is set!','Payroll')
                    // window.location.reload();
                }
            })
        })
        function ReplaceNumberWithCommas(yourNumber) {
            //Seperates the components of the number
            var components = yourNumber.toString().split(".");
            //Comma-fies the first part
            components [0] = components [0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            //Combines the two sections
            return components.join(".");
        }

        $('#btn-compute').on('click', function(){
            var employeeid = $(this).attr('data-employeeid');
            var netsalary = parseFloat($('#netsalary').text().replace(',', ''))
            var deductamount = 0;
            var allowanceamount = 0;
            var particulars = [];
            
            $('.standarddeduction').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    if($(this).attr('data-deducttype') == 2)
                    {
                        if($(this).find('.standarddedductioncustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.standarddedductioncustom').val());
                            deductamount+=parseFloat($(this).find('.standarddedductioncustom').val());
                        }

                    }else{
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        deductamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-deductionid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        amountpaid       : amountpaid,
                        paymenttype       : $(this).attr('data-deducttype'),
                        particulartype       : 1
                    };
                    particulars.push(obj)
                }
            })
            $('.otherdeduction').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    if($(this).attr('data-deducttype') == 2)
                    {
                        if($(this).find('.otherdedductioncustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.otherdedductioncustom').val());
                            deductamount+=parseFloat($(this).find('.otherdedductioncustom').val());
                        }

                    }else{
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        deductamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-deductionid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        amountpaid       : amountpaid,
                        paymenttype       : $(this).attr('data-deducttype'),
                        particulartype       : 2
                    };
                    particulars.push(obj)
                }
            })
            $('.standardallowance').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    if($(this).attr('data-allowancetype') == 2)
                    {
                        if($(this).find('.standardallowancecustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.standardallowancecustom').val());
                            allowanceamount+=parseFloat($(this).find('.standardallowancecustom').val());
                        }

                    }else{
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        allowanceamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-deductionid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        amountpaid       : amountpaid,
                        paymenttype       : $(this).attr('data-deducttype'),
                        particulartype       : 3
                    };
                    particulars.push(obj)
                }
            })
            $('.otherallowance').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    if($(this).attr('data-allowancetype') == 2)
                    {
                        if($(this).find('.otherallowancecustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.otherallowancecustom').val());
                            allowanceamount+=parseFloat($(this).find('.otherallowancecustom').val());
                        }

                    }else{
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        allowanceamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-deductionid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        amountpaid       : amountpaid,
                        paymenttype       : $(this).attr('data-deducttype'),
                        particulartype       : 4
                    };
                    particulars.push(obj)
                }
            })
            netsalary += allowanceamount;
            
            var newsalary = parseFloat(netsalary-deductamount).toFixed(2);
            newsalary = ReplaceNumberWithCommas(newsalary);
            $('#netsalary').hide();
            $('#newsalary').text(newsalary)
            $.ajax({
                url: '/hr/payrollv2/configuration',
                type: 'get',
                data: {
                    payrollid    :   $('#reservation').attr('data-id'),
                    particulars: JSON.stringify(particulars),
                    netsalary    : $('#netsalary').text(),
                    dayspresent    : $('#dayspresent').text(),
                    daysabsent    : $('#daysabsent').text(),
                    basicsalary    : $('#basicsalary').text(),
                    salarytype    : $('#basicsalary').attr('data-salarytype'),
                    employeeid   :   employeeid
                },
                success: function(data){
                    
                    // toastr.success('Payroll date range is set!','Payroll')
                    // window.location.reload();
                }
            })
        })
    })
  
  </script>
@endsection

