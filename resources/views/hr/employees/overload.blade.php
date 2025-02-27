@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>OVERLOAD</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">OVERLOAD</li>
                </ol>
                </div>
        </div>
    </div>
</section>

@php
$refid = DB::table('usertype')
    ->where('id', Session::get('currentPortal'))
    ->first()->refid;
@endphp
<style>
    .select2-container .select2-selection--single {
        height: 37px;
    }
</style>
{{-- MODALS --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal_subject_loads">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Subject Loads : <span id="payrollrange"></span> <input type="hidden" id="payrollrangeid"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <table width="100%" class="table table-sm table-bordered table-head-fixed" id="subject_loads_datatables"  style="font-size: 15px; table-layout: fixed;">
                    <input type="hidden" id="teacher_id">
                    <input type="hidden" id="salarytypeid">
                    <input type="hidden" id="amountperhourallsubjs">
                    <thead>
                            <tr>
                                <td class="" width="5%">&nbsp;&nbsp;</td>
                                <td class="" width="30%" style="vertical-align: middle;">Subjects</td>
                                <td class="text-center" width="8%" style="vertical-align: middle;">Total <br> Days</td>
                                <td class="text-center" width="8%" style="vertical-align: middle;">Total Hours</td>
                                <td class="text-center" width="10%" style="vertical-align: middle;">Day <br> Absent</td>
                                <td class="text-center" width="11%" style="vertical-align: middle;">Late</td>
                                <td class="text-center" width="13%" style="vertical-align: middle;">Salary <br> per HR</td>
                                <td class="text-center" width="10%" style="vertical-align: middle;">Total <br> Amount</td>
                                <td class="text-center" width="5%" style="vertical-align: middle;">
                                    <div class="form-group form-check" style="margin: 0px!important; padding: 0px!important; padding-top: 3px!important;">
                                        <input type="checkbox" class="form-check-input"id="checkallsubjs" style="width: 18px; height: 18px; padding: 0px;margin: 0px; position: relative;"/>
                                    </div>
                                </td>
                            </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" id="saveallsubjs">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
{{-- END MODAL --}}

{{-- MODALS --}}
<div class="modal fade" tabindex="-1" role="dialog" id="modal_payrolldate">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Payroll Date : <span id="payrolldaterange"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            {{-- <div class="col-md-12">
                <table width="100%" class="table table-sm table-bordered table-head-fixed" id="employeepayroll_datatables"  style="font-size: 16px; table-layout: fixed;">
                    <thead>
                            <tr>
                                <td class="text-left" width="60%"><b>Date</b></td>
                                <td class="text-center" width="10%"><b>-</b></td>
                                <td class="text-center" width="10%"><b>Late</b></td>
                                <td class="text-center" width="10%"><b>Hourly</b></td>
                                <td class="text-center" width="10%"></td>
                            </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div> --}}
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" style="visibility: hidden">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
{{-- END MODAL --}}


<section class="content">
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                {{-- <div class="col-md-3 p-0">
                    <div class="form-group">
                        <select class="form-control form-control-sm select2" id="payrollid"></select>
                    </div>
                </div> --}}
                <div class="col-md-4">
                    <div class="input-group" style="">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <select class="form-control form-control-sm select2" id="overloadid"></select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-success activateoverloaddate" id="activateoverloaddate" data-action="activate">
                                <i class="fa fa-share"></i> Activate
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control float-right input-payrolldates" id="reservationnew" readonly="" data-id="2">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-sm btn-primary btn-overload-dates-submit" id="btn-overload-dates-submit" data-action="add">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="employee_datatables"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="2%">&nbsp;&nbsp;No.</th>
                                    <th width="53%">Employee</th>
                                    <th class="text-center" width="15%">Subjects</th>
                                    {{-- <th class="text-center" width="15%">Salary /hr</th> --}}
                                    <th class="text-center" width="10%">Action</th>
                              </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
</section>
  
@endsection
@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>

<script>
    $(document).ready(function(){

        // time range
        var availableDates = ["2-1-2019","3-1-2019","4-1-2019"];
        $('#reservationnew').daterangepicker({
            locale: {
            format: 'M/DD/YYYY'
            }
        })
        $(function()
        {
            $('.input-adddates').datepicker({ beforeShowDay:
            function(dt)
            { 
                return [available(dt), "" ];
            }
        , changeMonth: true, changeYear: false});
        });


        function available(date) {
            dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
            if ($.inArray(dmy, availableDates) != -1) {
                return true;
            } else {
                return false;
            }
        }
        // ==============================================================================================================================================
        // variable calls
        var valid_data = true;
        var syid = @json($sy->id);
        var semid = @json($semester->id);
        var payrolldates = [];
        var employee_list = [];
        var overloadperiods = [];
        
        // ==============================================================================================================================================

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });
        
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // ============================================================= function calls =================================================================
        overloadperiod()
        load_employees()
        payrollperiods()

        // ==============================================================================================================================================
        
        // ============================================================= Modal Close ====================================================================
        $('#modal_payrolldate').on('hide.bs.modal', function (e) {
            $('#modal_payrolldate .modal-body').empty()
        })
        
        // ========================================================= click event sections ===============================================================

        $(document).on('click', '#btn-overload-dates-submit', function(){
            var dataaction = $(this).attr('data-action')
            
            if (dataaction == 'add') {
                var dates = $('#reservationnew').val()
            } else {
                var dates = $('#reservation').val()
            }
            Swal.fire({
                title: 'Processing data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })

            $.ajax({
                url: '/payrollclerk/setup/overload/addoverloadperiod',
                type: 'get',
                data: {
                    action: dataaction,
                    dates   :   dates
                },
                success: function(data){
                    if(data[0].status == 0){
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }else{
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                }
            })

        })

        $(document).on('click', '#activateoverloaddate', function(){
            var overloaddateid = $('#overloadid').val()

            if (overloaddateid == null || overloaddateid == '') {
                Toast.fire({
                    type: 'error',
                    title: 'Please Select Date Range'
                })

                valid_data = false;
            }

            if (valid_data) {
                $.ajax({
                    type: "GET",
                    url: "/payrollclerk/setup/overload/activateoverloadperiod",
                    data: {
                        overloaddateid : overloaddateid
                    },
                    success: function (data) {
                        if(data[0].status == 0){
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }else{
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                });
            }


        })


        // ==============================================================================================================================================


        function load_employees(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/overload/loademployees",
                success: function (data) {
                    employee_list = data
                    load_employeedatatable()
                }
            });
        }

        // this function is for the employee list datatable
        function load_employeedatatable(){
            $('#employee_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: true,
                autoWidth: true,
                order: false,
                data: employee_list,
                columns : [
                    {"data" : 'full_name'},
                    {"data" : null},
                    // {"data" : null},
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var index = row + 1; // Start indexing from 1
                            var text = '<span>&nbsp;&nbsp;' + index + '</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0">'+rowData.full_name+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a href="javascript:void(0)" class="text-primary" id="subject_loads" teacherid="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'"></a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            // if (rowData.salarybasistype == null || rowData.salarybasistype == '') {
                            //     var buttons = '<div class="text-center" style="display: flex; justify-content: center; align-items: center;">' +
                            //         // '<a href="javascript:void(0)" id="addoverload" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'"><i class="fas fa-plus text-primary"></i></a>&nbsp;&nbsp;' +
                            //         '<a href="javascript:void(0)" id="nosalarytype" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'"><i class="fas fa-minus text-danger"></i></a>' +
                            //         '<input type="hidden" class="" style="width: 18px; height: 18px;">' +
                            //         '</div>';
                            // } else {
                            //     var buttons = '<div class="text-center" style="display: flex; justify-content: center; align-items: center;">' +
                            //         '<a href="javascript:void(0)" id="addoverload" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'"><i class="fas fa-plus text-primary"></i></a>&nbsp;&nbsp;' +
                            //         '<a href="javascript:void(0)" id="viewpayroll" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'"><i class="fas fa-user-cog text-primary"></i></a>' +
                            //         '<input type="hidden" class="" style="width: 18px; height: 18px;">' +
                            //         '</div>';
                            // }
                            var buttons = '';
                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    }
                ]
            })
            // var label_text = $($('#employee_datatables_wrapper')[0].children[0])[0].children[0]
            // $(label_text)[0].innerHTML = `<div class="col-md-6 p-0">
            //                                 <div class="form-group">
            //                                     <select class="form-control form-control-sm select2" id="payrollid"></select>
            //                                 </div>
            //                             </div>`
            // var label_text = $($('#employee_datatables_wrapper')[0].children[0])[0].children[0]
            // $(label_text)[0].innerHTML = `<div class="col-md-6 p-0">
            //                                     <input type="text" class="form-control float-right input-payrolldates" id="reservation">
            //                             </div>`

        

        }

        
        // load all payroll dates that is active
        function payrollperiods(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/additionalearningdeductions/loadpayrollperiods",
                success: function (data) {
                    payrollperiodss = data;
                    
                    $('#payrollid').empty();
                    $('#payrollid').append('<option value="">Select Payroll Date</option>')
                    $('#payrollid').select2({
                        data: data,
                        allowClear: true,
                        placeholder: {
                            id: '',
                            text: 'Select Payroll Date',
                            template: function (data) {
                                return '<span style="font-size: 9px; font-weight: normal;">' + data.text + '</span>';
                            }
                        }
                    });
                    
                }
            });
        }


        function overloadperiod(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/overload/loadoverloadperiods",
                // data: "data",
                // dataType: "dataType",
                success: function (data) {
                    overloadperiods = data
                    
                    $('#overloadid').empty();
                    $('#overloadid').append('<option value="">Select Date Range</option>')
                    $('#overloadid').select2({
                        data: overloadperiods,
                        allowClear: true,
                        placeholder: {
                            id: '',
                            text: 'Select Date Range',
                            template: function (data) {
                                return '<span style="font-size: 9px; font-weight: normal;">' + data.text + '</span>';
                            }
                        }
                    });
                }
            });
        }

    })
</script>
@endsection


