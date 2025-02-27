@extends('hr.layouts.app')

@section('headerjavascript')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pag-IBIG Submission</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
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

        .btn-orange {
            background-color: #fd7e14;
            color: white;
        }

        .dark-orange {
            color: #fd7e14;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-3 wrapper">

        <div class="row">
            <!-- Sidebar Section -->
            <div class="col-md-3">
                <div class="card mb-3 shadow">
                    <div class="card-header">
                        <h5 class="card-title d-flex align-items-center mt-2">
                            <i class="fa fa-folder text-warning mr-2" style="font-size: 30px;"></i>

                            <!-- Requirement Name Span and Input for Editing -->
                            <span id="requirementName"
                                class="mr-2">{{ strtoupper($requirement->requirement_name) }}</span>
                            <input type="text" id="requirementNameInput" class="form-control d-none"
                                onkeyup="this.value = this.value.toUpperCase()"
                                value="{{ strtoupper($requirement->requirement_name) }}" />

                            <!-- Edit/Save Icon -->
                            <i id="editIcon" class="fa fa-pencil-alt text-info2 ml-2"
                                style="font-size: 20px; cursor: pointer;" title="Edit"></i>
                            <i id="saveIcon" class="fa fa-save text-success2 ml-2 d-none"
                                style="font-size: 20px; cursor: pointer;" title="Save"></i>
                        </h5>
                    </div>


                    <div class="card-body">
                        <p class="text-muted">Folder Details</p>
                        <p> <i class="fa fa-calendar-alt"></i> <strong>Date Created:</strong>
                            {{ \Carbon\Carbon::parse($requirement->created_at)->format('F j, Y') }} </p>
                        <p> <i class="fa fa-folder-plus"></i> <strong>Created by:</strong>
                            {{ $requirement->firstname . ' ' . ($requirement->middlename ? $requirement->middlename[0] . '. ' : '') . $requirement->lastname }}
                        </p>
                        <p> <i class="fas fa-calendar-check"></i> <strong>Due date:</strong>
                            {{ \Carbon\Carbon::parse($requirement->due_date)->format('F j, Y') }} </p>
                        <p> <i class="fas fa-clock"></i> <strong>Reminders:</strong> Once a week</p>

                        <p> <i class="fas fa-lock " style="color: #fd7e14"></i> <strong> Visibility: </strong> <span
                                class="badge bg-primary">
                                {{ $requirement->visibility_type }}
                            </span>
                        </p>

                        <div style="outline: 1px solid #ccc; padding: 5px;border-radius: 4px;">
                            <p> <i class="fas fa-align-left"></i> Description</p>
                            <p>{{ $requirement->requirement_desc }}</p>
                        </div>

                        @if ($requirement->visibility_type == 'selectedemployee')
                            <div class="mt-3" style="outline: 1px solid #ccc; padding: 5px;border-radius: 4px;">
                                <p> <i class="fas fa-users"></i> Assignee</p>
                                <div>

                                    @foreach ($employees as $employee)
                                        <span class="badge bg-secondary">{{ $employee->lastname }},
                                            {{ $employee->firstname }}
                                            {{ $employee->middlename }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @elseif ($requirement->visibility_type == 'selecteddepartments')
                            <div class="mt-3" style="outline: 1px solid #ccc; padding: 5px;border-radius: 4px;">
                                <p> <i class="fas fa-building"></i> Departments</p>
                                <div>
                                    @foreach ($departments as $department)
                                        <span class="badge bg-secondary">{{ $department->department }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($requirement->visibility_type == 'all')
                            <div class="mt-3" style="outline: 1px solid #ccc; padding: 5px;border-radius: 4px;">
                                <p> <i class="fas fa-users"></i> All employees</p>
                                <div>
                                    @foreach ($employees as $employee)
                                        <span class="badge bg-secondary">{{ $employee->lastname }},
                                            {{ $employee->firstname }}
                                            {{ $employee->middlename }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="col-md-9">
                <!-- Status Summary -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        {{-- <div class="status-box status-not-submitted">
                                          <span>1/4</span>
                                          <small>Not Submitted</small>
                                    </div> --}}
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-gray"><i class="fas fa-hourglass-start"></i></span>

                            <div class="info-box-content text-center">
                                <span class="info-box-number"
                                    style="font-size: 20px">{{ $requirement->totalNotSubmitted }}/{{ $requirement->totalEmployees }}
                                </span>
                                <span class="info-box-text">Not Submitted</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{-- <div class="status-box status-submitted">
                                          <span>3/4</span>
                                          <small>Submitted</small>
                                    </div> --}}
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-primary"><i class="fas fa-paper-plane"></i></span>

                            <div class="info-box-content text-center">
                                <span class="info-box-number" style="font-size: 20px">
                                    {{ $requirement->totalSubmitted }}/{{ $requirement->totalEmployees }} </span>
                                <span class="info-box-text ">Submitted</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{-- <div class="status-box status-approved">
                                          <span>0/4</span>
                                          <small>Approved</small>
                                    </div> --}}
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-success"><i class="far fa-check-circle"></i></span>

                            <div class="info-box-content text-center">
                                <span class="info-box-number" style="font-size: 20px">
                                    {{ $requirement->totalApproved }}/{{ $requirement->totalEmployees }} </span>
                                <span class="info-box-text ">Approved</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{-- <div class="status-box status-rejected">
                                          <span>0/4</span>
                                          <small>Rejected</small>
                                    </div> --}}
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>

                            <div class="info-box-content text-center">
                                <span class="info-box-number" style="font-size: 20px">
                                    {{ $requirement->totalRejected }}/{{ $requirement->totalEmployees }} </span>
                                <span class="info-box-text ">Rejected</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                </div>

                <!-- Employee Submissions List -->
                <div class="card shadow">
                    <div class="card-header">
                        <a href="javascript:window.history.back()" class="btn btn-sm btn-secondary text-white"><i
                                class="fas fa-chevron-left mr-1"></i> Back</a>
                        <div class="float-right">
                            <button type="button" class="btn btn-sm btn-primary text-white downloadAll" hidden><i
                                    class="fas fa-download mr-1"></i>
                                Download</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Employee Submissions Table -->
                        <div class="table-responsive">
                            <table class="table table-valign-middle" id="employeeTable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th scope="col" width="40%"> <input type="checkbox" class="mr-3"
                                                id="selectAll">Employees
                                        </th>
                                        <th scope="col" width="30%">Status</th>
                                        <th scope="col" width="20%">File</th>
                                        <th scope="col" width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Employee Item 1 -->
                                    {{-- <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="mr-3">
                                                <img src="{{ asset('assets/images/avatars/unknown.png') }}"
                                                    alt="Avatar" class="employee-avatar">
                                                <div>
                                                    <p class="mb-0"><strong>Trisha Bagonong</strong></p>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="text-primary">Submitted</span>
                                        </td>

                                        <td>
                                            <div>
                                                <i class="fas fa-file-pdf text-danger"></i>
                                                <strong>Pagibig.pdf</strong><br>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex">
                                                <button class="btn btn-primary btn-sm mr-1"><i
                                                        class="fas fa-download"></i></button>
                                                <button class="btn btn-info btn-sm mr-1"><i
                                                        class="fas fa-eye"></i></button>
                                                <button class="btn btn-success btn-sm mr-1"><i
                                                        class="fas fa-check"></i></button>
                                                <button class="btn btn-danger btn-sm mr-1"><i
                                                        class="fas fa-times"></i></button>
                                                <button class="btn btn-sm ml-2"><i class="fas fa-angle-down"></i></button>
                                            </div>
                                        </td>

                                    </tr> --}}
                                    <!-- Additional employee rows can be added here following the same structure -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div class="modal fade" id="approveRequirementModal" tabindex="-1" role="dialog"
        aria-labelledby="approveRequirementLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="approveRequirementLabel">
                        <img src="{{ asset('assets/images/avatars/unknown.png') }}" alt="PDF Icon" class="img-fluid"
                            style="width: 40px;" />
                        <span id="employeeName">Employee Name</span><br>
                        <small id="employeePosition">Position</small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- File Info -->
                    <div class="file-container">
                        {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <img src="{{ asset('assets/images/pdf.png') }}" alt="PDF Icon" class="img-fluid"
                                    style="width: 40px;" />
                                <span id="fileName">File.pdf</span><br>
                                <small id="fileSize">20.6 MB</small>
                            </div>
                            <div>
                                <p class="mb-0"><i class="far fa-calendar-alt"></i> Date Submitted: <span
                                        id="submissionDate">August 31, 2024 | 10:00 am</span></p>
                            </div>
                        </div> --}}
                    </div>

                    <!-- File Actions -->
                    {{-- <div class="mb-3 files_list" hidden>
                        <a href="#" id="downloadFile" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <a href="#" id="viewFile" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div> --}}

                    <!-- Remarks Section -->
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" rows="3" placeholder="Message"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success modal_btn_approve">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="imageModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index: 1050;">
        <span id="closeModal"
            style="position:absolute; top:10px; right:20px; font-size:30px; color:white; cursor:pointer;">&times;</span>

        <!-- Container for centering the content -->
        <div style="display: flex; justify-content: center; align-items: center; height: 100%; z-index: 1060;">
            <!-- Image element for displaying images -->
            <img id="modalImage" src="" style="display:none; max-width:90%; max-height:90%; padding-top:60px;">

            <!-- Iframe element for displaying PDFs -->
            <iframe id="modalPdf" src="" style="display:none; width:80%; height:80%; border:none;"></iframe>
        </div>
    </div>


@endsection

@section('footerjavascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>
        $('body').addClass('sidebar-collapse');
        var purpose = '';
        var historyData = '';
        Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        })

        $(document).ready(function() {
            console.log({!! json_encode($requirement) !!});
            populateEmployeeTable({!! json_encode($employees) !!});

            // Attach click event for viewing files
            $(document).on('click', '.view-file-btn', function() {
                const fileUrl = $(this).data('filepath');
                handleFilePreview(fileUrl);
            });

            // Event listener for download buttons
            $(document).on('click', '.download-file-btn', function() {
                const fileUrl = $(this).data('filepath');
                downloadFile(fileUrl);
            });

            $('.downloadAll').on('click', function() {
                var selectedEmployees = [];
                $('.employee-checkbox:checked').each(function() {
                    selectedEmployees.push($(this).data('history'));
                });

                if (selectedEmployees.length === 0) {
                    Toast.fire({
                        type: 'warning',
                        title: 'No employees selected!'
                    });
                    return;
                }

                var zip = new JSZip();

                selectedEmployees.forEach(function(historyData) {
                    var employeeFolderName = `${historyData.lastname} ${historyData.firstname}`
                        .trim();
                    var folder = zip.folder(employeeFolderName);

                    historyData.req_file.forEach(function(file) {
                        folder.file(file.filepath.split('/').pop(), file.filedata, {
                            binary: true
                        });
                    });
                });

                zip.generateAsync({
                        type: 'blob'
                    })
                    .then(function(content) {
                        const url = window.URL.createObjectURL(content);
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', `Requirement-Files-${new Date().getTime()}.zip`);
                        document.body.appendChild(link);
                        link.click();
                        window.URL.revokeObjectURL(url);
                    });
            });

            $('.employee-checkbox').on('change', function() {
                $('.downloadAll').prop('hidden', !$('.employee-checkbox:checked').length);
            })

            $('#selectAll').on('change', function() {
                $('.downloadAll').prop('hidden', !this.checked);
                $('.employee-checkbox[data-has_req="1"]').prop('checked', this.checked);
            });

            $('#employeeTable tbody').on('click',
                '.btn_approve, .btn_return, .btn_reject, .btn_view, .btn_download',
                function(
                    event) {
                    event.stopPropagation(); // Prevent row click event from firing
                    let action = $(this).data('action');
                    if (action == 'approve') {
                        purpose = 'approved';
                        $('.modal_btn_approve').text('Approve');
                        $('.modal_btn_approve').attr('class', 'btn btn-success modal_btn_approve');
                        $('#remarks').val('Requirement has been approved.');
                        parseHistoryData($(this).data('history'));
                    } else if (action == 'return') {
                        purpose = 'returned';
                        $('.modal_btn_approve').show();
                        $('#remarks').show();
                        $('.modal_btn_approve').text('Return');
                        $('.modal_btn_approve').attr('class', 'btn btn-orange modal_btn_approve');
                        $('#remarks').val('Requirement has been returned.');
                        parseHistoryData($(this).data('history'));
                    } else if (action == 'reject') {
                        purpose = 'rejected';
                        $('.modal_btn_approve').show();
                        $('#remarks').show();
                        $('.modal_btn_approve').text('Reject');
                        $('.modal_btn_approve').attr('class', 'btn btn-danger modal_btn_approve');
                        $('#remarks').val('Requirement has been rejected.');
                        parseHistoryData($(this).data('history'));
                    } else if (action == 'view') {
                        purpose = 'view';
                        $('.modal_btn_approve').hide();
                        $('#remarks').hide();
                        parseHistoryData($(this).data('history'));
                    } else if (action == 'download') {
                        historyData = $(this).data('history') || {};

                        // Ensure the data is an object; if it's a string, parse it to JSON
                        if (typeof historyData === 'string') {
                            historyData = JSON.parse(historyData);
                        }

                        if (historyData.req_file.length > 0) {
                            const zip = new JSZip(); // Create a new zip instance

                            // Combine the lastname and firstname for the folder name
                            const folderName = `${historyData.lastname} ${historyData.firstname}`.trim() ||
                                'employee'; // Fall back to 'employee' if not available

                            // Create a single folder with the employee's name in the ZIP
                            const folder = zip.folder(folderName);

                            // Loop through all the files and add them to the zip folder
                            let filesProcessed = 0;
                            historyData.req_file.forEach(file => {
                                const fileUrl = file.filepath;
                                const filename = fileUrl.split('/').pop(); // Extract filename

                                // Fetch the file and add it to the ZIP
                                fetch(fileUrl)
                                    .then(response => response.blob())
                                    .then(blob => {
                                        folder.file(filename,
                                            blob); // Add the file to the ZIP folder
                                        filesProcessed++;

                                        // Check if all files have been processed and then trigger the download
                                        if (filesProcessed === historyData.req_file.length) {
                                            // Generate the zip file and trigger download
                                            zip.generateAsync({
                                                type: 'blob'
                                            }).then(function(content) {
                                                // Trigger the download automatically
                                                const downloadLink = document.createElement(
                                                    'a');
                                                downloadLink.href = URL.createObjectURL(
                                                    content);
                                                downloadLink.download =
                                                    `${folderName}-files.zip`; // Name of the downloaded ZIP file
                                                downloadLink.click();
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error fetching file:', error);
                                    });
                            });
                        }
                    }



                });

            // Close modal when clicking outside the image
            $('#imageModal').on('click', function(e) {
                if (!$(e.target).is('#modalImage')) { // Check if clicked element is not the image
                    $(this).fadeOut();
                }
            });


            $('.modal_btn_approve').on('click', function() {
                // console.log('approving...', historyData);
                // Send the AJAX request
                if (purpose == 'approved') {

                    $('.modal_btn_approve').text('Approving...');
                    $('.modal_btn_approve').prop('disabled', true)
                    approve();
                } else if (purpose == 'returned') {
                    $('.modal_btn_approve').text('Returning...');
                    $('.modal_btn_approve').prop('disabled', true)
                    approve();
                } else if (purpose == 'rejected') {
                    Swal.fire({
                        text: 'Are you sure you want to Reject? ' + historyData.firstname + ' ' +
                            historyData.lastname + ' submission?',
                        title: "Reject Submission!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, reject it!'
                    }).then((result) => {
                        if (result.value) {
                            // Send the AJAX request
                            $('.modal_btn_approve').text('Rejecting...');
                            $('.modal_btn_approve').prop('disabled', true)
                            approve();
                        }
                    });
                }

            });
        });

        function getFileIcon(filePath) {
            const ext = filePath.split('.').pop().toLowerCase();

            // Mapping file extensions to Font Awesome icon classes
            const iconMap = {
                'pdf': 'fas fa-file-pdf',
                'jpg': 'fas fa-image',
                'jpeg': 'fas fa-image',
                'png': 'fas fa-image',
                'gif': 'fas fa-image',
                'doc': 'fas fa-file-word',
                'docx': 'fas fa-file-word',
                'xls': 'fas fa-file-excel',
                'xlsx': 'fas fa-file-excel'
            };

            // Return the corresponding icon, or the default file icon if no match is found
            return iconMap[ext] || 'fas fa-file-alt';
        }

        function parseHistoryData(data) {
            console.log(data);

            historyData = data || {};
            // Ensure the data is an object, if it's a string, parse it to JSON
            if (typeof historyData === 'string') {
                historyData = JSON.parse(historyData);
            }

            // Populate the modal fields with data from historyData
            $('#employeeName').text(
                `${historyData.firstname} ${historyData.lastname}`); // Full employee name
            $('#employeePosition').text(historyData.utype ||
                'N/A'); // Employee position (or N/A if null)

            // Set file-related information
            if (historyData.req_file && historyData.req_file.length > 0) {
                $('.file-container').empty(); // Clear the container before appending new files

                historyData.req_file.forEach(file => {
                    // Truncate filename if it's too long
                    const truncatedFilename = file.filename.length > 20 ?
                        `${file.filename.substring(0, 20)}....${file.fileextension}` :
                        file.filename;

                    // Format the `created_at` date
                    const formattedDate = new Date(file.created_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit'
                    }).replace(',', ' |');

                    // Append the file item to the container
                    // Append the file item to the container
                    $('.file-container').append(`
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <i class="${getFileIcon(file.filepath)}" style="font-size: 40px; color: lightgray;"></i> <!-- Use Font Awesome icon -->
                                <span>${truncatedFilename}</span><br>
                                <small>${file.size || 'Unknown size'}</small>
                            </div>
                            <div>
                                <p class="mb-0"><i class="far fa-calendar-alt"></i> Date Submitted: 
                                    <span>${formattedDate}</span>
                                </p>
                                <div class="mt-2">
                                    <button class="btn btn-outline-primary btn-sm download-file-btn" data-filepath="${file.filepath}"><i class="fas fa-download mr-1"></i> Download</button>
                                    <button class="btn btn-outline-secondary btn-sm view-file-btn" data-filepath="${file.filepath}"><i class="fas fa-eye mr-1"></i> View</button>
                                </div>
                            </div>
                        </div>
                    `);


                    console.log(file); // Debugging: Log each file object
                });
            } else {
                // If no files are available, display a fallback message
                $('.file-container').html('<p>No files available.</p>');
            }


            // Date Submitted
            // const submissionDate = historyData.latest_history ? historyData.latest_history.created_at :
            //     'N/A';
            // $('#submissionDate').text(submissionDate); // Display submission date

            // Show the modal
            $('#approveRequirementModal').modal();
        }

        function approve() {
            console.log(purpose);
            $.ajax({
                url: '{{ route('hr.requirements.approve') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    req_id: historyData.latest_history.requirement_id,
                    employee_id: historyData.id,
                    req_status: purpose,
                    req_remarks: $('#remarks').val()
                },
                success: function(data) {
                    console.log(data);
                    if (data.status == 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then((result) => {
                            if (result.value) {
                                $('.modal_btn_approve').prop('disabled', false);
                                console.log('Employees', data.employees);

                                populateEmployeeTable(data.employees);
                                populateStatusSummary(data.requirement);
                            }
                        });
                    } else {
                        alert(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });

            $('#approveRequirementModal').modal('hide');
        }

        // Function to handle file download
        function downloadFile(fileUrl) {
            if (!fileUrl) {
                Swal.fire({
                    title: 'Error',
                    text: 'The file URL is invalid.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Create a temporary anchor tag for downloading the file
            const $downloadLink = $('<a>')
                .attr('href', fileUrl)
                .attr('download', fileUrl.split('/').pop()) // Extract the filename from the URL
                .attr('target', '_blank'); // Open in a new tab (optional)

            // Append, trigger the download, and remove the anchor element
            $('body').append($downloadLink);
            $downloadLink[0].click();
            $downloadLink.remove();
        }


        // Function to handle file preview
        function handleFilePreview(fileUrl) {
            try {
                if (fileUrl.endsWith('.pdf')) {
                    // Show PDF in modal
                    $('#modalImage').hide();
                    $('#modalPdf').attr('src', fileUrl).show();
                } else if (fileUrl.endsWith('.xlsx') || fileUrl.endsWith('.xls') || fileUrl.endsWith('.doc') || fileUrl
                    .endsWith('.docx')) {
                    // Automatically prompt file download for Excel/Word documents
                    $('#imageModal').hide();
                    window.location.href = fileUrl;
                } else {
                    // Show image in modal
                    $('#modalPdf').hide();
                    $('#modalImage').attr('src', fileUrl).show();
                }

                $('#imageModal').fadeIn(); // Display the modal
            } catch (error) {
                Swal.fire({
                    title: 'Error',
                    text: 'The file provided is invalid.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }


        function populateEmployeeTable(data) {
            console.log(data);

            var table = $('#employeeTable').DataTable({
                data: data, // Assuming $employees is a valid array of objects
                destroy: true,
                pageLength: 5,
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                columns: [{
                        data: null, // This should match your data structure
                        render: function(data, type, row) {

                            const {
                                has_req,
                                latest_history
                            } = data;

                            // Stringify the row object to pass it as data-history to buttons, escaping special characters
                            let rowDataString = JSON.stringify(data)
                                .replace(/"/g, '&quot;') // Escape double quotes
                                .replace(/'/g, '&#39;') // Escape single quotes
                                .replace(/</g, '&lt;') // Escape less-than sign
                                .replace(/>/g, '&gt;'); // Escape greater-than sign

                            return `
                        <div class="d-flex align-items-center">
                            <input type="checkbox" class="mr-3 employee-checkbox" data-has_req="${has_req}" data-history="${rowDataString}" ${has_req ? '' : 'disabled'} >
                            <img src="{{ asset('assets/images/avatars/unknown.png') }}" alt="Avatar" class="employee-avatar" style="width: 40px; margin-right: 10px;">
                            <div>
                                <p class="mb-0"><strong>${data.firstname} ${data.middlename ? data.middlename[0] + '. ' : ''}${data.lastname}</strong> <span class="badge badge-warning">${data.utype}</span>  </p>
                            </div>
                        </div>
                    `;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let textclr = '';
                            if (row.latest_history) {
                                textclr = row.latest_history.req_status == 'submitted' ?
                                    'text-info2' :
                                    row.latest_history.req_status == 'approved' ? 'text-success2' :
                                    row.latest_history.req_status == 'returned' ? 'dark-orange' :
                                    'text-danger2'
                            }


                            return row.has_req == '0' ?
                                '<span class="text-muted">Not Submitted</span>' :
                                `<span class="${textclr }">${row.latest_history.req_status.charAt(0).toUpperCase() + row.latest_history.req_status.slice(1)}</span>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            let filename = 'Awaiting Submission...';
                            let fileextension = '';
                            const maxLength = 20; // Set the maximum length for the filename

                            if (row.req_file && row.req_file.length > 0) {
                                filename = row.req_file[0].filename;
                                fileextension = row.req_file[0].fileextension;

                                // Check if filename length exceeds maxLength
                                if (filename.length > maxLength) {
                                    filename = filename.substring(0, maxLength) +
                                        '...' + '.' + fileextension; // Truncate and add ellipsis
                                }
                            }

                            return row.has_req == '0' ?
                                '<span class="text-muted">' + filename + '</span>' :
                                `<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="${ fileextension == 'pdf' ? 'fas fa-file-pdf' : 'fas fa-file-image' } text-danger2"></i>
                                    <strong>${filename}</strong>  
                                </div>`;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {

                            const {
                                has_req,
                                latest_history
                            } = data;

                            // Stringify the row object to pass it as data-history to buttons, escaping special characters
                            let rowDataString = JSON.stringify(data)
                                .replace(/"/g, '&quot;') // Escape double quotes
                                .replace(/'/g, '&#39;') // Escape single quotes
                                .replace(/</g, '&lt;') // Escape less-than sign
                                .replace(/>/g, '&gt;'); // Escape greater-than sign

                            // Generate the buttons based on the conditions
                            let newbtn = has_req && latest_history.req_status == 'approved' ?
                                `<button class="btn btn-orange btn-sm mr-1 btn_return" data-action="return" data-history="${rowDataString}" title="Return">
                                    <i class="fas fa-undo-alt"></i>
                                </button>` :
                                has_req && latest_history.req_status != 'approved' ?
                                `<button class="btn btn-success btn-sm mr-1 btn_approve" data-action="approve" data-history="${rowDataString}" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>` :
                                `<button class="btn btn-success btn-sm mr-1" disabled title="Approve (Disabled)">
                                    <i class="fas fa-check"></i>
                                </button>`;
                            return `
                                <div class="d-flex">
                                    <button class="btn btn-primary btn-sm mr-1 btn_download" data-action="download" data-history="${rowDataString}" title="Download file" ${has_req ? '' : 'disabled'}>
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="btn btn-info btn-sm mr-1 btn_view" data-action="view" data-history="${rowDataString}" title="View file" ${has_req ? '' : 'disabled'}>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    ${newbtn}
                                    <button class="btn btn-danger btn-sm mr-1 btn_reject" data-action="reject" data-history="${rowDataString}" title="Reject file" ${has_req ? '' : 'disabled'}>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }


                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-toggle',
                        'collapse'); // Optional: Use data-toggle for Bootstrap collapsible
                },
                paging: true,
                searching: true,
                ordering: true,
            });

            // Event listener for row click
            $('#employeeTable tbody').on('click', 'tr', function() {
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
                                // Add this line for rejected status
                                'text-info'; // Default class

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
        }

        function populateStatusSummary(requirementData) {
            // Update the 'Not Submitted' info box
            $('.info-box .bg-gray').siblings('.info-box-content').find('.info-box-number')
                .text(requirementData.totalNotSubmitted + '/' + requirementData.totalEmployees);

            // Update the 'Submitted' info box
            $('.info-box .bg-primary').siblings('.info-box-content').find('.info-box-number')
                .text(requirementData.totalSubmitted + '/' + requirementData.totalEmployees);

            // Update the 'Approved' info box
            $('.info-box .bg-success').siblings('.info-box-content').find('.info-box-number')
                .text(requirementData.totalApproved + '/' + requirementData.totalEmployees);

            // Update the 'Rejected' info box
            $('.info-box .bg-danger').siblings('.info-box-content').find('.info-box-number')
                .text(requirementData.totalRejected + '/' + requirementData.totalEmployees);
        }
    </script>

    <script>
        // Handle Edit Icon Click
        $('#editIcon').on('click', function() {
            // Toggle visibility of text and input fields
            $('#requirementName').toggleClass('d-none');
            $('#requirementNameInput').toggleClass('d-none');

            // Toggle the visibility of edit and save icons
            $('#editIcon').toggleClass('d-none');
            $('#saveIcon').toggleClass('d-none');

            // Focus the input field
            $('#requirementNameInput').focus();
        });

        // Handle Save Icon Click
        $('#saveIcon').on('click', function() {
            const newRequirementName = $('#requirementNameInput').val();
            Swal.fire({
                title: 'Submitting...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });
            // Perform AJAX call to save the updated requirement name
            $.ajax({
                url: '/hr/updaterequirement', // Replace with actual update route
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    requirement_id: "{{ $requirement->id }}", // Send requirement ID if needed
                    requirement_name: newRequirementName,
                    purpose: 'updatename'
                },
                success: function(response) {
                    Swal.close();
                    // On success, update the display text and toggle back to view mode
                    $('#requirementName').text(newRequirementName.toUpperCase()).removeClass('d-none');
                    $('#requirementNameInput').addClass('d-none');
                    $('#editIcon').removeClass('d-none');
                    $('#saveIcon').addClass('d-none');

                    Toast.fire({
                        type: response.status,
                        title: response.message
                    });
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.log("Error updating requirement name:", error);
                }
            });
        });
    </script>
@endsection
