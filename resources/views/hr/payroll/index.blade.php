

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<script>
    var $ = jQuery;
    $(document).ready(function(){
        $(".filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".card").each(function() {
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
    })
</script>
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
  
  @if(count(collect($payrolldates))>0)
  <div class="row">
      <div class="col-5">
          <div class="form-group">

            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
                </span>
            </div>
            <input type="text" class="form-control float-right" id="payrolldates" value="{{$payrolldates->datefrom}} - {{$payrolldates->dateto}}" disabled>
            <div class="input-group-append">
                @if($newpayroll == 1)
                <button type="button" class="btn btn-sm btn-primary" id="newpayrollperiod">New</button>
                @else
                <button type="button" class="btn btn-sm btn-warning" id="changepayrollperiod" data-toggle="modal" data-target="#modal-changepayroll">Change</button>
                @endif
            </div>
            </div>
        </div>
        <div class="card card-success" style="border: none !important; box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important">
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
                  <div class="col-md-12 mb-2 div-each-employee " data-empid="{{$employee->employeeid}}" data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}} {{$employee->designation}}<" style="border-radius: 5px; border: 1px solid green; cursor: pointer">
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
      <div class="col-7">
            <div class="form-group" hidden>

            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    Leap Year
                </span>
            </div>
            <div class="input-group-append">
                <span class="input-group-text">
                    @if($payrolldates->leapyear == 0)
                        <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary1" name="leapyearactivation" value="1">
                        <label for="radioPrimary1">
                            Active
                        </label>
                        </div> &nbsp;&nbsp;
                        <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary2" name="leapyearactivation"  value="0" checked>
                        <label for="radioPrimary2">
                            Inactive
                        </label>
                        </div>
                    @else
                        <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary1" name="leapyearactivation"  value="1" checked>
                        <label for="radioPrimary1">
                            Active
                        </label>
                        </div> &nbsp;&nbsp;
                        <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary2" name="leapyearactivation"  value="0">
                        <label for="radioPrimary2">
                            Inactive
                        </label>
                        </div>
                    @endif
                </span>
            </div>
            </div>
            <!-- /.input group -->
        </div>
        <div  id="salarydetailscontainer"></div>
      </div>
  </div>
  <div class="row">
      <div class="col-5">
        {{-- <div class="row">
            <div class="col-md-12">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <input class="filter form-control" placeholder="Search employee" />
                    <div class="input-group-append">
                        <button type="button" class="btn btn-sm btn-warning"><strong>{{count($employees)}}</strong> Employees</button>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="row d-flex align-items-stretch text-uppercase" id="employeescontainer">
            @foreach($employees as $employee)
                <div class="card col-md-12 employee" style="border: none !important;box-shadow: none !important;" data-string="{{$employee->firstname}} {{$employee->middlename}} {{$employee->lastname}} {{$employee->suffix}} {{$employee->utype}}<" id="{{$employee->employeeid}}">
                    <div class="card-body p-0" >
                        <div class="row" id="{{$employee->employeeid}}">
                            <div class="col-md-10">
                                <h2 class="table-avatar" style="font-size: 12px;">
                                    <a href="#" class="">
                                    <a href="//hr/employees/profile/index?employeeid={{$employee->employeeid}}" style="font-size: 15px;">   {{$employee->lastname}}, {{$employee->firstname}} {{$employee->middlename}} {{$employee->suffix}} <small class="text-muted">{{$employee->utype}}</small> </a>
                                </h2>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-secondary btn-sm btn-block viewsalarydetails" id="{{$employee->employeeid}}">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div> --}}
      </div>
      {{-- <div class="col-md-7" id="salarydetailscontainer">

      </div> --}}
  </div>
  <div class="modal fade" id="modal-newpayroll" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >New Payroll</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body" >
                {{-- <div id="newlogscontainer" employeeid="{{$employeeinfo->id}}" usertypeid="{{$employeeinfo->usertypeid}}"></div> --}}
                <div class="row">
                    <div class="col-6">
                        <label>Date from</label>
                        <input type="date" class="form-control" id="newpayrolldatefrom" min="{{date('Y-m-d',strtotime($payrolldates->dateto))}}"/>
                    </div>
                    <div class="col-6">
                        <label>Date to</label>
                        <input type="date" class="form-control" id="newpayrolldateto" min="{{date('Y-m-d',strtotime($payrolldates->dateto))}}"/>
                    </div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitnewpayroll">Submit</button>
              </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-changepayroll" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" >Change Payroll</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body" >
                  {{-- <div id="newlogscontainer" employeeid="{{$employeeinfo->id}}" usertypeid="{{$employeeinfo->usertypeid}}"></div> --}}
                  <div class="row">
                      <div class="col-6">
                          <label>Date from</label>
                          <input type="date" class="form-control" id="changepayrolldatefrom" value="{{date('Y-m-d',strtotime($payrolldates->datefrom))}}"  @if($lastpayrolldates) min="{{$lastpayrolldates->dateto}}" @endif/>
                      </div>
                      <div class="col-6">
                          <label>Date to</label>
                          <input type="date" class="form-control" id="changepayrolldateto" value="{{date('Y-m-d',strtotime($payrolldates->dateto))}}" @if($lastpayrolldates) min="{{$lastpayrolldates->dateto}}" @endif />
                      </div>
                  </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="submitchangepayroll">Submit</button>
                </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
  @endif
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
  <!-- Bootstrap Switch -->
  <script src="{{asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<script>
    const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1000
    });
    $(document).ready(function(){
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
            getdetails(employeeid)
            // $.ajax({
            //     url: '/hr/payrollv2/getsalaryinfo',
            //     type: 'get',
            //     data: {
            //         payrollid    :   $('#reservation').attr('data-id'),
            //         employeeid   :   employeeid
            //     },
            //     success: function(data){
            //         $('#div-container-salaryinfo').empty()
            //         $('#div-container-salaryinfo').append(data)
            //         $('#btn-compute').attr('data-employeeid', employeeid)
            //         // toastr.success('Payroll date range is set!','Payroll')
            //         // window.location.reload();
            //     }
            // })
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
        @if(count(collect($payrolldates))==0)
            Swal.fire({
                title: 'Please set the payroll date range first!',
                type: 'warning',
                html:   '<input type="text" name="payrolldate" id="setpayrolldates" class="form-control form-control-sm daterangeupdate mb-2" >',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Set',
                showCancelButton: true,
                allowOutsideClick: false,
                onOpen: function () {
                    $('#setpayrolldates').daterangepicker({
                        locale: {
                            format: 'MM/DD/YYYY'
                        }
                    });
                }
                // ,
                // preConfirm: () => {
                //     if($("#prog_title").val().replace(/^\s+|\s+$/g, "").length == 0 || $('#prog_code').val().replace(/^\s+|\s+$/g, "").length == 0){
                //         Swal.showValidationMessage(
                //             "Please fill in the required section!"
                //         );
                //     }
                // }
            }).then((confirm) => {
                    if (confirm.value) {
                        var payrolldates = $('#setpayrolldates').val();
                        $.ajax({
                            url: '/hr/payroll/setpayrolldate',
                            type: 'get',
                            dataType: 'json',
                            data: {
                                payrolldates          :   $('#setpayrolldates').val()
                            },
                            complete: function(data){
                                toastr.success('Payroll date range is set!','Payroll')
                                window.location.reload();
                            }
                        })
                    }
                })
        @else
            
        getdetails($('.div-each-employee').first().attr('data-empid'))
            // getdetails($('#employeescontainer').find('.employee').first().attr('id'))
            $(document).on('click','input[name=leapyearactivation]', function(){
                $.ajax({
                    url: '/hr/payroll/leapyearactivation',
                    type: 'get',
                    dataType: 'json',
                    data: {
                        leapyearactivation          :   $(this).val()
                    },
                    complete: function(data){
                        toastr.success('Updated successfully!','Leap Year')
                        // window.location.reload();
                    }
                })
            });
            function getdetails(employeeid){
                $.ajax({
                    url: '/hr/payroll/getsalarydetails',
                    type: 'get',
                    data: {
                        employeeid          :   employeeid
                    },
                    success: function(data){
                        $('#salarydetailscontainer').empty()
                        $('#salarydetailscontainer').append(data)
                    }
                })
            }
            $(document).on('click','.viewsalarydetails', function(){
                var employeeid = $(this).attr('id');
                getdetails(employeeid)
            })
            $(document).on('click', '#newpayrollperiod', function(){
                $('#modal-newpayroll').modal('show')
            })
            

            $('#submitnewpayroll').on('click', function(){
                var submit = 0;
                if($('#newpayrolldatefrom').val().replace(/^\s+|\s+$/g, "").length == 0)
                { 
                    submit=1;
                    $('#newpayrolldatefrom').css('border','2px solid red')
                }else{
                    $('#newpayrolldatefrom').removeAttr('style')
                }
                if($('#newpayrolldateto').val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    submit=1;
                    $('#newpayrolldateto').css('border','2px solid red')
                }else{
                    $('#newpayrolldateto').removeAttr('style')
                }

                if(submit == 0)
                {
                    var datefrom = $('#newpayrolldatefrom').val();
                    var dateto   = $('#newpayrolldateto').val();
                    $.ajax({
                        url: '/hr/payroll/newpayroll',
                        type: 'GET',
                        data: {
                            datefrom          :   datefrom,
                            dateto            :   dateto
                        },complete: function(data){
                            window.location.reload()
                        }
                    })
                }
            })

            $('#submitchangepayroll').on('click', function(){
                var submit = 0;
                if($('#changepayrolldatefrom').val().replace(/^\s+|\s+$/g, "").length == 0)
                { 
                    submit=1;
                    $('#changepayrolldatefrom').css('border','2px solid red')
                }else{
                    $('#changepayrolldatefrom').removeAttr('style')
                }
                if($('#changepayrolldateto').val().replace(/^\s+|\s+$/g, "").length == 0)
                {
                    submit=1;
                    $('#changepayrolldateto').css('border','2px solid red')
                }else{
                    $('#changepayrolldateto').removeAttr('style')
                }

                if(submit == 0)
                {
                    var datefrom = $('#changepayrolldatefrom').val();
                    var dateto   = $('#changepayrolldateto').val();
                    $.ajax({
                        url: '/hr/payroll/changepayroll',
                        type: 'GET',
                        data: {
                            datefrom          :   datefrom,
                            dateto            :   dateto,
                            payrollid         :   '{{$payrolldates->id}}'
                        },complete: function(data){
                            window.location.reload()
                        }
                    })
                }
            })
            
        @endif
        
    })
</script>
@endsection

