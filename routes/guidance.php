<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/admission/{any}', 'GuidanceController\AdmissionController@admission_view');

    Route::get('/entranceexamsetup', 'GuidanceController\AdmissionController@entranceexam_setup');

    Route::view('/evaluation/instructioneval', 'guidanceV2.pages.instruction_eval');
    Route::view('/evaluation/peerevaluation', 'guidanceV2.pages.peer_eval');
    Route::view('/evaluation/setup', 'guidanceV2.pages.peer_eval_setup');

    Route::view('/addpassingrate', 'guidanceV2.pages.admission.addpassingrate');

    Route::get('/add-description', 'GuidanceController\AdmissionController@store_test_direction')->name('store.testdirection');
    Route::post('/add-testquestion', 'GuidanceController\AdmissionController@store_test_question')->name('store.testquestion');
    Route::post('/add-passingrate', 'GuidanceController\AdmissionController@store_passingrate')->name('store.passingrate');
    Route::post('/add-entrance-exam', 'GuidanceController\AdmissionController@store_entrance_exam')->name('store.entranceexam');
    Route::post('/add-examdate', 'GuidanceController\AdmissionController@store_examdate')->name('store.examdate');

    Route::get('/counseling/{any}', 'GuidanceController\CounselController@counsel_view');
    Route::get('/referral', 'GuidanceController\ReferralController@referral_view');
    Route::get('/referral-form', 'GuidanceController\ReferralController@referral_form');
    Route::post('/add-appointment', 'GuidanceController\CounselController@store_appointment')->name('store.appointment');
    Route::post('/add-referralsetup', 'GuidanceController\CounselController@store_referral_setup')->name('store.referralsetup');
    Route::post('/add-referral', 'GuidanceController\ReferralController@store_referral')->name('store.referral');

    Route::get('/get-referralsetup', 'GuidanceController\CounselController@get_referral_setup')->name('get.referralsetup');
    Route::get('/get-student', 'GuidanceController\CounselController@get_student')->name('get.student');
    Route::get('/get-teacher', 'GuidanceController\CounselController@get_teacher')->name('get.teacher');
    Route::get('/delete-referral', 'GuidanceController\ReferralController@delete_referral')->name('delete.referral');
    Route::get('/referrals', 'GuidanceController\ReferralController@referrals')->name('referrals');
    Route::get('/get-referral', 'GuidanceController\ReferralController@get_referral')->name('get.referral');
    Route::get('/acknowledgement', 'GuidanceController\ReferralController@acknowledge_referral')->name('acknowledge.referral');
    Route::get('/done-referral', 'GuidanceController\ReferralController@done_referral')->name('done.referral');
    Route::get('/delete-testquestion', 'GuidanceController\AdmissionController@delete_test_question')->name('delete.testquestion');

    Route::get('/get-all-setups', 'GuidanceController\AdmissionController@getAllAdmissionSetup')->name('get.all.setups');
    Route::get('/delete-passingrate', 'GuidanceController\AdmissionController@delete_passingrate')->name('delete.passingrate');
    Route::get('/edit/passingrate', 'GuidanceController\AdmissionController@edit_passingrate');
    Route::get('/activatePassingrate', 'GuidanceController\AdmissionController@activate_passingrate');

    Route::get('/edit-category', 'GuidanceController\AdmissionController@get_category')->name('get.category');
    Route::get('/update-category', 'GuidanceController\AdmissionController@update_category')->name('update.category');
    Route::get('/getall-category', 'GuidanceController\AdmissionController@allCategories')->name('getall.category');
    Route::get('/delete-category', 'GuidanceController\AdmissionController@delete_category')->name('delete.category');
    Route::get('/store-category', 'GuidanceController\AdmissionController@store_category')->name('store.category');
    Route::post('/update-passingrate', 'GuidanceController\AdmissionController@update_passingrate')->name('update.passingrate');

    Route::get('/get-category-questions', 'GuidanceController\AdmissionController@get_category_questions')->name('get.category.questions');
    Route::get('/edit-question', 'GuidanceController\AdmissionController@edit_question')->name('edit.question');
    Route::get('/update-instruction', 'GuidanceController\AdmissionController@update_instruction')->name('update.instruction');
    Route::post('/update-question', 'GuidanceController\AdmissionController@update_question')->name('update.question');
    Route::get('/delete-question', 'GuidanceController\AdmissionController@delete_question')->name('delete.question');
    Route::get('/store-titledirection', 'GuidanceController\AdmissionController@store_titledirection')->name('store.titledirection');

    Route::get('/delete-examdate', 'GuidanceController\AdmissionController@delete_examdate')->name('delete.examdate');
    Route::get('/edit-examdate', 'GuidanceController\AdmissionController@edit_examdate')->name('edit.examdate');
    Route::post('/update-examdate', 'GuidanceController\AdmissionController@update_examdate')->name('update.examdate');

    Route::get('/highpassers', 'GuidanceController\AdmissionController@getHighPassers')->name('high.passers');

    Route::get('/filter-courseby-department', 'GuidanceController\AdmissionController@filterCourseByDepartment')->name('filter.coursebydepartment');
    Route::get('/getAllTeachers', 'GuidanceController\AdmissionController@allTeachers')->name('getAllTeachers');

    Route::get('/getAllEvalCriteria', 'GuidanceController\CounselController@getAllEvalCriteria')->name('getAllEvalCriteria');
    Route::get('/getAllEvalLikert', 'GuidanceController\CounselController@getAllEvalLikert')->name('getAllEvalLikert');
    Route::get('/storeEvalCriteria', 'GuidanceController\CounselController@storeEvalCriteria')->name('storeEvalCriteria');
    Route::get('/storeLikert', 'GuidanceController\CounselController@storeLikert')->name('storeLikert');
    Route::get('/removeCriteria', 'GuidanceController\CounselController@removeCriteria')->name('removeCriteria');
    Route::get('/removeLikert', 'GuidanceController\CounselController@removeLikert')->name('removeLikert');

    // Video Conference
    Route::get('/video-conference', 'VideoConferenceController@index');
    Route::get('/virtualrooms', 'VideoConferenceController@virtualRooms')->name('virtualrooms');
    Route::post('/createvirtualroom', 'VideoConferenceController@createVirtualRoom')->name('createvirtualroom');
    Route::post('/deletevirtualroom', 'VideoConferenceController@deleteVirtualRoom')->name('deletevirtualroom');
    Route::get('/checkvirtualroom', 'VideoConferenceController@checkVirtualRoom')->name('checkvirtualroom');



});
