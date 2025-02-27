

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
        <div class="info-box" style="border: none !important;">
            <div class="info-box-content">
                <div class="row">
                    <div class="col-md-4">
                        <label>Payroll Period</label>
                        
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                              </span>
                            </div>
                            @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() == 0)
                                <input type="text" class="form-control form-control-sm float-right input-payrolldates" id="reservation" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-success" id="btn-payroll-dates-submit" data-action="new">
                                        <i class="fa fa-share"></i>
                                    </button>
                                </div>
                            @else
                                @if(DB::table('hr_payrollv2history')->where('payrollid', DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id)->where('deleted','0')->count() == 0)
                                    <input type="text" class="form-control form-control-sm float-right input-payrolldates" id="reservation" readonly value="{{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->datefrom))}} - {{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->dateto))}}" data-id="{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-sm btn-warning" id="btn-payroll-dates-submit" data-action="update">
                                            <i class="fa fa-share"></i>
                                        </button>
                                    </div>
                                @else
                                    <input type="text" class="form-control form-control-sm float-right input-payrolldates" readonly  data-id="{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}" value="{{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->datefrom))}} - {{date('m/d/Y', strtotime(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->dateto))}}">
                                    {{-- <div class="input-group-append">
                                        <button type="button" class="btn btn-sm btn-default" id="btn-payroll-dates-submit">
                                            <i class="fa fa-share"></i>
                                        </button>
                                    </div> --}}
                                @endif
                            @endif
                          </div>
                          @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                          <small><a style="cursor: pointer;" href="#" id="a-close-payroll-period"><u>Close this Payroll Period</u></a></small>
                          @endif
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label><br/>
                        @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                                  <h5><span id="numofreleased">{{DB::table('hr_payrollv2history')->where('payrollid',DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id)->where('deleted','0')->where('released','1')->count()}}</span>/{{count($employees)}} Released</h5>
                        @endif
                    </div>
                    <div class="col-md-4 text-right">
                        @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-sm btn-default" id="btn-print-summary"><i class="fa fa-file-pdf"></i> Export Summary</button>
                        @endif
                    </div>
                </div>
                {{-- </div>
                <div class="card-body p-1"> --}}
                            @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() > 0)
                <div class="row mt-2">
                    <div class="col-md-6">                    
                        <select class="form-control select2" id="select-employee">
                            <option value="0">Select employee</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div id="div-container-salaryinfo">
            
        </div>
        {{-- <div class="card card-success" style="border: none;"@if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() == 0) hidden @endif>
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
                      <label class="m-0">{{strtoupper($employee->lastname)}}</label>, {{ucwords(strtolower($employee->firstname))}}
                      <p class="text-bold text-muted p-0 m-0"><sup>{{$employee->designation}}</sup></p>
                  </div>
                  @endforeach
              </div>
              @endif
          </div>
          <!-- /.card-body -->
        </div> --}}
      {{-- <div class="col-md-7" @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() == 0) hidden @endif>
          <div class="card h-100" style="border: none;">
              <div class="card-header">
                  <small>Note: Please select an employee by clicking their name cards.</small>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                    </button>
                  </div>
              </div>
              <div class="card-body p-2" id="div-container-salaryinfo"  style="height:570px; overflow: scroll;">
                  
              </div>
              <div class="card-footer p-1 pr-3" id="card-footer-computation">                  
                <div class="row">
                    <div class="col-md-8">
                        <h4>&nbsp;</h4>
                        <button type="button" class="btn btn-sm btn-primary btn-compute" data-id="0" hidden>Compute</button>
                        <button type="button" class="btn btn-sm btn-primary btn-compute" data-id="1" id="btn-save-computation">Save Computation</button>
                        <button type="button" class="btn btn-sm btn-warning" id="btn-release-payslip">Release Pay Slip</button>
                        <button type="button" class="btn btn-sm btn-info" data-id="1" id="btn-printslip">Print Payslip</button>
                    </div>   
                    <div class="col-md-4 text-right">
                        <h4><span id="netsalary"></span><span id="newsalary"></span></h4>
                        <h6>Net Salary</h6>
                    </div>                     
                </div>
              </div>
          </div>
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
      
      $(function () {
            $('.select2').select2()
            $('#example2').DataTable({
              "paging": false,
              "lengthChange": true,
              "searching": true,
              "ordering": false,
              "info": true,
              "autoWidth": false,
              "responsive": true,
            });
        });
    $(document).ready(function(){
        @if(DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->count() >0)
            $('#a-close-payroll-period').on('click', function(){
                
                var payrollid = '{{DB::table('hr_payrollv2')->where('deleted','0')->where('status','1')->first()->id}}';
                Swal.fire({
                title: 'Are you sure?',
                html: "Once closed, you won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Close Payroll Period',
                cancelButtonText: 'Cancel',
                reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        payrollid
                        $.ajax({
                            url: '/hr/payrollv3/payrolldates',
                            type: 'get',
                            data: {
                                action: 'closepayroll',
                                payrollid   :   payrollid
                            },
                            success: function(data){
                                if(data == 1)
                                {
                                    window.location.reload();
                                }else{
                                    toastr.error('Something went wrong!','Payroll')
                                }
                            }
                        })
                    }
                })
            })
        @endif
        $('#card-footer-computation').hide()
        @if(DB::table('hr_payrollv2')->where('status','1')->where('deleted','0')->count() == 0)
            toastr.warning('Please select payroll period!','Payroll')
        @else

        @endif
        $('#reservation').daterangepicker({
            
        locale: {
        format: 'M/DD/YYYY'
        }
        })
        // $("#input-filter-employee").on("keyup", function() {
        //     var input = $(this).val().toUpperCase();
        //     var visibleCards = 0;
        //     var hiddenCards = 0;

        //     $(".container").append($("<div class='card-group card-group-filter'></div>"));


        //     $(".div-each-employee").each(function() {
        //         if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

        //         $(".card-group.card-group-filter:first-of-type").append($(this));
        //         $(this).hide();
        //         hiddenCards++;

        //         } else {

        //         $(".card-group.card-group-filter:last-of-type").prepend($(this));
        //         $(this).show();
        //         visibleCards++;

        //         if (((visibleCards % 4) == 0)) {
        //             $(".container").append($("<div class='card-group card-group-filter'></div>"));
        //         }
        //         }
        //     });

        // });
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
                    $('.btn-compute[data-id="1"]').attr('data-employeeid', employeeid)
                    $('input[type="radio"]:checked').each(function(){
                        $(this).click();
                    })
                    $('.btn-compute[data-id="0"]').click()
                    // toastr.success('Payroll date range is set!','Payroll')
                    // window.location.reload();
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
                url: '/hr/payrollv3/getsalaryinfo',
                type: 'get',
                data: {
                    payrollid    :   $('.input-payrolldates').attr('data-id'),
                    employeeid   :   employeeid
                },
                success: function(data){
                    $('#div-container-salaryinfo').empty()
                    $('#div-container-salaryinfo').append(data)
                    $('.btn-compute[data-id="1"]').attr('data-employeeid', employeeid)
                    $('input[type="radio"]:checked').each(function(){
                        $(this).click();
                    })
                    $('.btn-compute[data-id="0"]').click()
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
        $(document).on('click','.btn-compute', function(){
            var employeeid = $('#select-employee').val();
            var basicpay = parseFloat($('#td-basicpay-amount').attr('data-amount').replace(',', ''));
            var netsalary = parseFloat($('#netsalary').text().replace(',', ''))
            var overtimepay = parseFloat($('#td-overtimepay').attr('data-amount')); 
            
            // console.log(overtimepay)
            // var overtimepay = parseFloat($('#td-overtimepay').attr('data-amount').replace(',', '')); 
            var overtimeids = JSON.stringify($('#td-overtimepay').attr('data-ids')); 
            var tardinessamount = parseFloat($('#tardinessamount').text().replace(',', ''))    
            var lateminutes =      $('#countlateminutes').attr('data-value');          
            var undertimeminutes =      $('#undertimeminutes').attr('data-value');          
            var totalworkedhours =      $('#totalworkedhours').attr('data-value');          
            var amountperday =      $('#amountperday').attr('data-value');          
            var amountabsent =      parseFloat($('#amountabsent').attr('data-value').replace(',', ''));          
            var amountlate =      parseFloat($('#amountlate').attr('data-value').replace(',', ''));          
            var deductamount = 0;
            var allowanceamount = 0;
            var particulars = [];
            var totalearnings = basicpay;
            if(overtimepay>0)
            {
                var overtimepay = parseFloat($('#td-overtimepay').attr('data-amount').replace(',', '')); 
                totalearnings+=overtimepay;
            }
            var totaldeductions = (amountabsent+amountlate);

            
            $('.standarddeduction').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    var deducttype = 0;
                    if($(this).attr('data-deducttype') == 2)
                    {
                        deducttype = 2;
                        if($(this).find('.standarddedductioncustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.standarddeddsuctioncustom').val());
                            deductamount+=parseFloat($(this).find('.standarddedductioncustom').val());
                        }

                    }else{
                        deducttype = $(this).attr('data-deducttype');
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        deductamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-deductionid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        amountpaid       : amountpaid,
                        paymenttype       : deducttype,
                        particulartype       : 1
                    };
                    totaldeductions+=amountpaid;
                    particulars.push(obj)
                }
            })
            $('.otherdeduction').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    var deducttype = 0;
                    // if($(this).attr('data-deducttype') == 1)
                    // {
                    //     deducttype = $(this).attr('data-deducttype');
                    //     // deducttype = 1;
                    //     // if($(this).find('.otherdedductioncustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                    //     // {
                    //     //     amountpaid=parseFloat($(this).find('.otherdedductioncustom').val());
                    //     //     deductamount+=parseFloat($(this).find('.otherdedductioncustom').val());
                    //     // }

                    // }else{
                    //     deducttype = $(this).attr('data-deducttype');
                    // }
                        amountpaid=parseFloat($(this).attr('data-amount').replace(/,/g, ''));
                        deductamount+=parseFloat($(this).attr('data-amount').replace(/,/g, ''));
                    obj = {
                        particularid      : $(this).attr('data-deductionid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount').replace(/,/g, ''),
                        amountpaid       : amountpaid,
                        paymenttype       : $(this).attr('data-deducttype'),
                        particulartype       : 2
                    };
                    totaldeductions+=amountpaid;
                    particulars.push(obj)
                }
            })
            $('.standardallowance').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    var allowancetype = 0;
                    if($(this).attr('data-allowancetype') == 2)
                    {
                        if($(this).find('.standardallowancecustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.standardallowancecustom').val());
                            allowanceamount+=parseFloat($(this).find('.standardallowancecustom').val());
                        }

                    }else{
                        allowancetype = $(this).attr('data-allowancetype');
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        allowanceamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-allowanceid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        amountpaid       : amountpaid,
                        paymenttype       : allowancetype,
                        particulartype       : 3
                    };
                    totalearnings += amountpaid;
                    particulars.push(obj)
                }
            })
            $('.otherallowance').each(function(){
                if($(this).find('input[type="radio"]').is(':checked'))
                {
                    var amountpaid = 0;
                    var allowancetype = 0;
                    if($(this).attr('data-allowancetype') == 2)
                    {
                        if($(this).find('.otherallowancecustom').val().replace(/^\s+|\s+$/g, "").length > 0)
                        {
                            amountpaid=parseFloat($(this).find('.otherallowancecustom').val());
                            allowanceamount+=parseFloat($(this).find('.otherallowancecustom').val());
                        }

                    }else{
                        allowancetype = $(this).attr('data-allowancetype');
                        amountpaid=parseFloat($(this).attr('data-amount'));
                        allowanceamount+=parseFloat($(this).attr('data-amount'));
                    }
                    obj = {
                        particularid      : $(this).attr('data-allowanceid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-totalamount'),
                        amountpaid       : amountpaid,
                        paymenttype       : allowancetype,
                        particulartype       : 4
                    };
                    totalearnings += amountpaid;
                    particulars.push(obj)
                }
            })
            
            $('.td-leaves').each(function(){
                    obj = {
                        particularid      : 0,
                        ldateid      : $(this).attr('data-ldateid'),
                        description      : $(this).attr('data-description'),
                        totalamount       : $(this).attr('data-amount'),
                        amountpaid       : $(this).attr('data-amount'),
                        paymenttype       : 0,
                        employeeleaveid       : $(this).attr('data-empleaveid'),
                        particulartype       : 0
                    };
                    totalearnings += parseFloat($(this).attr('data-amount'));
                    particulars.push(obj)
            })
            $('.filedovertimes').each(function(){
                    obj = {
                        particularid      : $(this).attr('data-id'),
                        description      : $(this).attr('data-totalhours')+' hr(s)',
                        totalamount       : $(this).attr('data-amount'),
                        amountpaid       : $(this).attr('data-amount'),
                        paymenttype       : 0,
                        particulartype       : 6
                    };
                    particulars.push(obj)
            })
            
            $('.span-description').each(function(){
                if($(this).attr('data-type') == 1)
                {
                    allowanceamount+=parseFloat($(this).attr('data-amount').replace(',', ''));
                    totalearnings+=parseFloat($(this).attr('data-amount').replace(',', ''));

                }else{
                    deductamount+=parseFloat($(this).attr('data-amount').replace(',', ''));
                    totaldeductions+=parseFloat($(this).attr('data-amount').replace(',', ''));
                }
                obj = {
                    particularid      : 0,
                    description      : $(this).text(),
                    totalamount       : $(this).attr('data-amount'),
                    amountpaid       : $(this).attr('data-amount'),
                    paymenttype       : 0,
                    particulartype       : $(this).attr('data-type')
                };
                particulars.push(obj)
            })
            netsalary += allowanceamount;
            // console.log(totaldeductions)
            $('#span-total-earn-display').text(ReplaceNumberWithCommas(totalearnings.toFixed(2)))
            $('#span-total-earn').text(totalearnings.toFixed(2))
            $('#span-total-deduct-display').text(ReplaceNumberWithCommas(totaldeductions.toFixed(2)))
            $('#span-total-deduct').text(totaldeductions.toFixed(2))
            
            // var newsalary = parseFloat(netsalary-deductamount).toFixed(2);
            // newsalary = ReplaceNumberWithCommas(newsalary);
            var netpay = (totalearnings-totaldeductions);
            $('#span-netpay-display').text(ReplaceNumberWithCommas(netpay.toFixed(2)))
            $('#span-netpay').text(netpay.toFixed(2))
            // $('#netsalary').hide();
            // $('#newsalary').text(newsalary)
            if($(this).attr('data-id') == '1')
            {
                var additionalparticulars = [];
                if($('.additional-paticular').length > 0)
                {
                    $('.additional-paticular').each(function(){
                        obj = {
                            id              : $(this).attr('id'),
                            type            : $(this).attr('data-type'),
                            amount          : $(this).attr('data-amount'),
                            description     : $(this).text()
                        }
                        additionalparticulars.push(obj)
                    })
                }
                $.ajax({
                    url: '/hr/payrollv3/configuration',
                    type: 'get',
                    data: {
                        payrollid               : $('.input-payrolldates').attr('data-id'),
                        particulars             : JSON.stringify(particulars),
                        additionalparticulars   : JSON.stringify(additionalparticulars),
                        amountabsent            : amountabsent,
                        amountlate              : amountlate,
                        tardinessamount         : tardinessamount,
                        lateminutes             : lateminutes,
                        undertimeminutes        : undertimeminutes,
                        totalworkedhours        : totalworkedhours,
                        amountperday            : amountperday,
                        netsalary               : netpay,
                        totalearnings           : totalearnings,
                        totaldeductions         : totaldeductions,
                        dayspresent             : $('#dayspresent').text(),
                        daysabsent              : $('#daysabsent').text(),
                        basicsalary             : $('#td-basicpay-amount').attr('data-amount'),
                        monthlysalary           : $('#monthlysalary').text(),
                        salarytype              : $('#salarytype').text(),
                        employeeid              :   employeeid
                    },
                    success: function(data){
                        if(data == 1)
                        {                            
                            $('#btn-release-payslip').show();
                            $('.btn-compute[data-id="0"]').click()
                            toastr.success('Saved successfully!','Payroll Computation')
                        }
                        // toastr.success('Payroll date range is set!','Payroll')
                        // window.location.reload();
                    }
                })
            }
        })
        function numberofreleased(){
            var payrollid = $('.input-payrolldates').attr('data-id');
            $.ajax({
                url: '/hr/payrollv3/payrolldates',
                type: 'get',
                data: {
                    action                  : 'getnumberofreleased',
                    payrollid               : $('.input-payrolldates').attr('data-id')
                },
                success: function(data){
                    $('#numofreleased').text(data)
                }
            })
        }
        $(document).on('click','#btn-release-payslip', function(){
            var employeeid = $('#select-employee').val();
            Swal.fire({
            title: 'Are you sure?',
            html: "Once released, you will not be able to reconfigure this computation again!<br/>Please save all changes first!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Release',
            cancelButtonText: 'Cancel',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                            // $('.btn-compute[data-id="0"]').click()
                    // $('#btn-save-computation').hide();
                    window.open('/hr/payrollv3/export?exporttype=1&payrollid='+$('.input-payrolldates').attr('data-id')+'&employeeid='+employeeid,'_blank')
                    $('#select-employee').change()
                    $('#select-employee').change()
                    numberofreleased();
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    // Swal.fire(
                    // 'Cancelled',
                    // 'Your imaginary file is safe :)',
                    // 'error'
                    // )
                }
            })
                    numberofreleased();
        })
        $(document).on('click','#btn-export-payslip', function(){
            var employeeid = $('#select-employee').val();
            var payrollid = $('.input-payrolldates').attr('data-id');
            
            $('#btn-save-computation').hide();
            window.open('/hr/payrollv3/export?exporttype=1&payrollid='+$('.input-payrolldates').attr('data-id')+'&employeeid='+employeeid,'_blank')
        })
        $(document).on('click','.remove-particular', function(){
            // var particulartype = $(this).attr('data-id')
            // var particularamount = $(this).attr('data-amount')
            // var netsalary = parseFloat($('#newsalary').text().replace(',', ''))
            // if(particulartype == 1)
            // {
            // var newsalary = parseFloat(netsalary)-parseFloat(particularamount);
            // }
            // if(particulartype == 2)
            // {
            //     var newsalary = parseFloat(netsalary)+parseFloat(particularamount);
            // }
            // newsalary = ReplaceNumberWithCommas(newsalary);
            // $('#netsalary').hide();
            // $('#newsalary').text(parseFloat(newsalary).toFixed(2))
            $(this).closest('tr').remove();
            $('.btn-compute[data-id="0"]').click()
        })
        $(document).on('click', '.delete-particular', function(){
            var id = $(this).attr('id');
            var employeeid = $(this).attr('data-employeeid');
            var thisrow = $(this).closest('.row');
            Swal.fire({
            title: 'Are you sure about deleting this added particular?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                $.ajax({
                    url: '/hr/payrollv3/addedparticular',
                    type: 'get',
                    data: {
                        action         : 'delete',
                        payrollid      : $('.input-payrolldates').attr('data-id'),
                        employeeid     : employeeid,
                        id             : id
                    },
                    success: function(data){
                        if(data == 1)
                        {                            
                            toastr.success('Deleted successfully!','Added Particulars')
                            $('.div-each-employee[data-empid="'+employeeid+'"]').click()
                        }
                        // toastr.success('Payroll date range is set!','Payroll')
                        // window.location.reload();
                    }
                })
                }

            })
        })
        $('#btn-print-summary').on('click', function(){
            window.open('/hr/payrollv3/export?exporttype=2&payrollid='+$('.input-payrolldates').attr('data-id'),'_blank')
        })
    })
  
  </script>
@endsection

