<style>
    .scrollable-content {
        max-height: calc(100vh - 240px);
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .scrollable-content::-webkit-scrollbar {
        display: none;
    }
</style>
<div class="modal fade new_modal " id="studentLoadingModal" tabindex="-1" aria-labelledby="studentLoadingModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl mw-100 mh-100" style="width: 100vw; height: 93vh;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentLoadingModalLabel">Student Information</h5>
                <button type="button" id="studentLoadingModalClose" class="close" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <img id="studentPhoto" src="{{ asset('avatar/S(F) 1.png') }}" alt="Student Photo"
                                class="card-img-top"
                                style="height: 240px; object-fit: cover; object-position: center; border-radius: 10%; padding:10px">
                            <div class="card-body">
                                <table class="table table-sm" style="font-size:12px">
                                    <tr>
                                        <th>ID Number</th>
                                        <td id="studentIdNumber"></td>
                                    </tr>
                                    <tr>
                                        <th>Level</th>
                                        <td id="studentLevel"></td>
                                    </tr>
                                    <tr>
                                        <th>Section</th>
                                        <td id="studenAcadSection" data-section="0"></td>
                                    </tr>
                                    <tr>
                                        <th>Course</th>
                                        <td id="studentCourse"></td>
                                    </tr>
                                    <tr>
                                        <th>Loaded Subjects</th>
                                        <td id="LoadSubjectSide"></td>
                                    </tr>
                                    {{-- <tr>
                                        <th>Curriculum <sup><a href="#" id="editCurriculum"><i class="fa fa-edit text-info"></i></a></sup></th>
                                        <td id="studentCurriculum"></td>
                                    </tr> --}}
                                    <tr>
                                        <th>Student Status</th>
                                        <td id="studentStatus"></td>
                                    </tr>
                                </table>
                                <div class="text-center">
                                    <button id="updateCourse" class="btn btn-primary btn-sm">Update Course</button>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <!-- Alert for Conflict -->
                                <div class="conflict-alert">
                                    <i class="bi bi-exclamation-triangle-fill"></i> CONFLICT OF SCHEDULES
                                </div>
                                <!-- Conflict Details -->
                                <b id="conflictSubjectText"></b>
                                <p id="conflictSubjectParagraph">subject is in conflict with other Subject(s)</p>

                                <!-- Conflict and Available Schedule Tables -->
                                <table class="table table-sm " style="border: 1px solid #ddd;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table class="table table-sm" style="border: none;">
                                                    <thead>
                                                        <tr>
                                                            <th>Conflict Schedule</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="conflictSchedule">
                                                        <!-- Example Conflict Schedule -->
                                                        <tr>
                                                            <td>
                                                                <div class="schedule-details">
                                                                    <span class="subject"><b>Subject:</b> Undefined -
                                                                        Politics and Governance</span><br>
                                                                    <span class="time"><b>Time:</b> 9:00 AM - 11:30
                                                                        AM</span><br>
                                                                    <span class="room"><b>Room:</b> ROOM
                                                                        303</span><br>
                                                                    <span class="teacher"><b>Teacher:</b> John
                                                                        Doe</span><br>
                                                                    <span class="class"><b>Class:</b> Lecture</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <table class="table table-sm" style="border: none;">
                                                    <thead>
                                                        <tr>
                                                            <th>Available Schedules</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="availableSchedules">
                                                        <!-- Example Available Schedule -->
                                                        <tr>
                                                            <td>
                                                                <div class="Avail-schedule-details">
                                                                    <input type="radio" name="schedule" id="schedule1"
                                                                        value="1" class="avail-schedule-radio">
                                                                    <label for="schedule1">
                                                                        <span class="AvailSubject"><b>Subject:</b>
                                                                            Politics and Governance</span><br>
                                                                        <span class="AvailSection"><b>Section:</b>
                                                                            MHRM-1RA</span><br>
                                                                        <span class="AvailTime"><b>Time:</b> 1:00 AM -
                                                                            3:30 AM</span><br>
                                                                        <span class="AvailRoom"><b>Room:</b> ROOM
                                                                            301</span><br>
                                                                        <span class="AvailTeacher"><b>Teacher:</b> Via
                                                                            Gabule</span><br>
                                                                        <span class="AvailClass"><b>Class:</b>
                                                                            Lecture</span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <ul class="nav nav-tabs" id="studentInfoTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="student-info-tab" data-bs-toggle="tab"
                                    data-bs-target="#student-info" type="button" role="tab"
                                    aria-controls="student-info" aria-selected="false">Student Information</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="student-loading-tab" data-id=" " data-bs-toggle="tab"
                                    data-bs-target="#student-loading" type="button" role="tab"
                                    aria-controls="student-loading" aria-selected="true">Student
                                    Loading</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="enrollment-details-tab" data-bs-toggle="tab"
                                    data-bs-target="#enrollment-details" type="button" role="tab"
                                    aria-controls="enrollment-details" aria-selected="false">Enrollment
                                    Details</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="student-grades-tab" data-bs-toggle="tab"
                                    data-bs-target="#student-grades" type="button" role="tab"
                                    aria-controls="student-grades" aria-selected="false">Student Grades</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="studentInfoTabsContent"
                            style="padding:10px; border-top: 1.5px solid #ddd;">
                            <div class="tab-pane fade" id="student-info" role="tabpanel"
                                aria-labelledby="student-info-tab">
                                @include('superadmin.pages.student.studentinformationtab')
                            </div>

                            <div class="tab-pane fade" id="student-loading" role="tabpanel"
                                aria-labelledby="student-loading-tab" style="max-height: auto;">
                                <!-- Student Loading content -->
                                @include('superadmin.pages.student.studentloadingtab')
                            </div>


                            <div class="tab-pane fade" data-id="0" id="enrollment-details" role="tabpanel"
                                aria-labelledby="enrollment-details-tab">
                                <!-- Enrollment Details content -->
                                @include('superadmin.pages.student.enrollmentdetailstab')
                            </div>

                            <div class="tab-pane fade" id="student-grades" role="tabpanel"
                                aria-labelledby="student-grades-tab">
                                <!-- Student Grades content -->
                                @include('superadmin.pages.student.gradeevaltab')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sectionScheduleModal" tabindex="-1" aria-labelledby="sectionScheduleModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-id-student="">
    <div class="modal-dialog modal-fullscreen mw-100 mh-100" style="width: 100vw; height: 93vh;" role="document">
        <div class="modal-content" style="height: 100%;">
            <div class="modal-header py-2 bg-primary text-white">
                <h5 class="modal-title" id="sectionScheduleModalLabel">Section Schedule List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body table-responsive scrollable-content"
                style="max-height: 100%; overflow-y: auto; border-radius: 5px; 
            scrollbar-width: none; -ms-overflow-style: none;">
                <!-- Hide scrollbar for Webkit browsers -->
                <style>
                    .scrollable-content::-webkit-scrollbar {
                        display: none;
                    }
                </style>

                <!-- Select Section -->
                <div class="row mb-3 align-items-center" style="display: flex; justify-content: space-between;">
                    <div class="col-md-4 ml-3">
                        <label for="sectionSelect" class="form-label">Select Section</label>
                        <select class="form-select" id="sectionSelect" style="border-radius: 5px;">
                            <option value="all">All</option>
                        </select>
                    </div>
                    <div class="col-md-2 align-items-end" style="margin-right: 38px">
                        <label for="sectionSearch" class="form-label">Search</label>
                        <div class="input-group" style="width: 200px;">
                            <input type="text" id="searchSectionInput" class="form-control form-control-sm"
                                placeholder="Search">
                            <button class="btn btn-primary btn-sm" type="button" id="searchSectionButton"><i
                                    class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Table Container -->
                <div id="sectionTablesContainer">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" style="border-radius: 5px;">
                            <thead class="table-light"></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content bg-gray-dark" style="opacity: 78%; margin-top: 15em">
            <div class="modal-body" style="height: 250px">

                <div class="row">
                    <div class="col-md-12 text-center text-lg text-bold b-close">
                        Please Wait
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="loader"></div>
                    </div>
                </div>
                <div class="row" style="margin-top: -30px">
                    <div class="col-md-12 text-center text-lg text-bold">
                        Processing...
                    </div>
                </div>
            </div>
        </div>
    </div> {{-- dialog --}}
</div>




<div class="modal fade" id="grade_evaluation_modal" data-backdrop="static" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl " role="document">
        <div class="modal-content" style="height: 640px!important;">
            <div class="card-header py-2" style="background-color: rgb(216, 216, 216)">
                <p class="card-title mb-0 font-weight-bold text-sm" id="">Grade Evaluation</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style=" overflow-y: scroll">
                <div class="row pl-2" width="100%">
                    <div class="col-4">
                        <div class="d-flex flex-row text-sm align-items-end">
                            <div class="font-weight-bold" style="font-size: 12px!important">Student Name: </div>
                            <div class="ml-1 font-weight-bold" id="student_name_eval"
                                style="font-size: 13px!important">&nbsp;</div>
                        </div>
                        <div class="d-flex flex-row text-sm mt-3 align-items-end">
                            <div class="font-weight-bold" style="font-size: 12px!important">Course: </div>
                            <div class="ml-1 font-weight-bold" id="course_eval" style="font-size: 13px!important">
                                &nbsp;</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-row text-sm align-items-end">
                            <div class="font-weight-bold" style="font-size: 12px!important">Year Level: </div>
                            <div class="ml-1 font-weight-bold" id="yearlevel_eval" style="font-size: 13px!important">
                                &nbsp;</div>
                        </div>
                        <div class="d-flex flex-row text-sm mt-3 align-items-end">
                            <div class="font-weight-bold" style="font-size: 12px!important">Section: </div>
                            <div class="ml-1 font-weight-bold" id="section_eval" style="font-size: 13px!important">
                                &nbsp;</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex flex-row text-sm justify-content-end align-items-end">
                            <div class="font-weight-bold ">
                                <button type="button font-weight-bold" class="btn btn-sm btn-primary" id="print_grade_eval">Print</button>
                            </div>
                        </div>
                        <div class="d-flex flex-row text-sm mt-1 pt-1 align-items-end">
                            <div class="font-weight-bold" style="font-size: 12px!important">Curriculum: </div>
                            <div class="ml-1 font-weight-bold" id="curriculum_eval"
                                style="font-size: 13px!important">&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div id="grade_eval_table_container">

                </div>

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="updateCourseModal" aria-labelledby="updateCourseModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCourseModalLabel">Update Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="updateCourseSelect">Select Course</label>
                    <select class="form-control select2" id="updateCourseSelect" data-id=" ">
                        @foreach ($courses as $item)
                            <option value="{{ $item->id }}">({{ $item->courseabrv }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateCourseSave">Save changes</button>
            </div>
        </div>
    </div>
</div>


@php
    $curriculum = DB::table('college_curriculum')
        ->where('deleted', 0)
        ->select('id', 'courseID', 'curriculumname as text')
        ->get();
@endphp

<div class="modal fade" id="addCurriculumModal" aria-labelledby="addCurriculumModal" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Student Has No Curriculum<i
                        class="fa fa-exclamation-triangle text-warning"></i>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="updateCourseSelect">Select Curriculum</label>
                    <select class="form-control select2" id="curriculum_select" data-id=" ">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addCurriculum">Add Curriculum</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateCurriculumModal" aria-labelledby="updateCurriculumModal" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Update Curriculum<i
                        class="fa fa-exclamation-triangle text-warning"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="updateCourseSelect">Select Curriculum</label>
                    <select class="form-control select2" id="curriculum_select2" data-id=" ">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateCurriculum">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="creditedSubjects" aria-labelledby="creditedSubjects" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content" style="height: 680px!important;">
            <div class="modal-header">
                <h5 class="modal-title" id="">Credited Subjects</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="overflow-y: scroll">
                <button type="button" class="btn btn-sm btn-success" id="addCreditedSubject">Add Credited
                    Subject</button>
                <hr>
                <div id="credited_table">
                    <div class="no_credit text-center">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="creditedSubjectList" aria-labelledby="creditedSubjectList" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content" style="height: 640px!important;">
            <div class="modal-header">
                <h5 class="modal-title" id="">Credited Subjects Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="overflow-y: scroll">
                <div class="row">
                    <div class="col-md-7">
                        <div>
                            <label for="" class="text-sm">School Name</label>
                            <input type="text" class="form-control form-control-sm " id="school_name_credit">
                        </div>
                        <div>
                            <label for="" class="text-sm">School Address</label>
                            <input type="text" class="form-control form-control-sm " id="school_address_credit">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div>
                            <label for="" class="text-sm">School Year</label>
                            <input type="text" class="form-control form-control-sm " id="school_year_credit">
                        </div>
                        <div>
                            <button class="btn btn-sm btn-primary text-right d-none" id="updateCreditedSchool"
                                style="margin-top: 1.95rem !important">Update School Info</button>
                        </div>
                    </div>
                </div>
                <div id="creditedSubjectListHolder" class="mt-2">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="yourModalId" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <h5 class="modal-title text-warning text-center"><i class="fas fa-exclamation-triangle"></i> Conflict
                Schedule</h5>

            <span class="modal-title" style="font-size: 16px; margin: 10px">
                <strong><u><i id="conflictedSubject"
                            style="text-decoration-color:green; color:green"></i></u></strong> is in conflict with
                other schedules
            </span>
            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center">Conflict Details</th>
                        </tr>
                        <tr style="font-size:10px">
                            <th>Code</th>
                            <th>Subject</th>
                            <th>Schedule</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:10px" class="table-group-divider" id="conflictDetails">
                        <td><span class="modal-subject-code"></span></td>
                        <td><span class="modal-subject-desc"></span></td>
                        <td><span class="modal-schedule"></span></td>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end m-2">
                <button type="button" class="btn btn-success mr-2 load-anyways">Load Anyways</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="credit_prospectus" aria-labelledby="credit_prospectus" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Credit to:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="">
                <div>
                    <label for="" class="text-sm">Prospectus Subject</label>
                    <select class="form-control select2" id="credit_prospectus_subject" data-id=" ">
                    </select>
                </div>
                <div class="d-flex justify-content-end m-2">
                    <button type="button" class="btn btn-success mr-2" id="credit_to_prospectus_subject">Credit
                        Subject</button>
                </div>
            </div>
        </div>
    </div>
</div> --}}


<script>
    var eval_name;
    var eval_course;
    var eval_section;
    var eval_curriculum;
    var eval_gradelevel;
    var eval_courseID;
    var curr_id;
    var levelid;

    $('#curriculum_select').select2()

    var curr_select = @json($curriculum)



    $('#loadSubjects').click(function () {
    console.log('wewew');

    var sectionId = $('#StudentSection').val();
    $('#modal-overlay').modal('show');

    // Setting data attribute correctly
    $('#loadsubjects').data('data-id', sectionId);

    setTimeout(function () {
        loadSectionSchedule('all');
    }, 500);

    // Disabling remove buttons for added subjects
    $.each(addedSubj, function (index, subject) {
        $('.sectionTablesload .remove-sched[data-schedid="' + subject + '"]').addClass('disabled');
    });
});

    $(document).on('click', '#printCOR', function() {
        var studentId = $('#studentLoadingModal').data('student-id');
        var syid = $('#filter_sy').val();
        var semid = $('#filter_semester').val();

        console.log('eeeeeehh');

        // AJAX request to check if the student is enrolled
        $.ajax({
            url: `/check-enrollment-status/`,
            method: 'GET',
            data: {
                studentId: studentId,
                syid: syid,
                semid: semid
            },
            success: function(response) {
                if (response.isEnrolled) {
                    window.open(
                        `/student/loading/print/student-loading/${studentId}/${syid}/${semid}`,
                        '_blank'
                    );
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Student Not Enrolled',
                        text: 'The selected student is not enrolled for this term. Please verify enrollment details or contact administration for assistance.',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false,
                        iconColor: '#f39c12',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error checking the enrollment status. Please try again later.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });


    $('#saveStudentLoading').click(function() {
        var data = [];
        var syid = $('#filter_sy').val();
        var semid = $('#filter_semester').val();
        var studentId = $(this).attr('data-id');

        $('#subjectsTableBody tr').each(function() {
            var schedid = $(this).attr('data-schedid');
            var sectionId = $(this).attr('data-section-id');
            var subjid = $(this).attr('data-subject-id');
            data.push({
                'schedid': schedid,
                'studid': studentId,
                'subjid': subjid,
                'sectionid': sectionId,
                'syid': syid,
                'semid': semid
            });
        });

        console.log(data, 'data');
        if (data.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No subjects',
                text: 'No subjects being loaded',
                showConfirmButton: true,
                confirmButtonText: 'Ok'
            });
            return;
        }

        $.ajax({
            url: '/student/loading/save-loaded-subjects',
            method: 'POST',
            data: {
                studentId: studentId,
                subjects: data,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    type: 'success',
                    title: 'Success!',
                    text: 'Student loading saved successfully.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    timer: 2000,
                    timerProgressBar: true
                });
                getUpdatedStudentLoading(studentId, 'all', 0);

                recalculateTotalUnits();
                $('#subjectsTableBody').empty();
                load_all_student();

                updateLedger(studentId, syid, semid);

                $('#studentLoadingModalClose').prop('disabled',
                    false); // Allow closing modal after save

            },
            error: function(xhr, status, error) {
                console.error("Error saving student loading:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error saving student loading',
                    showConfirmButton: true,
                    confirmButtonText: 'Ok'
                });
            }
        });
    });



    $(document).on('click', '#studentLoadingModalClose', function() {
        // Remove 'show' class from the modal

        var hasLoadedSubjects = $('#subjectsTableBody tr').length > 0;

        if (hasLoadedSubjects) {
            // Show confirmation dialog if there are unsaved subjects
            Swal.fire({
                icon: 'warning',
                title: 'Unsaved Changes Detected!',
                text: 'You have unsaved subjects loaded. Do you really want to close the modal?',
                showCancelButton: true,
                confirmButtonText: 'Yes, Close it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.value) {
                    $('#studentLoadingModal').removeClass('show');
                    $('#studentLoadingModal').css('display', 'none');
                    $('.modal-backdrop').remove();
                }
            });
        } else {
            $('#studentLoadingModal').removeClass('show');
            $('#studentLoadingModal').css('display', 'none');
            $('.modal-backdrop').remove();

        }
    });

    $('#addCurriculumModal, #updateCurriculumModal').on('show.bs.modal', function(e) {
        $('#curriculum_select').attr('disabled', true);
        setTimeout(function() {
            curriculum()
        }, 500);

    })


    function openStudentInformationModal(studentId) {
        $.ajax({
            url: '/student/loading/Student-Information',
            // async:true,
            method: 'GET',
            data: {
                studentId: studentId,
                syid: $('#filter_sy').val(),
                semid: $('#filter_semester').val()
            },
            dataType: 'json',
            success: function(response) {
                console.log(response, 'response');
                eval_name = response.firstname + ' ' + response.lastname;
                eval_course = response.courseabrv;
                eval_section = response.sectionDesc || '';
                eval_curriculum = response.curriculumname || '';
                eval_gradelevel = response.levelname;
                curr_id = response.curriculumID;
                levelid = response.levelid;
                eval_courseID = response.courseid;
                $('#studentLoadingModalLabel').text(response.firstname + ' ' +
                    response.middlename + ' ' + response.lastname);
                // Populate form fields with student data
                $('#printStudInfo').attr('data-id', studentId);

                $('#studentIdNo').val(response.sid || '');
                $('#firstName').val(response.firstname || '');
                $('#middleName').val(response.middlename || '');
                $('#lastName').val(response.lastname || '');
                $('#suffix').val(response.suffix || '');
                $('#dateOfBirth').val(response.dob || '');
                $('#placeOfBirth').val(response.pob || '');
                $('#gender').val(response.gender || '');
                $('#mobileNumber').val(response.contactno || '');
                $('#emailAddress').val(response.semail || '');
                $('#religion').val(response.religionname || '');
                $('#nationality').val(response.nationality || '');
                $('#permanentAddress').val(response.address || '');
                $('#presentAddress').val(response.address || '');
                $('#studenAcadSection').attr('data-section', response.sectionid)

                // Populate parent information father information
                $('#fatherFirstName').val(response.ffname || '');
                $('#fatherMiddleName').val(response.mmname || '');
                $('#fatherLastName').val(response.flname || '');
                $('#fatherSuffix').val(response.fsuffix || '');
                $('#fContactNumber').val(response.fcontactno || '');
                $('#fOccupation').val(response.foccupation || '');
                $('#fHAE').val(response.fea || '');
                $('#mEthnicity').val(response.fethnicity || '');

                // Populate parent information mother information
                $('#motherFirstName').val(response.mfname || '');
                $('#motherMiddleName').val(response.mmname || '');
                $('#motherLastName').val(response.mlname || '');
                $('#motherSuffix').val(response.msuffix || '');
                $('#mContactNumber').val(response.mcontactno || '');
                $('#mOccupation').val(response.moccupation || '');
                $('#mHAE').val(response.mea || '');
                $('#mEthnicity').val(response.methnicity || '');

                // Populate parent information guardian information
                $('#guardianFirstName').val(response.gfname || '');
                $('#guardianMiddleName').val(response.gmname || '');
                $('#guardianLastName').val(response.glname || '');
                $('#guardianSuffix').val(response.gsuffix || '');
                $('#gContactNumber').val(response.gcontactno || '');
                $('#gOccupation').val(response.goccupation || '');
                $('#gHAE').val(response.gea || '');
                $('#gEthnicity').val(response.gethnicity || '');

                //Last School Attended Information
                $('#lastSchoolAttended').val(response.lastschoolatt || '');
                $('#lastGradeLevelCompleted').val(response.lastschoolsy || '');
                $('#schoolContactNo').val(response.scn || '');
                $('#schoolMailingAddress').val(response.cmaosla || '');

                //Pre-school School Attended Information
                $('#preSchoolName').val(response.psschoolname || '');
                $('#preSchoolYearGraduated').val(response.pssy || '');
                $('#preSchoolType').val(response.psschooltype || '');

                //Grade School Attended Information
                $('#gradeSchoolName').val(response.gsschoolname || '');
                $('#gradeSchoolYearGraduated').val(response.gssy || '');
                $('#gradeSchoolType').val(response.gsschooltype || '');

                //Junior School Attended Information
                $('#juniorHighSchoolName').val(response.jhsschoolname || '');
                $('#juniorHighSchoolYearGraduated').val(response.jhssy || '');
                $('#juniorHighSchoolType').val(response.jhsschooltype || '');

                //Senior School Attended Information
                $('#seniorHighSchoolName').val(response.shsschoolname || '');
                $('#seniorHighSchoolYearGraduated').val(response.shssy || '');
                $('#seniorHighSchoolType').val(response.shsschooltype || '');

                //Medical Information of Student
                // $('#Height').val(response.gsschoolname || '');
                // $('#Weight').val(response.shssy || '');
                // $('#otherMed').val(response.shsschooltype || '');
                // $('#anyAllergies').val(response.gsschoolname || '');
                // $('#medAllergies').val(response.shssy || '');
                // $('#medHistory').val(response.shsschooltype || '');
                // $('#otherMedInfo').val(response.shsschooltype || '');

                // Populate the additional table information
                // $('#studentPhoto').img(response.picurl || '');
                
                $('#studentIdNumber').text(response.sid || '');
                $('#studentLevel').text(response.levelname || '');
                $('#studenAcadSection').text(response.sectionDesc || '');
                $('#studentCourse').text(response.courseDesc || '');
                $('#studentStatus').text(response.studstatus || '');

                $('#sectionScheduleModalLabel').data('data-id-student', studentId);
                
                $('#studentLoadingModal').data('student-id', studentId);
                // Show the modal
                var studentLoadingModal = new bootstrap.Modal(document
                    .getElementById(
                        'studentLoadingModal'));
                studentLoadingModal.show();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching student data:", error);
                alert("Failed to load student data. Please try again later.");
            }
        });
    }



    function curriculum() {
        var cur = curr_select.filter(curr => curr.courseID == eval_courseID)
        $('#curriculum_select').removeAttr('disabled')
        $('#curriculum_select').empty()
        $('#curriculum_select').append(`<option value="">Select Curriculum</option>`)
        $('#curriculum_select2').removeAttr('disabled')
        $('#curriculum_select2').empty()
        $('#curriculum_select2').append(`<option value="">Select Curriculum</option>`)
        $.each(cur, function(key, value) {
            $('#curriculum_select').append(`<option value="${value.id}">${value.text}</option>`)
            $('#curriculum_select2').append(`<option value="${value.id}">${value.text}</option>`)

        })
    }

    $(document).on('click', '#addCurriculum', function() {
        var curr_value = $('#curriculum_select').val()
        if (curr_value == '') {
            Toast.fire({
                type: 'warning',
                title: 'Please select a curriculum'
            })
        } else {
            $.ajax({
                type: 'GET',
                url: '/student/curriculum/add',
                data: {
                    studid: studID,
                    currid: curr_value
                },
                success: function(response) {
                    check_curriculum();
                    $('#addCurriculumModal').modal('hide');
                    show_curr()
                },
            })
        }

    })

    $(document).on('click', '#updateCurriculum', function() {
        var curr_value = $('#curriculum_select2').val()
        if (curr_value == '') {
            Toast.fire({
                type: 'warning',
                title: 'Please select a curriculum'
            })
        } else {
            $.ajax({
                type: 'GET',
                url: '/student/curriculum/update',
                data: {
                    studid: studID,
                    currid: curr_value
                },
                success: function(response) {
                    Toast.fire({
                        type: 'success',
                        title: 'Curriculum updated successfully'
                    })
                    check_curriculum();
                    $('#updateCurriculumModal').modal('hide');
                    show_curr()
                },
            })
        }

    })

    $(document).on('click', '#editCurriculum', function() {
        $('#updateCurriculumModal').modal('show');
    })

    $(document).on('click', '#updateCourse', function() {
        $('#updateCourseModal').modal('show');

        $('#updateCourseSave').off('click').on('click', function() {
            var studentId = $('#studentLoadingModal').data(
                'student-id');
            var courseid = $('#updateCourseSelect').val();


            if (studentId && courseid) {
                swal.fire({
                    title: 'Update Student Course',
                    text: 'Are you sure you want to update the student\'s course?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        updateStudentCourse(studentId, courseid);
                        $('#updateCourseModal').modal(
                            'hide');
                    }
                });
            } else {
                swal.fire({
                    title: 'Error',
                    text: 'Please make sure both student ID and course are selected.',
                    icon: 'error'
                });
            }
        });
    });



    function updateStudentCourse(studentId, courseid) {
        var formData = new FormData();
        formData.append('studentId', studentId);
        formData.append('courseid', courseid);

        $.ajax({
            url: '/student/updateCourse',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $('#studentCourse').text(response.courseDesc || '');
                    swal.fire({
                        title: 'Success',
                        text: response.message || 'Course updated successfully!',
                        icon: 'success'
                    }).then(() => {
                        openStudentInformationModal(
                            studentId
                        )
                    });
                }
            },

            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    swal.fire({
                        title: 'Error',
                        text: value[0],
                        icon: 'error'
                    });
                });
            }
        });
    }

    function checkScheduleConflictsAndDisplay(sectionId, sectionData) {
        return new Promise((resolve, reject) => {
            const promises = [];
            const conflictTable = $('#studentLoadingConflictDisplay');
            conflictTable.empty(); // Clear the table before populating new data.

            sectionData.forEach(function(schedule) {
                const promise = new Promise((resolveInner) => {
                    $.ajax({
                        url: '/student/subject/dean/conflict',
                        method: 'GET',
                        data: {
                            'syid': $('#filter_sy').val(),
                            'semid': $('#filter_semester').val(),
                            'stime': schedule.stime,
                            'etime': schedule.etime,
                            'day': [schedule.dayId],
                            'room': schedule.room,
                            'schedid': schedule.schedid,
                        },
                        success: function(data) {
                            console.log(data, 'sheesahble');

                            if (data && data.length > 0) {
                                data.forEach(conflict => {
                                    // Display the conflict in the table.
                                    const row = `
                                    <tr>
                                        <td>${conflict.subjDesc}</td>
                                        <td>${conflict.subjCode}</td>
                                        <td>${conflict.teachername}</td>
                                        <td>${conflict.roomname}</td>
                                        <td>${conflict.stime} - ${conflict.etime}</td>
                                        <td>${conflict.dayname}</td>
                                    </tr>`;
                                    conflictTable.append(row);
                                });
                            }
                            resolveInner();
                        },
                        error: function(err) {
                            console.error('Error checking conflicts:',
                                err);
                            resolveInner();
                        }
                    });
                });
                promises.push(promise);
            });

            Promise.all(promises).then(() => {
                resolve(sectionData);
                $('#modal-overlay').modal('hide');
                $('#sectionScheduleModal').modal('show');
            });
        });
    }

    $('#grade_evaluation_modal').on('hidden.bs.modal', function(event) {
        $('.eval_modal_table').remove()
    })

    var temp_course = $('#input_course').val()

    $.ajax({
        type: 'GET',
        url: '/student/loading/student/set/course',
        data: {
            studid: stud_id,
            courseid: $('#input_course').val(),
            curriculum: $('#input_curriculum').val(),
            levelid: $('#input_level').val(),
            syid: $('#filter_sy').val(),
            semid: $('#filter_semester').val(),
        },
        success: function(data) {
            if (data[0].status == 1) {

                Toast.fire({
                    type: 'success',
                    title: data[0].data
                })

                temp_course = all_courses.filter(x => x.id == temp_course)

                if (stud_course != null && stud_course != 0) {
                    $('#label_course')[0].innerHTML = '<p class="mb-0">' + $(
                            '#input_course option:selected').text() + '</p>' +
                        '<a href="#" id="button_to_updatestudentcourse_modal"><i class="far fa-edit text-primary"></i> Update Course</a>'

                    $('#label_curriculum').text($(
                        '#input_curriculum option:selected').text())

                } else {
                    $('#label_course')[0].innerHTML = '<p class="mb-0">--</p>' +
                        '<a href="#" id="button_to_updatestudentcourse_modal"><i class="far fa-edit text-primary"></i> Update Course</a>'
                }




                $('#label_college').text(temp_course[0].collegeDesc)

                var student_index = all_students.findIndex(x => x.id == stud_id)

                all_students[student_index].courseid = $('#input_course').val()
                all_students[student_index].courseDesc = temp_course[0].courseDesc
                all_students[student_index].collegeDesc = temp_course[0].collegeDesc
                all_students[student_index].levelid = $('#input_level').val()
                all_students[student_index].levelname = $(
                    '#input_level option:selected').text()

                stud_course = $('#input_course').val()
                sections()
                check_enrollment()

            }
        }
    })
</script>
