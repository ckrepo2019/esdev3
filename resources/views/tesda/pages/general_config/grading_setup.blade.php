@extends('tesda.layouts.app2')

@section('pagespecificscripts')
@endsection

@section('content')
    <style>
        table tr {
            font-size: 12px;
        }

        .invalid-input {
            border: 1px solid red;
            background-color: #ffffff;
            /* Light red background */
        }

        .is-invalid {
            border: 1px solid red !important;
        }

        .invalid-input {
            border: 1px solid red !important;
            background-color: #ffffff;
            /* Light red background */
        }

        .select2-container {
            min-width: 100% !important;
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <h1><i class="fas fa-cog"></i>Grading Setup</h1>
            <ol class="breadcrumb float-sm-left">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">{{ isset($page) ? $page : 'Grading Setup' }}</li>
            </ol>
        </div>
    </section><br>

    {{-- <div class="content">
        <div class="container-fluid">
            <div class="card">
                <h5><i class="fas fa-filter m-2"></i>Filter</h5>
                <div class="row ml-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Student Type</label>
                            <select class="form-control select2 courses" style="width: 100%;">
                                <option value="">Select</option>
                                <option value="old">Old Student</option>
                                <option value="new">New Student</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Student Status</label>
                            <select class="form-control select2 courseType" style="width: 100%;">
                                <option value="">Select</option>
                                <option value="old">Old Student</option>
                                <option value="new">New Student</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title">Grading Setup</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12 text-left">
                            <button class="btn btn-success" id="addGradingSetupButton" data-toggle="modal"
                                data-target="#gradingSetupModal">Add Grading
                                Setup
                            </button>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="gradingSetupTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Grading Description</th>
                                            <th>Component Percentage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gradingSetupBody">
                                        <!-- Rows will be dynamically populated -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grading Setup Modal -->
    <div class="modal fade" id="gradingSetupModal" tabindex="-1" role="dialog" aria-labelledby="gradingSetupLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gray">
                    <h5 class="modal-title" id="gradingSetupLabel">Class Record Components Setup</h5>
                    <button type="button" id="closeModalGrading" class="close" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>

                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="gradingDescription">Grading Description</label>
                        <input type="text" id="gradingDescription" class="form-control"
                            placeholder="Enter Grading Description" required>
                    </div>
                    <!-- Button to trigger the 'Add Grading Component' modal -->
                    <button class="btn btn-success mb-3" id="addGrading" data-toggle="modal"
                        data-target="#addGradingComponent">
                        Add Grading Component
                    </button>
                    <!-- Table for Grading Setup -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Main Component Description</th>
                                <th>Component %</th>
                                <th>Sub-Component Description</th>
                                <th>Sub-Component %</th>
                                <th># of Columns in Class Record</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="gradingSetupList">
                            <!-- Grading Setup Data will go here -->
                        </tbody>
                    </table>
                    <button class="btn btn-success float-right d-none" id = 'saveGrading'>Save</button>
                    <button class="btn btn-success float-right d-none" id = 'updateGrading'>Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addGradingComponent" tabindex="-1" aria-labelledby="addGradingComponentLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gray">
                    <h5 class="modal-title" id="addGradingComponentLabel">New Grading Components</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="font-size: 12px">
                    <form id="gradingComponentForm">
                        <div class="mb-3">
                            <label for="componentgradingDescription" class="form-label">Main Component
                                Description</label>
                            <input type="text" class="form-control" id="componentgradingDescription"
                                placeholder="Enter Grading Component Description" required>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="componentPercentage" class="form-label">Component Percentage (%)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control" id="componentPercentage"
                                        placeholder="Enter Percentage" required>
                                    <span class="input-group-text input-group-text-sm border-0 transparent"><i
                                            class="fas fa-percent"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="classColumns" class="form-label"># of Columns</label>
                                <select class="form-select select2" id="classColumns" required>
                                    <option value="" selected disabled>Select Columns</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="subComponent">
                            <label class="form-check-label" for="subComponent">Sub Component</label>
                        </div>
                        <div id="additionalInputsContainer"></div>
                        <button type="button" id="addMoreBtn" class="btn btn-sm btn-primary d-none mt-3">+ Add
                            More</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="saveGradingComponent">Save</button>
                    <button class="btn btn-success float-right" id = 'updateGradingComponent'>Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            $('#gradingSetup').DataTable();
            $('.select2').select2()


            $('#subComponent').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#addMoreBtn').toggleClass('d-none', !isChecked);
                $('#classColumns').prop('disabled', isChecked);
                if (isChecked) {
                    appendInputSet();
                } else {
                    $('#additionalInputsContainer').empty();
                }
            });

            $('#addMoreBtn').on('click', function() {
                appendInputSet();
            });

            function appendInputSet() {
                $('#additionalInputsContainer').append(`
                    <div class="row mt-3 subcomponent-row" style="font-size: 12px" data-sub-id="0">
                        <div class="col-sm-4">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control form-control-sm subgradingDescription" name="subgradingDescription[]" placeholder="Enter description">
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Percentage (%)</label>
                            <input type="number" class="form-control form-control-sm subgradingcomponent" name="gradingComponent[]" placeholder="Enter component">
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label">Columns</label>
                            <select class="form-control form-control-sm select2 subcolumnNumber" name="columnNumber[]">
                                <option value="" disabled selected>Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                            </select>
                        </div>
                        <div class="col-sm-2 d-flex justify-content-center align-items-end">
                            <button class="btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt removeAddComponent"></i></button>
                        </div>
                    </div>
                `);
                $('.select2').select2();
            }

            $(document).on('click', '.removeAddComponent', function() {
                $(this).closest('.subcomponent-row').remove(); // Remove the closest subcomponent row
            });


            $(document).on("click", ".edit-row", function () {
    let row = $(this).closest("tr");
    let mainDescription = row.find("td:eq(0)").text().trim();
    let componentPercentage = row.find("td:eq(1)").text().replace('%', '').trim();
    let numColumns = row.find("td:eq(4)").text().trim();
    
    // Store the reference to the main component row
    let componentId = row.attr("data-id");
    
    // Populate main component fields in the modal
    $("#componentgradingDescription").val(mainDescription);
    $("#componentPercentage").val(componentPercentage);
    $("#classColumns").val(numColumns);

    // Enable inputs for editing
    $("#componentgradingDescription, #componentPercentage, #classColumns").prop("disabled", false);

    // Clear subcomponent fields before adding new ones
    $("#additionalInputsContainer").empty();

    // Find and populate subcomponents
    let nextRow = row.next();
    while (nextRow.length && nextRow.hasClass("subComponentRow")) {
        let subDescription = nextRow.find("td:eq(2)").text().trim();
        let subPercentage = nextRow.find("td:eq(3)").text().replace('%', '').trim();
        let subColumns = nextRow.find("td:eq(4)").text().trim();

        let subComponentHtml = `
            <div class="row mt-3 subcomponent-row">
                <div class="col-sm-4">
                    <input type="text" name="subgradingDescription[]" class="form-control" value="${subDescription}">
                </div>
                <div class="col-sm-3">
                    <input type="text" name="gradingComponent[]" class="form-control" value="${subPercentage}">
                </div>
                <div class="col-sm-3">
                    <select class="form-control form-control-sm select2 subcolumnNumber" name="columnNumber[]">
                        <option value="" disabled selected>Select</option>
                        <option value="1" ${subColumns == "1" ? "selected" : ""}>1</option>
                        <option value="2" ${subColumns == "2" ? "selected" : ""}>2</option>
                        <option value="3" ${subColumns == "3" ? "selected" : ""}>3</option>
                        <option value="4" ${subColumns == "4" ? "selected" : ""}>4</option>
                        <option value="5" ${subColumns == "5" ? "selected" : ""}>5</option>
                        <option value="10" ${subColumns == "10" ? "selected" : ""}>10</option>
                        <option value="15" ${subColumns == "15" ? "selected" : ""}>15</option>
                    </select>
                </div>
                <div class="col-sm-2 d-flex justify-content-center align-items-end">
                    <button class="btn btn-danger btn-sm" type="button"><i class="fas fa-trash-alt removeAddComponent"></i></button>
                </div>
            </div>
        `;
        $("#additionalInputsContainer").append(subComponentHtml);
        nextRow = nextRow.next();
    }

    // Show modal for editing
    $("#addGradingComponent").modal("show");

    // Update button action (change save to update)
    $("#saveGradingComponent").text("Update").off("click").on("click", function () {
        row.find("td:eq(0)").text($("#componentgradingDescription").val().trim());
        row.find("td:eq(1)").text($("#componentPercentage").val().trim() + "%");
        row.find("td:eq(4)").text($("#classColumns").val());

        // Update subcomponent rows
        let subcomponentRows = row.nextUntil(":not(.subComponentRow)");
        subcomponentRows.remove(); // Remove existing subcomponent rows before re-adding them

        $("#additionalInputsContainer .subcomponent-row").each(function () {
            let subDesc = $(this).find("input[name='subgradingDescription[]']").val().trim();
            let subPercent = $(this).find("input[name='gradingComponent[]']").val().trim();
            let subCol = $(this).find("select[name='columnNumber[]']").val();

            let subRow = `
                <tr class="subComponentRow">
                    <td colspan="2"></td>
                    <td>${subDesc}</td>
                    <td>${subPercent}%</td>
                    <td>${subCol}</td>
                    <td></td>
                </tr>`;
            row.after(subRow); // Insert subcomponent rows after the main component
        });

        // Close modal after updating
        $("#addGradingComponent").modal("hide");
    });
});


            $("#saveGradingComponent").click(function() {
                let isValid = true;

                // Reset previous validation states
                $("#gradingComponentForm input, #gradingComponentForm select").removeClass("is-invalid");

                // Validate main component fields
                const mainComponentDescription = $("#componentgradingDescription").val().trim();
                const componentPercentage = $("#componentPercentage").val().trim();
                const numColumns = $("#classColumns").val();

                if (!mainComponentDescription) {
                    $("#componentgradingDescription").addClass("is-invalid");
                    isValid = false;
                }

                if (!componentPercentage) {
                    $("#componentPercentage").addClass("is-invalid");
                    isValid = false;
                }

                let subComponents = [];
                let totalSubPercentage = 0;

                const isSubComponent = $("#subComponent").is(":checked");

                if (isSubComponent) {
                    $("#additionalInputsContainer .subcomponent-row").each(function() {
                        const subComponentDescription = $(this).find(
                            "input[name='subgradingDescription[]']").val().trim();
                        const subComponentPercentage = $(this).find(
                            "input[name='gradingComponent[]']").val().trim();
                        const subColumns = $(this).find("select[name='columnNumber[]']").val();

                        if (!subComponentDescription) {
                            $(this).find("input[name='subgradingDescription[]']").addClass(
                                "is-invalid");
                            isValid = false;
                        }

                        if (!subComponentPercentage) {
                            $(this).find("input[name='gradingComponent[]']").addClass("is-invalid");
                            isValid = false;
                        }

                        if (!subColumns) {
                            $(this).find("select[name='columnNumber[]']").addClass("is-invalid");
                            isValid = false;
                        }

                        totalSubPercentage += parseFloat(subComponentPercentage) || 0;

                        subComponents.push({
                            subDescription: subComponentDescription,
                            subPercentage: parseFloat(subComponentPercentage) || 0,
                            columnsInECR: subColumns
                        });
                    });

                    if (totalSubPercentage !== 100) {
                        Swal.fire('Validation Error',
                            `Total subcomponent percentage must be 100%. Currently: ${totalSubPercentage}%`,
                            'error');
                        return;
                    }
                }

                // Stop if validation failed
                if (!isValid) {
                    Swal.fire('Validation Error', 'Please fill out all required fields.', 'error');
                    return;
                }

                let componentData = {
                    mainDescription: mainComponentDescription,
                    componentPercentage: parseFloat(componentPercentage),
                    numColumns: parseInt(numColumns) || 0,
                    subComponents: subComponents
                };

                let tableRow = `
                    <tr class="componentRow" data-id="${componentData.id || 0}">
                        <td>${mainComponentDescription}</td>
                        <td>${componentData.componentPercentage}%</td>
                        <td></td>
                        <td></td>
                        <td>${componentData.numColumns}</td>
                        <td style="white-space: nowrap">
                            <button class="btn btn-sm border-0 p-0 text-info edit-row" style="margin-right: 5px;"><i class="fas fa-pencil-alt"></i></button>
                            <button class="btn btn-sm border-0 p-0 text-danger delete-row" data-id="${componentData.id}"> <i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>`;

                $("#gradingSetupList").append(tableRow);

                subComponents.forEach(sub => {
                    let subComponentRow = `
                        <tr class="subComponentRow">
                            <td colspan="2"></td>
                            <td>${sub.subDescription}</td>
                            <td>${sub.subPercentage}%</td>
                            <td>${sub.columnsInECR}</td>
                        </tr>`;
                    $("#gradingSetupList").append(subComponentRow);
                });

                // Reset the form and close the modal
                $("#gradingComponentForm")[0].reset();
                $("#classColumns").prop("disabled", false);
                $("#addGradingComponent").modal("hide");
            });

            $(document).on("click", ".delete-row", function() {
                let row = $(this).closest("tr");
                row.nextUntil(".componentRow").addBack().remove();
            });

            $('#addGradingComponent').on('show.bs.modal', function() {
                $('#updateGradingComponent').hide();
                $('#saveGradingComponent').show();
                $('#subComponent').prop('checked', false);
            });

            $('#aaddGrading').on('show.bs.modal', function() {
                $('#subComponent').prop('checked', false);
            });

            // When "Add Grading Setup" button is clicked
            $(document).on('click', '#addGradingSetupButton', function() {
                $('#saveGrading').removeClass('d-none');
                $('#updateGrading').addClass('d-none');
                $('#gradingDescription').val('');
            });

            // When "Edit" button is clicked
            $(document).on('click', '.gradingEdit', function() {
                $('#saveGrading').addClass('d-none');
                $('#updateGrading').removeClass('d-none');

                let description = $(this).closest('tr').find('td:first').text();
                $('#gradingDescription').val(description);
                $('#gradingSetupModal').modal('show');
            });


            $('#saveGrading').on('click', function() {
                const gradingDescription = $("#gradingDescription").val().trim();

                const subgradingDetails = [];
                let totalPercentage = 0; // Track total percentage

                $('.componentRow').each(function() {
                    const mainDescription = $(this).find("td").eq(0).text().trim();
                    const componentPercentage = parseFloat($(this).find("td").eq(1).text().replace(
                        '%', '').trim()) || 0;
                    const numColumns = $(this).find("td").eq(4).text().trim();
                    const subComponents = [];

                    // Add percentage to total
                    totalPercentage += componentPercentage;

                    $(this).nextUntil(".componentRow").each(function() {
                        const subgradingDescription = $(this).find("td").eq(1).text()
                            .trim();
                        const subgradingcomponent = parseFloat($(this).find("td").eq(2)
                            .text().replace('%', '').trim()) || 0;
                        const subcolumnNumber = $(this).find("td").eq(3).text().trim();

                        subComponents.push({
                            subgradingDescription,
                            subgradingcomponent,
                            subcolumnNumber
                        });
                    });

                    subgradingDetails.push({
                        mainDescription,
                        componentPercentage,
                        numColumns,
                        subComponents
                    });
                });

                if (!gradingDescription) {
                    $("#gradingDescription").addClass("is-invalid");
                    Swal.fire('Validation Error', 'Please enter Grading Description', 'error');
                    return;
                }

                // Ensure total percentage is exactly 100%
                if (totalPercentage !== 100) {
                    Swal.fire('Validation Error',
                        'Total component percentage must be exactly 100%. Currently: ' +
                        totalPercentage + '%', 'error');
                    return;
                }

                console.log(subgradingDetails, 'This is the final grading setup');

                $.ajax({
                    url: '/tesda/add-grading',
                    type: 'POST',
                    data: {
                        _token: $("meta[name='csrf-token']").attr("content"),
                        gradingDescription,
                        gradingSetupList: subgradingDetails
                    },
                    success: function(response) {
                        Swal.fire('Success', 'Grading setup saved successfully.', 'success');
                        fetchGradingSetup();
                        $('#gradingSetupModal').modal('hide'); 
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'Failed to save grading setup. Please try again.',
                            'error');
                    }
                });
            });

            $(document).on('click', '#closeModalGrading', function(event) {
                event.preventDefault();
                var hasUnsavedChanges = $('#gradingSetupList tr').length >0;

                if (hasUnsavedChanges) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Unsaved Changes Detected!',
                        text: 'You have unsaved grading components. Do you really want to close the modal?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Close it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.value) {
                            $('#gradingSetupModal').modal('hide');
                        }
                    });
                } else {
                    $('#gradingSetupModal').modal('hide');
                }
            });


            $('#addGradingComponent').on('hidden.bs.modal', function() {
                $('#gradingComponentForm')[0].reset();
                $('#additionalInputsContainer').empty();
                $('#saveGrading').show();
            });

            fetchGradingSetup()

            function fetchGradingSetup() {
                $.ajax({
                    url: '/tesda/display-grading',
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response, 'This is the response');

                        if (response.success) {
                            if ($.fn.DataTable.isDataTable("#gradingSetupTable")) {
                                $("#gradingSetupTable").DataTable().destroy();
                            }
                            $("#gradingSetupBody").empty();

                            $("#gradingSetupTable").DataTable({
                                data: response.data,
                                columns: [{
                                        data: "gradDesc",
                                        title: "Grading Description"
                                    },
                                    {
                                        data: null,
                                        title: "Components",
                                        render: function(data, type, row) {
                                            return row.components.map((component,
                                                index) => {
                                                const color = index % 2 === 0 ?
                                                    "text-primary" :
                                                    "text-success";
                                                return `
                                                <span class="${color}">
                                                    ${component.descriptionComp || "N/A"} (${component.component || 0}%)
                                                </span>
                                            `;
                                            }).join(", ");
                                        },
                                    },
                                    {
                                        data: null,
                                        title: "Action",
                                        className: "text-center",
                                        render: function(data, type, row) {
                                            return `
                                                <div class="row text-center">
                                                    <div class="col-md-6" style="border-right: 1px solid #ddd">
                                                        <a href="#"><i class="text-info fas fa-pencil-alt gradingEdit" data-id="${row.id}"></i></a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <a href="#"><i class="text-danger far fa-trash-alt deleteGrading" data-id="${row.id}"></i></a>
                                                    </div>
                                                </div>
                                            `;
                                        },
                                    },
                                ],
                                ordering: true,
                                paging: true,
                                searching: true,
                                "dom": '<"top"fB>rt<"bottom"lip>',
                                buttons: [
                                    'pageLength',
                                    'copyHtml5',
                                    'excelHtml5',
                                    'csvHtml5',
                                    'pdfHtml5',
                                    'print'
                                ]
                            });
                        }
                    },
                    error: function(error) {
                        console.error("Error fetching grading setup data:", error);
                    },
                });
            }

            $(document).on("click", ".deleteGrading", function() {
                const gradingId = $(this).data("id");
                console.log(gradingId, 'Grading ID');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action will delete the grading setup permanently!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    console.log(result, 'SweetAlert result');

                    if (result.value) {
                        console.log('Confirmed deletion');

                        $.ajax({
                            url: "/tesda/delete-grading",
                            type: "DELETE",
                            data: {
                                id: gradingId,
                                _token: $('meta[name="csrf-token"]').attr(
                                    "content"),
                            },
                            success: function(response) {
                                Swal.fire(
                                    "Deleted!",
                                    "The grading setup has been deleted successfully.",
                                    "success"
                                );
                                fetchGradingSetup();
                            },
                            error: function(error) {
                                console.error("Error deleting grading setup:",
                                    error);
                                Swal.fire(
                                    "Error",
                                    "An error occurred while trying to delete the grading setup.",
                                    "error"
                                );
                            },
                        });
                    }
                });
            });

            var gradingId;
            $(document).on('click', '.gradingEdit', function() {
                gradingId = $(this).data('id');
                getUpdateComponent()
                // Show update button and hide   save button when editing
                $('#saveGrading').hide();
                $('#updateGrading').show();

            });

            function getUpdateComponent() {

                $.ajax({
                    url: `/tesda/get-grading-details/${gradingId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            gradingData = response.data;
                            $("#gradingSetupList").empty();
                            $('#gradingDescription').val(gradingData.gradDesc);

                            gradingData.components.forEach(component => {
                                const mainComponentRow = `
                                                <tr class="componentRow" data-id="${component.id}">
                                                    <td>${component.mainDescription}</td>
                                                    <td>${component.component}%</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>${component.numColumns}</td>
                                                    <td style="white-space: nowrap;">
                                                        <i class="fas fa-pencil-alt border-0 p-0 btn btn-sm text-info componentEdit" style="font-size: small;" data-id="${component.id} || " ></i>
                                                        <i class="fas fa-trash-alt border-0 p-0 btn btn-sm text-danger removeComponent" data-id="${component.id}" style="font-size: small; margin-left: 5px;"></i>
                                                    </td>
                                                </tr>`;
                                $("#gradingSetupList").append(mainComponentRow);

                                if (component.subComponents.length > 0) {
                                    component.subComponents.forEach(subComponent => {
                                        const subComponentHtml = `
                                            <tr class="subComponentRow" data-sub-id="${subComponent.id}">
                                                <td colspan="2"></td>
                                                <td>${subComponent.subgradingDescription}</td>
                                                <td>${subComponent.subgradingcomponent}%</td>
                                                <td>${subComponent.subcolumnNumber}</td>
                                                <td></td>
                                            </tr>`;
                                        $("#gradingSetupList").append(
                                            subComponentHtml);
                                    });
                                }
                            });

                            $('#gradingSetupModal').modal('show');
                            $('#updateGradingComponent').attr('data-id', gradingId);
                            $('#updateGrading').attr('data-id', gradingId);

                        } else {
                            Swal.fire('Error', 'Grading setup details could not be fetched.',
                                'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error',
                            'An error occurred while fetching grading setup details.',
                            'error');
                    }
                });


            }

            $(document).on("click", ".removeComponent", function() {
                var row = $(this).closest("tr");
                var id = $(this).data('id');
                console.log(id, 'ID to delete');

                if (!id) {
                    console.error("No ID found for deletion.");
                    return;
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "This will delete the grading setup and its sub-components.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: `/tesda/delete-gradingrow/${id}`,
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr("content")
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire("Deleted!", response.message, "success");
                                    row.nextUntil(".componentRow").remove();
                                    row.remove();
                                    fetchGradingSetup();
                                } else {
                                    Swal.fire("Error!",
                                        "Failed to delete the grading setup.",
                                        "error");
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire("Error!", "An error occurred while deleting.",
                                    "error");
                            }
                        });
                    }
                });
            });



            $('#addGradingButton').on('click', function() {
                $('#gradingSetupList').empty();
                $('#gradingDescription').val('');
                $('#updateGradingComponent').removeAttr('data-id');
                $('#saveGrading').show();
                $('#gradingSetupModal').modal('show');
                $('#updateGrading').hide();
            });

            $(document).on('click', '.gradingEdit', function(e) {
                e.preventDefault();
                let studentId = $(this).data('id');
                $('#saveGrading').hide();
                $('#updateGrading').show();
            });

            $(document).on('click', '#addGradingSetupButton', function(e) {
                e.preventDefault();
                let studentId = $(this).data('id');
                $('#saveGrading').show();
                $('#updateGrading').hide();

            });

            // Reset modal when it is hidden
            $('#gradingSetupModal').on('hidden.bs.modal', function() {
                $(".componentRow").remove();
                $(".subComponentRow").remove();
                $("#gradingDescription").val('');
                $('#saveGrading').show();
                $('#updateGrading').hide();
                $('#updateGradingComponent').removeAttr('data-id');
            });
            $(document).on('click', '#componentEdit', function() {
                // $('#saveGrading').addClass('d-none');
                $('#updateGrading').removeClass('d-none');
            });

            //  $('#gradingSetupModal').on('show.bs.modal', function() {
            //     $('#updateGradingComponent').removeAttr('data-id');
            //     $('#saveGrading').show();
            //     $('#updateGrading').hide();
            // });


            $(document).on('click', '.componentEdit', function() {
                const componentRow = $(this).closest('.componentRow');
                const componentId = $(this).data('id');

                // Extract the data from the clicked row
                const mainDescription = componentRow.find('td:nth-child(1)').text().trim();
                const percentage = componentRow.find('td:nth-child(2)').text().replace('%', '').trim();
                const numColumns = componentRow.find('td:nth-child(5)').text().trim();

                // Populate the modal fields
                $("#componentgradingDescription").val(mainDescription);
                $("#componentPercentage").val(percentage);
                $("#classColumns").val(numColumns);

                // Handle subcomponents (if any)
                const subComponents = [];
                componentRow.nextUntil('.componentRow').each(function() {
                    const subComponentID = $(this).attr('data-sub-id');
                    const subDescription = $(this).find('td:nth-child(2)').text().trim();
                    const subPercentage = $(this).find('td:nth-child(3)').text().replace('%', '')
                        .trim();
                    const subColumnNumber = $(this).find('td:nth-child(4)').text().trim();

                    if (subDescription) {
                        subComponents.push({
                            subId: subComponentID,
                            subgradingDescription: subDescription,
                            subgradingcomponent: subPercentage,
                            subcolumnNumber: subColumnNumber
                        });
                    }
                });
                console.log(subComponents,
                    'Subcomponents data');

                if (subComponents.length > 0) {
                    $('#subComponent').prop('checked', true);
                    $('#classColumns').prop('disabled', true);

                    $('#addMoreBtn').removeClass('d-none');

                    const additionalInputsContainer = $('#additionalInputsContainer');
                    additionalInputsContainer.empty(); // Clear previous inputs

                    // Append each subcomponent row dynamically to the container
                    subComponents.forEach((sub, index) => {
                        additionalInputsContainer.append(`
                                <div class="row mb-3 subcomponent-row" data-id="${componentId}" data-sub-id="${sub.subId}">
                                    <div class="col-sm-4">
                                        <label>Description</label>
                                        <input type="text" class="form-control form-control-sm subgradingDescription" value="${sub.subgradingDescription}" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Percentage (%)</label>
                                        <input type="number" class="form-control form-control-sm subgradingcomponent" value="${sub.subgradingcomponent}" required>
                                    </div>
                                    <div class="col-3">
                                        <label>Columns</label>
                                        <select class="form-control form-control-sm  select2 subcolumnNumber" required>
                                            <option value="" disabled>Select</option>
                                            <option value="1" ${sub.subcolumnNumber == 1 ? 'selected' : ''}>1</option>
                                            <option value="2" ${sub.subcolumnNumber == 2 ? 'selected' : ''}>2</option>
                                            <option value="3" ${sub.subcolumnNumber == 3 ? 'selected' : ''}>3</option>
                                            <option value="4" ${sub.subcolumnNumber == 4 ? 'selected' : ''}>4</option>
                                            <option value="5" ${sub.subcolumnNumber == 5 ? 'selected' : ''}>5</option>
                                            <option value="10" ${sub.subcolumnNumber == 10 ? 'selected' : ''}>10</option>
                                            <option value="15" ${sub.subcolumnNumber == 15 ? 'selected' : ''}>15</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 d-flex justify-content-center align-items-end">
                                        <button type="button" class="btn btn-sm btn-danger removeAddedComponent" data-id="${componentId}" data-sub-id="${sub.subId}"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                </div>
                            `);
                    });
                } else {
                    $('#subComponent').prop('checked', false);
                    $('#addMoreBtn').addClass('d-none');
                    $('#additionalInputsContainer').empty();
                }

                $('#addGradingComponent').modal('show');
                $('#updateGradingComponent').data('id', componentId);
                $('#updateGrading').data('id', componentId);

                $('#saveGradingComponent').hide();
                $('#updateGradingComponent').show();
            });


            $(document).on('click', '#updateGradingComponent', function() {
                var gradingId = $(this).data('id');
                var mainComponentDescription = $("#componentgradingDescription").val();
                var componentPercentage = parseFloat($("#componentPercentage").val()) || 0;
                var numColumns = parseInt($("#classColumns").val()) || 0;
                var components = [];
                var componentId = $(this).data("id");

                $(".componentRow").each(function() {
                    var subComponents = [];
                    var totalSubPercentage = 0; // Track the total percentage

                    $(".subcomponent-row").each(function() {
                        var subComponentId = $(this).data("sub-id") || 0;
                        var subDescription = $(this).find(".subgradingDescription").val()
                            .trim();
                        var subPercentage = parseFloat($(this).find(".subgradingcomponent")
                            .val()) || 0;
                        var subColumnNumber = $(this).find(".subcolumnNumber").val();

                        totalSubPercentage += subPercentage; // Accumulate total percentage

                        subComponents.push({
                            id: subComponentId || null,
                            subDescription,
                            subPercentage,
                            subColumnNumber
                        });
                    });

                    console.log(subComponents, 'subComponents');

                    // Validation: Ensure subcomponents total to 100%
                    if (totalSubPercentage !== 100) {
                        Swal.fire('Validation Error',
                            `Total subcomponent percentage must be 100%. Currently: ${totalSubPercentage}%`,
                            'error');
                        return;
                    }

                    components.push({
                        id: componentId || null,
                        mainComponentDescription,
                        componentPercentage,
                        numColumns,
                        subComponents
                    });

                    console.log(components, 'components');
                });

                // If validation fails, stop execution
                if (components.length === 0) return;

                // Send the data through AJAX
                $.ajax({
                    url: `/tesda/update-grading/${gradingId}`,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        mainComponentDescription,
                        componentPercentage,
                        numColumns,
                        components
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success');
                            getUpdateComponent(gradingId)
                            $('#addGradingComponent').modal('hide');

                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to update grading setup.', 'error');
                    },
                });
            });


            $(document).on('click', '#updateGrading', function() {
                var gradingId = $(this).data('id');
                var gradingDescription = $("#gradingDescription").val().trim();

                if (!gradingDescription) {
                    Swal.fire('Validation Error', 'Grading Description is required.', 'error');
                    return;
                }

                let totalPercentage = 0;
                let isValid = true;
                const gradingComponents = [];

                $('.componentRow').each(function() {
                    const mainDescription = $(this).find("td").eq(0).text().trim();
                    const componentPercentage = parseFloat($(this).find("td").eq(1).text().replace(
                        '%', '').trim()) || 0;
                    const numColumns = $(this).find("td").eq(4).text().trim();

                    // Validate the main component fields
                    if (!mainDescription || isNaN(componentPercentage)) {
                        isValid = false;
                        $(this).find("td").addClass("invalid-input");
                    } else {
                        $(this).find("td").removeClass("invalid-input");
                    }

                    // Add only componentPercentage to total (excluding subcomponent percentages)
                    totalPercentage += componentPercentage;

                    gradingComponents.push({
                        mainDescription,
                        componentPercentage,
                        numColumns
                    });
                });

                if (!isValid) {
                    Swal.fire('Validation Error', 'All fields must be filled out.', 'error');
                    return;
                }

                // Ensure total percentage is exactly 100% (only from main components)
                if (totalPercentage !== 100) {
                    Swal.fire('Validation Error',
                        'Total component percentage must equal 100%. Currently: ' + totalPercentage +
                        '%', 'error');
                    return;
                }

                $.ajax({
                    url: `/tesda/update-gradingDesc/${gradingId}`,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        gradingDescription,
                        gradingSetupList: gradingComponents
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success');
                            $('#addGradingComponent').modal('hide');
                            fetchGradingSetup();
                            $('#gradingSetupModal').modal('hide');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to update grading setup.', 'error');
                    },
                });
            });

            $(document).on('click', '.removeAddedComponent', function() {
                var subId = $(this).data('sub-id');
                var subcomponentRow = $(this).closest('.subcomponent-row');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This subgrading component will be deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: `/tesda/delete-subgrading/${subId}`,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Remove the row from the UI
                                    subcomponentRow.remove();

                                    // Recalculate the total percentage
                                    updateTotalPercentage();

                                    Swal.fire('Deleted!', response.message, 'success');
                                    getUpdateComponent(gradingId)

                                }
                            },
                            error: function() {
                                Swal.fire('Error',
                                    'Failed to delete subgrading component.',
                                    'error');
                            }
                        });
                    }
                });
            });


            function updateTotalPercentage() {
                let totalPercentage = 0;

                $('.subgradingcomponent').each(function() {
                    let value = parseFloat($(this).val()) || 0;
                    totalPercentage += value;
                });

                if (totalPercentage !== 100) {
                    Swal.fire('Warning', 'Total subcomponent percentage must be 100%.', 'warning');
                }
            }
        })
    </script>
@endsection
