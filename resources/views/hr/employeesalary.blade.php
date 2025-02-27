

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
    table{
        /* font-size: 12px;; */
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
}div.dataTables_wrapper div.dataTables_paginate ul.pagination {
    font-size: 12px;
}

    
@media screen and (max-width : 1920px){
  .div-only-mobile{
  visibility:hidden;
  }
}
@media screen and (max-width : 906px){
 .desk{
  visibility:hidden;
  }
 .div-only-mobile{
  visibility:visible;
  }
  .viewpayrollasbutton{
      width: 100% ;
      display: block;
  }
  .formfilteremployees{
      display: inline;
  }
  /* .formprintfilteremployees{
      padding-top: 10px !important;
  } */
  

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
  @if(isset($noemployees))
  <div class="row">
    <div class="col-md-12">
      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-exclamation-triangle"></i>
          </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="alert alert-info alert-dismissible">
            <h5><i class="icon fas fa-info"></i> Alert!</h5>
            <ol>
                <li><strong>Assign each employees to their respective departments.</strong>
                    <br>
                    > Click "Employees" from the side navigation
                    <br>
                    > Select an employee you want to update his/her department
                    <br>
                    > From the "Department & Designation" section, click the yellow button from it's top right side
                    <br>
                    > Update the employee's department and designation
                </li>
                <br>
                <li><strong>Set their basic salary information.</strong>
                    <br>
                    > Click "Employees" from the side navigation
                    <br>
                    > Select an employee you want to update his/her basic salary information
                    <br>
                    > From the "Basic Salary Information" tab, configure the selected employee's basic salary information
                    <br>
                    > Update the employee's department and designation
                </li>
            </ol>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
  @endif
  @if(isset($employees))
  <div class="row">
      <div class="col-md-7">
          
                @if($existsinhistory == 0)
                    <form action="/payroll/changedate" method="get" class="m-0 ">
                        <div class="row mb-2">
                            <div class="col-md-3">
                                
                                <button type="button" class="btn btn-sm btn-default mr-3 viewpayrollasbutton" >Payroll as of:<br><small>(MM-DD-YYYY)</small></button>
                            </div>
                            <div class="col-md-7">
                                <input type="hidden" name="payrolldateid" value="{{$payrolldate[0]->id}}" id="payrolldateid"/>
                                <input type="text" name="payrolldate" style="font-size:25px" class="form-control form-control-sm daterangeupdate mb-2" style="display: inline;position:relative;" id="reservation1" value="{{$payrolldate[0]->datefrom}} - {{$payrolldate[0]->dateto}}" disabled>
                            </div>
                            <div class="col-md-2 formpayrollsubmit">
                                <button type="button" class="btn btn-sm btn-warning float-right mb-2 btn-block changepayrolldate">Change</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="row mb-2">
                        <div class="col-md-3">
                            
                            <button type="button" class="btn btn-sm btn-default mr-3 viewpayrollasbutton" >Payroll as of:<br><small>(MM-DD-YYYY)</small></button>
                        </div>
                        <div class="col-md-7">
                            <input type="hidden" name="payrolldateid" value="{{$payrolldate[0]->id}}" id="payrolldateid"/>
                            <input type="text" name="payrolldate" style="font-size:25px" class="form-control form-control-sm daterangeupdate mb-2" style="display: inline;position:relative;" id="reservation2" value="{{$payrolldate[0]->datefrom}} - {{$payrolldate[0]->dateto}}" disabled>
                        </div>
                        <div class="col-md-2 formpayrollsubmit">
                    <button type="button" class="btn btn-sm btn-primary float-right mb-2 btn-block" data-toggle="modal" data-target="#newpayrolldate">New</button>
                    <div class="modal fade" id="newpayrolldate" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/payroll/newdate" method="get">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Set new payroll date</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="oldpayrolldateid" value="{{$payrolldate[0]->id}}" id="payrolldateid"/>
                                        <p>New payroll date:</p>
                                        <input type="text" name="newpayrolldate" class="form-control form-control-sm setnewpayrolldate col-md-7 mb-2"  id="setnewpayrolldate" >
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </div>
            @endif
      </div>
  </div>
  <div class="row">
    <div class="col-md-7 p-0">
        <div class="row">
            <div class="col-md-3 col-3">
                <button type="button" class="btn btn-sm btn-default viewfilterbutton"><i class="fa fa-filter"></i> Filter</button>
            </div>
            <div class="col-md-7 col-7">
                <form action="/payroll/dashboard" method="get" class="m-0 formfilteremployees">
                    <select class="form-control form-control-sm" name="filteremployees">
                        <option value="all" {{'all' == $filteremployees ? 'selected' : ''}}>All</option>
                        <option value="Hourly" {{'Hourly' == $filteremployees ? 'selected' : ''}}>Hourly</option>
                        <option value="Daily" {{'Daily' == $filteremployees ? 'selected' : ''}}>Daily</option>
                        <option value="Monthly" {{'Monthly' == $filteremployees ? 'selected' : ''}}>Monthly</option>
                        <option value="Project" {{'Project' == $filteremployees ? 'selected' : ''}}>Project</option>
                    </select>
                </form>
            </div>
            {{-- <div class="col-md-2 col-2">
                <form action="/printfilteredsalary/print" method="get" class="m-0 formprintfilteremployees" target="_blank">
                    <input type="hidden" name="salarytype" value="{{$filteremployees}}"/>
                    <input type="hidden" name="printfilteredemployees" value="1"/>

                    <button type="button" class="btn btn-sm btn-default btn-block float-right mb-2 viewprintbutton" data-toggle="modal" data-target="#selectdisplaytype">Print</button>
                    <div id="selectdisplaytype" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title employeename"></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- <label>Title</label> -->
                                    <input type="hidden" name="displaytype" >
                                    <div class="submit-section">
                                        @if(count($employees) == 0)
                                            <button type="button" class="btn btn-sm btn-default btn-block float-right mb-2" disabled>Payslip Summary</button>
                                        @else
                                            <button type="submit" class="selectdisplaytype btn btn-sm btn-default btn-block float-right mb-2 tablesummary"><strong>Payslip Summary</strong></button>
                                        @endif
                                    <br>&nbsp;
                                        @if(count($employees) == 0)
                                            <button type="button" class="btn btn-sm btn-default btn-block float-right mb-2" disabled>Detailed Payslips</button>
                                        @else
                                            <!-- <input type="hidden" name="displaytype" value="tablesummary" > -->
                                            <button type="submit" class="selectdisplaytype btn btn-sm btn-default btn-block float-right mb-2 individual"><strong>Detailed Payslips</strong></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div> --}}
        </div>
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-sm-4 float-right text-center">
                <h4>
                    <strong>Leap Year</strong>
                </h4>
            </div>
            <div class="col-sm-8">
                <form action="/payrollleapyear" method="get">
                    @if($existsinhistory == 0)
                        @if($payrolldate[0]->leapyear == 0)
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline  p-2">
                                    <input type="radio" id="radioPrimary1" name="leapyearactivation" value="1">
                                    <label for="radioPrimary1">
                                        Active
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline  p-2">
                                    <input type="radio" id="radioPrimary2" name="leapyearactivation" value="0" checked="">
                                    <label for="radioPrimary2">
                                        Inactive
                                    </label>
                                </div>
                            </div>
                        @else
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline  p-2">
                                    <input type="radio" id="radioPrimary1" name="leapyearactivation" value="1" checked="">
                                    <label for="radioPrimary1">
                                        Active
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline  p-2">
                                    <input type="radio" id="radioPrimary2" name="leapyearactivation" value="0">
                                    <label for="radioPrimary2">
                                        Inactive
                                    </label>
                                </div>
                            </div>
                        @endif
                    @else
                        @if($payrolldate[0]->leapyear == 0)
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline  p-2">
                                    <input type="radio" id="radioPrimary1" name="leapyearactivation" value="1" disabled>
                                    <label for="radioPrimary1">
                                        Active
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline  p-2">
                                    <input type="radio" id="radioPrimary2" name="leapyearactivation" value="0" checked="" disabled>
                                    <label for="radioPrimary2">
                                        Inactive
                                    </label>
                                </div>
                            </div>
                        @else
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline  p-2">
                                    <input type="radio" id="radioPrimary1" name="leapyearactivation" value="1" checked="" disabled>
                                    <label for="radioPrimary1">
                                        Active
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline  p-2">
                                    <input type="radio" id="radioPrimary2" name="leapyearactivation" value="0" disabled>
                                    <label for="radioPrimary2">
                                        Inactive
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endif
                </form>
            </div>
        </div>
    </div>
  </div>
<div class="row">
<div class="col-md-7" style="border-right: 3px solid #ddd">
    @if(isset($employees))
    <form action="/employeesalry/generatepayslip" method="get" name="payform" id="payform">
    </form>
    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
        <div class="row">
            <div class="col-sm-12" style="overflow:scroll;">
                <table id="example1" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th></th>
                            {{-- <th>Deductions</th>
                            <th>Salary type</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($employees)>0)
                            @foreach($employees as $employee)
                                <tr>
                                    <td>
                                        <span hidden>{{$employee->id}}</span>
                                        <h2 class="table-avatar">
                                            @php
                                                    $number = rand(1,3);
                                                    if(strtoupper($employee->gender) == 'FEMALE'){
                                                        $avatar = 'avatar/T(F) '.$number.'.png';
                                                    }
                                                    else{
                                                        $avatar = 'avatar/T(M) '.$number.'.png';
                                                    }
                                                @endphp
                                            <a href="#" class="avatar">
                                                    <img src="{{ asset($employee->picurl) }}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'"/>
                                            <a href="/hr/employeeprofile?employeeid={{$employee->id}}">  {{$employee->firstname}} {{$employee->middlename}} {{$employee->lastname}} {{$employee->suffix}} <span>{{$employee->utype}}</span></a>
                                        </h2>
                                    </td>
                                    <td>
                                        <form action="/payroll/dashboard" method="get">
                                            <input type="hidden" name="viewdetails" value="1">
                                            <input type="hidden" name="employeeid" value="{{$employee->id}}">
                                            <button type="submit" class="btn btn-outline-secondary btn-sm btn-block">Salary Details</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="col-md-5 text-uppercase" id="detailcontainer">
    @if(isset($firstemployee))
    <h2 class="table-avatar">
        <a href="/hr/employeeprofile?employeeid={{$firstemployee[0]->employee_info[0]->id}}">{{$firstemployee[0]->employee_info[0]->firstname}} {{$firstemployee[0]->employee_info[0]->middlename[0]}}. {{$firstemployee[0]->employee_info[0]->lastname}} {{$firstemployee[0]->employee_info[0]->suffix}}</a>
    </h2>
    <br>
    <div class="row" id="salarysummaryheader">
    </div>

    <br>
        @if($firstemployee[0]->basicpay == 0.00)
        <div class="row">
          <div class="col-md-12">
              <!-- /.card-header -->
                <div class="alert alert-warning alert-dismissible">
                  <h5><i class="icon fas fa-info"></i> Alert!</h5>
                  <ol>
                        <li><strong>Basic Salary Information not yet set.</strong>
                            <br>
                            > Click the employee's name from the table
                            <br>
                            > From the "Basic Salary Information" tab, configure the selected employee's basic salary information
                        </li>
                  </ol>
                </div>
            <!-- /.card -->
          </div>
        </div>
        @else
            <table class="table" width="100%" id="">
                <tr>
                    <th>Basic Pay</th>
                    <td class="text-success float-right">&#8369; <span class="salary">{{$firstemployee[0]->basicpay}}<span></td>
                </tr>
                <tr>
                    <th>Salary type</th>
                    <td class="text-success float-right">{{$firstemployee[0]->ratetype}}</td>
                </tr>
            </table>
            <br>
            
            @if($firstemployee[0]->employee_info[0]->released == 0)
            <h4 class="text-info">Salary Details</h4>
            <br>
            <table class="table" width="100%" id="salarycontainer">
                <tbody id="earningscontainer">
                    <tr>
                        <td class="text-success">Attendance - Present</td>
                        <td class="text-right text-success">&#8369; {{$firstemployee[0]->attendancesalary}}</td>
                    </tr>
                    <tr>
                        <td class="text-success">Attendance - Absent ({{$firstemployee[0]->numberofabsent}} day/s)</td>
                        <td class="text-right text-success">&#8369; {{$firstemployee[0]->absentdeduction}}</td>
                    </tr>
                    @if($firstemployee[0]->leavesearn > 0.00)
                            <tr>
                                <td class="text-success">Leaves ({{$firstemployee[0]->leavesnumdays}} day/s)</td>
                                <td class="text-right text-success">&#8369; {{$firstemployee[0]->leavesearn}}</td>
                            </tr>
                    @endif
                    @if($firstemployee[0]->overtimepay != 0)
                        <tr>
                            <td class="text-success">Overtime</td>
                            <td class="text-right text-success">&#8369; {{$firstemployee[0]->overtimepay}}</td>
                        </tr>
                    @endif
                    @if($firstemployee[0]->holidaypay != 0)
                        <tr>
                            <td class="text-success">Holiday</td>
                            <td class="text-right text-success">&#8369; {{$firstemployee[0]->holidaypay}}</td>
                        </tr>
                    @endif
                    @if($firstemployee[0]->holidayovertimepay != 0)
                        <tr>
                            <td class="text-success">Holiday OT</td>
                            <td class="text-right text-success">&#8369; {{$firstemployee[0]->holidayovertimepay}}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @include('hr.employeesalary.firstemployeeallowances')
            <table class="table" width="100%" id="deductionscontainer">
                @if($firstemployee[0]->leavesdeduct > 0.00)
                        <tr>
                            <td class="text-danger">Leaves ({{$firstemployee[0]->leavesnumdays}} day/s)</td>
                            <td class="text-right text-danger">&#8369; {{$firstemployee[0]->leavesdeduct}}</td>
                        </tr>
                @endif
                @if($firstemployee[0]->latedeductions > 0.00)
                        <tr>
                            <td class="text-danger">Tardiness</td>
                            <td class="text-right text-danger">&#8369; {{$firstemployee[0]->latedeductions}}</td>
                        </tr>
                @endif
            </table>
            @include('hr.employeesalary.firstemployeedeductions')

            <table class="table" width="100%">
                <tr>
                    <th>Total Earnings</th>
                    <td class="float-right">&#8369; <span class="salary totalearnings">{{$firstemployee[0]->totalearnings}}<span></td>
                </tr>
                <tr>
                    <th>Total Deductions</th>
                    <td class="float-right">&#8369; <span class="salary totaldeductions">{{$firstemployee[0]->totaldeductions}}</span></td>
                </tr>
                <tr>
                    <th><h3>Net Salary</h3></th>
                    <td class="float-right"><h5>&#8369; <span class="salary totalpay">{{$firstemployee[0]->netsalary}}<strong><strong></span></h5></td>
                </tr>
            </table>
            @else
            @endif
            @include('hr.employeesalary.firstemployeesubmitforms')
        @endif
    @else
        <div class="row">
          <div class="col-md-12">
              <!-- /.card-header -->
                <div class="alert alert-info alert-dismissible">
                  <h5><i class="icon fas fa-info"></i> <strong>Set new payroll date!</strong></h5>
                </div>
            <!-- /.card -->
          </div>
        </div>
    @endif


    {{-- &nbsp; --}}
</div>
</div>
@endif
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>
    // document.addEventListener('contextmenu', function(e) {
    //     e.preventDefault();
    // });
    // document.onkeydown = function(e) {
    //     if(event.keyCode == 123) {
    //         return false;
    //     }
    //     if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
    //         return false;
    //     }
    //     if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
    //         return false;
    //     }
    //     if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
    //         return false;
    //     }
    //     if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
    //         return false;
    //     }
    // }
    @if(isset($firstemployee))
        if('{{$firstemployee[0]->employee_info[0]->released}}' == 1){

            $('input[type="radio"]').attr('disabled',true);

        }
    @endif

    $(document).on('click','input[name=leapyearactivation]', function(){
        $(this).closest('form').submit();
    });

    var standardallowancehalf = 0;
    var standarddeductionhalf = 0;
    var firstClick = 0;
    var firstName = '';
    var canClicked = false;

    $(document).on('click','.radiostandardallowance', function(){

        // if(firstName != $(this).attr('name')){

        //     if(firstClick != $(this).val()){

        //         canClicked = true;
        //         firstClick = $(this).val();
        //         firstName = $(this).attr('name')

        //     }else{
        //         canClicked = false
        //     }

        // }
        // else{

        //      if(firstClick != $(this).val()){

        //         canClicked = true;
        //         firstClick = $(this).val();
        //         firstName = $(this).attr('name')
                
        //     }else{
        //         canClicked = false
        //     }

        // }

        // if(canClicked){
            if($(this).closest('td').attr('payment') == 'half'){
                standardallowancehalf+=1;
                var totalearnings                               = $('.totalearnings').text().replace(/,/g, "");
                var floatearnings                               = parseFloat(totalearnings.replace(/,/g, ""));
                var floatamount                                 = parseFloat($(this).closest('td').attr('amount').replace(/,/g, ""))
                var totalstandardallowancediff                  = floatearnings - floatamount;
                var totalstandardallowancediffwithseparator     = totalstandardallowancediff.toFixed(2);

                $('.totalearnings').text( totalstandardallowancediffwithseparator.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );

                var changepayment = $(this).closest('tr').find('input[name="standardallowanceids[]"]').val().replace('full', 'half');
                
                $(this).closest('tr').find('input[name="standardallowanceids[]"]').val(changepayment)
            }
            if($(this).closest('td').attr('payment') == 'full'){

                if(standardallowancehalf > 0){

                    standardallowancehalf-=1;
                }
                    var totalearnings =  parseFloat($('.totalearnings').text().replace(/,/g, ""));
                        totalearnings+= parseFloat($(this).closest('td').attr('amount').replace(/,/g, ""))/2;
                        $('.totalearnings').text( (totalearnings.toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));


                var changepayment = $(this).closest('tr').find('input[name="standardallowanceids[]"]').val().replace('half', 'full');
                
                $(this).closest('tr').find('input[name="standardallowanceids[]"]').val(changepayment)

            }
            
            var totaldeductions = parseFloat($('.totaldeductions').text().replace(/,/g, ""));
            var totalearnings =  parseFloat($('.totalearnings').text().replace(/,/g, ""));
            $('.totalpay').text(((totalearnings - totaldeductions).toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            $('input[name=totalearnings]').val(totalearnings);
            $('input[name=totaldeductions]').val(totaldeductions);
            $('input[name=netpay]').val($('.totalpay').text());
        // }
       
        // }


    });
    var otherallowancehalf = 0;
    $(document).on('click','.radiootherallowance', function(){

        // if(firstName != $(this).attr('name')){

        //     if(firstClick != $(this).val()){

        //         canClicked = true;
        //         firstClick = $(this).val();
        //         firstName = $(this).attr('name')

        //     }else{
        //         canClicked = false
        //     }

        // }
        // else{

        //     if(firstClick != $(this).val()){

        //         canClicked = true;
        //         firstClick = $(this).val();
        //         firstName = $(this).attr('name')
                
        //     }else{
        //         canClicked = false
        //     }

        // }

        // if(canClicked){

            if($(this).closest('td').attr('payment') == 'half'){
                otherallowancehalf+=1;
                var totalearnings                               = $('.totalearnings').text().replace(/,/g, "");
                var floatearnings                               = parseFloat(totalearnings.replace(/,/g, ""));
                var floatamount                                 = parseFloat($(this).closest('td').attr('amount').replace(/,/g, ""))
                var totalotherallowancediff                     = floatearnings - floatamount;
                var totalotherallowancediffwithseparator        = totalotherallowancediff.toFixed(2);

                $('.totalearnings').text( totalotherallowancediffwithseparator.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );

                var changepayment = $(this).closest('tr').find('input[name="otherallowanceids[]"]').val().replace('full', 'half');
                
                $(this).closest('tr').find('input[name="otherallowanceids[]"]').val(changepayment)
            }
            if($(this).closest('td').attr('payment') == 'full'){

                if(otherallowancehalf > 0){

                    otherallowancehalf-=1;

                }
                    var totalearnings =  parseFloat($('.totalearnings').text().replace(/,/g, ""));
                        totalearnings+= parseFloat($(this).closest('td').attr('amount').replace(/,/g, ""))/2;
                        $('.totalearnings').text( (totalearnings.toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

                var changepayment = $(this).closest('tr').find('input[name="otherallowanceids[]"]').val().replace('half', 'full');
                
                $(this).closest('tr').find('input[name="otherallowanceids[]"]').val(changepayment)

            }
            
            var totaldeductions = parseFloat($('.totaldeductions').text().replace(/,/g, ""));
            var totalearnings =  parseFloat($('.totalearnings').text().replace(/,/g, ""));
            $('.totalpay').text(((totalearnings - totaldeductions).toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));

            $('input[name=totalearnings]').val(totalearnings);
            $('input[name=totaldeductions]').val(totaldeductions);
            $('input[name=netpay]').val($('.totalpay').text());
        // }

    });
    
    $(document).on('click','.radiostandarddeduction', function(){

        // if(firstName != $(this).attr('name')){

        //     if(firstClick != $(this).val()){

        //         canClicked = true;
        //         firstClick = $(this).val();
        //         firstName = $(this).attr('name')

        //     }else{
        //         canClicked = false
        //     }

        // }
        // else{

        //     if(firstClick != $(this).val()){

        //         canClicked = true;
        //         firstClick = $(this).val();
        //         firstName = $(this).attr('name')
                
        //     }else{
        //         canClicked = false
        //     }

        // }

        // if(canClicked){
            
            if($(this).closest('td').attr('payment') == 'half'){
                standarddeductionhalf+=1;
                var totaldeductions                             = $('.totaldeductions').text().replace(/,/g, "");
                var floatdeductions                             = parseFloat(totaldeductions.replace(/,/g, ""));
                var floatamount                                 = parseFloat($(this).closest('td').attr('amount').replace(/,/g, ""))
                var totalstandarddeductiondiff                  = floatdeductions - floatamount;
                var totalstandarddeductiondiffwithseparator     = totalstandarddeductiondiff.toFixed(2);

                $('.totaldeductions').text(totalstandarddeductiondiffwithseparator.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );

                var changepayment = $(this).closest('tr').find('input[name="standarddeductionids[]"]').val().replace('full', 'half');
                
                $(this).closest('tr').find('input[name="standarddeductionids[]"]').val(changepayment)

            }
            if($(this).closest('td').attr('payment') == 'full'){

                if(standarddeductionhalf > 0){

                    standarddeductionhalf-=1;

                }
                    var totaldeductions =  parseFloat($('.totaldeductions').text().replace(/,/g, ""));
                        totaldeductions+= parseFloat($(this).closest('td').attr('amount').replace(/,/g, ""))/2;
                        $('.totaldeductions').text( (totaldeductions.toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                var changepayment = $(this).closest('tr').find('input[name="standarddeductionids[]"]').val().replace('half', 'full');
                
                $(this).closest('tr').find('input[name="standarddeductionids[]"]').val(changepayment)

            }
            var totaldeductions = parseFloat($('.totaldeductions').text().replace(/,/g, ""));
            var totalearnings =  parseFloat($('.totalearnings').text().replace(/,/g, ""));
            $('.totalpay').text(((totalearnings - totaldeductions).toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
            
            $('input[name=totalearnings]').val(totalearnings);
            $('input[name=totaldeductions]').val(totaldeductions);
            $('input[name=netpay]').val($('.totalpay').text());

        // }

    })
    var otherdeductionhalf = 0;
    $(document).on('click','.radiootherdeduction', function(){

        // if(firstName != $(this).attr('name')){

        //     if(firstClick != $(this).val()){

        //         canClicked = true;
        //         firstClick = $(this).val();
        //         firstName = $(this).attr('name')

        //     }else{
        //         canClicked = false
        //     }

        // }
        // else{

        //     if(firstClick != $(this).val()){

        //         canClicked = true;
        //         firstClick = $(this).val();
        //         firstName = $(this).attr('name')
                
        //     }else{
        //         canClicked = false
        //     }

        // }

        // if(canClicked){

            if($(this).closest('td').attr('payment') == 'half'){
                otherdeductionhalf+=1;
                var totaldeductions                             = $('.totaldeductions').text().replace(/,/g, "");
                var floatdeductions                             = parseFloat(totaldeductions.replace(/,/g, ""));
                var floatamount                                 = parseFloat($(this).closest('td').attr('amount').replace(/,/g, ""))
                var totalotherdeductiondiff                  = floatdeductions - floatamount;
                var totalotherdeductiondiffwithseparator     = totalotherdeductiondiff.toFixed(2);

                $('.totaldeductions').text(totalotherdeductiondiffwithseparator.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') );

                var changepayment = $(this).closest('tr').find('input[name="otherdeductionids[]"]').val().replace('full', 'half');
                
                $(this).closest('tr').find('input[name="otherdeductionids[]"]').val(changepayment)

            }
            if($(this).closest('td').attr('payment') == 'full'){

                if(otherdeductionhalf > 0){

                    otherdeductionhalf-=1;

                }
                    var totaldeductions =  parseFloat($('.totaldeductions').text().replace(/,/g, ""));
                        totaldeductions+= parseFloat($(this).closest('td').attr('amount').replace(/,/g, ""))/2;
                        $('.totaldeductions').text( (totaldeductions.toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
                var changepayment = $(this).closest('tr').find('input[name="otherdeductionids[]"]').val().replace('half', 'full');
                
                $(this).closest('tr').find('input[name="otherdeductionids[]"]').val(changepayment)

            }
            var totaldeductions = parseFloat($('.totaldeductions').text().replace(/,/g, ""));
            var totalearnings =  parseFloat($('.totalearnings').text().replace(/,/g, ""));
            $('.totalpay').text(((totalearnings - totaldeductions).toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
            
            $('input[name=totalearnings]').val(totalearnings);
            $('input[name=totaldeductions]').val(totaldeductions);
            $('input[name=netpay]').val($('.totalpay').text());

        // }

        // }
    })
    $(document).on('click','#payemployeesalary', function(){
        $('#formcontainer').empty()
        $('#standardallowancescontainer').find('input[type=hidden],input[type=radio]:checked').clone().appendTo('#formcontainer');
        $('#otherallowancescontainer').find('input[type=hidden],input[type=radio]:checked').clone().appendTo('#formcontainer');
        $('#standarddeductionscontainer').find('input[type=hidden],input[type=radio]:checked').clone().appendTo('#formcontainer');
        $('#otherdeductionscontainer').find('input[type=hidden],input[type=radio]:checked').clone().appendTo('#formcontainer');
        
    })







    $(document).on('click','.selectdisplaytype', function(){
        if($(this).hasClass('tablesummary') == true){
            $('input[name=displaytype]').val('tablesummary');
        }else{
            $('input[name=displaytype]').val('individual');
        }
    })
    $(document).on('change','select[name=filteremployees]',function(){
        $('form.formfilteremployees').submit();
    })
    $(function () {
    // });
        $('#editpayrolldate').on('click', function(){
            $('#changepayrolldate').attr('readonly',false);
        });
        $.fn.DataTable.ext.pager.numbers_length = 5;
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        $('#reservation1').daterangepicker({
            locale: {
                format: 'MM-DD-YYYY'
            }
        });
        $('#reservation2').daterangepicker({
            locale: {
                format: 'MM-DD-YYYY'
            }
        });
        $('#setnewpayrolldate').val('{{$payrolldate[0]->dateto}} - {{$payrolldate[0]->dateto}}');
        $('#setnewpayrolldate').daterangepicker({
            locale: {
                format: 'MM-DD-YYYY'
            },
            minDate : '{{$payrolldate[0]->dateto}}' + 1
        });
        $('input[name=date]').on('change',function(){
            $('#generatepaydate').attr('type','submit');
        })
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
        $('.changepayrolldate').on('click', function(){
            $('input[name=payrolldate]').attr('disabled',false);
            $(this).remove();
            $('.formpayrollsubmit').append(
                '<button type="submit" class="btn btn-sm btn-warning btn-block mb-2">Update</button>'
            )
        });
        $.fn.digits = function(){ 
            return this.each(function(){ 
                $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
            })
        }
        $("span.salary").digits();
        $('input[name=deductions]').on('click', function(){
            if($(this).prop('checked')==true){
                    // var employee_id = $(this).closest('div.form-group').find('input[name=employee_id]').val();
                    $(this).closest('div.form-group').find('input[name=status]').val('1');
                    $(this).closest('div.form-group').find('input[name=deductionid]').val($(this).val());
                    // var deduction_id = $(this).val();
                    $(this).closest('form[name=editdeductions]').submit();


            }
            else{
                    var employee_id = $(this).closest('div.form-group').find('input[name=employee_id]').val();
                    $(this).closest('div.form-group').find('input[name=status]').val('0');
                    $(this).closest('div.form-group').find('input[name=deductionid]').val($(this).val());
                    // var deduction_id = $(this).val();
                    $(this).closest('form[name=editdeductions]').submit();
            }
        });
   })
   $(document).ready(function(){
    $('body').addClass('sidebar-collapse')
        $(document).on('click','.deleteearning', function(){
            $('form[name=deleteearning]').submit();
        });
        $(document).on('click','.editearning', function(){
            $('form[name=editearning]').submit();
        });
        $(document).on('click','.employeeotherdeductiondelete', function(){
            $('form[name=employeeotherdeductiondelete]').submit();
        });
        $(document).on('click','.employeeotherdeductionedit', function(){
            $('form[name=employeeotherdeductionedit]').submit();
        });
   })
          $(document).on('click','#employeesalarypaybutton',function(){
              
            Swal.fire({
              title: $(this).attr('employeename'),
              html: '<br> Generating payslip...',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Continue',
              allowOutsideClick: false
            })
            .then((result) => {
              if (result.value) {
                event.preventDefault(); 
                $('#employeesalarypaysubmit').submit()
              }
            })
          })
  </script>
@endsection

