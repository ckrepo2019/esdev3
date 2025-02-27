@extends('hr.layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<style>
    #employee_datatables .form-control {
        height: calc(2rem + 1px)!important;
        padding: 0.2rem 1.5rem!important;
    }
    #loademployee2_datatables > thead > tr > th {
        padding-right: 0px;
        padding-left: 0px;
    }

    @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");

    .wrapper .icon {
        background-color: none!important;
        position: relative;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .tooltip {
        text-align: center;
        width: 150px;
        position: absolute;
        top: 0;
        font-size: 14px;
        padding: 5px 8px;
        border-radius: 5px;
        box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .tooltip::before {
        position: absolute;
        content: "";
        height: 8px;
        width: 8px;
        bottom: -3px;
        left: 50%;
        transform: translate(-50%) rotate(45deg);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 1000;

    }

    .wrapper .icon:hover .tooltip {
        top: -33px;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        z-index: 1000;

    }

    .wrapper .icon:hover span,
    .wrapper .icon:hover .tooltip {
        text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.1);
        z-index: 1000;

    }

    .wrapper .pdfs:hover .tooltip,
    .wrapper .pdfs:hover .tooltip::before {
        background: #0275d8;
        color: #fff;
        z-index: 1000;
    }
    .select2-container .select2-selection--single {
        height: 37px;
    }

</style>
<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Allowance Printables</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Allowance Printables</li>
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
{{-- MODAL --}}
{{-- MODAL END --}}

<section class="content">
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="allowances_datatables"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="2%">&nbsp;&nbsp;No.</th>
                                    <th width="53%">Deductions</th>
                                    <th class="text-center" width="15%">Action</th>
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
        // variable calls
        var allowances = [];
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
        load_allowances()
    
       
        
        // ==============================================================================================================================================
        
        // ============================================================= Modal Close ====================================================================
     

        // ========================================================= click event sections ===============================================================
        $(document).on('click', '#pdfallowance', function(){
            var allowanceid = $(this).attr('allowanceid')
            var payrollid = $('#payrollid').val()

            window.open('/payrollclerk/setup/printallowance?allowanceid='+allowanceid+'&payrollid='+payrollid,'_blank')
        })
        // ========================================================== Functions =========================================================================
        function load_allowances(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/allowanceslist",
                success: function (data) {
                    allowances = data;
                    load_allowancesdatatable()
                    payrollperiods()
                }
            });
        }

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

        // this function is for the deductions datatable
        function load_allowancesdatatable(){
            $('#allowances_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                order: false,
                data: allowances,
                columns : [
                    {"data" : 'description'},
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
                            var text = '<span>'+rowData.description+'</span>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = `<div class="text-center pdfs" style="display: flex; justify-content: center; align-items: center; font-size: 18px">
                                            <ul style="display: inline-flex; margin: 0; padding: 0;">
                                                <li class="icon" id="pdfallowance" allowanceid="${rowData.id}">
                                                    <span class="tooltip">Print PDF</span>
                                                    <span><i class="fas fa-file-pdf text-primary"></i></span>
                                                </li> &nbsp;&nbsp;
                                            </ul>
                                        </div>`;
                                        // <li class="icon">
                                        //     <span class="tooltip">Per Department</span>
                                        //     <span><i class="fas fa-users-cog text-primary"></i></span>
                                        // </li>
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }
                    }
                ]
            })
            var label_text = $($('#allowances_datatables_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = `<div class="col-md-6 p-0">
                                            <div class="form-group">
                                                <select class="form-control form-control-sm select2" id="payrollid"></select>
                                            </div>
                                        </div>`
        }

        
        // ==============================================================================================================================================

    })
</script>
@endsection


