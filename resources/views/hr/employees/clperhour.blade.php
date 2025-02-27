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
    }

    .wrapper .icon:hover .tooltip {
        top: -30px;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .wrapper .icon:hover span,
    .wrapper .icon:hover .tooltip {
        text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.1);
    }

    .wrapper .instagram:hover .tooltip,
    .wrapper .instagram:hover .tooltip::before {
        background: #f0ad4e;
        color: #000;
    }

</style>
<section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>PER HOUR</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">PER HOUR</li>
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

<div class="modal fade" tabindex="-1" role="dialog" id="modalperhour">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="display: flex">
            List Rate Per Hour
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <table width="100%" class="table table-sm table-bordered " id="loadhourly_datatables"  style="font-size: 16px; table-layout: fixed;">
                    <thead>
                            <tr>
                                <th class="text-left" width="70%">Hourly Rate</th>
                                <th class="text-center" width="30%">Action</th>
                            </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modaladdhourlyrate">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="display: flex">
            Add Rate Per Hour
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="hourlyamount">Amount</label>
                    <input type="number" class="form-control" id="hourlyamount" aria-describedby="" min="0" oninput="sanitizeInput(this)">
                </div>
                <button class="btn btn-primary" id="savehourlyrate">Submit</button>
            </div>
          </div>
        </div>
        
      </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modalassignperemp">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="display: flex">
            Assign Per Employee
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <table width="100%" class="table table-sm table-bordered " id="loademployee2_datatables"  style="font-size: 16px; table-layout: fixed;">
                    <input type="hidden" id="amountdesc">
                    <thead>
                            <tr>
                                <th class="text-left" width="75%">&nbsp;Employee</th>
                                <th class="text-center" width="25%">
                                    <div class="form-group form-check" style="margin: 0px!important; padding: 0px!important; padding-top: 5px!important;">
                                        <input type="checkbox" class="form-check-input" id="checkallemps" style="width: 18px; height: 18px; padding: 0px;margin: 0px; position: relative;"/>
                                    </div>
                                </th>
                            </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="assignperemps">Assign</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
{{-- MODAL END --}}

<section class="content">
    <div class="card border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table width="100%" class="table table-sm table-bordered table-head-fixed " id="employee_datatables"  style="font-size: 16px">
                        <thead>
                              <tr>
                                    <th width="2%">&nbsp;&nbsp;No.</th>
                                    <th width="53%">Employee</th>
                                    <th class="text-center" width="15%">Per Hour</th>
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
    function sanitizeInput(inputElement) {
        // Remove non-numeric characters (except for dot and minus sign)
        inputElement.value = inputElement.value.replace(/[^0-9.-]/g, '');

        // Remove leading zeros
        inputElement.value = inputElement.value.replace(/^(-)?0+(?=\d)/, '$1');

        // If the input contains multiple dots, keep only the first one
        inputElement.value = inputElement.value.replace(/(\..*)\./g, '$1');

        // Allow at most two decimal places
        var parts = inputElement.value.split('.');
        if (parts.length > 1) {
            parts[1] = parts[1].slice(0, 2);
            inputElement.value = parts.join('.');
        }
    }
</script>
<script>
    $(document).ready(function(){
        // variable calls
        
        var all_sched = [];
        var employee_list = [];
        var hourlyrates = [];
        var checkboxStates = [];
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
        load_employees()
        load_hourlyrate()
        // allsched()
       
        
        // ==============================================================================================================================================
        
        // ============================================================= Modal Close ====================================================================
        $('#modalperhour').on('hide.bs.modal', function (e) {
            $(this).find('input').val('');
        })
        $('#modaladdhourlyrate').on('hide.bs.modal', function (e) {
            $(this).find('input').val('');
        })
        $('#modalassignperemp').on('hide.bs.modal', function (e) {
            $('tr').css('background-color', '');
            load_employees()
        })


        // ========================================================= click event sections ===============================================================
        // $(document).on('input', 'input[type="number"]', function() {
        //     if (this.value === '') {
        //         this.value = '0';
        //     }

        //     var parts = this.value.split('.');
        //     if (parts.length > 1) {
        //         parts[1] = parts[1].slice(0, 2);
        //         this.value = parts.join('.');
        //     }
        // });


        $(document).on('click', '#addperhour', function(){
            $('#modalperhour').modal('show')
        })

        $(document).on('click', '#addhourlyrate', function(){
            $('#modaladdhourlyrate').modal('show')
        })

        $(document).on('click', '#savehourlyrate', function(){
            var valid_data = true;
            var hourlyamount = $('#hourlyamount').val()

            if (hourlyamount == null || hourlyamount == 0) {
                Toast.fire({
                    type: 'error',
                    title: 'Amount Should not be empty or 0 !'
                })
                valid_data = false;
            } 
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/collegesubjectperhour/addhourlyrate",
                data: {
                    hourlyamount : hourlyamount
                },
                success: function (data) {
                    if(data[0].status == 0){
                        Toast.fire({
                        type: 'error',
                        title: data[0].message
                        })
                    }else{
                        load_hourlyrate()
                        Toast.fire({
                        type: 'success',
                        title: data[0].message
                        })
                    }
                }
            });
        })

        $(document).on('input', '.peremployeehourlyrate', function(){
            var teacherid = $(this).attr('teacherid')
            // var hourlyamount = $('#peremployeehourlyrate'+teacherid).val();
            var hourlyamount = $(this).val();
            console.log(hourlyamount);
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/collegesubjectperhour/addhourlyrateperemp",
                data: {
                    teacherid : teacherid,
                    hourlyamount : hourlyamount
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
        })

        $(document).on('click', '.assignperemp', function(){
            var datadesc = $(this).attr('datadesc')
            var id = $(this).attr('data-id')

            var closestTr = $('#assignperemp' + id).closest('tr');
            closestTr.css({
                'background-color': 'rgba(42, 166, 255, 0.313)'
            });

            $('#amountdesc').val(datadesc)
            $('#modalassignperemp').modal('show')
        })

        // check all subjects in Subject loads Modal
        $(document).on('change', '#checkallemps',  function(){
            if ($(this).is(':checked')) {
                $('.employees').prop('checked', true);
                $('#assignperemps').addClass('savebyall')
                $('#assignperemps').removeClass('savebyrow')

            } else {
                $('#assignperemps').removeClass('savebyall')
                $('#assignperemps').addClass('savebyrow')
                $('.employees').prop('checked', false);
            }
        })

        // Update "Check All" checkbox when individual subject checkboxes are clicked
        $(document).on('change', '.employees', function() {
            var allEmployeesChecked = $('.employees:checked').length === $('.employees').length;
            $('#assignperemps').removeClass('savebyall')
            $('#assignperemps').addClass('savebyrow')
            $('#checkallemps').prop('checked', allEmployeesChecked);
        });

        // Click Save Changes
        $(document).on('click', '.savebyrow, .savebyall', function(){
            var amount = $('#amountdesc').val();
            saveCheckedRows(amount)
        })
        // ========================================================== Functions =========================================================================
        function load_employees(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/parttime/loademployees",
                success: function (data) {
                    employee_list = data
                    load_employeedatatable()
                    load_employeedatatable2()
                }
            });
        }

        function load_hourlyrate(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/collegesubjectperhour/loadhourlyrates",
                success: function (data) {
                    hourlyrates = data
                    load_hourlyratesdatatable()
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
                            var text = '<a class="mb-0" style="text-transform: uppercase;">'+rowData.full_name+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.clsubjperhour == null) {
                                var text = '<span class="badge badge-warning" style="font-weight: 400; font-size: 14px; color: #000;">No Basic Salary info</span>';
                            } else {
                                var text = '<input type="number" class="form-control text-center peremployeehourlyrate" id="peremployeehourlyrate'+rowData.id+'" teacherid="'+rowData.id+'" name="default-amount" value="'+rowData.clsubjperhour+'" step="1" min="0"  pattern="\d+(\.\d{1,2})?" title="Enter a valid number with up to two decimal places"/>';
                            }
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                            $(td).css('height', 'calc(2rem + 1px)')
                            $(td).css('padding', '0.2rem .75rem')
                        }
                    }
                ]
            })
            var label_text = $($('#employee_datatables_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = `<a href="javascript:void(0)" class="text-primary" id="addperhour" style="margin-left: 10px; height: 34px;"><i class="fas fa-plus"></i>&nbsp;Add Per Hour</a>`
        }

        function load_hourlyratesdatatable(){
            $('#loadhourly_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                searching: false,
                order: false,
                data: hourlyrates,
                columns : [
                    {"data" : null},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '<span>'+rowData.amount+'</span>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a href="javascript:void(0)" class="text-primary assignperemp" id="assignperemp'+rowData.id+'" data-id="'+rowData.id+'" datadesc="'+rowData.amount+'" style=""><i class="fas fa-users"></i></a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    }
                ]
            })
            var label_text = $($('#loadhourly_datatables_wrapper')[0].children[0])[0].children[0]
            $(label_text)[0].innerHTML = `<a href="javascript:void(0)" class="text-primary" id="addhourlyrate" style="margin-left: 10px; height: 34px;"><i class="fas fa-plus"></i>&nbsp;Add</a>`
        }

        // this function is for the employee list datatable
        function load_employeedatatable2(){
            $('#loademployee2_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                order: false,
                searching: false,
                data: employee_list,
                columns : [
                    {"data" : 'full_name'},
                    {"data" : null}
                ], 
                columnDefs: [
                    {
                        'targets': 0,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<a class="mb-0" style="text-transform: uppercase;">'+rowData.full_name+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-left')
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.clsubjperhour == null) {
                                var text = `
                                <li class="icon instagram">
                                    <span class="tooltip">No Basic Salary Info</span>
                                    <span><i class="fas fa-minus text-warning" style=""></i></span>
                                </li>
                                `;
                            } else if (rowData.clsubjperhour == 0) {
                                var text = '<input type="checkbox" class="employees" teacherid="'+rowData.id+'" style="width: 18px; height: 18px; margin-top: 5px;">';
                            } else {
                                var text = '<input type="number" class="form-control text-center peremployeehourlyrate" id="peremployeehourlyrate'+rowData.id+'" teacherid="'+rowData.id+'" name="default-amount" value="'+rowData.clsubjperhour+'" step="1" min="0"  pattern="\d+(\.\d{1,2})?" title="Enter a valid number with up to two decimal places"/>';
                            }
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-center');
                        }
                    }
                ]
            })
        }


        // Function to save checked rows
        function saveCheckedRows(amount) {
            var employeesarray = [];
          
            $('.employees:checked').each(function() {
                var teacherid = $(this).attr('teacherid');
                
                var obj = {
                    teacherid: teacherid,
                    amount: amount
                };


                employeesarray.push(obj);
            });
        
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log('CSRF Token:', csrfToken);

            $.ajax({
                type: 'POST', // Change this to 'POST'
                url: '/payrollclerk/setup/collegesubjectperhour/addhourlyrateperrow',
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Include the token in the request headers
                },
                data: JSON.stringify({ employeesarray: employeesarray }), // Send data as JSON
                contentType: 'application/json', // Set content type to JSON
                success: function(data) {
                    if (data[0].status == 0) {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        });
                    } else {
                        load_employees();
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        });
                    }
                },
                error: function(error) {
                    console.error('Error saving data', error);
                }
            });
        }
        
        // ==============================================================================================================================================

    })
</script>
@endsection


