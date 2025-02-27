<?php

use Illuminate\Support\Facades\Route;

// Route::get('/hasher', function () {
//     return Hash::make('_20EsEs15_');

// });

// General Config/tesda/getStudent
Route::get('coursesSetup', 'TesdaController\GeneralConfigController@tesda_courses_setup');
Route::get('gradingSetup', 'TesdaController\GeneralConfigController@tesda_grading_setup');
Route::get('gradeTransmutation', 'TesdaController\GeneralConfigController@tesda_grade_transmutation');
Route::get('batches', 'TesdaController\GeneralConfigController@tesda_batches');

// Student Information
Route::get('enrollment', 'TesdaController\StudentController@tesda_enrollment');
Route::get('studentInfo', 'TesdaController\StudentController@tesda_studentInfo');
Route::post('/addStudent', 'TesdaController\TesdaStudentInformationController@AddStudentInformation');
Route::get('/getStudent', 'TesdaController\TesdaStudentInformationController@GetStudentInformation');
Route::get('/enroll/{id}', 'TesdaController\TesdaStudentInformationController@GetStudentInformationToEnroll');
Route::get('/studentInformation/{id}', 'TesdaController\TesdaStudentInformationController@StudentInformation');
Route::get('/studentCOR/{id}', 'TesdaController\TesdaStudentInformationController@studentCOR');
Route::get('/printCOR/{id}', 'TesdaController\TesdaStudentInformationController@tesdaPrintCor');
Route::get('/printTOR/{id}', 'TesdaController\TesdaStudentInformationController@tesdaPrintTor');

Route::get('/printCORall', 'TesdaController\TesdaStudentInformationController@tesdaPrintCor');

Route::get('/printTORall', 'TesdaController\TesdaStudentInformationController@tesdaPrintTorAll');

Route::post('/enrollment/save', 'TesdaController\StudentController@tesda_student_enrollment');
Route::get('/enrollment-history/{id}', 'TesdaController\TesdaStudentInformationController@student_enrollment_history');
Route::delete('/delete-student/{id}', 'TesdaController\TesdaStudentInformationController@delete_student');
Route::put('/update-student-enrolled/{id}', 'TesdaController\TesdaStudentInformationController@update_student_enrolled');
Route::get('/signatories', 'TesdaController\TesdaStudentInformationController@signatories');
Route::delete('/delete-signatories/{id}', 'TesdaController\TesdaStudentInformationController@delete_signatory');
Route::get('/delete-signatories/{id}', 'TesdaController\TesdaStudentInformationController@delete_signatory');
Route::get('/update/signatories', 'TesdaController\TesdaStudentInformationController@updateOrAddSignatories');


// Grade Status
Route::get('gradeStatus', 'TesdaController\GeneralConfigController@tesda_gradeStatus');



Route::post('/add-grading', 'TesdaController\TesdaGradingSetupController@tesda_gradeStatus');
Route::get('/add-grading', 'TesdaController\TesdaGradingSetupController@tesda_gradeStatus');
Route::delete('/delete-grading', 'TesdaController\TesdaGradingSetupController@deleteGradingSetup');
Route::get('/get-grading-details/{id}', 'TesdaController\TesdaGradingSetupController@getGradingDetails');
Route::put('/update-grading/{id}', 'TesdaController\TesdaGradingSetupController@updateGradingDetails');
Route::put('/update-gradingDesc/{id}', 'TesdaController\TesdaGradingSetupController@updateGradingDesc');
Route::get('/display-grading', 'TesdaController\TesdaGradingSetupController@displayGradingSetup');
Route::delete('/delete-gradingrow/{id}', 'TesdaController\TesdaGradingSetupController@deleteGradingSetupRow');
Route::delete('/delete-subgrading/{id}', 'TesdaController\TesdaGradingSetupController@deleteAddedComponent');

// Report
Route::get('cor', 'TesdaController\ReportController@tesda_cor');
Route::get('tor', 'TesdaController\ReportController@tesda_tor');
Route::get('attendanceSummary', 'TesdaController\ReportController@tesda_attendanceSummary');
Route::get('enrollmentSummary', 'TesdaController\ReportController@tesda_enrollmentSummary');
Route::get('applicationForGraduation', 'TesdaController\ReportController@tesda_applicationForGraduation');
Route::get('certifications', 'TesdaController\ReportController@tesda_certifications');
Route::post('/certifications/enrollment/get', 'TesdaController\ReportController@tesda_certifications_enrollment_get');

Route::view('tesdaPrintCompletionCert', 'tesda.pages.report.tesdaPrintCompletionCert');
Route::get('/printCertCompletion/{id}', 'TesdaController\ReportController@tesdaPrintCertCompletion');
Route::get('/printCertCompletionAll', 'TesdaController\ReportController@tesdaPrintCertCompletionAll');
Route::get('/printApplicationForGraduation', 'TesdaController\ReportController@printApplicationForGraduation');
Route::get('/printSpecialOrderReport', 'TesdaController\ReportController@printSpecialOrderRequest');

Route::view('tesdaPrintNationalCert', 'tesda.pages.report.tesdaPrintNationalCert');

Route::view('tesdaPrintApplicationGraduation', 'tesda.pages.report.tesdaPrintApplicationGraduation');

//Course Setup
Route::get('/course_setup/get/course_type', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_course_type');
Route::get('/course_setup/add/course_type', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_add_course_type');
Route::get('/course_setup/add/course', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_add_course');
Route::get('/course_setup/get/courses', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_courses');
Route::get('/course_setup/delete/course', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_delete_courses');
Route::get('/course_setup/get/course', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_course_edit');
Route::get('/course_setup/update/course', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_course_update');
Route::get('/course_setup/add/series', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_add_series');
Route::get('/course_setup/get/series', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_series');
Route::get('/course_setup/update/series/active', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_update_series_active');
Route::get('/course_setup/add/competency', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_add_competency');
Route::get('/course_setup/get/competency_type', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_competency_type');
Route::get('/course_setup/get/competencies', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_competencies');
Route::get('/course_setup/get/competency', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_competency');
Route::get('/course_setup/delete/competency', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_delete_competency');
Route::get('/course_setup/update/competency', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_update_competency');
Route::get('/course_setup/update/signatories', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_update_signatories');
Route::get('/course_setup/get/signatories', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_get_signatories');
Route::get('/course_setup/remove/signatory', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_remove_signatories');
Route::get('/course_setup/update/series', 'TesdaController\TesdaCourseSetupController@tesda_courses_setup_update_series');
//Course Setup

//Batch Setup
Route::get('/batch_setup/add/batch', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_add_batch');
Route::get('/batch_setup/get/batches', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_get_batches');
Route::get('/batch_setup/get/batch', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_get_batch');
Route::get('/batch_setup/edit/batch', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_edit_batch');
Route::get('/batch_setup/delete/batch', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_delete_batch');
Route::get('/batch_setup/get/batches/duration', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_get_batches_duration');
Route::get('/batch_setup/update/batch/building', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_update_batches_building');
Route::get('/batch_setup/update/batch/room', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_update_batches_room');
Route::get('/batch_setup/update/batch/duration', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_update_batches_duration');
Route::get('/batch_setup/update/batch/capacity', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_update_batches_capacity');
Route::get('/batch_setup/add/batch/schedule/{batch_id}', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_add_competency_schedule');
Route::get('/batch_setup/get/batch/schedule', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_get_competency_schedule');
Route::get('/batch_setup/add/batch/scheduledetail', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_add_competency_schedule_detail');
Route::get('/batch_setup/get/batch/scheduledetail', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_get_competency_schedule_detail');
Route::get('/batch_setup/update/batch/scheduledetail', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_update_competency_schedule_detail');
Route::get('/batch_setup/delete/batch/scheduledetail', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_delete_competency_schedule_detail');
Route::get('/batch_setup/get/course_series', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_get_course_series');
Route::get('/batch_setup/get/trainers', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_get_trainers');
Route::get('/batch_setup/get/trainer/scheds', 'TesdaController\TesdaBatchSetupController@tesda_batch_setup_get_trainer_schedule');
Route::get('/batch_setup/get/ecr_template', 'TesdaController\TesdaBatchSetupController@get_ecr_templates');
//Batch Setup

//grade point equivalency setup
Route::get('/setup/gradepointequivalency/create', 'TesdaController\GeneralConfigController@tesda_create_gradepointEquivalency');
Route::get('/setup/gradepointequivalency/fetch', 'TesdaController\GeneralConfigController@tesda_fetch_gradepointEquivalency');
Route::get('/setup/selectedgradepointequivalency/fetch', 'TesdaController\GeneralConfigController@tesda_fetch_selected_grade_point_equivalence');
Route::post('/setup/gradepointequivalency/update', 'TesdaController\GeneralConfigController@tesda_update_gradepointEquivalency');
Route::get('/setup/gradepointequivalency/remove', 'TesdaController\GeneralConfigController@tesda_delete_gradepointEquivalency');

//grade transmutation
Route::view('/setup/gradetransmutation/index', 'tesda.pages.general_config.tesda_grade_equivalency');
Route::get('/setup/gradetransmutation/create', 'TesdaController\GeneralConfigController@tesda_create_gradepointTransmutation');
Route::get('/setup/gradetransmutation/fetch', 'TesdaController\GeneralConfigController@tesda_fetch_gradepointTransmutation');
Route::get('/setup/selectedgradetransmutation/fetch', 'TesdaController\GeneralConfigController@tesda_fetch_selected_grade_transmutation');
Route::post('/setup/selectedgradetransmutation/update', 'TesdaController\GeneralConfigController@tesda_update_grade_transmutation');
Route::get('/setup/gradetransmutation/remove', 'TesdaController\GeneralConfigController@tesda_delete_gradepointTransmutation');


//Home Route
Route::view('tesda/home', 'tesda.pages.home');
//SideNav SETUP
Route::view('/setup', 'tesda.pages.setup');
//SideNav Student
Route::view('/studentmanagement', 'tesda.pages.studentmanagement');
//SideNav Grade Status
Route::view('/gradestatus_nav', 'tesda.pages.gradestatus_nav');
//SideNav Grade Status
Route::view('/reports', 'tesda.pages.reports');


// ENROLLMENT
Route::post('/enrollment/get', 'TesdaController\StudentController@tesda_enrollment_get');
Route::get('/studinfo/get', 'TesdaController\StudentController@tesda_studinfo_get');
Route::post('/enrollment/save', 'TesdaController\StudentController@tesda_enrollment_save');
Route::post('/enrollment/delete', 'TesdaController\StudentController@tesda_enrollment_delete');
Route::get('/seedtesda', function () {
    // OPTIONAL: Restrict access (adjust logic as needed)
    if (!Auth::check()) {
        abort(403, 'Unauthorized');
    }

    // Run database seeder
    Artisan::call('db:seed --force');

    return 'TesdaSeeder has been executed successfully!';
});


// PDf NATIONAL CERTIFICATE
Route::get('/pdf/national_certificate', 'TesdaController\TesdaCertificationController@exportNationalCertToPdf');
Route::get('/pdf/honorable_dismissal/{id}', 'TesdaController\TesdaCertificationController@exportHonorableDismissalCertToPdf');
Route::get('/pdf/honorable_dismissal_all', 'TesdaController\TesdaCertificationController@exportHonorableDismissalCertToPdf');

// ENROLLMENT SUMMARY
Route::get('/enrollmentsummary', 'TesdaController\StudentController@tesda_enrollment_summary');
Route::post('/allownodp', 'TesdaController\StudentController@tesda_allownodp');

//Trainers
Route::view('/tesda_trainer/scheduling', 'tesda_trainer.pages.scheduling');
Route::get('/trainer/schedule/get', 'TesdaController\TesdaTrainerController@get_trainer_schedule');
Route::view('/trainer/grading', 'tesda_trainer.pages.grading');
Route::get('/trainer/systemgrading/{schedid}', 'TesdaController\TesdaTrainerController@view_system_grading');
Route::get('/trainer/schedule/getscheddetails', 'TesdaController\TesdaTrainerController@get_sched_details');
Route::get('/trainer/display/ecrtable', 'TesdaController\TesdaTrainerController@display_ecr_template');
Route::post('/trainer/systemgrades/save_grades', 'TesdaController\TesdaTrainerController@save_grades');
Route::get('/trainer/systemgrades/get_grades', 'TesdaController\TesdaTrainerController@display_term_grades');
Route::post('/trainer/systemgrades/submit_grades', 'TesdaController\TesdaTrainerController@submit_grades');
