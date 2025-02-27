@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 6) {
        $extend = 'adminPortal.layouts.app2';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } elseif ($check_refid->refid == 31) {
                $extend = 'guidanceV2.layouts.app2';
            } elseif ($check_refid->refid == 33) {
                $extend = 'inventory.layouts.app2';
            } elseif ($check_refid->refid == 35) {
                $extend = 'tesda.layouts.app2';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)

@section('headerjavascript')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">

    <style>
        .status-box {
            padding: 20px;
            text-align: center;
        }

        .status-box span {
            font-size: 24px;
            font-weight: bold;
        }

        .status-box small {
            display: block;
            font-size: 14px;
            margin-top: 5px;
        }

        .status-approved {
            background-color: #28a745;
            color: white;
        }

        .status-submitted {
            background-color: #17a2b8;
            color: white;
        }

        .status-not-submitted {
            background-color: #6c757d;
            color: white;
        }

        .status-rejected {
            background-color: #dc3545;
            color: white;
        }

        .employee-item {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .wrapper {
            font-size: 0.75rem
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
    <style>
        .accordion-content {
            background-color: #f8f9fa;
            /* Light background */
            border: 1px solid #e0e0e0;
            /* Border color */
            border-radius: 5px;
            /* Rounded corners */
            padding: 10px;
            /* Padding around the content */
        }

        .text-success {
            background-color: #e9fbe9;
            /* Light green background for approved */
            border-left: 4px solid #28a745;
            /* Green left border */
        }

        .text-info {
            background-color: #e3f2fd;
            /* Light blue background for submitted */
            border-left: 4px solid #007bff;
            /* Blue left border */
        }

        .text-orange {
            background-color: #fff3cd;
            /* Light orange background for returned */
            border-left: 4px solid #ffc107;
            /* Orange left border */
        }

        .text-danger {
            background-color: #f8d7da;
            /* Light red background for rejected */
            border-left: 4px solid #dc3545;
            /* Red left border */
        }

        .mb-0 {
            margin-bottom: 0;
            /* No margin below paragraphs */
        }

        .text-info2 {
            color: #007bff;
        }

        .text-success2 {
            color: #28a745;
        }

        .text-danger2 {
            color: #dc3545;
        }

        .text-orange2 {
            color: #ffc107;
        }

        /* Style for the DataTable search wrapper */
        .search-wrapper {
            display: flex !important;
            justify-content: flex-end !important;
            /* Align items to the right */
            margin-top: 10px !important;
            /* Optional: Add some space above */
        }

        #requirements_table {
            width: 100%;
            /* Ensure the table takes full width */
        }


        #requirements_table_filter {
            display: flex;
            justify-content: flex-end;
            /* Aligns child elements to the end (right) */
        }
    </style>
@endsection

@section('pagespecificscripts')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">

    <style>
        .status-box {
            padding: 20px;
            text-align: center;
        }

        .status-box span {
            font-size: 24px;
            font-weight: bold;
        }

        .status-box small {
            display: block;
            font-size: 14px;
            margin-top: 5px;
        }

        .status-approved {
            background-color: #28a745;
            color: white;
        }

        .status-submitted {
            background-color: #17a2b8;
            color: white;
        }

        .status-not-submitted {
            background-color: #6c757d;
            color: white;
        }

        .status-rejected {
            background-color: #dc3545;
            color: white;
        }

        .employee-item {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .wrapper {
            font-size: 0.75rem
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
    <style>
        .accordion-content {
            background-color: #f8f9fa;
            /* Light background */
            border: 1px solid #e0e0e0;
            /* Border color */
            border-radius: 5px;
            /* Rounded corners */
            padding: 10px;
            /* Padding around the content */
        }

        .text-success {
            background-color: #e9fbe9;
            /* Light green background for approved */
            border-left: 4px solid #28a745;
            /* Green left border */
        }

        .text-info {
            background-color: #e3f2fd;
            /* Light blue background for submitted */
            border-left: 4px solid #007bff;
            /* Blue left border */
        }

        .text-orange {
            background-color: #fff3cd;
            /* Light orange background for returned */
            border-left: 4px solid #ffc107;
            /* Orange left border */
        }

        .text-danger {
            background-color: #f8d7da;
            /* Light red background for rejected */
            border-left: 4px solid #dc3545;
            /* Red left border */
        }

        .mb-0 {
            margin-bottom: 0;
            /* No margin below paragraphs */
        }

        .text-info2 {
            color: #007bff;
        }

        .text-success2 {
            color: #28a745;
        }

        .text-danger2 {
            color: #dc3545;
        }

        .text-orange2 {
            color: #ffc107;
        }

        /* Style for the DataTable search wrapper */
        .search-wrapper {
            display: flex !important;
            justify-content: flex-end !important;
            /* Align items to the right */
            margin-top: 10px !important;
            /* Optional: Add some space above */
        }

        #requirements_table {
            width: 100%;
            /* Ensure the table takes full width */
        }


        #requirements_table_filter {
            display: flex;
            justify-content: flex-end;
            /* Aligns child elements to the end (right) */
        }
    </style>
@endsection


@section('content')
    <div class="container-fluid ">
        <div class="card shadow">
            <div class="card-header">
                <h5>My Requirements</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-valign-middle table-hover" id="requirements_table"
                        style="overflow: auto;width: 100%;">
                        <thead>
                            <tr>
                                <th>Required by</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Requirement Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Your data rows go here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal Structure -->
    <div class="modal fade" id="submitRequirementModal" tabindex="-1" role="dialog"
        aria-labelledby="submitRequirementLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="submitRequirementLabel">Submit Requirement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- User Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong id="requirementBy">Mark John Ajero</strong><br>
                            <small id="requirementByPosition">Human Resource</small>
                        </div>
                        <div>
                            <p class="mb-0"><i class="far fa-calendar-alt"></i> Date Created: <span id="createdDate">5
                                    March 2024</span></p>
                            <p class="mb-0"><i class="far fa-calendar-alt"></i> Due date: <span id="dueDate">5 Sept
                                    2024</span></p>
                        </div>
                    </div>
                    <!-- Requirement Description -->
                    <div class="form-group">
                        <label for="requirementDescription">Requirement description</label>
                        <textarea class="form-control" id="requirementDescription" rows="3" readonly>
                            Please submit your Pag-IBIG details to complete your employee profile. This information is required for compliance.
                        </textarea>
                    </div>

                    <!-- Dropzone Upload -->
                    <div class="form-group">
                        <label>Upload file</label>
                        <form action="/hr/upload-requirement" class="dropzone" id="fileDropzone">
                            @csrf <!-- This will generate the CSRF token input field -->
                            <input type="hidden" name="req_id" id="requirementId">
                            <input type="hidden" name="employee_id" id="employeeId" value="{{ $employee->id }}">
                            <div class="dz-message">
                                <i class="fas fa-cloud-upload-alt"></i> Drag & drop your files here or click to select files
                            </div>
                        </form>
                        <!-- Buttons for controlling uploads -->
                        <button id="startUpload" type="button" class="btn btn-primary mt-2">Start Upload</button>
                        <button id="cancelUpload" type="button" class="btn btn-danger mt-2">Cancel All</button>
                    </div>


                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success btn_submit_files">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <!-- dropzonejs -->
    {{-- <script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script> --}}
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        var employee = {!! json_encode($employee) !!};
        var requirements = {!! json_encode($requirements) !!};
        $(document).ready(function() {
            console.log(requirements);

            populateTable(requirements);

            // Handle the Submit button click in the modal
            $('.btn_submit_files').on('click', function() {
                // Get the requirement ID and employee ID
                var requirementId = $('#requirementId').val();
                var employeeId = $('#employeeId').val();

                // Check if the Dropzone has any uploaded files
                var dropzone = Dropzone.forElement("#fileDropzone");

                // Check if there are any files that have been uploaded
                var uploadedFiles = dropzone.getAcceptedFiles();

                if (uploadedFiles.length === 0) {
                    Swal.fire({
                        title: 'No files uploaded',
                        text: 'Please upload a file before submitting.',
                        type: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return; // Prevent submission if no files are uploaded
                }


                // All checks are passed, now send the submission AJAX request
                $.ajax({
                    url: '/hr/submit-requirement', // Your endpoint to handle submission
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        req_id: requirementId,
                        employee_id: employeeId,
                        // Add any other necessary data here
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            console.log('Requirement submitted successfully:', data);
                            Toast.fire({
                                type: 'success',
                                title: 'Requirement submitted successfully!'
                            });
                            // Close the modal
                            $('#submitRequirementModal').modal('hide');
                            // Optionally refresh the requirements table or provide feedback
                            populateTable(data.requirements);
                        } else {
                            Swal.fire({
                                title: 'Submission Failed',
                                text: data.message ||
                                    'An error occurred while submitting the requirement.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Submission error:', error);
                        Swal.fire({
                            title: 'Submission Error',
                            text: 'An error occurred while submitting the requirement. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });


        });


        function populateTable(data) {
            var table = $('#requirements_table').DataTable({
                // dom: '<"top"i>rt<"bottom"f<"search-wrapper">>p', // Adjust the DOM structure
                paging: false,
                searching: true,
                ordering: false,
                data: data,
                destroy: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                columns: [{
                        data: null,
                        render: function(data) {
                            return `
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/avatars/unknown.png') }}" alt="Avatar" class="employee-avatar" style="width: 40px; margin-right: 10px;">
                                    <div>
                                        <p class="mb-0"><strong>${data.firstname} ${data.middlename ? data.middlename[0] + '. ' : ''}${data.lastname}</strong></p>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            let textclr = '';
                            if (data.latest_history) {
                                textclr = data.latest_history.req_status === 'submitted' ?
                                    'text-info2' :
                                    data.latest_history.req_status === 'approved' ?
                                    'text-success2' :
                                    data.latest_history.req_status === 'returned' ? 'text-orange2' :
                                    'text-danger2';
                            }

                            return data.has_req == '0' ?
                                '<span class="text-muted">Not Submitted</span>' :
                                `<span class="${textclr}">${data.latest_history.req_status.charAt(0).toUpperCase() + data.latest_history.req_status.slice(1)}</span>`;
                        }
                    },
                    {
                        data: 'due_date', // Directly use property name
                        render: function(data) {
                            return data ? `<i class="far fa-calendar-alt"></i> ${new Intl.DateTimeFormat('en-US', {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric'
                            }).format(new Date(data))}` : ''; // Handle null values
                        }
                    },
                    {
                        data: 'requirement_desc', // Directly use property name
                        render: function(data) {
                            return data ? data : ''; // Handle null values
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            const {
                                requirement_desc,
                                created_at,
                                due_date,
                                id,
                                latest_history
                            } = data;
                            return `<button class="btn btn-success btn-sm mr-1 btn_submit" 
                                data-requirement='${JSON.stringify(data)}'
                                ${latest_history && latest_history.req_status === 'submitted' || latest_history && latest_history.req_status === 'approved' ? 'disabled' : ''}>
                                Submit
                            </button>`;
                        }
                    }

                ],
                createdRow: function(row, data) {
                    $(row).attr('data-toggle',
                        'collapse'); // Optional: Use data-toggle for Bootstrap collapsible
                }

            });

            $('#requirements_table tbody').on('click', 'tr', function() {
                var tr = $(this);
                var row = table.row(tr);

                // Toggle the row
                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    // Get the content you want to display in the accordion
                    var rowData = row.data();

                    // Initialize content variable
                    var content = '';

                    // Check if has_req is 1
                    if (rowData && rowData.has_req == '1') {
                        // Loop through req_history
                        content +=
                            '<div class="accordion-content"><p><strong>Requirement History:</strong></p>';
                        content += '<ul>';

                        rowData.req_history.forEach(function(history) {
                            // Extracting necessary details from history
                            const creator = `${history.lastname}, ${history.firstname}`;
                            const statusClass = history.req_status === 'approved' ? 'text-success' :
                                history.req_status === 'returned' ? 'text-orange' :
                                history.req_status === 'rejected' ? 'text-danger' :
                                'text-info'; // Default class for other statuses

                            // Format the timestamp for the history
                            const timestamp = new Date(history.created_at).toLocaleString('en-US', {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            // Start building the content for each history entry
                            content += `
                                <div class="d-flex justify-content-between align-items-center mb-2 border rounded p-2 ${statusClass}">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <i class="fas ${history.req_status === 'approved' ? 'fa-check-circle' : 
                                                            history.req_status === 'returned' ? 'fas fa-undo-alt' : 
                                                            history.req_status === 'rejected' ? 'fa-times-circle' : 
                                                            'fa-file-upload'}" style="font-size: 1.3rem"></i>
                                        </div>

                                        <div>
                                            <strong>${history.req_status.toUpperCase()} ${history.req_status != 'submitted' ? 'by ' + creator : ''}</strong>
                                            <p class="mb-0">
                                                <span class="font-weight-bold text-gray mt-1">Files: </span>
                                                <span class="d-flex flex-wrap">
                                                    ${  
                                                        rowData.req_file && rowData.req_file.length > 0
                                                            ? rowData.req_file
                                                                .map(function(file) {
                                                                    var filename = file.filename;
                                                                    var fileExtension = file.fileextension.toLowerCase();
                                                                    var badgeClass = 
                                                                        history.req_status === 'approved'
                                                                            ? 'text-success2'
                                                                            : history.req_status === 'returned'
                                                                            ? 'text-warning2'
                                                                            : history.req_status === 'rejected'
                                                                            ? 'text-danger2'
                                                                            : 'text-info2'; // Default class for other statuses
                                                                    
                                                                    var fileIcon = 
                                                                        fileExtension === 'pdf'
                                                                            ? 'fas fa-file-pdf'
                                                                            : fileExtension === 'doc' || fileExtension === 'docx'
                                                                            ? 'fas fa-file-word'
                                                                            : fileExtension === 'xls' || fileExtension === 'xlsx'
                                                                            ? 'fas fa-file-excel'
                                                                            : fileExtension === 'png' || fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension === 'gif'
                                                                            ? 'fas fa-file-image'
                                                                            : 'fas fa-file'; // Default icon for other file types

                                                                    // Truncate the filename to 20 characters and add ellipsis if needed
                                                                    var truncatedFilename = filename.length > 20 
                                                                        ? filename.substring(0, 20) + '....' + fileExtension
                                                                        : filename;

                                                                    var fileDisplay = '<span class="mr-2 mb-1 ' + badgeClass + '" ' +
                                                                                        'style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' +
                                                                                        '<i class="' + fileIcon + ' mr-1"></i>' +
                                                                                        truncatedFilename + '</span>';
                                                                    
                                                                    return fileDisplay;
                                                                })
                                                                .join('')
                                                            : ''
                                                        }
                                                </span>
                                            </p>
                                            <p class="mb-0">Comments: ${history.req_remarks || 'None'}</p>
                                        </div>
                                    </div>
                                    <div class="text-muted">
                                        ${timestamp}
                                    </div>
                                </div>
                            `;
                        });

                        content += '</ul></div>';
                    } else {
                        content =
                            '<div class="accordion-content"><p class="text-center">No requirement history available.</p></div>';
                    }

                    // Show the content in the child row
                    row.child(content).show();
                    tr.addClass('shown');
                }
            });

            // Add click listener for buttons/icons
            $('#requirements_table tbody').on('click', '.btn_submit', function(event) {
                event.stopPropagation(); // Prevent row click event from firing

                // Get the data from the button
                var requirementData = $(this).data('requirement'); // Use the entire object
                var id = requirementData.id;

                var requirementBy =
                    `${requirementData.firstname} ${requirementData.middlename ? requirementData.middlename[0] + '. ' : ''}${requirementData.lastname}`

                // Populate the modal fields
                $('#requirementId').val(id);
                $('#requirementBy').text(requirementBy || "Unknown"); // Adjust as needed
                $('#requirementByPosition').text(requirementData.utype ||
                    "Unknown Position"); // Adjust as needed
                $('#requirementDescription').val(requirementData.requirement_desc);

                // Assuming you have the created_at and due_date in the requirementData
                $('.modal-body .mb-0').eq(0).html(
                    `<i class="far fa-calendar-alt"></i> Date Created: ${requirementData.created_at}`);
                $('.modal-body .mb-0').eq(1).html(
                    `<i class="far fa-calendar-alt"></i> Due date: ${requirementData.due_date}`);

                console.log('submitting...');

                // Show the modal
                $('#submitRequirementModal').modal();
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            if (Dropzone.instances.length > 0) {
                Dropzone.instances.forEach(dz => dz.destroy()); // Destroy existing Dropzone instances
            }

            // Initialize Dropzone
            const dropzone = new Dropzone("#fileDropzone", {
                maxFilesize: 5, // MB
                acceptedFiles: ".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.gif",
                autoProcessQueue: false,
                addRemoveLinks: true,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                },
                init: function() {
                    const myDropzone = this;

                    $("#startUpload").on("click", function() {
                        myDropzone.processQueue();
                    });

                    $("#cancelUpload").on("click", function() {
                        myDropzone.removeAllFiles(true);
                    });
                },
            });
        });
    </script>
@endsection
