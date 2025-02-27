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
        // variable calls
        var syid = @json($sy->id);
        var semid = @json($semester->id);
        var payrolldates = [];
        var employee_list = [];
        var collegeClassSched = [];
        var subjectarray = [];
        var employeeoverload = [];
        var alloverload = [];
        
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
        loadpayrollactive()
        load_alloverload()
        load_employees()
        allsched()
        // ==============================================================================================================================================
        
        // ============================================================= Modal Close ====================================================================
        $('#modal_payrolldate').on('hide.bs.modal', function (e) {
            $('#modal_payrolldate .modal-body').empty()
        })
        
        // ========================================================= click event sections ===============================================================

        $(document).on('click', '#subject_loads', function(){
            var teacherid = $(this).attr('teacherid');
            var salarytypeid = $(this).attr('data-salarytype')

            $('#teacher_id').val(teacherid)
            $('#salarytypeid').val(salarytypeid)
            var empactivepayroll = payrolldates.filter(x=>x.salarytypeid == salarytypeid)[0]
            if (empactivepayroll) {
                // Format the date range
                var dateFrom = new Date(empactivepayroll.datefrom);
                var dateTo = new Date(empactivepayroll.dateto);

                var options = { year: 'numeric', month: 'short', day: 'numeric' };
                var formattedDateFrom = dateFrom.toLocaleDateString('en-US', options);
                var formattedDateTo = dateTo.toLocaleDateString('en-US', options);

                var dateRange = formattedDateFrom + ' - ' + formattedDateTo;
            }
            
            $('#payrollrange').text(dateRange)
            $('#payrollrangeid').val(empactivepayroll.id)
            $('#modal_subject_loads').modal('show')
            allsched()
            load_employeeoverload(teacherid)
        })


        $(document).on('click', '#addoverload', function(){
            var teacherid = $(this).attr('teacherid');
            var rowid = $(this).attr('data-id');
            var salarytypeid = $(this).attr('data-salarytype')
            var amountperhourallsubjects = $('#subject_amountperhourallsubjs' + rowid).val();
            var empactivepayroll = payrolldates.filter(x=>x.salarytypeid == salarytypeid)[0]
            if (empactivepayroll) {
                // Format the date range
                var dateFrom = new Date(empactivepayroll.datefrom);
                var dateTo = new Date(empactivepayroll.dateto);

                var options = { year: 'numeric', month: 'short', day: 'numeric' };
                var formattedDateFrom = dateFrom.toLocaleDateString('en-US', options);
                var formattedDateTo = dateTo.toLocaleDateString('en-US', options);

                var dateRange = formattedDateFrom + ' - ' + formattedDateTo;
            }
            
            $('#payrollrange').text(dateRange)
            $('#teacher_id').val(teacherid)
            $('#payrollrangeid').val(empactivepayroll.id)
            $('#amountperhourallsubjs').val(amountperhourallsubjects)

            allsched()
            load_employeeoverload(teacherid)

            $('.subjectsamount').each(function() {
                $(this).val(amountperhourallsubjects);
            });
            
            // $('.subjects').prop('checked', true);
            // $('#checkallsubjs').prop('checked', true);
            
            $('#modal_subject_loads').modal('show')
            
        })

        // check all subjects in Subject loads Modal
        $(document).on('change', '#checkallsubjs',  function(){
            if ($(this).is(':checked')) {
                $('.subjects').prop('checked', true);
            } else {
                $('.subjects').prop('checked', false);
            }
        })

        // Update "Check All" checkbox when individual subject checkboxes are clicked
        $(document).on('change', '.subjects', function() {
            var allSubjectsChecked = $('.subjects:checked').length === $('.subjects').length;
            $('#checkallsubjs').prop('checked', allSubjectsChecked);
        });

        // Click Save Changes
        $(document).on('click', '#saveallsubjs', function(){
            var active = 1;
            var action = 'btnsavechanges';
            var rowid = null;

            saveCheckedRows(active, rowid, action)
        })

        // click each checkbox in Subject Loads Modal
        $(document).on('click', '.assignsubj', function(){
            var teacherid = $('#teacher_id').val();
            var rowid = $(this).attr('data-id')
            var action = 'savebyrow';
            if ($(this).is(':checked')) {
                var active = 1;
                saveCheckedRows(active, rowid, action)
                console.log('na check bobords');
            } else {
                var active = 0;
                saveCheckedRows(active, rowid, action)
                console.log('wala na check pri');
            }
            
        })

        // click setting icon viewpayroll
        $(document).on('click', '#viewpayroll', function(){
            var salarytypeid = $(this).attr('data-salarytype')
            var teacherid = $(this).attr('teacherid');

            var empactivepayroll = payrolldates.filter(x=>x.salarytypeid == salarytypeid)[0]

            if (empactivepayroll) {
                // Format the date range
                var dateFrom = new Date(empactivepayroll.datefrom);
                var dateTo = new Date(empactivepayroll.dateto);

                var options = { year: 'numeric', month: 'short', day: 'numeric' };
                var formattedDateFrom = dateFrom.toLocaleDateString('en-US', options);
                var formattedDateTo = dateTo.toLocaleDateString('en-US', options);

                var dateRange = formattedDateFrom + ' - ' + formattedDateTo;
            }

            // filter the alloverload array to get the employee specific load 
            var empspecificload = alloverload.filter(x=>x.employeeid == teacherid && x.active == 1)
            console.log(empactivepayroll);

            var dateRangeArray = [];

            var startDate = new Date(dateFrom);
            var endDate = new Date(dateTo);

            while (startDate <= endDate) {
                var formattedDate = startDate.toISOString().slice(0, 10);
                var dayOfWeek = getDayOfWeek(startDate);
                dateRangeArray.push({ date: formattedDate, day: dayOfWeek });
                startDate.setDate(startDate.getDate() + 1);
            }

            function getDayOfWeek(date) {
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                return days[date.getDay()];
            }

            // Iterate through the empspecificload array
            empspecificload.forEach(item => {
                // Create a table element
                var table = $(`
                    <table width="100%" class="table table-sm table-bordered table-head-fixed" id="employeepayroll_datatables"  style="font-size: 16px; table-layout: fixed;">
                        <thead>
                                <tr>
                                    <td class="text-left" width="70%"><b>Date : ${item.subjdesc}</b></td>
                                    <td class="text-center" width="10%"><b>-</b></td>
                                    <td class="text-center" width="10%"><b>Hourly</b></td>
                                    <td class="text-center" width="10%"></td>
                                </tr>
                        </thead>
                    </table>`);

                // Create a table row for each date in dateRangeArray
                dateRangeArray.forEach(dateRange => {
                    var row = $('<tr></tr>');
                    var dayAbbreviation = dateRange.day.slice(0, 3);
                    // Create table cells for the item properties and dateRangeArray data
                    var cell1 = $('<td width="70%"></td>').text(`${dateRange.date} : ${dayAbbreviation}`);
                    var cell2 = $('<td class="text-center" width="10%"></td>').text(`-`);
                    var cell3 = $('<td class="text-center" width="10%"></td>').text(item.hourlyrate);
                    var cell4 = $('<td class="text-center" width="10%"><input type="checkbox" class="" style="width: 18px; height: 18px; margin-top: 5px;" checked></td>');


                    // Append cells to the row
                    row.append(cell1, cell2, cell3, cell4);

                    // Append the row to the table
                    table.append(row);
                });

                // Append the table to the modal body
                $('#modal_payrolldate .modal-body').append(table)
            });
            // employee_payroll_tables()
            $('#payrolldaterange').text(dateRange)
            $('#modal_payrolldate').modal('show')
        })

        // click red setting icon nosalarytype
        $(document).on('click', '#nosalarytype', function(){
            Toast.fire({
                type: 'warning',
                title: 'Employee No Salary Type'
            });
        })
        // ==============================================================================================================================================


        function load_employees(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/overload/loademployees",
                success: function (data) {
                    employee_list = data
                        // Loop through employee_list
                        $.each(employee_list, function (index, employee) {
                        var employeeId = employee.id;

                        // Find matching alloverload entries by employeeId
                        var matchingAlloverload = alloverload.filter(function (alloverloadItem) {
                            return alloverloadItem.employeeid === employeeId && alloverloadItem.active === 1;
                        });

                        employee.matchingCount = matchingAlloverload.length;

                    });
                    load_employeedatatable()
                }
            });
        }

        // this function is for the employee list datatable
        function load_employeedatatable(){
            var teacherload = collegeClassSched;
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
                            var teacherloaddata = teacherload.filter(x=>x.teacherID == rowData.id);
                            var countteacherload = teacherloaddata.length;
                            var teacherid = rowData.id;

                            var text = '<a href="javascript:void(0)" class="text-primary" id="subject_loads" teacherid="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'">'+rowData.matchingCount+' of '+countteacherload+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            if (rowData.salarybasistype == null || rowData.salarybasistype == '') {
                                var buttons = '<div class="text-center" style="display: flex; justify-content: center; align-items: center;">' +
                                    '<a href="javascript:void(0)" id="addoverload" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'"><i class="fas fa-plus text-primary"></i></a>&nbsp;&nbsp;' +
                                    '<a href="javascript:void(0)" id="nosalarytype" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'"><i class="fas fa-user-cog text-danger"></i></a>' +
                                    '<input type="hidden" class="" style="width: 18px; height: 18px;">' +
                                    '</div>';
                            } else {
                                var buttons = '<div class="text-center" style="display: flex; justify-content: center; align-items: center;">' +
                                    '<a href="javascript:void(0)" id="addoverload" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'"><i class="fas fa-plus text-primary"></i></a>&nbsp;&nbsp;' +
                                    '<a href="javascript:void(0)" id="viewpayroll" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'" data-salarytype="'+rowData.salarybasistype+'"><i class="fas fa-user-cog text-primary"></i></a>' +
                                    '<input type="hidden" class="" style="width: 18px; height: 18px;">' +
                                    '</div>';
                            }
                            
                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    }
                ]
            })
        }

        // this function is for subject load datatable
        function subject_loads_tables(finalCollegeClassSched, employeeoverload, payrolldates){
            var teacherid = $('#teacher_id').val();
            var salarytype_id = $('#salarytypeid').val();
            var teacherdata = finalCollegeClassSched.filter(x => x.teacherID == teacherid);
            // Assuming payrolldates is the array of active payroll data
            var activePayroll = payrolldates.filter(x => x.id == salarytype_id);
            // Check if active payroll is available
            if (activePayroll && activePayroll.length > 0) {
                // Access properties of the first element in the activePayroll array
                var activePayrollDates = activePayroll[0].datesarray;
                var activePayrollDays = activePayroll[0].daysarray;
                console.log(activePayrollDays);
                // Filter teacherdata based on active payroll dates
                // teacherdata = teacherdata.filter(entry => {
                //     // Check if scheddetail is present and has a valid stime
                //     if (entry.scheddetail && entry.scheddetail.length > 0 && entry.scheddetail[0].stime) {
                //         var entryDate = new Date(entry.scheddetail[0].stime);

                //         // Log the entry and relevant information for debugging
                //         console.log("Entry:", entry);
                //         console.log("Entry Date:", entryDate.toLocaleDateString());

                //         // Check if the date is in the active payroll dates
                //         if (isNaN(entryDate.getTime())) {
                //             console.error("Invalid time value in entry:", entry);
                //         }

                //         return activePayrollDates.includes(entryDate.toLocaleDateString());
                //     }
                //     return false;
                // });

                // Count the number of matching days for each entry in teacherdata
                // Define an array to map numeric day values to corresponding strings
                var dayNames = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

                teacherdata = teacherdata.map(entry => {
                    var matchingDays = dayNames.reduce((count, dayName) => {
                        var matchingOccurrences = entry.scheddetail.filter(sched => sched.day === dayNames.indexOf(dayName) + 1).length;
                        return {
                            ...count,
                            [dayName]: matchingOccurrences
                        };
                    }, {});

                    return {
                        ...entry,
                        matchingDays: matchingDays
                    };

                    console.log('matchingDays');
                });

            }
            // console.log('teacherdata');
            // console.log(teacherdata);
            // console.log('teacherdata');

            $('#subject_loads_datatables').DataTable({
                destroy: true,
                lengthChange: false,
                scrollX: false,
                autoWidth: false,
                searching: false,
                order: false,
                data: teacherdata, 
                columns : [
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
                    {"data" : null},
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
                        'createdCell': function (td, cellData, rowData, row, col) {
                                  
                            var totalHours = 0;
                            
                            var starttime =  moment(rowData.scheddetail[0].stime, "HH:mm:ss");
                            var endttime =  moment(rowData.scheddetail[0].etime, "HH:mm:ss");
                            var duration = moment.duration(endttime.diff(starttime));
                            totalHours += duration.asHours();

                            if (totalHours > 1) {
                                var ext = 'hrs';
                            } else {
                                var ext = 'hr';
                            }
                            var text = '<div style="display: inline-grid;">' +
                                '<span class="mb-0">' + rowData.subjDesc + '</span>' +
                                '<small style="font-size: 12px;"> <span class="badge badge-info" style="font-weight: bold;"> '+totalHours+' '+ext+' </span>&nbsp;' + rowData.subjCode + '&nbsp; :&nbsp;';
                          

                            
                            // Loop through the scheddetail for the current class schedule
                            for (var i = 0; i < rowData.scheddetail.length; i++) {
                                var day = rowData.scheddetail[i].day;

                                // Convert day to the corresponding day name
                                var dayNames = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat","Sun"];
                                var dayName = dayNames[day - 1];
                                text += '&nbsp;' + dayName + '&nbsp;/' 
                                
                            }

                            text += '</small></div>';
                            $(td)[0].innerHTML = text;
                            $(td).addClass('align-middle text-left');
                        }
                    },
                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function (td, cellData, rowData, row, col) {
                            var text = '<span>' + rowData.matchingDays + '</span>';
                            $(td).html(text).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var assigned = employeeoverload.filter(x => x.subjid == rowData.id && x.semid == rowData.semesterID && x.syid == rowData.syID);
                            
                            var text = '<span>0</span>';
                            
                            $(td).html(text).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var assigned = employeeoverload.filter(x => x.subjid == rowData.id && x.semid == rowData.semesterID && x.syid == rowData.syID);
                            
                            var text = '<input type="number" class="form-control text-center" style="border: none;" id="daysabsent' + rowData.id + '" name="default-amount" step="1" min="0"';
                          
                            text += '>';
                            
                            $(td).html(text).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 5,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var assigned = employeeoverload.filter(x => x.subjid == rowData.id && x.semid == rowData.semesterID && x.syid == rowData.syID);
                            
                            var text = '<input type="number" class="form-control text-center" style="border: none;" id="daysabsent' + rowData.id + '" name="default-amount" step="1" min="0"';
                          
                            text += '>';
                            
                            $(td).html(text).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 6,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var assigned = employeeoverload.filter(x => x.subjid == rowData.id && x.semid == rowData.semesterID && x.syid == rowData.syID);
                            
                            var text = '<input type="number" class="form-control text-center subjectsamount" style="border: none;" id="subject_amountperhour' + rowData.id + '" name="default-amount" step="1" min="0"';
                            
                            if (assigned.length > 0) {
                                text += ' value="' + assigned[0].hourlyrate + '"';
                            }
                            
                            text += '>';
                            
                            $(td).html(text).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 7,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var assigned = employeeoverload.filter(x => x.subjid == rowData.id && x.semid == rowData.semesterID && x.syid == rowData.syID);
                            
                            var text = '<span>0</span>';
                            
                            $(td).html(text).addClass('align-middle text-center');
                        }
                    },
                    {
                        'targets': 8,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var assigned = employeeoverload.filter(x => x.subjid == rowData.id && x.semid == rowData.semesterID && x.syid == rowData.syID);
                            
                            if (assigned.length > 0) {
                                if (assigned[0].active === 0) {
                                    var text = '<input type="checkbox" class="subjects assignsubj" data-id="'+rowData.id+'" data-syid="'+rowData.syID+'" data-semid="'+rowData.semesterID+'" id="subject'+rowData.id+'" data-scheddetails=\'' + JSON.stringify(rowData.scheddetail) + '\' style="width: 18px; height: 18px; margin-top: 5px;">';
                                } else {
                                    var text = '<input type="checkbox" class="subjects assignsubj" data-id="'+rowData.id+'" data-syid="'+rowData.syID+'" data-semid="'+rowData.semesterID+'" id="subject'+rowData.id+'" data-scheddetails=\'' + JSON.stringify(rowData.scheddetail) + '\' style="width: 18px; height: 18px; margin-top: 5px;" checked>';
                                }
                            } else {
                                var text = '<input type="checkbox" class="subjects" data-subjdesc="'+rowData.subjDesc+'" data-id="'+rowData.id+'" data-syid="'+rowData.syID+'" data-semid="'+rowData.semesterID+'" id="subject'+rowData.id+'" data-scheddetails=\'' + JSON.stringify(rowData.scheddetail) + '\' style="width: 18px; height: 18px; margin-top: 5px;">';
                            }
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    }
                ]
            });
        }

        // get all sched in college
        function allsched(){
            $.ajax({
                type: "GET",
                url: "/student/loading/allsched",
                data: {
                    syid: syid,
                    semid: semid,
                    filtersubjgroup: $('#filter_schedulegroup').val(),
                    filterroom: $('#filter_room').val(),
                    filterteacher: $('#filter_teacher').val(),
                    filterclasstype: $('#filter_classtype').val(),
                },
                success: function (data) {
                    var jsonString  = data;
                    var jsonObject = $.parseJSON(jsonString);

                    collegeClassSched = jsonObject.data[0].college_classsched;

                    var scheddetail = jsonObject.data[0].scheddetail;
                    var scheduleDetails = {};

                    $.each(collegeClassSched, function(index, classSched) {
                        scheduleDetails[classSched.id] = { ...classSched, scheddetail: [] };
                    });

                    $.each(scheddetail, function(index, detail) {
                        const { id, headerID, ...restOfDetail } = detail;
                        if (scheduleDetails[headerID]) {
                            scheduleDetails[headerID].scheddetail.push({ ...restOfDetail });
                        }
                    });

                    // Now, you have the combined data in scheduleDetails
                    var finalCollegeClassSched = Object.values(scheduleDetails);
                    // You can use finalCollegeClassSched in your functions
                    subject_loads_tables(finalCollegeClassSched, employeeoverload, payrolldates);
                    // console.log('payrolldates');
                    // console.log(payrolldates);
                    // console.log('payrolldates');
                    load_employees();
                }
            });
        }

        // Function to save checked rows
        function saveCheckedRows(active, rowid, action) {
   
            if (active == 0 && action == 'savebyrow') {
                var teacherid = $('#teacher_id').val();
                var rowid = rowid
                var amountperhour = $('#subject_amountperhour' + rowid).val();
                var subjid = rowid;
                var syid = $(this).attr('data-syid');
                var semid = $(this).attr('data-semid');
         
                var obj = {
                    teacherid: teacherid,
                    amountperhour: amountperhour,
                    subjid: subjid,
                    active: active,
                    syid: syid,
                    semid: semid,
                    action: action
                };


                subjectarray.push(obj);
            } else {
                $('.subjects:checked').each(function() {
                    console.log('aaaaaaaaaaaaaaa');
                    var teacherid = $('#teacher_id').val();
                    var rowid = $(this).attr('data-id')
                    var amountperhour = $('#subject_amountperhour' + rowid).val();
                    var subjid = $(this).attr('data-id');
                    var syid = $(this).attr('data-syid');
                    var semid = $(this).attr('data-semid');
                    var datascheddetails = $(this).data('scheddetails');
                    var subjdesc = $(this).attr('data-subjdesc'); 
                    // Create variables for each day and initialize them to 0
                    var monday = 0;
                    var tuesday = 0;
                    var wednesday = 0;
                    var thursday = 0;
                    var friday = 0;
                    var saturday = 0;
                    var sunday = 0;

                    // Initialize total hours
                    var totalHours = 0;
                    
                    // Iterate through the scheddetails and set the corresponding day variables to 1
                    datascheddetails.forEach(function(detail) {
                        if (detail.day === 1) {
                            monday = 1;
                        } else if (detail.day === 2) {
                            tuesday = 1;
                        } else if (detail.day === 3) {
                            wednesday = 1;
                        } else if (detail.day === 4) {
                            thursday = 1;
                        } else if (detail.day === 5) {
                            friday = 1;
                        } else if (detail.day === 6) {
                            saturday = 1;
                        } else if (detail.day === 7) {
                            sunday = 1;
                        }
                    });

                    // Calculate the total hours between stime and etime
                    var startTime = new Date('1970-01-01T' + datascheddetails[0].stime);
                    var endTime = new Date('1970-01-01T' + datascheddetails[0].etime);
                    var hours = (endTime - startTime) / 1000 / 60 / 60;
                    totalHours += hours;
                    

                    var obj = {
                        teacherid: teacherid,
                        amountperhour: amountperhour,
                        subjid: subjid,
                        active: active,
                        syid: syid,
                        semid: semid,
                        action: action,
                        // Include the day variables in the object
                        monday: monday,
                        tuesday: tuesday,
                        wednesday: wednesday,
                        thursday: thursday,
                        friday: friday,
                        saturday: saturday,
                        sunday: sunday,
                        totalHours: totalHours,
                        datascheddetails : datascheddetails,
                        subjdesc: subjdesc
                    };


                    subjectarray.push(obj);
                    console.log(subjectarray);
                });
            }

            
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // return false;
            // Perform an AJAX request to save the data
            $.ajax({
                type: 'POST', // Change this to 'POST'
                url: '/payrollclerk/setup/overload/saveallsubjectperemployee',
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Include the token in the request headers
                },
                data: JSON.stringify({ subjectarray: subjectarray }), // Send data as JSON
                contentType: 'application/json', // Set content type to JSON
                success: function(data) {
                    if (data[0].status == 0) {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        });
                    } else {
                        load_employeeoverload(data[0].employeeid);
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

        // return all assigned subjects in employee_overload
        function load_employeeoverload(teacherid){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/overload/loadallemployeeoverload",
                data: {
                    teacherid : teacherid,
                    syid : syid,
                    semid : semid
                },
                success: function (data) {
                    employeeoverload = data;
                    load_alloverload()
                    load_employees()
                    allsched()
                }
            });
        }

        // return all assigned subjects in employee_overload
        function load_alloverload(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/overload/loadalloverload",
                data: {
                    syid : syid,
                    semid : semid
                },
                success: function (data) {
                    alloverload = data;
                    // Count the data
                    var dataCount = employeeoverload.length;
                }
            });
        }

        // load all payroll dates that is active
        function loadpayrollactive(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/overload/loadallpayrollactive",
                success: function (data) {
                    payrolldates = data.map(entry => {
                        const startDate = new Date(entry.datefrom);
                        const endDate = new Date(entry.dateto);
                        const datesArray = getDatesArray(startDate, endDate);
                        const daysArray = datesArray.map(date => getDayName(date.getDay()));

                        return {
                            ...entry,
                            datesarray: datesArray,
                            daysarray: daysArray
                        };
                    });
                }
            });
        }

        function getDatesArray(startDate, endDate) {
            const datesArray = [];
            let currentDate = startDate;

            while (currentDate <= endDate) {
                datesArray.push(new Date(currentDate));
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return datesArray;
        }

        function getDayName(dayIndex) {
            const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            return days[dayIndex];
        }

        // function employee_payroll_tables(){
        //     $('#employeepayroll_datatables').DataTable({
        //         destroy: true,
        //         lengthChange: false,
        //         scrollX: false,
        //         autoWidth: false,
        //         order: false,
        //         searching: false
        //     });
        // }

    })
</script>
@endsection


