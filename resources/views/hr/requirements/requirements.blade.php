@extends('hr.layouts.app')

@section('headerjavascript')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirements and Employees Layout</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        .content-wrapper>.content {
            padding: 0px;
        }

        .table-icon {
            font-size: 1.5rem;
        }

        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .status-not-submitted {
            background-color: gray;
        }

        .status-submitted {
            background-color: blue;
        }

        .status-approved {
            background-color: green;
        }

        .status-rejected {
            background-color: red;
        }

        .employee-card {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            display: flex;
            align-items: center;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .employee-info {
            flex: 1;
        }

        .status-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .status-item {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        .status-circle {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .not-submitted {
            background-color: gray;
        }

        .submitted {
            background-color: blue;
        }

        .approved {
            background-color: green;
        }

        .rejected {
            background-color: red;
        }

        .progress {
            flex-grow: 1;
            margin-right: 15px;
        }

        .progress-bar {
            height: 8px;
        }

        /* .more-options {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    font-size: 1.5rem;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    cursor: pointer;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                } */

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 15px;
        }

        .activity-item {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .file-actions {
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 8px;
        }

        .wrapper {
            font-size: 0.75rem
        }

        .text-blue {
            color: blue;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-5 wrapper">
        <div class="row">
            <!-- Requirements Section -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Requirements</h4>
                            <button class="btn btn-primary btn-sm shadow btn-create">+ Create a requirement</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-valign-middle" style="width: 100%;" id="tbl_requirements">
                                <thead>
                                    <tr>
                                        <th scope="col">Requirement Description</th>
                                        <th scope="col">Date creation</th>
                                        <th scope="col">Due date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- <tr class="row_requirement" style="cursor: pointer;">
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-folder text-warning"
                                                    style="font-size: 30px; margin-right: 10px;"></i>
                                                PAG-IBIG
                                            </div>
                                        </td>
                                        <td>March 5, 2024</td>
                                        <td>September 5, 2024</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-dark" style="width: 20%;"></div>
                                                <div class="progress-bar bg-primary" style="width: 50%;"></div>
                                                <div class="progress-bar bg-success" style="width: 30%;"></div>
                                                <div class="progress-bar bg-danger" style="width: 50%;"></div>
                                            </div>
                                            <div class="status-container">
                                                <!-- Status bar -->
                                                <!-- Status labels -->
                                                <div class="status-item">
                                                    <span class="status-circle not-submitted"></span> Not submitted
                                                </div>
                                                <div class="status-item">
                                                    <span class="status-circle submitted"></span> Submitted
                                                </div>
                                                <div class="status-item">
                                                    <span class="status-circle approved"></span> Approved
                                                </div>
                                                <div class="status-item">
                                                    <span class="status-circle rejected"></span> Rejected
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <!-- More options -->
                                            <div class="more-options">
                                                <i class="fas fa-edit text-primary mr-2" style="cursor: pointer;"></i>
                                                <i class="fas fa-trash-alt text-danger" style="cursor: pointer;"></i>
                                            </div>
                                        </td>
                                    </tr> --}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees Section -->
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Employees</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="teachersTable" class="table table-striped table-valign-middle" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Info</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $teachers = DB::table('teacher')
                                            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                                            ->where('isactive', 1)
                                            ->where('teacher.deleted', 0)
                                            ->select('teacher.*', 'usertype.utype')
                                            ->get();
                                    @endphp

                                    @foreach ($teachers as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('assets/images/avatars/unknown.png') }}" alt="Avatar"
                                                    class="employee-avatar"
                                                    style="width: 40px; height: 40px; border-radius: 50%;">
                                            </td>
                                            <td>{{ $item->firstname }} {{ $item->lastname }}</td>
                                            <td>{{ $item->utype }}</td>
                                            <td>
                                                <i class="fas fa-info-circle empinfo text-info"
                                                    data-name="{{ $item->firstname }} {{ $item->lastname }}"
                                                    data-utype="{{ $item->utype }}" data-id="{{ $item->id }}"
                                                    style="cursor: pointer;"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    // Initialize DataTable with search bar aligned to the left
                    $('#teachersTable').DataTable({
                        dom: '<"row"<"col-sm-12 col-md-6"f><"col-sm-12 col-md-6"l>>' +
                            // Adjust search bar and length menu placement
                            '<"row"<"col-sm-12"tr>>' +
                            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                        lengthChange: false,
                        responsive: true,
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search employees..."
                        }
                    });
                });
            </script>

        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="activityLogModal" tabindex="-1" aria-labelledby="activityLogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <!-- Modal Header -->
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h5 class="modal-title" id="activityLogModalLabel">Trisha Bagongon</h5>
                            <p class="mb-0 text-muted" id="utype">College Instructor</p>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <h6>Activity Log</h6>

                    <!-- Activity Log Item 1 -->
                    <div class="activity-item">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-folder text-warning" style="font-size: 30px; margin-right: 10px;"></i>
                                <div>
                                    <strong>PAG-IBIG</strong><br>
                                    <small class="text-muted">August 31, 2024 | 10:00 am</small>
                                </div>
                            </div>
                        </div>

                        <!-- File Actions -->
                        <div class="mt-2">
                            <div class="file-actions d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-check-circle text-success"></i> File Submitted<br>
                                    <small class="text-muted">August 31, 2024 | 10:00 am</small>
                                </div>
                                <div>
                                    <i class="fas fa-file-alt"></i> Pagibig.pdf
                                </div>
                            </div>
                            <div class="file-actions d-flex justify-content-between align-items-center mt-2">
                                <div>
                                    <i class="fas fa-check-circle text-success"></i> File Approved by Mark John Ajero<br>
                                    <small class="text-muted">August 31, 2024 | 10:00 am</small>
                                </div>
                                <div>
                                    <i class="fas fa-file-alt"></i> Pagibig.pdf
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log Item 2 (similar to item 1) -->
                    <div class="activity-item">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-folder text-warning" style="font-size: 30px; margin-right: 10px;"></i>
                                <div>
                                    <strong>PAG-IBIG</strong><br>
                                    <small class="text-muted">August 31, 2024 | 10:00 am</small>
                                </div>
                            </div>
                        </div>

                        <!-- File Actions -->
                        <div class="mt-2">
                            <div class="file-actions d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-check-circle text-success"></i> File Submitted<br>
                                    <small class="text-muted">August 31, 2024 | 10:00 am</small>
                                </div>
                                <div>
                                    <i class="fas fa-file-alt"></i> Pagibig.pdf
                                </div>
                            </div>
                            <div class="file-actions d-flex justify-content-between align-items-center mt-2">
                                <div>
                                    <i class="fas fa-check-circle text-success"></i> File Approved by Mark John Ajero<br>
                                    <small class="text-muted">August 31, 2024 | 10:00 am</small>
                                </div>
                                <div>
                                    <i class="fas fa-file-alt"></i> Pagibig.pdf
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="requestRequirementModal" tabindex="-1" aria-labelledby="requestRequirementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestRequirementModalLabel">Request a Requirement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <!-- Requirement Folder Name -->
                        <div class="mb-3">
                            <label for="requirementName" class="form-label">Requirement Folder Name</label>
                            <input type="text" class="form-control" id="requirementName"
                                onkeyup="this.value = this.value.toUpperCase();"
                                placeholder="Enter requirement folder name">
                        </div>

                        <!-- Requirement Description -->
                        <div class="mb-3">
                            <label for="requirementDescription" class="form-label">Requirement Description</label>
                            <textarea class="form-control" id="requirementDescription" rows="3"
                                placeholder="Enter requirement description"></textarea>
                        </div>

                        <!-- Due Date & Reminder -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dueDate" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="dueDate" placeholder="mm/dd/yy">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dueReminder" class="form-label">Due Reminder</label>
                                <select class="form-control" id="dueReminder">
                                    <option value="weekly">Once a week</option>
                                    <option value="daily">Every day</option>
                                </select>
                            </div>
                        </div>

                        <!-- Employees Selection -->
                        <div class="mb-3">
                            <label class="form-label">Employees</label><br>
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline mr-4">
                                    <input type="radio" id="radioPrimary1" name="visibilitytype" value="all">
                                    <label for="radioPrimary1">
                                        All
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline mr-4">
                                    <input type="radio" id="radioPrimary3" name="visibilitytype"
                                        value="selectedemployee">
                                    <label for="radioPrimary3">
                                        Selected Employee
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline mr-4">
                                    <input type="radio" id="radioPrimary4" name="visibilitytype" checked
                                        value="onlyme">
                                    <label for="radioPrimary4">
                                        Only me
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline mr-2">
                                    <input type="radio" id="radioPrimary2" name="visibilitytype"
                                        value="selecteddepartments">
                                    <label for="radioPrimary2">
                                        By Departments
                                    </label>
                                </div>
                            </div>

                        </div>

                        <!-- Select Employees -->
                        <div class="mb-3 employee_wrapper" hidden>
                            <label for="selectEmployees" class="form-label">Select employees to require</label>
                            <select class="form-select select2" id="selectEmployees" multiple style="width: 100%">
                                @foreach (DB::table('teacher')->orderBy('lastname', 'asc')->where('deleted', 0)->get() as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->lastname }},
                                        {{ $teacher->firstname }}
                                        {{ $teacher->middlename }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Department -->
                        <div class="mb-3 department_wrapper" hidden>
                            <label for="selectedDepartment" class="form-label">Select department to require</label>
                            <select class="form-select select2" id="selectedDepartment" multiple style="width: 100%">
                                @foreach (DB::table('hr_departments')->orderBy('department', 'asc')->where('deleted', 0)->get() as $department)
                                    <option value="{{ $department->id }}"> {{ $department->department }} </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="submitRequirement">Yes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal Structure -->
    <div class="modal fade" id="editRequirementModal" tabindex="-1" aria-labelledby="editRequirementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRequirementModalLabel">Edit Requirement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editRequirementForm">
                        <!-- Hidden field to store requirement ID -->
                        <input type="hidden" id="requirementId">

                        <!-- Requirement Folder Name -->
                        <div class="mb-3">
                            <label for="editRequirementName" class="form-label">Requirement Folder Name</label>
                            <input type="text" class="form-control" id="editRequirementName"
                                onkeyup="this.value = this.value.toUpperCase()"
                                placeholder="Enter requirement folder name">
                        </div>

                        <!-- Requirement Description -->
                        <div class="mb-3">
                            <label for="editRequirementDescription" class="form-label">Requirement Description</label>
                            <textarea class="form-control" id="editRequirementDescription" rows="3"
                                placeholder="Enter requirement description"></textarea>
                        </div>

                        <!-- Due Date & Reminder -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editDueDate" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="editDueDate" placeholder="mm/dd/yy">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editDueReminder" class="form-label">Due Reminder</label>
                                <select class="form-control" id="editDueReminder">
                                    <option value="weekly">Once a week</option>
                                    <option value="daily">Every day</option>
                                </select>
                            </div>
                        </div>

                        <!-- Employees Selection -->
                        <div class="mb-3">
                            <label class="form-label">Employees</label><br>
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline mr-4">
                                    <input type="radio" id="editRadioPrimary1" name="editVisibilityType"
                                        value="all">
                                    <label for="editRadioPrimary1">
                                        All
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline mr-4">
                                    <input type="radio" id="editRadioPrimary3" name="editVisibilityType"
                                        value="selectedemployee">
                                    <label for="editRadioPrimary3">
                                        Selected Employee
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline mr-4">
                                    <input type="radio" id="editRadioPrimary4" name="editVisibilityType" checked
                                        value="onlyme">
                                    <label for="editRadioPrimary4">
                                        Only me
                                    </label>
                                </div>
                                <div class="icheck-primary d-inline mr-2">
                                    <input type="radio" id="editRadioPrimary2" name="editVisibilityType"
                                        value="selecteddepartments">
                                    <label for="editRadioPrimary2">
                                        By Departments
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Select Employees -->
                        <div class="mb-3 employee_wrapper" hidden>
                            <label for="editSelectEmployees" class="form-label">Select employees to require</label>
                            <select class="form-select select2" id="editSelectEmployees" multiple style="width: 100%">
                                @foreach (DB::table('teacher')->orderBy('lastname', 'asc')->where('deleted', 0)->get() as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->lastname }},
                                        {{ $teacher->firstname }}
                                        {{ $teacher->middlename }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Department -->
                        <div class="mb-3 department_wrapper" hidden>
                            <label for="editSelectedDepartment" class="form-label">Select department to require</label>
                            <select class="form-select select2" id="editSelectedDepartment" multiple style="width: 100%">
                                @foreach (DB::table('hr_departments')->orderBy('department', 'asc')->where('deleted', 0)->get() as $department)
                                    <option value="{{ $department->id }}"> {{ $department->department }} </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="updateRequirement">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $('body').addClass('sidebar-collapse')
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        $(document).ready(function() {
            fetchRequirements({!! json_encode($requirements) !!});
            // Initialize Select2

            $('#selectEmployees, #editSelectEmployees').select2({
                multiple: true,
                placeholder: 'Select employees to require',
                theme: 'bootstrap4' // Corrected theme spelling
            });
            $('#selectedDepartment, #editSelectedDepartment').select2({
                multiple: true,
                placeholder: 'Select departments to require',
                theme: 'bootstrap4' // Corrected theme spelling
            });

            $(document).on('click', '.empinfo',
                function() {
                    console.log('opening...');
                    let id = $(this).data('id');
                    let name = $(this).data('name');
                    let utype = $(this).data('utype');

                    // Send AJAX request to get teacher logs
                    $.ajax({
                        url: '/hr/getTeacherLogs',
                        method: 'GET',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log(response);

                            // Clear previous log data
                            $('#activityLogModal .modal-body').empty();

                            $('#activityLogModalLabel').text(name)
                            $('#utype').text(utype)
                            // Helper function to format dates in "August 31, 2024 | 10:00 am" format
                            function formatDateTime(dateString) {
                                let date = new Date(dateString);
                                let formattedDate = date.toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });
                                let formattedTime = date.toLocaleTimeString('en-US', {
                                    hour: 'numeric',
                                    minute: '2-digit',
                                    hour12: true
                                });
                                return `${formattedDate} | ${formattedTime}`;
                            }

                            // Check if there are logs
                            if (Object.keys(response).length === 0) {
                                $('#activityLogModal .modal-body').html(
                                    '<p class="text-center">No activity logs found for this teacher.</p>'
                                );
                            } else {
                                // Clear previous log data
                                $('#activityLogModal .modal-body').empty();

                                // Loop through each requirement and create log items
                                $.each(response, function(requirementId, logs) {
                                    // Generate the requirement header
                                    let requirementHtml = `
                                    <div class="activity-item">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-folder text-warning" style="font-size: 30px; margin-right: 10px;"></i>
                                                <div>
                                                    <strong>${logs[0].requirement_name}</strong><br>
                                                    <small class="text-muted">${formatDateTime(logs[0].req_created_at)}</small>
                                                </div>
                                            </div>
                                        </div>
                                `;

                                    // Generate the log entries for each file action within this requirement
                                    logs.forEach(function(log) {
                                        let truncatedFilename = log.filename ?
                                            (log.filename.length > 20 ? log.filename
                                                .substring(0, 20) + '....' + log
                                                .fileextension : log.filename) :
                                            'No file name';

                                        requirementHtml += `
                                        <div class="mt-2">
                                            <div class="file-actions d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-check-circle ${log.req_status === 'submitted' ? 'text-blue' : log.req_status === 'rejected' ? 'text-danger' : 'text-success'}"></i>  <span class="${log.req_status === 'submitted' ? 'text-blue' : log.req_status === 'rejected' ? 'text-danger' : 'text-success'}">File ${log.req_status.charAt(0).toUpperCase() + log.req_status.slice(1)}</span><br>
                                                    <small class="text-muted">${formatDateTime(log.created_at)}</small>
                                                </div>
                                                <div>
                                                    <i class="fas fa-file-alt"></i> ${truncatedFilename}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    });

                                    // Close the activity-item div
                                    requirementHtml += '</div>';

                                    // Append the generated HTML for this requirement to the modal body
                                    $('#activityLogModal .modal-body').append(
                                        requirementHtml);
                                });
                            }


                            // Show the modal
                            $('#activityLogModal').modal();
                        },
                        error: function() {
                            console.log("Error fetching data.");
                        }
                    });
                });


            $('.btn-create').on('click', function() {
                console.log('createmodal...');

                $('#requestRequirementModal').modal()
            })

            $(document).on('click', '.row_requirement', function() {
                var id = $(this).data('id');
                console.log('opening req...ID', id);
                window.location.href = `/hr/viewrequirement?id=${id}`;
            });

            $('#submitRequirement').click(function() {
                if ($('#requirementName').val().replace(/^\s+|\s+$/g, "").length == 0) {
                    $('#requirementName').css('border', '1px solid red');
                    toastr.warning('Requirement name is required!', 'Create Requirement');
                    return;
                } else {
                    $('#requirementName').css('border', '');
                }

                if ($('#dueDate').val().replace(/^\s+|\s+$/g, "").length == 0) {
                    $('#dueDate').css('border', '1px solid red');
                    toastr.warning('Due date is required!', 'Create Requirement');
                    return;
                } else {
                    $('#dueDate').css('border', '');
                }

                if ($('input[name="visibilitytype"]:checked').val() == null) {
                    $('#visibilityWrapper').css('border', '1px solid red');
                    toastr.warning('Select visibility!', 'Create Requirement');
                    return;
                } else {
                    $('#visibilityWrapper').css('border', '');
                }

                if ($('input[name="visibilitytype"]:checked').val() == 'selectedemployee') {
                    if ($('#selectEmployees').val() == null || $('#selectEmployees').val().length == 0) {
                        $('#selectEmployees').css('border', '1px solid red');
                        toastr.warning('Please provide at least one employee!', 'Create Requirement');
                        return;
                    } else {
                        $('#selectEmployees').css('border', '');
                    }
                } else if ($('input[name="visibilitytype"]:checked').val() == 'selecteddepartments') {
                    if ($('#selectedDepartment').val() == null || $('#selectedDepartment').val().length ==
                        0) {
                        $('#selectedDepartment').css('border', '1px solid red');
                        toastr.warning('Please provide at least one department!');
                        return;
                    } else {
                        $('#selectedDepartment').css('border', '');
                    }
                }

                // Gather the form data
                let data = {
                    requirement_name: $('#requirementName').val(),
                    requirement_desc: $('#requirementDescription').val(),
                    due_date: $('#dueDate').val(),
                    due_reminder: $('#dueReminder').val(),
                    visibility_type: $('input[name="visibilitytype"]:checked').val(),
                    employees: $('#selectEmployees').val(), // Array of selected employees
                    departments: $('#selectedDepartment').val(), // Array of selected departments
                    _token: '{{ csrf_token() }}' // Laravel CSRF token
                };

                // Show the loading spinner
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Make the AJAX request
                $.ajax({
                    url: '{{ route('requirement.save') }}', // Route defined in web.php
                    type: 'POST',
                    data: data,

                    success: function(response) {
                        // Close the loading spinner
                        Swal.close();

                        // Handle success (e.g., close the modal and show a success message)
                        if (response.status == 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                type: 'success',
                                confirmButtonText: 'OK'
                            }).then(function() {
                                $('#requestRequirementModal').modal('hide');

                                fetchRequirements(response.requirements);
                            });
                        }
                    },
                    error: function(xhr) {
                        // Close the loading spinner
                        Swal.close();

                        // Handle validation errors
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';

                            // Loop through each error and add it to the errorMessage
                            $.each(errors, function(key, errorArray) {
                                errorMessage += errorArray[0] +
                                    '<br>'; // Get the first error message for each field
                            });

                            Swal.fire({
                                title: 'Validation Error',
                                html: errorMessage, // Use html to display multiple error lines
                                type: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // General error handling
                            Swal.fire({
                                title: 'Error',
                                text: 'Something went wrong!',
                                type: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });


            $('input[name="visibilitytype"]').change(function() {
                var selectedValue = $('input[name="visibilitytype"]:checked').val();
                console.log('selectedValue', selectedValue);

                // Hide both wrappers by default
                $('.employee_wrapper, .department_wrapper').hide();

                // Toggle based on the selected visibility type
                if (selectedValue == 'selectedemployee') {
                    $('.employee_wrapper').show(); // Show employee wrapper
                    $('.employee_wrapper').attr('hidden', false); // Show employee wrapper
                    $('#selectedDepartment').val('').trigger('change');
                } else if (selectedValue == 'selecteddepartments') {
                    $('.department_wrapper').show(); // Show department wrapper
                    $('.department_wrapper').attr('hidden', false); // Show department wrapper
                    $('#selectEmployees').val('').trigger('change');
                }
            });

            $(document).on('click', '.delete-requirement', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                console.log('delete requirement', id);

                // Confirm deletion
                Swal.fire({
                    title: 'Are you sure you want to delete this requirement?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {

                        // Show the loading spinner
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we process your request.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Make the AJAX request
                        $.ajax({
                            url: '{{ route('requirement.destroy', ['id' => ':id']) }}'
                                .replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.close();
                                // Handle success (e.g., close the modal and show a success message)
                                if (response.status == 'success') {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: response.message,
                                        type: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(function() {
                                        fetchRequirements(response
                                            .requirements);
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.close();
                                // Handle validation errors
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessage = '';

                                    // Loop through each error and add it to the errorMessage
                                    $.each(errors, function(key, errorArray) {
                                        errorMessage += errorArray[0] +
                                            '<br>'; // Get the first error message for each field
                                    });

                                    Swal.fire({
                                        title: 'Validation Error',
                                        html: errorMessage, // Use html to display multiple error lines
                                        type: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    // General error handling
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Something went wrong!',
                                        type: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // Event listener for visibility type change
            $('input[name="editVisibilityType"]').change(function() {
                toggleEditVisibilityWrappers($(this).val());
            });

            // Handle update button click
            $('#updateRequirement').click(function() {
                // Collect form data and send it via AJAX
                var formData = {
                    id: $('#requirementId').val(),
                    name: $('#editRequirementName').val(),
                    description: $('#editRequirementDescription').val(),
                    due_date: $('#editDueDate').val(),
                    reminder: $('#editDueReminder').val(),
                    visibility_type: $('input[name="editVisibilityType"]:checked').val(),
                    selected_employees: $('#editSelectEmployees').val(),
                    selected_departments: $('#editSelectedDepartment').val(),
                    _token: '{{ csrf_token() }}'
                };

                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '/hr/updaterequirement',
                    method: 'PUT',
                    data: formData,
                    success: function(response) {
                        Swal.close();
                        // Handle success response, e.g., refresh the list or close modal
                        $('#editRequirementModal').modal('hide');
                        // Refresh list or perform other actions
                        if (response.status == 'success') {
                            Toast.fire({
                                type: response.status,
                                title: response.message,
                            });

                            fetchRequirements(response.requirements)
                        }
                    },
                    error: function(error) {
                        Swal.close();
                        // Handle error response
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!',
                        });

                    }
                });
            });

            $(document).on('click', '.edit-requirement', function() {
                const reqid = $(this).data('id')
                openEditModal(reqid)
            })
        })


        function fetchRequirements(data) {
            // Initialize or reinitialize the DataTable
            $('#tbl_requirements').DataTable({
                data: data, // Ensure the data is properly formatted as an array of objects
                destroy: true, // Destroy any existing instance of the DataTable before recreating it
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                columns: [{
                        data: 'requirement_name',
                        render: function(data, type, row) {
                            return `
                        <div class="d-flex align-items-center row_requirement" data-id="${row.id}" style="cursor: pointer;">
                            <i class="fas fa-folder text-warning" style="font-size: 30px; margin-right: 10px;"></i>
                            ${data || 'Untitled Requirement'}
                        </div>
                    `;
                        }
                    },
                    {
                        data: 'created_at',
                        render: function(data, type, row) {
                            // Check if created_at exists and is valid
                            if (data) {
                                return new Intl.DateTimeFormat('en-US', {
                                    month: 'long',
                                    day: 'numeric',
                                    year: 'numeric'
                                }).format(new Date(data));
                            }
                            return 'N/A'; // Default value if created_at is missing
                        }
                    },
                    {
                        data: 'due_date',
                        render: function(data, type, row) {
                            // Check if due_date exists and is valid
                            if (data) {
                                return new Intl.DateTimeFormat('en-US', {
                                    month: 'long',
                                    day: 'numeric',
                                    year: 'numeric'
                                }).format(new Date(data));
                            }
                            return 'N/A'; // Default value if due_date is missing
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            // Assuming custom logic for progress bars (you may replace these percentages with dynamic values)
                            return `
                        <div class="progress">
                            <div class="progress-bar bg-dark" style="width: ${row.percentageNotSubmitted}%;"></div>
                            <div class="progress-bar bg-primary" style="width: ${row.percentageSubmitted}%;"></div>
                            <div class="progress-bar bg-success" style="width: ${row.percentageApproved}%;"></div>
                            <div class="progress-bar bg-danger" style="width: ${row.percentageRejected}%;"></div>
                        </div>
                        <div class="status-container mt-2">
                            <div class="status-item">
                                <span class="status-circle bg-dark"></span> Not submitted
                            </div>
                            <div class="status-item" style="color: blue">
                                <span class="status-circle submitted"></span> Submitted
                            </div>
                            <div class="status-item text-success">
                                <span class="status-circle approved"></span> Approved
                            </div>
                            <div class="status-item text-danger">
                                <span class="status-circle rejected"></span> Rejected
                            </div>
                        </div>
                    `;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                        <div class="btn-group">
                            <a href="javascript:void(0)" class="btn btn-sm edit-requirement" data-id="${row.id}">
                                <i class="fa text-primary fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm delete-requirement" data-id="${row.id}">
                                <i class="fa text-danger fa-trash-alt"></i>
                            </a>
                        </div>
                    `;
                        }
                    }
                ],
                createdRow: function(row, data, dataIndex) {
                    // You can add custom row styling here if needed
                }
            });
        }

        // Trigger edit modal
        function openEditModal(requirementId) {
            // Fetch requirement data via an AJAX request or from a stored dataset
            $.ajax({
                url: '/hr/editrequirement?id=' + requirementId,
                method: 'GET',
                success: function(data) {
                    console.log('Requirement', data);
                    var requirement = data.requirement

                    // Populate modal fields with fetched data
                    $('#requirementId').val(requirement.id);
                    $('#editRequirementName').val(requirement.requirement_name);
                    $('#editRequirementDescription').val(requirement.requirement_desc);
                    $('#editDueDate').val(requirement.due_date);
                    $('#editDueReminder').val(requirement.due_reminder);

                    // Set visibility type and toggle employee/department fields
                    $('input[name="editVisibilityType"][value="' + requirement.visibility_type + '"]').prop(
                        'checked',
                        true);
                    toggleEditVisibilityWrappers(requirement.visibility_type);

                    // Populate employees and departments if applicable
                    if (requirement.employees) {
                        $('#editSelectEmployees').val(requirement.employees).trigger('change');
                    }
                    if (requirement.departments) {
                        $('#editSelectedDepartment').val(requirement.departments).trigger('change');
                    }

                    // Show the modal
                    $('#editRequirementModal').modal('show');
                }
            });
        }

        // Toggle visibility of employee/department fields in edit form
        function toggleEditVisibilityWrappers(value) {
            if (value === 'selectedemployee') {
                $('.employee_wrapper').show();
                $('.department_wrapper').hide();
                $('.employee_wrapper').attr('hidden', false); // Show employee wrapper
                $('#editSelectEmployees').val('').trigger('change');
            } else if (value === 'selecteddepartments') {
                $('.employee_wrapper').hide();
                $('.department_wrapper').show();
                $('.department_wrapper').attr('hidden', false); // Show employee wrapper
                $('#editSelectedDepartment').val('').trigger('change');
            } else {
                $('.employee_wrapper, .department_wrapper').hide();
            }
        }
    </script>
@endsection
