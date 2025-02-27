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
                    <h1>MWSP</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">MWSP</li>
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
          <h5 class="modal-title">Subject Loads</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <table width="100%" class="table table-sm table-bordered table-head-fixed" id="subject_loads_datatables"  style="font-size: 16px; table-layout: fixed;">
                    <input type="hidden" id="teacher_id">
                    <input type="hidden" id="amountperhourallsubjs">
                    <thead>
                            <tr>
                                <td class="" width="5%">&nbsp;&nbsp;</td>
                                <td class="" width="75%">Subjects</td>
                                <td class="text-center" width="15%">Salary /hr</td>
                                <td class="text-center" width="5%">
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
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="saveallsubjs">Save changes</button>
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
                                    <th width="38%">Employee</th>
                                    <th class="text-center" width="15%">Subjects</th>
                                    <th class="text-center" width="15%">Salary /hr</th>
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

        var employee_list = [];
        var collegeClassSched = [];
        var subjectarray = [];
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
        allsched()
        // ==============================================================================================================================================
        
        // ========================================================= click event sections ===============================================================

        $(document).on('click', '#subject_loads', function(){
            var teacherid = $(this).attr('teacherid');

            $('#teacher_id').val(teacherid)
            $('#modal_subject_loads').modal('show')
            // subject_loads_tables()
            allsched()
            
        })


        $(document).on('click', '#addmwsp', function(){
            var teacherid = $(this).attr('teacherid');
            var rowid = $(this).attr('data-id');
            var amountperhourallsubjects = $('#subject_amountperhourallsubjs' + rowid).val();

            
            $('#teacher_id').val(teacherid)
            $('#amountperhourallsubjs').val(amountperhourallsubjects)

            // subject_loads_tables()
            allsched()


            $('.subjectsamount').each(function() {
                $(this).val(amountperhourallsubjects);
            });
            
            $('.subjects').prop('checked', true);
            $('#checkallsubjs').prop('checked', true);
            
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
            saveCheckedRows();
        })
        // ==============================================================================================================================================


        function load_employees(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/mwsp/loademployees",
                success: function (data) {
                    employee_list = data
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

                            var text = '<a href="javascript:void(0)" class="text-primary" id="subject_loads" teacherid="'+rowData.id+'">0 of '+countteacherload+'</a>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var text = '<input type="number" class="form-control text-center" id="subject_amountperhourallsubjs'+rowData.id+'" name="default-amount" step="1" min="0"/>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 4,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            var buttons = '<div class="text-center" style="display: flex; justify-content: center; align-items: center;">' +
                              '<a href="javascript:void(0)" id="addmwsp" class="mb-0" style="" teacherid="'+rowData.id+'" data-id="'+rowData.id+'"><i class="fas fa-plus"></i></a>' +
                              '<input type="hidden" class="" style="width: 18px; height: 18px;">' +
                              '</div>';
                            $(td)[0].innerHTML =  buttons
                            $(td).addClass('text-center')
                            $(td).addClass('align-middle')
                        }
                    }
                ]
            })
        }

        // this function is for subject load datatable
        function subject_loads_tables(finalCollegeClassSched){
            var teacherid = $('#teacher_id').val();
            var teacherdata = finalCollegeClassSched.filter(x=>x.teacherID == teacherid);
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
                            var text = '<div style="display: inline-grid;">' +
                                '<span class="mb-0">' + rowData.subjDesc + '</span>' +
                                '<small style="font-size: 10px;">' + rowData.subjCode + '&nbsp; : &nbsp;';
                            
                            // Loop through the scheddetail for the current class schedule
                            for (var i = 0; i < rowData.scheddetail.length; i++) {
                                var day = rowData.scheddetail[i].day;

                                // Convert day to the corresponding day name
                                var dayNames = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat","Sun"];
                                var dayName = dayNames[day - 1];

                                // Add the schedule details to the text 
                                // text += '' + dayName + ': ' +
                                //     rowData.scheddetail[i].stime + ' - ' +
                                //     rowData.scheddetail[i].etime;
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
                        'createdCell':  function (td, cellData, rowData, row, col) {

                            var text = '<input type="number" class="form-control text-center subjectsamount" id="subject_amountperhour'+rowData.id+'" name="default-amount" step="1" min="0"/>';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    },
                    {
                        'targets': 3,
                        'orderable': false, 
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            // console.log(rowData.scheddetail);
                            var text = '<input type="checkbox" class="subjects" data-id="'+rowData.id+'" data-syid="'+rowData.syID+'" data-semid="'+rowData.semesterID+'" id="subject'+rowData.id+'" data-scheddetails=\'' + JSON.stringify(rowData.scheddetail) + '\' style="width: 18px; height: 18px; margin-top: 5px;">';
                            $(td)[0].innerHTML =  text
                            $(td).addClass('align-middle  text-center')
                        }
                    }
                ]
            });

            // var label_text = $($('#subject_loads_datatables_wrapper')[0].children[0])[0].children[0]
            // $(label_text)[0].innerHTML = `<div class="form-check">
            //                                 <input class="form-check-input" type="checkbox" id="checkallsubjs" style="width: 18px; height: 18px;">
            //                                 &nbsp;<label class="form-check-label" for="checkallsubjs" style="padding-top: 3px;">
            //                                     Check All
            //                                 </label>
            //                             </div>`
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
                    // For example, subject_loads_tables(finalCollegeClassSched);
                    subject_loads_tables(finalCollegeClassSched);
                    load_employees();
                }
            });
        }

        // Function to save checked rows
        function saveCheckedRows() {
            $('.subjects:checked').each(function() {
                var teacherid = $('#teacher_id').val();
                var rowid = $(this).attr('data-id')
                var amountperhour = $('#subject_amountperhour' + rowid).val();
                var subjid = $(this).attr('data-id');
                var active = 1;
                var syid = $(this).attr('data-syid');
                var semid = $(this).attr('data-semid');

                var datascheddetails = $(this).data('scheddetails');
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
                    // Include the day variables in the object
                    monday: monday,
                    tuesday: tuesday,
                    wednesday: wednesday,
                    thursday: thursday,
                    friday: friday,
                    saturday: saturday,
                    sunday: sunday,
                    totalHours: totalHours,
                    datascheddetails : datascheddetails
                };


                subjectarray.push(obj);
               
            });

            // Perform an AJAX request to save the data
            $.ajax({
                type: 'GET',
                url: '/payrollclerk/setup/mwsp/saveallsubjectperemployee',
                data: { subjectarray : subjectarray },
                success: function(data) {
                    // console.log('Data saved successfully', response);
                    if(data[0].status == 0){
                        Toast.fire({
                        type: 'error',
                        title: data[0].message
                    })
                    }else{
                        allsched()
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                    }
                },
                error: function(error) {
                    console.error('Error saving data', error);
                }
            });
        }

        // return all assigned subjects in employee_mwsp
        function load_employeemwsp(){
            $.ajax({
                type: "GET",
                url: "/payrollclerk/setup/mwsp/loadallemployeemwsp",
                data: "data",
                dataType: "dataType",
                success: function (response) {
                    
                }
            });
        }


    })
</script>
@endsection


