@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
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
  .printbutton{
      display: block;
      width: 100%;
  }

}
.swal2-header{
    border:none;
    padding: 0px;
}
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Payroll Summary</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Payroll Summary</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@php
    $totalpayroll = 0;
@endphp
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-3">
                <form action="/payrollhistory" method="get">
                    <input type="hidden" value="1" name="changepayrollhistory"/>
                    <select name="payrollid" class="form-control form-control-sm">
                        @foreach($getdaterange as $daterange)
                            <option value="{{$daterange->id}}" {{$daterange->selected == '1' ? 'selected' : ''}}>{{$daterange->datefrom}} - {{$daterange->dateto}}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if(count($history) > 0)
                <div class="col-md-9">
                    <button type="button" class="btn btn-sm btn-primary printbutton float-right exportbutton" data-toggle="modal" data-target="#exportoptions"><i class="fa fa-download"></i> Export | Release payroll</button>
                    <div id="exportoptions" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Format</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="/hr/printpayrollhistory/{{Crypt::encrypt('bypayrollperiod')}}" method="get"  target="_blank" name="export">
                                        @foreach($getdaterange as $daterange)
                                            @if($daterange->selected == '1')
                                                <input type="hidden" name="payrollid" value="{{$daterange->id}}"/>
                                            @endif
                                        @endforeach
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-control form-control-sm" name="format" required>
                                                    <option value="">Select Format</option>
                                                    <option value="table">Table Format</option>
                                                    <option value="payslip">Payslip Format</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control form-control-sm" name="exporttype" required>
                                                    <option value="">Select Export Type</option>
                                                    <option value="pdf">PDF</option>
                                                    <option value="excel">Excel</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn float-right btn-primary exportformbutton">Export | Release</button>
                                    </form>
                                        {{-- <button type="button" class="btn btn-default"> Payslip Format</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row div-only-mobile">
            <span class="right badge badge-warning">Swipe the table to the left to view more information</span>
            
        </div>
        {{-- <span class="right badge badge-warning">For individual releasing, </span> --}}
    </div>
    <div class="card-body" style="overflow: scroll;heigh: 400px;">
        <table id="example1" class="table" style="font-size: 13px;">
            <thead style="text-align: center;background-color: #ccffff;">
                <tr>
                    <th style="width: 20%;">Employee</th>
                    <th style="width: 13%;">Designation</th>
                    <th style="width: 15%;">Rate</th>
                    <th style="background-color: #ccffcc">
                        Days<br>Worked
                    </th>
                    <th style="background-color: #ffd6cc">
                        Days<br>Absent
                    </th>
                    <th style="background-color: #ccffcc">
                        Total<br>Earnings
                    </th>
                    <th style="background-color: #ffd6cc">
                        Total<br>Deductions
                    </th>
                    <th>
                        Net Pay
                    </th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody class="accordionitemtr text-uppercase" style="text-align: center;">
                @foreach($history as $historydetail)
                    <tr>
                        <td style="width: 20%;">
                            <span class="card-title" style="font-size: 13px;">
                                <a data-toggle="collapse" id="{{$historydetail->history->id}}{{$historydetail->history->lastname}}" data-parent="#accordion" href="#{{$historydetail->history->id}}{{$historydetail->history->lastname}}" class="accordionitem collapsed">
                                    {{$historydetail->history->lastname}}, {{$historydetail->history->firstname}} {{$historydetail->history->middlename[0]}}. {{$historydetail->history->suffix}}
                                </a>
                            </span>
                        </td>
                        <td>{{$historydetail->history->designation}}</td>
                        <td>&#8369; {{$historydetail->history->basicpay}} / {{$historydetail->history->ratetype}}</td>
                        <td style="background-color: #ccffcc; text-align: center;">
                            @if($historydetail->daysworked == 0)
                                ---
                            @else
                                {{$historydetail->daysworked}}
                            @endif
                        </td>
                        <td style="background-color: #ffd6cc;text-align: center;">
                            @if($historydetail->daysabsent == 0)
                                ---
                            @else
                                {{$historydetail->daysabsent}}
                            @endif
                        </td>
                        <td style="background-color: #ccffcc">&#8369; {{$historydetail->history->totalearnings}}</td>
                        <td style="background-color: #ffd6cc">&#8369; {{$historydetail->history->totaldeductions}}</td>
                        <td>&#8369; {{number_format($historydetail->history->netpay,2,'.',',')}}</td>
                        <td>
                            <form action="/hr/printpayrollhistory/{{Crypt::encrypt('individual')}}" target="_blank">
                                <input name="employeeid" value="{{$historydetail->history->employeeid}}" type="hidden"/>
                                <input name="payrollid" value="{{$historydetail->history->payrollid}}" type="hidden"/>
                                @if($historydetail->history->isreleased == 0)
                                    <button type="submit" class="btn btn-sm btn-default float-right">Pay</button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-default float-right">Print</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    <tr id="{{$historydetail->history->id}}">
                        <td colspan="9">
                            <div id="{{$historydetail->history->id}}{{$historydetail->history->lastname}}" class="accordionitemdiv{{$historydetail->history->id}}{{$historydetail->history->lastname}} panel-collapse in collapse" style="">
                                <div class="card-body">
                                    <table style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td >
                                                    <div class="row">
                                                        <div class="col-6">Salary Period</div>
                                                        <div class="col-6">
                                                            <small>{{$historydetail->history->payrolldatefrom}} - {{$historydetail->history->payrolldateto}}</small>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">Basic Pay</div>
                                                        <div class="col-6"><small>&#8369; {{number_format($historydetail->history->basicpay,2,'.',',')}}</small></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">Rate Type</div>
                                                        <div class="col-6"><small>{{$historydetail->history->ratetype}}</small></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">Attendance</div>
                                                        <div class="col-6"><small>&#8369; {{number_format($historydetail->history->attendancesalary,2,'.',',')}}</small></div>
                                                    </div>
                                                    @if($historydetail->history->overtimepay > 0)
                                                    <div class="row">
                                                        <div class="col-6">Overtime</div>
                                                        <div class="col-6"><small>&#8369; {{number_format($historydetail->history->overtimepay,2,'.',',')}}</small></div>
                                                    </div>
                                                    @endif
                                                    @if($historydetail->history->holidaypay > 0)
                                                    <div class="row">
                                                        <div class="col-6">Holiday/s</div>
                                                        <div class="col-6"><small>&#8369; {{$historydetail->history->holidaypay}}</small></div>
                                                    </div>
                                                    @endif
                                                    @if($historydetail->history->holidayovertimepay > 0)
                                                    <div class="row">
                                                        <div class="col-6">Holiday/s (Overtime)</div>
                                                        <div class="col-6"><small>&#8369; {{$historydetail->history->holidayovertimepay}}</small></div>
                                                    </div>
                                                    @endif
                                                    @foreach($historydetail->historydetail as $historydetails)
                                                        @if($historydetails->type == "earnedleave")
                                                            <div class="row">
                                                                <div class="col-6">{{$historydetails->description}}</div>
                                                                <div class="col-6"><small> + &#8369; {{$historydetails->amount}}</small></div>
                                                            </div>
                                                        @elseif($historydetails->type == "deductleave")
                                                        <div class="row">
                                                            <div class="col-6">{{$historydetails->description}}</div>
                                                            <div class="col-6"><small> - &#8369; {{$historydetails->amount}}</small></div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px;">
                                                    <table style="width:100%; font-size: 12px;">
                                                        <tr>
                                                            <th colspan="2">Standard Allowances</th>
                                                        </tr>
                                                        @foreach($historydetail->historydetail as $historydetails)
                                                            @if($historydetails->type == "standardallowance")
                                                                <tr>
                                                                    <td style="width: 80% !important;">{{$historydetails->description}}</td>
                                                                    <td>&#8369; {{number_format($historydetails->amount,2,'.',',')}}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </table>
                                                    <table style="width:100%; font-size: 12px;">
                                                        <tr>
                                                            <th colspan="2">Other Allowances</th>
                                                        </tr>
                                                        @foreach($historydetail->historydetail as $historydetails)
                                                            @if($historydetails->type == "otherallowance")
                                                                <tr>
                                                                    <td style="width: 80% !important;">{{$historydetails->description}}</td>
                                                                    <td>&#8369; {{number_format($historydetails->amount,2,'.',',')}}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </table>
                                                </td>
                                                <td style="font-size: 11px;">
                                                    <table style="width:100%; font-size: 12px;">
                                                        <tr>
                                                            <th colspan="2">Standard Deductions</th>
                                                        </tr>
                                                        @foreach($historydetail->historydetail as $historydetails)
                                                            @if($historydetails->type == "standarddeduction")
                                                                <tr>
                                                                    <td style="width: 80% !important;">{{$historydetails->description}}</td>
                                                                    <td>&#8369; {{number_format($historydetails->amount,2,'.',',')}}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </table>
                                                    <table style="width:100%; font-size: 12px;">
                                                        <tr>
                                                            <th colspan="2">Other Deductions</th>
                                                        </tr>
                                                        @foreach($historydetail->historydetail as $historydetails)
                                                            @if($historydetails->type == "otherdeduction")
                                                                <tr>
                                                                    <td style="width: 80% !important;">{{$historydetails->description}}</td>
                                                                    <td>&#8369; {{number_format($historydetails->amount,2,'.',',')}}</td>
                                                                </tr>
                                                            @endif

                                                        @endforeach
                                                        @if($historydetail->history->tardinessamount > 0)
                                                                <tr>
                                                                    <td style="width: 80% !important;">Tardiness</td>
                                                                    <td>&#8369; {{number_format($historydetail->history->tardinessamount,2,'.',',')}}</td>
                                                                </tr>
                                                        @endif
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr style="font-size: 12px" class="text-uppercase">
                                                <td>
                                                    <strong>Total Earnings: &#8369; {{$historydetail->history->totalearnings}}</strong>
                                                </td>
                                                <td>
                                                    <strong>Total Deductions: &#8369; {{$historydetail->history->totaldeductions}}</strong>
                                                </td>
                                            </tr>
                                            <tr style="font-size: 12px" class="text-uppercase">
                                                <td><p><strong>Net Salary: &#8369; {{number_format($historydetail->history->netpay,2,'.',',')}}</strong> </p></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>

                    @php
                        if($historydetail->history->netpay>=0)
                        {
                        $totalpayroll += $historydetail->history->netpay;
                        }
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-8">&nbsp;</div>
        <div class="col-md-4">
            <span >
                <h5><strong>TOTAL PAYROLL: &#8369; {{number_format($totalpayroll,2,'.',',')}}</strong></h5>
            </span>
        </div>
    </div>
</div>
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
    // $(document).ready(function(){
    //     @if (\Session::has('excelfeedback'))
    //         Swal.fire({
    //             title: "Payroll summary exported successfully!",
    //             text: "Directory: {{\Session::get('excelfeedback')}}",
    //             type: 'success'
    //         })
    //     @endif
    // })
    // $()
    // $(document).on('click','a.nav-link', function(){
    //     $('div.tab-pane').removeClass('fade show active');
    //     $('div.tab-pane#'+$(this).attr('id')).addClass('fade show active');
    // })
        // $("#example1").DataTable({
        //     pageLength : 10,
        //     lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        // });
    $(document).on('click','.accordionitem', function(){
        // console.log()
        if($(this).hasClass('collapsed') == true){
            // console.log($(this).closest('.card-primary').find('.accordionitemdiv').attr()
            $(this).removeClass('collapsed')
            $(this).closest('.accordionitemtr').find('.accordionitemdiv'+$(this).attr('id')).addClass('show')
        }else{
            $(this).addClass('collapsed')
            $(this).closest('.accordionitemtr').find('.accordionitemdiv'+$(this).attr('id')).removeClass('show')
        }
    })
    $(document).on('change','select[name=payrollid]', function(){
        $(this).closest('form').submit();
    })
    $(function () {
        $(".dataTable").DataTable({
            pageLength : 5,
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
        $.fn.digits = function(){ 
            return this.each(function(){ 
                $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") ); 
            })
        }
        $("span.salary").digits();
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
   });
   $(document).on('change','select[name="format"]', function(){
       if($(this).val() == 'payslip'){
        $('select[name="exporttype"]').empty()
        $('select[name="exporttype"]').append(
            '<option value="pdf">PDF</option>'
        )
       }
   })
   $(document).on('change','select[name="exporttype"]', function(){
       if($(this).val() == 'excel'){
           $(this).closest('form').prop('target','')
       }
   });
   $(document).on('click','.exportformbutton', function(){
       $('#exportoptions').modal('hide');
    //    if($('select[name="exporttype"]').val() == 'excel'){

    //     Swal.fire({
    //         title: 'Exporting payroll as excel file...',
    //         // html: 'I will close in <b></b> milliseconds.',
    //         timerProgressBar: true,
    //         onBeforeOpen: () => {
    //             Swal.showLoading()
    //         }
    //     }).then((result) => {
    //         /* Read more about handling dismissals below */
    //         // if (result.dismiss === Swal.DismissReason.timer) {
    //         //     console.log('I was closed by the timer')
    //         // }
    //     })
    //    }
   })
    // $('.exportbutton').click(function() {
        
    //     // console.log()
    //     Swal.fire({
    //         title               : 'Export as',
    //         // confirmButtonText   : 'EXCEL',
    //         cancelButtonText    : 'PDF',
    //         confirmButtonClass  : 'some-class',
    //         cancelButtonClass   : 'some-class',
    //         showCancelButton    : true,
    //         showCloseButton     : true,
    //         showConfirmButton    : false,
    //         // showLoaderOnConfirm: true,
    //         allowOutsideClick   : false,
    //         preConfirm: () => {
    //             $.ajax({
    //                 url         : '/printpayrollhistory/{{Crypt::encrypt("bypayrollperiod")}}',
    //                 data        : {
    //                             exporttype  : 'excel',
    //                             payrollid   : $('input[name=payrollid]').val(),
    //                 },
    //                 success    : function(data){
    //                     console.log('asda')
    //                 }

    //             })
    //         }
    //         }).then(function(result) {
    //             if (result.value) {
    //             } else {
    //                 if(result.dismiss == 'cancel'){
    //                     $('input[name=exporttype]').val('pdf');
    //                     $('form[name=export]').submit();
    //                 }
    //             }
    //         })
    // });
    // $('.exportbutton').click(function() {
        
    //     // console.log()
    //     Swal.fire({
    //         // confirmButtonText: 'EXCEL',
    //         title               : 'Export as',
    //         html                : '<button type="button" id="excelbutton" class="btn btn-default m-3">Excel</button>'+
    //                               '<button type="button" id="pdfbutton" class="btn btn-default">PDF</button>',

    //         // cancelButtonText    : 'Cancel',
    //         showConfirmButton   : false,
    //         showCancelButton    : false
    //     })

    // });
    // $(document).on('click', "#excelbutton", function() {
    //     $(this).closest('.swal2-shown').addClass('swal2-close')
    //     $(this).closest('.swal2-shown').removeClass('swal2-shown')
    // });
  </script>
@endsection
