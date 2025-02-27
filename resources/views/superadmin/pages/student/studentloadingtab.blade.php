<div class="row mb-3 mt-1">
    <div class="col-md-6">
        <button id="loadSubjects" class="btn btn-success btn-sm">Load
            Subjects</button>
        <button id="saveStudentLoading" class="btn btn-success btn-sm" data-id="0">Save</button>
    </div>
    <div class="col-md-6 text-end">
        <div style="float:right">
            <button id="printCOR" class="btn btn-primary btn-sm" data-id = "0"><i class="fa fa-print"></i> Print
                COR</button>
        </div>
    </div>
</div>

<div class="card" style="max-height: auto;">
    <div class="card-body" id = "modalBody">
        <div class="row mb-3">
            <div class="col-md-3 text-end">
                <label for="studentSection" class="form-label">Student Curriculum: <a href="#"
                        id="editCurriculum"><i class="fa fa-edit text-info"></i></a> </label>
                <div id="studentCurriculum"></div>
            </div>
            <div class="col-md-4 text-end" style="text-align:right;">
                <label for="studentSection" class="form-label">Student Section</label>
            </div>
            <div class="col-md-3 text-end">
                <select id="StudentSection" class="form-select" style="width: 100%">
                    <option value="">Select Section</option>
                    {{-- @foreach ($StudentSection as $section)
                        <option value="{{ $section->id }}">{{ $section->sectionDesc }}</option>
                    @endforeach --}}
                </select>
            </div>
            <div class="col-md-2 text-end" style="text-align:right;">
                <button id="updateStudentSection" class="btn btn-sm btn-primary">Update
                    Section</button>

            </div>
        </div>
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table class="table table-striped table-bordered" id="studentLoadingDatatable">
                <thead class="table-light"
                    style="position: sticky; top: 0; z-index: 1; border-bottom: 2px solid #575757;">
                    <tr style="font-size: 12px;">
                        <th>Section</th>
                        <th>Subject</th>
                        <th>Pre-requisite Subject</th>
                        <th>Lec</th>
                        <th>Lab</th>
                        <th>Cr. Unit</th>
                        <th>Class</th>
                        <th>Time & Day Schedule</th>
                        <th>Instructor</th>
                        <th>Room</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="subjectsTableBody2" style="font-size: 10px;">
                    <!-- Table rows will be populated dynamically -->
                </tbody>
                <tbody id="subjectsTableBody" style="font-size: 10px;">
                    <!-- Table rows will be populated dynamically -->
                </tbody>
                <tr style="font-size: 12px;">
                    <td colspan="5" class="text-end">
                        <p style="text-align: right;">Total Units Loaded</p>
                    </td>
                    <th>
                        <span id="totalUnitsLoaded"></span>
                    </th>
                    <td colspan="6"></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Conflict Details Modal -->
<div class="modal fade" id="conflictDetailsModal" tabindex="-1" aria-labelledby="conflictDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="conflictDetailsModalLabel">Time Conflict Detected</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The following subjects have a time conflict:</p>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Day</th>
                        </tr>
                    </thead>
                    <tbody id="conflictDetailsModalBody"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="loadAnywayBtn" class="btn btn-danger">Load Anyway</button>
                <button type="button" id="cancelConflictBtn" class="btn btn-secondary"
                    data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>




<script>
    // function populateStudentLoadingTable(subjects, studentId) {
    //             var tableBody = $('#subjectsTableBody');
    //             tableBody.empty(); // Clear previous data
    //             var totalUnits = 0;

    //             subjects.forEach(function(subject) {
    //                 var totalSubjectUnits = parseFloat(subject.lecunits) + parseFloat(subject.labunits);
    //                 var schedid = subject.schedid;
    //                 var row = `
    //                         <tr data-student-id="${studentId}" data-section-id="${sectionId}" data-subject-id="${subjId}" data-schedid="${schedid}">
    //                             <td>${subject.sectionDesc || 'N/A'}</td>
    //                             <td>${subject.subjCode} - ${subject.subjDesc}</td>
    //                             <td>${subject.lecunits}</td>
    //                             <td>${subject.labunits}</td>
    //                             <td>${totalSubjectUnits.toFixed(2)}</td>
    //                             <td>${subject.schedotherclass || 'Not Available'}</td>
    //                             <td>${subject.stime ? subject.stime + ' - ' + subject.etime + ' / ' + subject.day : '<span style="color: red;">Not Available</span>'}</td>
    //                             <td>${subject.instructor || '<span style="color: red;">Not Assigned</span>'}</td>
    //                             <td>${subject.roomname || '<span style="color: red;">Not Assigned</span>'}</td>
    //                             <td><a href="#" class="remove-subject" data-id="${subject.subjId}" style="color: red; text-decoration: underline;">DROP</a></td>
    //                         </tr>
    //                     `;

    //                 tableBody.append(row); // Append row to table
    //                 totalUnits += totalSubjectUnits; // Update total units
    //             });

    //             $('#totalUnitsLoaded').text(totalUnits.toFixed(2)); // Display total units loaded
    //             recalculateTotalUnits(); // Call to recalculate total units if necessary
    //         }
    var currentStudentId;

    var loadedSubjects = [];
    var sectionTables = {};
    var currentStudentId;
    // Event handler for loading subjects
    var curr_data

    function show_curr() {
        $.ajax({
            type: 'GET',
            url: '/student/curriculum',
            data: {
                studid: studID
            },
            success: function(response) {
                $('#studentCurriculum').html(response.curriculumname || '');
                console.log(response.curriculumname)
                curr_data = response.curriculumname
            },

        });
    }

    function showToast(type, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        Toast.fire({
            type: type,
            icon: type,
            title: message
        });
    }


    $(document).on('click', '.load-subject', function() {
        var $button = $(this);
        var conflict = $button.attr('data-conflict');
        var conflicts = $button.data('conflicts') || [];
        var isLoading = $button.hasClass('btn-success');
        var buttonDay = $button.data('day');
        var stime = parseInt($button.data('stime'), 10);
        var etime = parseInt($button.data('etime'), 10);

        // Prevent further clicks if already disabled
        if ($button.hasClass('disabled')) return;
        $button.addClass('disabled');

        // Get all subject data
        var subjectData = getSubjectData($button);
        $('#conflictedSubject').text(subjectData.subjDesc);

        var schedules = $('.schedule_row');
        var hasTimeConflict = false;
        var conflictDetails = [];

        schedules.each(function() {
            var scheduleDay = $(this).attr('data-days');
            var scheduleTime = parseInt($(this).attr('data-time'), 10);
            var schedule = $(this).attr('data-time');
            var conflictSubjectDesc = $(this).attr('data-subject');
            var subjectCode = $(this).attr('data-code');

            if (scheduleDay) {
                var daysArray = scheduleDay.split('/');

                // Check for day and time conflict
                if (daysArray.includes(buttonDay) && scheduleTime >= stime && scheduleTime <= etime) {
                    hasTimeConflict = true;
                    conflictDetails.push({
                        subjDesc: conflictSubjectDesc,
                        subjCode: subjectCode,
                        schedule: schedule,
                        stime: scheduleTime,
                        etime: etime,
                        dayname: buttonDay
                    });
                }
            }
        });

        if (hasTimeConflict) {
            $('#yourModalId').find('.modal-body #conflictDetails').empty(); // Clear previous entries
            conflictDetails.forEach(conflict => {
                const row = `
            <tr>
                <td>${conflict.subjCode}</td>
                <td>${conflict.subjDesc}</td>
                <td>${conflict.schedule}</td>
            </tr>`;
                $('#yourModalId').find('.modal-body #conflictDetails').append(row);
            });

            // Show the conflict modal
            $('#yourModalId').modal('show');

            // Handle "Load Anyway" button click in conflict modal
            $('.load-anyways').off('click').on('click', function() {
                $('#yourModalId').modal('hide');
                processSubjectLoading($button, subjectData, true); // Force loading
                showToast('success', 'Successfully loaded subject despite conflict');
                $button.removeClass('btn-success').addClass('btn-danger');
            });

            // Handle Cancel button in conflict modal
            $('.btn-secondary').off('click').on('click', function() {
                $('#yourModalId').modal('hide');
                $button.removeClass('disabled');
            });

            return; // Exit function to avoid further execution
        }


        // Proceed if no conflict is detected
        if (conflict === 'true' && conflicts.length > 0 && isLoading) {
            $button.removeClass('disabled');
            $('#conflictDetails').empty();
            conflicts.forEach(conflict => {
                const row = `
            <tr>
                <td>${conflict.subjCode}</td>
                <td>${conflict.subjDesc}</td>
                <td>${conflict.stime} - ${conflict.etime} ${conflict.dayname}</td>
                <td>${conflict.roomname}</td>
                <td>${conflict.teachername}</td>
            </tr>`;
                $('#conflictDetails').append(row);
            });

            $('#yourModalId').modal('show');

            $('.load-anyways').off('click').on('click', function() {
                $('#yourModalId').modal('hide');
                processSubjectLoading($button, subjectData, true);
                showToast('success', 'Successfully loaded subjects');
                $button.removeClass('btn-success').addClass('btn-danger');
            });

            $('[data-dismiss="modal"]').off('click').on('click', function() {
                $('#yourModalId').modal('hide');
                $button.removeClass('disabled');
            });
        } else {
            processSubjectLoading($button, subjectData, isLoading);
            if (isLoading) {
                showToast('success', 'Successfully loaded subjects');
                $button.removeClass('btn-success').addClass('btn-danger');
            } else {
                showToast('warning', 'Successfully unloaded subjects');
                $button.removeClass('btn-danger').addClass('btn-success');
            }
        }
    });



    function formatDays(days) {
        if (!days) return '<span style="color: red;">Not Available</span>';
        return days.replace(/Monday/g, 'M')
            .replace(/Tuesday/g, 'T')
            .replace(/Wednesday/g, 'W')
            .replace(/Thursday/g, 'Th')
            .replace(/Friday/g, 'F')
            .replace(/Saturday/g, 'Sa')
            .replace(/Sunday/g, 'Su');
    }

    var addedSubj = [];

    function getUpdatedStudentLoading(studentId, sectionId, subjectID) {
        $.ajax({
            url: `/student/loading/get-added-student-loading/${studentId}/${sectionId}/${$('#filter_sy').val()}/${$('#filter_semester').val()}/${subjectID}`,
            method: 'GET',
            success: function(response) {
                checkScheduleConflicting(studentId);
                var subjectsTableBody = $('#subjectsTableBody2');
                var subjectTableBody2 = $('#subjectsTableBody');

                // Clear previous rows
                subjectsTableBody.empty();
                subjectTableBody2.empty();

                // Populate added subjects list
                console.log(response, 'response');
                addedSubj = response.loadedSubjects.map(subject => subject.schedid);

                console.log(addedSubj, 'Loaded Subjects');

                if (response.studentLoading && response.studentLoading.length > 0) {
                    var enrolled = response.enrolled;

                    var addedSubjectsCount = response.studentLoading.filter(function(subject) {
                        return subject.loadStatus !== 1 && subject.loadStatus !== '1' && subject
                            .subjectID !== subjectID;
                    }).length;

                    $('#LoadSubjectSide').text(addedSubjectsCount);

                    response.studentLoading.forEach(function(subject) {
                        var schedule = subject.schedule;
                        var time = schedule.split('(')[0].trim();

                        var daysMatch = schedule.match(/\((.*?)\)/);
                        var days = daysMatch ? daysMatch[1] : '';

                        console.log("Time:", time);
                        console.log("Days:", days);
                        console.log(schedule, 'ehhh');



                        var isDropped = subject.loadStatus == 1 || subject.loadStatus == '1';
                        var statusText = isDropped ? 'DROPPED' : 'ADDED';
                        var rowDisabledClass = isDropped ? 'disabled-row' : '';
                        var removeActionText = enrolled ? (isDropped ? 'REMOVE' : 'DROP') :
                            'REMOVE';

                        // Assuming that you pass day, stime, etime, room, and teacher info
                        // checkScheduleConflicts(subject.schedid);
                        console.log(subject, 'subject');

                        var row = `
                        <tr data-student-id="${studentId}" data-section-id="${subject.sectionID}" 
                            data-subject-id="${subject.subjectID}" data-schedid="${subject.schedid}" 
                            class="${rowDisabledClass}">
                            <td>
                                <div class="ribbon">
                                    <span class="ribbon-text">${subject.sectionDesc || 'N/A'}</span>
                                    <div><span class="badge ${isDropped ? 'bg-danger' : 'bg-success'} text-dark">${statusText}</span></div>
                                </div>
                            </td>
                            <td>${subject.subjCode} - ${subject.subjDesc}</td>
                            <td>${subject.prerequisites || '<span style="color: red;">No prerequisite</span>'}</td>
                            <td>${subject.lecunits || 0}</td>
                            <td>${subject.labunits || 0}</td>
                            <td>${(parseFloat(subject.lecunits) + parseFloat(subject.labunits)).toFixed(2)}</td>
                            <td>${subject.schedotherclass}</td>
                            <td ${isDropped == 1 ? '' : 'class="schedule_row"'} data-days="${days}" data-time="${time}" data-subject="${subject.subjDesc}" data-code="${subject.subjCode}">${formatSchedule(subject.schedule)}</td>
                            <td>${subject.instructor || '<span class="text-danger">Not Assigned</span>'}</td>
                            <td>${subject.roomname || '<span class="text-danger">Not Assigned</span>'}</td>
                            <td schedid-id="${subject.schedid}">
                                ${isDropped ? 
                                    `<span style="color: red;">Dropped by: ${subject.deletedByName}</span>` : 
                                    `<a href="#" class="remove-subject ${isDropped ? 'disabled-link' : ''}" 
                                    data-id="${subject.id}" 
                                    style="${isDropped ? 'pointer-events: none; color: gray;' : 'color: red; text-decoration: underline;'}">
                                    ${removeActionText}
                                    </a>`}
                            </td>
                        </tr>`;
                        if (enrolled || !isDropped) {
                            subjectsTableBody.append(row);
                        }
                    });
                } else {
                    $('#LoadSubjectSide').text('');
                }

                // Update sections dropdown
                $('#StudentSection').empty();
                $.each(response.gensections, function(indexInArray, value) {
                    var selectedSection = $('#studenAcadSection').attr('data-section');
                    var isSelected = selectedSection == value.sectionid ? 'selected' : '';
                    $('#StudentSection').append(`
                    <option value="${value.sectionid}" ${isSelected}>${value.sectiondesc}</option>
                `);
                });

                $('#StudentSection').trigger('change');
                recalculateTotalUnits();
            },
            error: function(xhr) {
                console.error("Error fetching updated student loading:", xhr.responseText);
                toastr.error('Failed to fetch updated student loading. Please try again.');
            }
        });
    }

    function checkScheduleConflicting(studid) {
        var syid = $('#filter_sy').val();
        var semid = $('#filter_semester').val();

        var requestData = {
            syid: syid,
            semid: semid,
            studid: studid
        };

        $.ajax({
            url: `/check-schedule-conflicts`,
            method: 'GET',
            data: requestData,
            success: function(response) {
                console.log(response, 'Response');
                $('#conflictSchedule').empty();
                $('#availableSchedules').empty();

                if (response.withconflict === 1) {
                    $('#conflictSubjectParagraph').html(
                        `<b style="color: green;">${response.subjCode || ''} - ${response.subjDesc || ''}</b> subject is in conflict with other subject(s).`
                    );

                    response.conflict.forEach(function(conflict) {
                        var conflictRow = `
                        <tr>
                            <td>
                                <div class="schedule-details">
                                    <span class="subject"><b>Subject:</b> ${conflict.subjCode} - ${conflict.subjDesc}</span><br>
                                    <span class="time"><b>Time:</b> ${formatTime(conflict.stime)} - ${formatTime(conflict.etime)}</span><br>
                                    <span class="room"><b>Room:</b> ${conflict.roomname || 'TBA'}</span><br>
                                    <span class="class"><b>Class:</b> ${conflict.schedotherclass || 'Lecture'}</span>
                                </div>
                            </td>
                        </tr>
                    `;
                        $('#conflictSchedule').append(conflictRow);

                        // Populate available schedules if provided
                        if (conflict.available && conflict.available.length > 0) {
                            conflict.available.forEach(function(available, index) {
                                var availableRow = `
                                <tr>
                                    <td>
                                        <div class="Avail-schedule-details">
                                            <label for="schedule-${index}">
                                                <span class="AvailSubjectDesc"><b>Subject:</b> ${available.subjDesc || 'TBA'}</span><br>
                                                <span class="AvailSection"><b>Section:</b> ${available.sectionDesc || 'TBA'}</span><br>
                                                <span class="AvailTime"><b>Time:</b> ${formatTime(available.stime)} - ${formatTime(available.etime)}</span><br>
                                                <span class="AvailRoom"><b>Room:</b> ${available.roomname || 'TBA'}</span><br>
                                                <span class="AvailClass"><b>Class:</b> ${available.schedotherclass || 'Lecture'}</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            `;
                                $('#availableSchedules').append(availableRow);
                            });
                        }
                    });
                } else {
                    // No conflicts
                    $('#conflictSubjectParagraph').text('No conflicts found.');
                    $('#conflictSchedule').empty();
                    $('#availableSchedules').empty();
                }
            },
            error: function(xhr) {
                console.error("Error checking schedule conflicts:", xhr.responseText);
                toastr.error('Failed to check schedule conflicts. Please try again.');
            }
        });
    }

    function formatTime(time) {
        var hours = Number(time.match(/^(\d+)/)[1]);
        var minutes = Number(time.match(/:(\d+)/)[1]);
        var AMPM = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + AMPM;
        return strTime;
    }



    function formatSchedule(schedule) {
        if (!schedule) return '<span style="color: red;">Not Available</span>';
        var schedules = schedule.split(',');
        var formattedSchedules = schedules.map(function(sched) {
            var parts = sched.trim().split(' ');
            var time = parts[0] + ' ' + parts[1] + ' ' + parts[2];
            var days = parts.slice(3).join(' ');
            days = days.replace(/Monday/g, 'M')
                .replace(/Tuesday/g, 'T')
                .replace(/Wednesday/g, 'W')
                .replace(/Thursday/g, 'Th')
                .replace(/Friday/g, 'F')
                .replace(/Saturday/g, 'Sa')
                .replace(/Sunday/g, 'Su');
            return time + ' ' + days;
        });

        return formattedSchedules.join('<br>');
    }

    function checkScheduleConflicts(studid) {
        var syid = $('#filter_sy').val();
        var semid = $('#filter_semester').val();

        var requestData = {
            syid: syid,
            semid: semid,
            studid: studid
        };

        $.ajax({
            url: `/check-schedule-conflicts`,
            method: 'GET',
            data: requestData,
            success: function(response) {
                console.log(response, 'Response');

                // Clear previous conflict details
                $('#conflictSchedule').empty();

                // Get the subject and conflict data from the response
                var item = response;
                console.log(response.conflict, 'Conflicts');
                console.log(item, 'eyyyy');

                // Check if there are conflicts
                if (item.withconflict == 1) {
                    // Display subject and conflict message if there are conflicts
                    // $('#conflictSubjectText').text(`${item.subjCode || ''} - ${item.subjDesc || ''}`);
                    $('#conflictSubjectParagraph').html(
                        `<b style="color: green;">${item.subjCode || ''} - ${item.subjDesc || ''}</b> subject is in conflict with the other Subject(s)`
                    );
                    // Loop through the conflicts and populate the table
                    item.conflict.forEach(function(conflict) {
                        console.log(conflict, 'Conflict');
                        var conflictRow = `
                                <tr>
                                    <td>
                                        <div class="schedule-details">
                                            <span class="subject"><b>Subject:</b> ${conflict.subjCode} - ${conflict.subjDesc}</span><br>
                                            <span class="time"><b>Time:</b> ${formatTime(conflict.stime)} - ${formatTime(conflict.etime)}</span><br>
                                            <span class="room"><b>Room:</b> ${conflict.roomID ? conflict.roomname : 'TBA'}</span><br>
                                            <span class="teacher"><b>Teacher:</b> ${conflict.teacherID ? conflict.teacherID : 'TBA'}</span><br>
                                            <span class="class"><b>Class:</b> ${conflict.schedotherclass}</span>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        $('#conflictSchedule').append(conflictRow);
                    });
                } else {
                    // No conflicts, hide the conflict section and clear text
                    $('#conflictSubjectText').text('');
                    $('#conflictSubjectParagraph').text('');
                    $('#conflictSchedule').empty(); // Optional, in case any rows were left
                }
            },
            error: function(xhr) {
                console.error("Error checking schedule conflicts:", xhr.responseText);
                toastr.error('Failed to check schedule conflicts. Please try again.');
            }
        });
    }


    $('#StudentSection').change(function() {
        var sectionId = $(this).val();

        if (sectionId) {
            loadStudentScheduleBySection(sectionId, currentStudentId); // Use currentStudentId
        } else {
            $('#subjectsTableBody').empty();
            $('#totalUnitsLoaded').text('0');
        }
    });

    function recalculateTotalUnits() {
        var totalUnits = 0;

        // Calculate total units from the first table
        $('#subjectsTableBody tr').each(function() {
            var lecUnits = parseFloat($(this).find('td:eq(2)').text()) || 0; // Lecture units
            var labUnits = parseFloat($(this).find('td:eq(3)').text()) || 0; // Lab units
            if ($(this).find('td:eq(0) .badge').text().trim() !== 'DROPPED') {
                var lecUnits = parseFloat($(this).find('td:eq(3)').text()) || 0; // Lecture units
                var labUnits = parseFloat($(this).find('td:eq(4)').text()) || 0; // Lab units
                totalUnits += lecUnits + labUnits; // Sum both lecture and lab units
            }
        });

        $('#subjectsTableBody2 tr').each(function() {
            var totalUnitsFromRow = parseFloat($(this).find('td:eq(5)').text()) ||
                0;
            if ($(this).find('td:eq(0) .badge').text().trim() !== 'DROPPED') {
                totalUnits += totalUnitsFromRow; // Add to total
            }
        });

        $('#totalUnitsLoaded').text(totalUnits.toFixed(2)); // Update the total units loaded display
    }

    function updateEnrolledCount($row, change) {
        var $enrolledCell = $row.find('td:eq(9)');
        var currentEnrolled = parseInt($enrolledCell.text()) || 0;
        $enrolledCell.text(currentEnrolled + change);
    }

    $('#sectionSelect').change(function() {
        var selectedSectionId = $(this).val();
        loadSectionSchedule(selectedSectionId);
    });


    function updateLedger(studid, syid, semid) {
        $.ajax({
            url: '/api_updateledger',
            method: 'GET',
            data: {
                studid: studid,
                syid: syid,
                semid: semid,
            },
            async: true, // Ensures the request runs in the background
            success: function(response) {
                // console.log(response);
            },
            error: function(xhr, status, error) {
                console.error("Error updating ledger:", error);
            }
        });
    }

    function removeSubject() {
        $('#subjectsTableBody2').on('click', '.remove-subject', function() {
            var $button = $(this);
            var $row = $button.closest('tr');
            var subjectId = $button.data('id');
            var studentId = $row.data('student-id');
            var syid = $('#filter_sy').val();
            var semid = $('#filter_semester').val();
            var schedid = $row.data('schedid');
            var actionText = $button.text().trim().toUpperCase();


            if (actionText === 'REMOVE') {
                console.warn('Removing subject without enrollment:', subjectId);

                Swal.fire({
                    title: 'Are you sure you want to remove this subject?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Immediately remove the row
                        $row.remove();
                        recalculateTotalUnits();

                        // Remove the subject from the addedSubj array
                        addedSubj = addedSubj.filter(subject => subject != schedid);

                        console.log(addedSubj, 'addedSubj');

                        // Send the DELETE request to the server
                        $.ajax({
                            url: `/student/loading/delete-loaded-subject/${subjectId}`,
                            type: 'DELETE',
                            data: {
                                studentId: studentId,
                                syid: syid,
                                semid: semid,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log(123123123);

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.success,
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to delete subject.',
                                    icon: 'error',
                                    confirmButtonText: 'Okay',
                                });
                            },
                        });
                    }
                });

                return;
            }


            if (!studentId) {
                console.error('Student ID is missing');
                $row.remove();
                recalculateTotalUnits();
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'This subject will be dropped from the student\'s load.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, drop it!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/student/loading/delete-loaded-subject/' + subjectId,
                        method: 'DELETE',
                        data: {
                            studentId: studentId,
                            syid: syid,
                            semid: semid,
                            schedid: schedid,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            recalculateTotalUnits();
                            console.log(1231232);

                            getUpdatedStudentLoading(studentId, 'all', 0);

                            $('.load-subject[data-id="' + subjectId + '"]').removeClass(
                                'disabled'
                            );
                            updateLedger(studentId, syid, semid);
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'Okay',
                            });

                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Unable to Drop Subject',
                                text: 'This subject schedule contains grades.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                            });
                        },
                    });
                }
            });
        });
    }

    $(document).ready(function() {
        removeSubject();
    });



    $('#updateStudentSection').click(function() {
        // Get the studentId stored in the modal data
        var currentStudentId = $('#studentLoadingModal').data('student-id');
        var newSectionId = $('#StudentSection').val();
        if (currentStudentId && newSectionId) {
            swal.fire({
                title: 'Update Student Section',
                text: 'Are you sure you want to update the student section?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    updateStudentSection(currentStudentId, newSectionId);
                    swal.fire({
                        title: 'Success',
                        text: 'Student section updated successfully.',
                        type: 'success'
                    });
                }
            });
        } else {
            swal.fire({
                title: 'Error',
                text: 'No section selected.',
                type: 'error'
            });
        }
    });

    function updateSection(studentId, newSectionId) {
        $.ajax({
            url: '/student/update-section',
            type: 'POST',
            data: {
                studentId: studentId,
                newSectionId: newSectionId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#studentAcadSection').text(response.sectionDesc || '');
                alert(response.message);
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
            },
        });
    };





    function loadAllSubjects(sectionId) {
        var tableId = 'sectionTable_' + sectionId;
        var $table = $('#' + tableId);
        var $rows = $table.find('tbody tr');

        // Loop through each row and trigger the load-subject action
        $rows.each(function() {
            var $row = $(this);
            var $loadButton = $row.find('.load-subject');

            // Check if the button is already in "Remove Sched" mode (i.e., subject already loaded)
            if ($loadButton.hasClass('btn-success')) {
                // Simulate clicking the load subject button to load this subject
                $loadButton.click();
            }
        });
    }


    // Handle "Load All Subjects" button click
    $(document).on('click', '.load-all-subjects', function() {
        var sectionId = $(this).data('section-id');
        loadAllSubjects(sectionId);
    });




    async function createSectionTable(sectionId, sectionData) {
        const updatedSectionData = await checkScheduleConflicts(sectionId, sectionData);
        var tableId = 'sectionTable_' + sectionId;
        var tableHtml = `
                <div class="card-body m-3 shadow" style="font-size: 10px; box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15); background-color: white;">
                    <div class="card-header">
                        <div class="section-header" style="font-size: 12px; font-weight: bold;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="section-name">Section:</div>
                                    <input type="text" class="form-control section-input ms-3" style="width: 150px;" 
                                        value="${sectionData.length > 0 ? sectionData[0].section : 'N/A'}" readonly>
                                    <div style="margin-left: 20px; font-size: 13px; font-weight: bold; width: 130px;">
                                        Loaded Subjects: <span id="subject-count-${sectionId}">${sectionData.length}</span>
                                    </div>
                                </div>
                                <button class="btn btn-success btn-sm load-all-subjects" data-section-id="${sectionId}">
                                    Load All Subjects
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="${tableId}" class="table table-bordered table-striped sectionTablesload" style="width: 100%;">
                            <thead class="thead-light">
                                <tr style="color:black; font-size:12px">
                                    <th>Section</th>
                                    <th>Subject</th>
                                    <th>Lec</th>
                                    <th>Lab</th>
                                    <th>Cr. Unit</th>
                                    <th>Class</th>
                                    <th>Time & Day Schedule</th>
                                    <th>Instructor</th>
                                    <th>Room</th>
                                    <th>Enrolled</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end pe-2">Total Units Loaded:</td>
                                    <td class="text-end pe-2"><span id="total-units-value-${sectionId}">0</span></td>
                                    <td colspan="6"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            `;

        $('#sectionTablesContainer').append(tableHtml);
        console.log('Updated Section Data:', updatedSectionData);

        var table = $('#' + tableId).DataTable({
            data: updatedSectionData,
            columns: [{
                    data: 'section'
                },
                {
                    data: 'subjCode',
                    render: function(data, type, row) {
                        var conflictIndicator = row.hasConflict ?
                            '<i class="fa fa-exclamation-triangle text-warning ms-2" title="Schedule Conflict"></i>' :
                            '';
                        return `${data} - ${row.subjDesc} ${conflictIndicator}`;
                    }
                },
                {
                    data: 'lecunits'
                },
                {
                    data: 'labunits'
                },
                {
                    data: 'cr_unit'
                },
                {
                    data: 'schedotherclass'
                },
                {
                    data: 'time_day',
                    render: function(data, type, row) {
                        return formatSchedule(data);
                    }
                },
                {
                    data: 'instructor',
                    render: function(data, type, row) {
                        return data ? data :
                            '<span style="color: red;">Not Assigned</span>';
                    }
                },
                {
                    data: 'room',
                    render: function(data, type, row) {
                        return data ? data :
                            '<span style="color: red;">Not Assigned</span>';
                    }
                },
                {
                    data: 'enrolled'
                },
                {
                    data: 'subjectId',
                    render: function(data, type, row) {
                        let isLoaded = addedSubj.includes(row.schedid);

                        if (isLoaded) {
                            // If already loaded, show text only
                            return `<span class="text-danger" style="font-weight: bold;">Loaded Subject</span>`;
                        } else {
                            // Otherwise, display the "Load Subject" button
                            let conflictData = row.conflicts ? JSON.stringify(row
                                .conflicts) : '[]'; // Serialize conflicts
                            let buttonDisabled = row.hasConflict ? '' :
                                ''; // Disable button if there's a conflict
                            let buttonTitle = row.hasConflict ?
                                'Cannot load subject due to schedule conflict' :
                                'Load this subject';

                            return `
                                    <button class="btn btn-success btn-xs load-subject"
                                        data-id="${data}"
                                        data-schedid="${row.schedid}"
                                        data-prereq-id="${row.prereqID}"
                                        data-prereq-desc="${row.prereqDESC}"
                                        data-section="${row.sectionId}"
                                        data-conflict="${row.hasConflict}"
                                        data-etime="${row.etime}"
                                        data-stime="${row.stime}"
                                        data-day="${row.days}"
                                        data-conflicts='${conflictData}' // Embed conflict data
                                        title="${buttonTitle}"
                                        ${buttonDisabled}>
                                        Load Subject
                                    </button>`;
                        }
                    }
                }


            ],
            paging: false,
            ordering: false,
            info: false,
            searching: false,
            language: {
                emptyTable: "No data available in table"
            },
            drawCallback: function(settings) {
                var api = this.api();
                var totalUnits = api.column(4, {
                    page: 'current'
                }).data().reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                $('#total-units-value-' + sectionId).text(totalUnits.toFixed(2));
            }
        });
    }


    function loadSectionSchedule(sectionId) {
        $.ajax({
            url: '/student/loading/get-section-schedule', // Adjust the URL as needed
            method: 'GET',
            data: {
                section_id: sectionId,
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val()
            },
            success: function(response) {
                $('#sectionTablesContainer').empty();

                if (sectionId === 'all') {
                    // Group data by section if 'all' is selected
                    $.each(groupDataBySection(response.data), function(sectionId, sectionData) {
                        createSectionTable(sectionId, sectionData);
                    });
                } else {

                    createSectionTable(sectionId, response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading section schedule:", error);
                alert('Failed to load section schedule. Please try again.');
            },
            complete: function() {
                // Hide the loading modal after the AJAX call finishes
                // $('#modal-overlay').modal('hide');
            }
        });
    }
    // function loadSectionSchedule(sectionId, studid) {
    //     $.ajax({
    //         url: '/student/loading/get-section-schedule', // Adjust the URL as needed
    //         method: 'GET',
    //         data: {
    //             section_id: sectionId,
    //             studid: studid,
    //             syid: $('#filter_sy').val(),
    //             semid: $('#filter_semester').val()
    //         },
    //         success: function(response) {
    //             $('#sectionTablesContainer').empty();

    //             if (sectionId === 'all') {
    //                 // Group data by section if 'all' is selected
    //                 $.each(groupDataBySection(response.data), function(sectionId, sectionData) {
    //                     createSectionTable(sectionId, sectionData);
    //                 });
    //             } else {

    //                 createSectionTable(sectionId, response.data);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("Error loading section schedule:", error);
    //             alert('Failed to load section schedule. Please try again.');
    //         },
    //         complete: function() {
    //             // Hide the loading modal after the AJAX call finishes
    //             // $('#modal-overlay').modal('hide');
    //         }
    //     });
    // }

    $(document).on('change', '#sectionSelect', function() {
        var selectedSectionId = $(this).val();
        loadSectionSchedule(selectedSectionId);
    });


    $('#loadSubjects').click(function() {
        var sectionId = $('#StudentSection').val();
        var studid = $('#studid').val();

        $('#modal-overlay').modal('show');

        $('#loadsubjects').data('data');
        setTimeout(function() {
            loadSectionSchedule('all');
        }, 500);
        $.each(addedSubj, function(index, subject) {
            $('.sectionTablesload .remove-sched[data-schedid="' + subject + '"]').addClass(
                'disabled')
        })
    });

    // Function to group data by section
    function groupDataBySection(data) {
        var groupedData = {};

        // Assuming each entry has a sectionId
        data.forEach(function(item) {
            if (!groupedData[item.sectionId]) {
                groupedData[item.sectionId] = [];
            }
            groupedData[item.sectionId].push(item);
        });
        return groupedData;
    }

    function checkScheduleConflicts(sectionId, sectionData) {
        return new Promise((resolve, reject) => {
            const promises = [];

            // sectionData.forEach(function(schedule) {
            //     const promise = new Promise((resolveInner) => {
            //         $.ajax({
            //             url: '/student/subject/dean/conflict',
            //             method: 'GET',
            //             data: {
            //                 'syid': $('#filter_sy').val(),
            //                 'semid': $('#filter_semester').val(),
            //                 'stime': schedule.stime,
            //                 'etime': schedule.etime,
            //                 'day': [schedule.dayId],
            //                 'room': schedule.room,
            //                 'schedid': schedule.schedid,
            //             },
            //             success: function(data) {
            //                 if (data && data.length > 0) {
            //                     data.forEach(conflict => {
            //                         const conflictingSchedule =
            //                             sectionData.find(
            //                                 s => s.schedid ===
            //                                 conflict.schedid
            //                             );
            //                         if (conflictingSchedule) {
            //                             conflictingSchedule
            //                                 .hasConflict = true;
            //                         }
            //                     });
            //                 }
            //                 resolveInner();
            //             },
            //             error: function(err) {
            //                 console.error('Error checking conflicts:',
            //                     err);
            //                 resolveInner();
            //             }
            //         });
            //     });
            //     promises.push(promise);
            // });

            Promise.all(promises).then(() => {
                resolve(sectionData);
                $('#modal-overlay').modal('hide');
                $('#sectionScheduleModal').modal('show');
            });
        });
    }

    function setButtonState($button, isLoading) {
        if (isLoading) {
            $button.html('Load Subject').removeClass('btn-danger').addClass('btn-success');
        } else {
            $button.html('Remove Sched').removeClass('btn-success').addClass('btn-danger');
        }
    }

    // $(document).on('click', '.load-subject', function() {
    //     var conflict = $(this).attr('data-conflict')
    //     // if (conflict == 'true') {
    //     //     $('#yourModalId').modal('show');
    //     // }

    //     var $button = $(this);
    //     var subjectId = $button.data('id');
    //     var prereqID = $button.data('prereq-id');
    //     var prereqDesc = $button.data('prereq-desc');
    //     var schedid = $button.data('schedid');
    //     var sectionID = $button.data('section');
    //     var $row = $button.closest('tr');

    //     // Prevent further clicks if already disabled
    //     if ($button.hasClass('disabled')) return;
    //     $button.addClass('disabled');

    //     var subjectData = {
    //         id: subjectId,
    //         prereqID: prereqID,
    //         prereqDesc: prereqDesc,
    //         sectionID: sectionID,
    //         schedid: schedid,
    //         sectionDesc: $row.find('td:eq(0)').text().trim(),
    //         subjCode: $row.find('td:eq(1)').text().trim().split(' - ')[0],
    //         subjDesc: $row.find('td:eq(1)').text().trim().split(' - ')[1],
    //         lecunits: parseFloat($row.find('td:eq(2)').text()) || 0,
    //         labunits: parseFloat($row.find('td:eq(3)').text()) || 0,
    //         schedotherclass: $row.find('td:eq(5)').text().trim(),
    //         schedule: $row.find('td:eq(6)').text().trim(),
    //         day: $row.find('td:eq(6)').text().trim().slice(-
    //             1), // Extract day from the table row
    //         instructor: $row.find('td:eq(7)').text().trim(),
    //         roomname: $row.find('td:eq(8)').text().trim()
    //     };

    //     var schedules = $('.schedule_row');
    //     var hasTimeConflict = false;
    //     schedules.each(function() {
    //         console.log(schedules, 'schedules');

    //         var schedule = $(this).text().trim();
    //         var time = schedule.split(' ')[0] + ' ' + schedule.split(' ')[1];
    //         var newTime = new Date(time);
    //         var oldTime = new Date(subjectData.schedule.split(' ')[0] + ' ' + subjectData.schedule
    //             .split(' ')[1]);
    //         if (newTime.getHours() >= oldTime.getHours() && newTime.getHours() < oldTime.getHours() +
    //             6) {
    //             hasTimeConflict = true;
    //         }
    //     });
    //     if (hasTimeConflict) {
    //         alert('Time conflict detected!');
    //         $button.removeClass('disabled');
    //     }

    //     // Show modal with the subject data
    //     $('#yourModalId').find('.modal-subject-code').text(subjectData.subjCode);
    //     $('#yourModalId').find('.modal-subject-desc').text(subjectData.subjDesc);
    //     $('#yourModalId').find('.modal-schedule').text(subjectData.schedule);
    //     $('#yourModalId').find('.modal-instructor').text(subjectData.instructor);
    //     $('#yourModalId').find('.modal-room-name').text(subjectData.roomname);
    //     $('#conflictedSubject').text(subjectData.subjDesc);
    //     // Show the modal


    //     var [schedtime, days] = subjectData.schedule.split('|');
    //     var isLoading = $button.hasClass('btn-success');

    //     if (isLoading) {
    //         // Check if the subject has already been loaded
    //         if (loadedSubjects.includes(subjectId)) {
    //             Swal.fire({
    //                 title: 'Error',
    //                 text: 'Can\'t be loaded again, subject has already been loaded.',
    //                 icon: 'error',
    //                 confirmButtonText: 'OK'
    //             });
    //             $button.removeClass('disabled');
    //         } else {
    //             // Check for schedule conflicts
    //             // conflict_sched(subjectData.sectionID, subjectData.sectionDesc,
    //             //     subjectData.schedid, subjectData.id, subjectData.subjDesc,
    //             //     subjectData.sectionDesc, subjectData.schedotherclass,
    //             //     schedtime,
    //             //     subjectData.day, subjectData.roomname, '', '').then(
    //             //     function(
    //             //         conflictData) {
    //             //         if (conflictData.hasConflicts) {
    //             //             // Construct the conflict details in a readable format
    //             //             var conflictInfo = conflictData.conflicts.map(
    //             //                 conflict =>
    //             //                 `<strong>Subject:</strong> ${conflict.subjectDesc}<br>
    //             //     <strong>Time:</strong> ${conflict.time}<br>
    //             //     <strong>Day:</strong> ${conflict.day}<br>`
    //             //             ).join('<br>');

    //             //             // Show the conflict details in a modal
    //             //             Swal.fire({
    //             //                 title: 'Schedule Conflict',
    //             //                 html: '<strong>Conflicting Schedule(s):</strong><br>' +
    //             //                     conflictInfo,
    //             //                 icon: 'warning',
    //             //                 confirmButtonText: 'OK'
    //             //             });
    //             //             $button.removeClass('disabled');
    //             //         } else {
    //             //             // Add subject to student loading
    //             //             addSubjectToStudentLoading(subjectData).then(
    //             //                 function() {
    //             //                     loadedSubjects.push(subjectId);
    //             //                     $button.html('Remove Sched')
    //             //                         .removeClass(
    //             //                             'btn-success').addClass(
    //             //                             'btn-danger').removeClass(
    //             //                             'disabled');
    //             //                 }).catch(function(error) {
    //             //                 console.error("Error loading subject:",
    //             //                     error);
    //             //                 $button.removeClass('disabled');
    //             //             });
    //             //         }
    //             //     }).catch(function(error) {
    //             //     console.error("Error checking conflict:", error);
    //             //     $button.removeClass('disabled');
    //             // });
    //         }
    //     } else {
    //         // Remove subject from student loading
    //         removeSubjectFromStudentLoading(subjectData.id).then(function() {
    //             loadedSubjects = loadedSubjects.filter(id => id !==
    //                 subjectId);
    //             $button.html('Load Subject').removeClass('btn-danger')
    //                 .addClass(
    //                     'btn-success').removeClass('disabled');
    //         }).catch(function(error) {
    //             console.error("Error removing subject:", error);
    //             $button.removeClass('disabled');
    //         });
    //     }
    // });

    $(document).on('click', '.remove-subject', function() {
        var $button = $(this);
        var $row = $button.closest('tr');
        var subjectId = $button.data('id');
        var studentId = $row.data('student-id');
        var syid = $('#filter_sy').val();
        var semid = $('#filter_semester').val();
        var schedid = $row.data('schedid');
        var actionText = $button.text().trim().toUpperCase();


        if (actionText === 'REMOVE') {
            console.warn('Removing subject without enrollment:', subjectId);

            Swal.fire({
                title: 'Are you sure?',
                text: 'This subject will be dropped from the student\'s load.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, drop it!',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/student/loading/delete-loaded-subject/' + subjectId,
                        type: 'DELETE',
                        data: {
                            studentId: studentId,
                            syid: syid,
                            semid: semid,
                            schedid: schedid,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(2131232);
                            $row.remove();
                            recalculateTotalUnits();
                            Swal.fire({
                                title: 'Subject Dropped',
                                text: response.message,
                                icon: 'success',    
                                confirmButtonText: 'OK',
                            });
                        },
                        error: function(xhr) {
                            console.error('Unable to Drop Subject', xhr.responseText);
                        },
                    });
                }
            });

            return;
        }

        if (!studentId) {
            console.error('Student ID is missing');
            $row.remove();
            recalculateTotalUnits();
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'This subject will be dropped from the student\'s load.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, drop it!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/student/loading/delete-loaded-subject/' + subjectId,
                    method: 'DELETE',
                    data: {
                        studentId: studentId,
                        syid: syid,
                        semid: semid,
                        schedid: schedid,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        recalculateTotalUnits();
                        getUpdatedStudentLoading(studentId, 'all', 0);

                        $('.load-subject[data-id="' + subjectId + '"]').removeClass(
                            'disabled'
                        );
                        updateLedger(studentId, syid, semid);
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.success,
                            type: 'success',
                            confirmButtonText: 'Okay',
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Unable to Drop Subject',
                            text: 'This subject schedule contains grades.',
                            type: 'error',
                            confirmButtonText: 'OK',
                        });
                    },
                });
            }
        });
    });

    function loadStudentScheduleBySection(sectionId, studentId) {
        $.ajax({
            url: '/student/loading/Student-Loading/' + sectionId,
            method: 'GET',
            success: function(response) {
                if (response && response.subjects) {
                    populateStudentLoadingTable(response.subjects, studentId); // Use studentId

                } else {
                    console.error("No subjects found for this section.");
                    $('#subjectsTableBody').empty(); // Clear the table if no subjects found
                    $('#totalUnitsLoaded').text('0'); // Reset total units
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading student schedule:", error);
            }
        });
    }

    $(document).on('hidden.bs.modal', '#sectionScheduleModal', function() {
        loadedSubjects = [];
    });

    function processSubjectLoading($button, subjectData, isLoading) {
        console.log(subjectData, isLoading, $button);

        if (isLoading) {
            // Check if subject is already loaded
            if (loadedSubjects.includes(subjectData.id)) {
                Swal.fire({
                    title: 'Error',
                    text: 'Can\'t be loaded again, subject has already been loaded.',
                    type: 'error',
                    confirmButtonText: 'OK'
                });
                $button.removeClass('disabled');
                return;
            }

            // Add subject to student loading
            addSubjectToStudentLoading(subjectData)
                .then(function() {
                    loadedSubjects.push(subjectData.id);
                    $button.html('Remove Sched')
                        .removeClass('btn-success')
                        .addClass('btn-danger')
                        .removeClass('disabled');
                })
                .catch(function(error) {
                    console.error("Error loading subject:", error);
                    $button.removeClass('disabled');
                });
        } else {
            // Remove subject from student loading
            removeSubjectFromStudentLoading(subjectData.id)
                .then(function() {
                    loadedSubjects = loadedSubjects.filter(id => id !== subjectData.id);
                    $button.html('Load Subject')
                        .removeClass('btn-danger')
                        .addClass('btn-success')
                        .removeClass('disabled');
                })
                .catch(function(error) {
                    console.error("Error removing subject:", error);
                    $button.removeClass('disabled');
                });
        }
    }

    function getSubjectData($button) {
        var $row = $button.closest('tr');
        return {
            id: $button.data('id'),
            prereqID: $button.data('prereq-id'),
            prereqDesc: $button.data('prereq-desc'),
            sectionID: $button.data('section'),
            schedid: $button.data('schedid'),
            sectionDesc: $row.find('td:eq(0)').text().trim(),
            subjCode: $row.find('td:eq(1)').text().trim().split(' - ')[0],
            subjDesc: $row.find('td:eq(1)').text().trim().split(' - ')[1],
            lecunits: parseFloat($row.find('td:eq(2)').text()) || 0,
            labunits: parseFloat($row.find('td:eq(3)').text()) || 0,
            schedotherclass: $row.find('td:eq(5)').text().trim(),
            schedule: $row.find('td:eq(6)').text().trim(),
            instructor: $row.find('td:eq(7)').text().trim(),
            roomname: $row.find('td:eq(8)').text().trim()
        };
    }

    async function conflict_sched(sectionID, sectionDesc, schedid, subjectid, subject,
        yearDesc, schedotherclass, schedtime, day, roomname, enrolled, students) {
        try {
            // Split schedtime into start and end times
            const [startTime, endTime] = schedtime.split(' - ').map(time => time
                .trim());

            let endTimeFormatted = endTime.split(' / ')[0].trim();
            let dayFormatted = endTime.split(' / ')[1] ? endTime.split(' / ')[1]
                .trim() :
                '';

            // Format days
            // let formattedDays = (Array.isArray(day) && day.length) ? day.join(', ') :
            //     dayFormatted || day || '';


            // AJAX call using async/await
            const response = await $.ajax({
                type: 'GET',
                url: '/student/subject/dean/conflict',
                data: {
                    'syid': $('#filter_sy').val(),
                    'semid': $('#filter_semester').val(),
                    'time': `${startTime} - ${endTimeFormatted}`,
                    'day': Array.isArray(day) ? JSON.stringify(day) : [day].map(
                        String),
                    'room': roomname,
                    'schedid': schedid
                }
            });

            // Check the response data for conflicts
            if (response && response.length > 0) {
                return {
                    hasConflicts: true,
                    conflicts: response.conflictDetails || []
                };
            } else {
                return {
                    hasConflicts: false
                };
            }
        } catch (error) {
            console.error("Error during conflict check:", error);
            throw error;
        }
    }

    function populateStudentLoadingTable(subjects, studentId) {
        var tableBody = $('#subjectsTableBody');
        tableBody.empty(); // Clear previous data
        var totalUnits = 0;

        subjects.forEach(function(subject) {
            var totalSubjectUnits = parseFloat(subject.lecunits) + parseFloat(subject.labunits);
            var schedid = subject.schedid;
            var row = `
                        <tr data-student-id="${studentId}" data-section-id="${sectionId}" data-subject-id="${subjId}" data-schedid="${schedid}">
                            <td>${subject.sectionDesc || 'N/A'}</td>
                            <td>${subject.subjCode} - ${subject.subjDesc}</td>
                            <td>${subject.lecunits}</td>
                            <td>${subject.labunits}</td>
                            <td>${totalSubjectUnits.toFixed(2)}</td>
                            <td>${subject.schedotherclass || 'Not Available'}</td>
                            <td class="schedule_row" data-subject="${subject.sectionDesc}" data-code="${subject.subjCode}">${subject.stime ? subject.stime + ' - ' + subject.etime + ' / ' + subject.day : '<span style="color: red;">Not Available</span>'}</td>
                            <td>${subject.instructor || '<span style="color: red;">Not Assigned</span>'}</td>
                            <td>${subject.roomname || '<span style="color: red;">Not Assigned</span>'}</td>
                            <td><a href="#" class="remove-subject" data-id="${subject.subjId}" style="color: red; text-decoration: underline;">DROP</a></td>
                        </tr>
                    `;

            tableBody.append(row); // Append row to table
            totalUnits += totalSubjectUnits; // Update total units
        });

        $('#totalUnitsLoaded').text(totalUnits.toFixed(2)); // Display total units loaded
        recalculateTotalUnits(); // Call to recalculate total units if necessary
    }

    // function addSubjectToStudentLoading(subjectData) {
    //     return new Promise((resolve, reject) => {
    //         var totalSubjectUnits = subjectData.lecunits + subjectData.labunits;
    //         var newRow = `<tr >
    //                 <td>${subjectData.sectionDesc || 'N/A'} <span class="badge bg-success">Added</span></td>
    //                 <td>${subjectData.subjCode} - ${subjectData.subjDesc}</td>
    //                 <td>${subjectData.prereqDesc ? subjectData.prereqDesc : '<span style="color: red;">No prerequisite</span>'}</td>
    //                 <td>${subjectData.lecunits.toFixed(1)}</td>
    //                 <td>${subjectData.labunits.toFixed(1)}</td>
    //                 <td>${totalSubjectUnits.toFixed(2)}</td>
    //                 <td>${subjectData.schedotherclass || 'N/A'}</td>
    //                 <td>${subjectData.schedule || '<span class="text-danger">Not Available</span>'}</td>
    //                 <td>${subjectData.instructor || '<span class="text-danger">Not Assigned</span>'}</td>
    //                 <td>${subjectData.roomname || '<span class="text-danger">Not Assigned</span>'}</td>
    //                     <td><a href="#" class="remove-subject" data-id="${subjectData.id}" style="color: red; text-decoration: underline;">DROP</a></td>
    //             </tr>`;

    //         // Add the new row to the table
    //         $('#subjectsTableBody').append(newRow);
    //         recalculateTotalUnits();

    //         // Resolve the promise after adding the subject
    //         resolve();
    //     });
    // }

    function addSubjectToStudentLoading(subjectData) {
        return new Promise((resolve, reject) => {
            var isDuplicate = $('#subjectsTableBody tr').filter(function() {
                return $(this).find('td:eq(1)').text().trim().includes(subjectData.subjCode);
            }).length > 0;

            if (isDuplicate) {
                Toast.fire({
                    type: 'error',
                    title: 'Subject code already added.'
                });
                reject('Duplicate subject code');
                return;
            }

            var totalSubjectUnits = subjectData.lecunits + subjectData.labunits;
            var removeActionText = $('#studentStatus').text() === 'NOT ENROLLED' ? 'REMOVE' : 'DROP';
            var newRow = `<tr>
                    <td>${subjectData.sectionDesc || 'N/A'}</td>
                    <td>${subjectData.subjCode} - ${subjectData.subjDesc}</td>
                    <td>${subjectData.prereqDESC}</td>
                    <td>${subjectData.lecunits.toFixed(1)}</td>
                    <td>${subjectData.labunits.toFixed(1)}</td>
                    <td>${totalSubjectUnits.toFixed(2)}</td>
                    <td>${subjectData.schedotherclass || 'N/A'}</td>
                    <td>${subjectData.schedule || '<span class="text-danger">Not Available</span>'}</td>
                    <td>${subjectData.instructor || '<span class="text-danger">Not Assigned</span>'}</td>
                    <td>${subjectData.roomname || '<span class="text-danger">Not Assigned</span>'}</td>
                    <td><a href="#" class="remove-subject" data-id="${subjectData.id}" style="color: red; text-decoration: underline;">${removeActionText}</a></td>
                </tr>`;

            $('#subjectsTableBody').append(newRow);

            var response = {
                studentLoading: JSON.parse(localStorage.getItem('studentLoading'))
            };

            if (response.studentLoading && response.studentLoading.length > 0) {
                var status = $('#studentStatus').text();
                var addedSubjectsCount = response.studentLoading.filter(function(subject) {
                    return subject.loadStatus !== 1 && subject.loadStatus !== '1' && subject
                        .subjectID != subjectData.id;
                }).length;
                $('#LoadSubjectSide').text(addedSubjectsCount);
            }
            recalculateTotalUnits();
            resolve();
        });
    }

    function addSubjectToStudentLoading(subjectData) {
        return new Promise((resolve, reject) => {
            console.log(subjectData, 'subjectData');

            var schedule = subjectData.schedule;
            console.log(schedule, 'schedule');
            var time = schedule.trim().split(' ').slice(0, -1).join(' ');
            var days = schedule.trim().split(' ').slice(-1).join(' ');

            // Log the extracted values for verification
            console.log("Time:", time);
            console.log("Day:", days);



            var totalSubjectUnits = subjectData.lecunits + subjectData.labunits;

            var isDropped = subjectData.loadStatus == 1 || subjectData.loadStatus === '1';
            var statusText = isDropped ? 'DROPPED' : 'ADDED';
            var rowDisabledClass = '';
            var status = $('#studentStatus').text();
            var removeActionText = status === 'NOT ENROLLED' ? 'REMOVE' : 'DROP';

            // Create the new row
            var newRow = `<tr data-subject-id="${subjectData.id}" data-section-id="${subjectData.sectionID}" data-schedid="${subjectData.schedid}" class="${rowDisabledClass}">
                        <td>${subjectData.sectionDesc || 'N/A'} 
                            <span class="badge bg-success">${statusText}</span>
                        </td>
                        <td>${subjectData.subjCode} - ${subjectData.subjDesc}</td>
                        <td>${subjectData.prereqDesc ? subjectData.prereqDesc : '<span style="color: red;">No prerequisite</span>'}</td>
                        <td>${subjectData.lecunits.toFixed(1)}</td>
                        <td>${subjectData.labunits.toFixed(1)}</td>
                        <td>${totalSubjectUnits.toFixed(2)}</td>
                        <td>${subjectData.schedotherclass || 'N/A'}</td>
                        <td class="schedule_row">${subjectData.schedule || '<span class="text-danger">Not Available</span>'}</td>
                        <td>${subjectData.instructor || '<span class="text-danger">Not Assigned</span>'}</td>
                        <td>${subjectData.roomname || '<span class="text-danger">Not Assigned</span>'}</td>
                        <td>
                            <a href="#" class="remove-subject" data-id="${subjectData.id}" style="color: red; text-decoration: underline;">
                                ${removeActionText}
                            </a>
                        </td>
                    </tr>`;

            $('#subjectsTableBody').append(newRow);

            recalculateTotalUnits();

            resolve();
        });
    }



    // Array to keep track of loaded subjects
    var loadedSubjects = [];

    // Event handler for loading subjects



    // Event to clear loaded subjects when modal is closed
    $(document).on('hidden.bs.modal', '#sectionScheduleModal', function() {
        loadedSubjects = [];
    });

    function removeSubjectFromStudentLoading(subjectId) {
        return new Promise((resolve, reject) => {
            // Find the row with the matching data-id attribute and remove it
            var $row = $('#subjectsTableBody').find(`a.remove-subject[data-id="${subjectId}"]`)
                .closest('tr');
            if ($row.length) {
                $row.remove();
                recalculateTotalUnits();
                resolve();
            } else {
                reject(
                    'Subject not found');
            }
        });
    }

    // Function to populate sections
    function populateSections(initialSectionId = "all") {
        $.ajax({
            url: '/student/getSections',
            method: 'GET',
            data: {
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val(),
            },
            success: function(response) {
                console.log('123123');

                // Start with default options
                let temp_html = '<option value="">Select Section</option>';
                temp_html += '<option value="all">All</option>';

                // Populate options dynamically
                $.each(response, function(index, section) {
                    temp_html += `<option value="${section.id}">${section.sectionDesc}</option>`;
                });

                // Update the select dropdown and set the default selection
                $('#sectionSelect').empty().append(temp_html).val(initialSectionId);
            }
        });
    }

    function updateStudentSection(studentId, sectionId) {
        var formData = new FormData();
        formData.append('studentId', studentId);
        formData.append('sectionId', sectionId);

        $.ajax({
            url: '/student/loading/update-section',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                _sid = $('#saveStudentLoading').attr('data-id')
                openStudentInformationModal(_sid);
                load_all_student()
                swal.fire({
                    title: 'Success',
                    text: response.message,
                    type: 'success'
                });
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    swal.fire({
                        title: 'Error',
                        text: value[0],
                        type: 'error'
                    });
                });
            }
        });
    }


    $(document).on('show.bs.modal', '#sectionScheduleModal', function() {
        populateSections("all");
    })

    $(document).ready(function() {
        $('#searchSectionInput').on('keyup', function() {
            let searchTerm = $(this).val().toLowerCase();

            $('.sectionTablesload').each(function() {
                let hasVisibleRows = false;
                $(this).find('tbody tr').each(function() {
                    let rowText = $(this).text().toLowerCase();
                    let isVisible = rowText.includes(searchTerm);
                    $(this).toggle(isVisible);
                    if (isVisible) hasVisibleRows = true;
                });

                let sectionCard = $(this).closest('.card-body');
                if (!hasVisibleRows) {
                    sectionCard.hide();
                } else {
                    sectionCard.show();
                }
            });
        });
    });
</script>
