<?php
use Illuminate\Support\Facades\Route;

Route::get('/submit-pooling', 'GuidanceController\AdmissionController@submit_pooling')->name('submit.pooling');
Route::view('/pooling-entry', 'admission.pages.poolingentry');
Route::get('/gototest', 'GuidanceController\AdmissionController@gototest');
Route::get('/submit-test', 'GuidanceController\AdmissionController@submit_test')->name('submit.test');
Route::get('/checkifdonetest', 'GuidanceController\AdmissionController@checkifdonetest')->name('checkifdonetest');
Route::get('/diagnostictest', 'GuidanceController\AdmissionController@diagnostic_view');
Route::get('/filter-examdate', 'GuidanceController\AdmissionController@filter_examdates')->name('filter.examdate');
Route::get('/filter-acadprog', 'GuidanceController\AdmissionController@filter_acadprog')->name('filter.acadprog');
Route::get('/filter-examsetup', 'GuidanceController\AdmissionController@filter_examsetup')->name('filter.examsetup');
Route::post('/save-student-info', 'GuidanceController\AdmissionController@student_info_save')->name('student.info.save');
Route::post('/update-student-info', 'GuidanceController\AdmissionController@student_info_update')->name('student.info.update');
Route::get('/find-examdate', 'GuidanceController\AdmissionController@find_examdate')->name('find.examdate');
Route::get('/save-answer', 'GuidanceController\AdmissionController@save_answer')->name('save.answer');
// STUDENT PREREGISTER
Route::view('/prereg', 'admission.pages.prereg-applicant')->name('prereg.applicant');

// VERIFY POOLING NUMBER
Route::get('/verify-pooling', 'GuidanceController\AdmissionController@verify_pooling')->name('verify.pooling');

Route::middleware(['auth'])->group(function () {
    Route::get('/applicant/{any}', 'GuidanceController\AdmissionController@applicant_view');
    Route::get('/get-applicants', 'GuidanceController\AdmissionController@get_applicants')->name('get.applicants');
    Route::get('/edit-applicant', 'GuidanceController\AdmissionController@edit_applicant');
    Route::get('/admissionresult', 'GuidanceController\AdmissionController@admission_result');
    Route::get('/update-test-question', 'GuidanceController\AdmissionController@update_test_question')->name('update.test.question');
    Route::get('/viewresult', 'GuidanceController\AdmissionController@view_result');
    Route::get('/retaketest', 'GuidanceController\AdmissionController@retake_test');
    Route::get('/accept-student', 'GuidanceController\AdmissionController@accept_student');
    Route::get('/decline-student', 'GuidanceController\AdmissionController@decline_student');
    Route::get('/verify-student', 'GuidanceController\AdmissionController@verify_student');
    Route::get('/getallresults', 'GuidanceController\AdmissionController@admission_getall_result');
    Route::get('/delete-applicant', 'GuidanceController\AdmissionController@delete_applicant')->name('delete.applicant');

    Route::post('/add-criteria', 'GuidanceController\AdmissionController@store_criteria')->name('criteria.store');
    // Route::post('/update-criteria', 'GuidanceController\AdmissionController@update_criteria')->name('criteria.update');
    Route::post('/delete-criteria', 'GuidanceController\AdmissionController@delete_criteria')->name('criteria.delete');
    Route::get('/all-criteria', 'GuidanceController\AdmissionController@all_criteria')->name('criteria.all');
    Route::get('/show-subcriteria', 'GuidanceController\AdmissionController@show_subcriteria')->name('subcriteria.show');
    Route::post('/store-student-criteria', 'GuidanceController\AdmissionController@storeStudentCriteria')->name('store.student.criteria');
    Route::get('/answer-history', 'GuidanceController\AdmissionController@getAnswerHistory')->name('answer.history');
    Route::get('/getAllSubject', 'GuidanceController\AdmissionController@getAllSubject')->name('getAllSubject');

    Route::get('/admission-waitlist-report_pdf', 'GuidanceController\AdmissionController@generatePdf');

});
