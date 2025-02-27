<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use BigBlueButton\Parameters\CreateMeetingParameters;

Route::get('/cklmsmeet', 'ExternalApiController@cklmsmeet');

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

Route::get('/forcelogout', function () {
    Auth::logout();
    Session::flush();
    return redirect('/login');
});





//All API
Route::get('enrollmentsetup/list', 'EnrollmentSetupController@list')->name('list');
Route::get('college/teacher/schedule', 'CTController\CTController@ci_schedule');

Route::get('gradesdetail/update', 'TeacherControllers\TeacherGradingV2@udpate_grade_detail')->name('udpate_grade_detial');
Route::get('gradesheader/update', 'TeacherControllers\TeacherGradingV2@udpate_grade_header')->name('udpate_grade_header');

Route::middleware(['auth'])->group(function () {

    Route::get('/current/billingassesment', 'StudentControllers\EnrollmentInformation@billingassesment');
    Route::get('/current/schedule', 'StudentControllers\EnrollmentInformation@schedule');
    Route::get('/current/enrollment', 'StudentControllers\EnrollmentInformation@enrollment');

    Route::get('/student/enrollment/record/cashtrans/transactions', 'StudentControllers\EnrollmentInformation@cashier_transactions');
    Route::get('/student/enrollment/record/online/payment', 'StudentControllers\EnrollmentInformation@uploaded_payments');

    Route::view('/payment', 'studentPortal.pages.cashupload');
    Route::get('/payment/balanceinfo', 'StudentControllers\BillingInformationController@enrollment_history');

    Route::view('/student/enrollment/record/reportcard', 'studentPortal.pages.s_grades');
    Route::view('/student/enrollment/record/billinginformation', 'studentPortal.pages.billingInformation');
    Route::view('/student/enrollment/record/cashier', 'studentPortal.pages.cashtrans');
    Route::view('/student/enrollment/record/online', 'studentPortal.pages.cashtransonline');
    Route::view('/student/enrollment/record/classschedule', 'studentPortal.pages.s_classschedule');
    Route::view('/student/enrollment/record/profile', 'studentPortal.pages.s_profile');

    Route::get('/student/enrollment/record/profile/info', 'StudentControllers\EnrollmentInformation@enrollment_student_information');
    Route::post('/student/enrollment/record/profile/update/photo', 'StudentControllers\EnrollmentInformation@upload_photo');

    Route::get('/ledger', 'StudentControllers\EnrollmentInformation@student_ledger');
    Route::post('/payment/upload', 'StudentControllers\EnrollmentInformation@send_payment');
    Route::get('/calendar', 'StudentControllers\EnrollmentInformation@school_calendar');

    Route::get('/student/enrollment/record/classschedule/list', 'StudentControllers\EnrollmentInformation@class_schedule');
    Route::get('/student/enrollment/record/gradelevelsection', 'StudentControllers\EnrollmentInformation@grade_level_section');

    Route::get('/student/enrollment/record/reportcard/grades', 'StudentControllers\EnrollmentInformation@enrollment_reportcard');
    Route::get('/student/enrollment/record/reportcard/observedvalues', 'StudentControllers\EnrollmentInformation@observedvalues');
    Route::get('/student/enrollment/record/reportcard/attendance', 'StudentControllers\EnrollmentInformation@attendance');

});


Route::middleware(['auth'])->group(function () {

    Route::view('/documenttracking', 'general.documenttracking.pages.DocumentTracking-creator', []);

});



//Student
Route::middleware(['auth', 'isStudent'])->group(function () {

    Route::get('/studentgrades', 'StudentControllers\StudentController@loadGrades')->name('loadGrades');
    Route::get('/gradeannouncement/{id}', 'StudentControllers\StudentController@updategradenotification')->name('updategradenotification');

    Route::get('studentSchedule', 'StudentControllers\StudentController@loadStudentSchedule')->name('loadSchedule');

    Route::get('/studentgetevent', 'StudentControllers\StudentController@loadCalendar')->name('studentgetevent');
    Route::get('/studentgeteventtype', 'StudentControllers\StudentController@loadCalendar')->name('studentgeteventtype');
    Route::get('/schoolCalendar', 'StudentControllers\StudentController@loadCalendar')->name('loadCalendar');
    Route::get('/studentinformation', 'StudentControllers\StudentController@loadProfile')->name('loadProfile');

    Route::get('/insertEvent', 'StudentControllers\StudentController@insertEvent')->name('insertEvent');
    Route::get('/viewAnnouncement/{id}', 'StudentControllers\StudentController@viewAnnouncement');
    Route::get('/viewAllAnnouncement', 'StudentControllers\StudentController@viewAllAnnouncement');
    Route::get('/student/billing', 'StudentControllers\StudentController@loadBilling');
    Route::get('/homeevent/{id}', 'StudentControllers\StudentSeverEventController@homeevent');
    Route::get('/tapstate/{id}', 'StudentControllers\StudentSeverEventController@tapstate');
    Route::get('/loadAllNotification', 'StudentControllers\StudentSeverEventController@loadAllNotification');
    Route::get('/notifications', 'StudentControllers\StudentSeverEventController@loadNotifications');
    Route::get('/countUnreadNotifictions', 'StudentControllers\StudentSeverEventController@countUnreadNotifictions');

    Route::get('/subjectattendanceevent/{id}/{sectionid}/{blockid}', 'StudentControllers\StudentSeverEventController@subjectattendanceevent');
    Route::get('/student/teacherevaluation', 'StudentControllers\StudentController@teacherevaluation');

    Route::get('/student/requirements/index', 'RegistrarControllers\StudentRequirementsController@studentreqsindex')->name('studentreqsindex');
    Route::post('/student/studentrequirementsuploadphoto', 'RegistrarControllers\StudentRequirementsController@studentrequirementsuploadphoto')->name('studentrequirementsuploadphoto');
    Route::get('/student/studentrequirementsdeletephoto', 'RegistrarControllers\StudentRequirementsController@studentrequirementsdeletephoto')->name('studentrequirementsdeletephoto');

    Route::get('/student/apptes/index', 'ApplicationFormsController@apptesindex')->name('apptesindex');
    Route::get('/student/apptes/update', 'ApplicationFormsController@apptesupdate')->name('apptesupdate');
    Route::get('/student/apptes/submit', 'ApplicationFormsController@apptessubmit')->name('apptessubmit');
    Route::get('/student/apptes/export', 'ApplicationFormsController@apptesexport')->name('apptesexport');

    Route::get('/student/college/enrollment/record', 'StudentControllers\StudentController@college_enrollment');
    Route::get('/student/college/enrollment/record/subject', 'StudentControllers\StudentController@subject_enrollment');
    Route::get('/student/college/grades', 'StudentControllers\StudentController@student_grade');
    Route::get('/student/college/billing', 'StudentControllers\StudentController@student_billing');
    Route::get('/student/college/ledger', 'StudentControllers\StudentController@student_student_ledger');
    Route::get('/student/college/previousbalance', 'StudentControllers\StudentController@previous_balance');

    //student preregistration
    Route::get('/student/preenrollment/personalinfo', 'StudentControllers\StudentController@personalinfo');
    Route::get('/student/preenrollment/checktype', 'StudentControllers\StudentController@check_student_type');
    Route::get('/student/preenrollment/infoupdate', 'StudentControllers\StudentController@check_infoupdate');
    Route::get('/student/preenrollment/submitinfo', 'StudentControllers\StudentController@submitinfo');
    Route::get('/student/preenrollment', 'StudentControllers\StudentController@student_preenrollment');
    Route::get('/student/preenrollment/list', 'StudentControllers\StudentController@student_preenrollment_list');
    Route::post('/student/preenrollment/submit', 'StudentControllers\StudentController@student_preenrollment_submit');
    //student preregistration

    //enrollment record basic ed
    Route::get('/student/enrollment/record', 'StudentControllers\StudentController@enrollment_record');
    Route::get('/student/enrollment/record/grades', 'StudentControllers\StudentController@enrollment_grades');
    Route::get('/student/enrollment/record/attendance', 'StudentControllers\StudentController@enrollment_attendance');
    Route::get('/student/enrollment/record/subjects', 'StudentControllers\StudentController@enrollment_subjects');
    Route::get('/student/enrollment/record/grades', 'StudentControllers\StudentController@enrollment_grades');
    //enrollment record basic ed

    //student preregistration
    Route::get('/student/preenrollment/personalinfo', 'StudentControllers\StudentController@personalinfo');
    Route::get('/student/preenrollment/infoupdate', 'StudentControllers\StudentController@check_infoupdate');
    Route::get('/student/preenrollment/submitinfo', 'StudentControllers\StudentController@submitinfo');
    Route::get('/student/preenrollment', 'StudentControllers\StudentController@student_preenrollment');
    Route::get('/student/preenrollment/list', 'StudentControllers\StudentController@student_preenrollment_list');
    Route::post('/student/preenrollment/submit', 'StudentControllers\StudentController@student_preenrollment_submit');
    //student preregistration

    //student online payments
    Route::get('/student/onlinepayment/list', 'StudentControllers\StudentController@student_onlinepayment_list');
    //student online payments


    Route::get('/student/requirements/list', 'StudentControllers\StudentController@student_requirement_list');
    Route::get('/student/get/downpayment', 'StudentControllers\StudentController@get_dp');
    Route::get('/student/get/balanceforward', 'StudentControllers\StudentController@bal_forward');


});

Route::get('/student/submit/form', 'StudentControllers\StudentController@submitSurvey');
Route::get('/student/view/surveyForm', 'StudentControllers\StudentController@surveyForm');

Route::get('/testingesayloading', 'ParentControllers\ParentsController@testing');
Route::post('/parent/update/studpic', 'ParentControllers\ParentsController@updateStudPic')->name('updateStudPic');

//enrollment record basic ed
Route::get('/parent/enrollment/record', 'ParentControllers\ParentsController@enrollment_record');
Route::get('/parent/enrollment/record/grades', 'ParentControllers\ParentsController@enrollment_grades');
Route::get('/parent/enrollment/record/attendance', 'ParentControllers\ParentsController@enrollment_attendance');
Route::get('/parent/enrollment/record/subjects', 'ParentControllers\ParentsController@enrollment_subjects');
Route::get('/parent/enrollment/billing', 'ParentControllers\ParentsController@student_billing');
Route::get('/parent/enrollment/ledger', 'ParentControllers\ParentsController@student_student_ledger');
Route::get('/parent/enrollment/previousbalance', 'ParentControllers\ParentsController@previous_balance');
//enrollment record basic ed

//Parents Portal
Route::middleware(['auth', 'isParent'])->group(function () {

    Route::get('/parentgetevent', 'ParentControllers\ParentsController@loadCalendar')->name('parentgetevent');
    Route::get('/parentgeteventtype', 'ParentControllers\ParentsController@loadCalendar')->name('parentgeteventtype');
    Route::get('/parentschoolCalendar', 'ParentControllers\ParentsController@loadCalendar')->name('parentschoolCalendar');

    Route::get('/insertEvent', 'ParentControllers\ParentsController@insertEvent')->name('insertEvent');
    Route::get('/parentsPortalDashboard', 'ParentControllers\ParentsController@loaddDashboard');
    Route::get('/parentsPortalSchedule', 'ParentControllers\ParentsController@loadSchedule');
    Route::get('/parentsPortalGrades', 'ParentControllers\ParentsController@loadGrades');
    Route::get('/parentsgradeannouncement/{id}', 'ParentControllers\ParentsController@updategradenotification')->name('updategradenotification');
    Route::get('/parentsPortalBilling', 'ParentControllers\ParentsController@loadBilling');
    Route::get('/parentsstudentprofile', 'ParentControllers\ParentsController@loadstudentprofile');
    //Announcement
    Route::get('/parentviewAnnouncement/{id}', 'ParentControllers\ParentsController@viewAnnouncement');
    Route::get('/parentviewAllAnnouncement', 'ParentControllers\ParentsController@viewAllAnnouncement');

    Route::get('/parent/submit/form', 'ParentControllers\ParentsController@submitSurvey');
    Route::get('/parent/view/surveyForm', 'ParentControllers\ParentsController@surveyForm');
    //ParentSeverEventController
    Route::get('/parenthomeevent', 'ParentControllers\ParentServerEventController@homeevent');
    Route::get('/parenttapstate', 'ParentControllers\ParentServerEventController@tapstate');
    Route::get('/parentloadAllNotification', 'ParentControllers\ParentServerEventController@loadAllNotification');
    Route::get('/parentnotifications', 'ParentControllers\ParentServerEventController@loadNotifications');
    Route::get('/parentcountUnreadNotifictions', 'ParentControllers\ParentServerEventController@countUnreadNotifictions');

});

Route::get('/getremBill', 'ParentControllers\ParentsController@getremBill');
Route::post('/parentEnterAmount', 'ParentControllers\ParentsController@parentEnterAmount');
Route::get('/parent/onlinepayment', 'ParentControllers\ParentsController@onlinepayment')->name('onlinepayment');


//grading report
Route::get('posting/grade/getstudents', 'TeacherControllers\TeacherGradingV4@get_student');
Route::get('grades/report/mastersheet', 'TeacherControllers\MasterSheetController@mastersheet');
Route::get('grades/report/mastersheet/excel', 'TeacherControllers\MasterSheetController@excel_mastersheet');
Route::get('grades/report/mastersheet/excel/composite', 'TeacherControllers\MasterSheetController@excel_composite');
Route::get('grades/report/mastersheet/gsa', 'TeacherControllers\MasterSheetController@generateGSA');
Route::get('grades/report/mastersheet/lp', 'TeacherControllers\MasterSheetController@generateLP');
Route::get('grades/report/gradingsheet/finalcomposite', 'TeacherControllers\MasterSheetController@finalcomposite');
Route::get('grades/report/gradingsheet/bysubject', 'TeacherControllers\MasterSheetController@bysubject');
Route::get('grades/report/consolodiated', 'TeacherControllers\MasterSheetController@consolidated_pdf');
Route::get('grades/report/gradingsheet/gradestatus', 'TeacherControllers\MasterSheetController@grade_status');
Route::get('grades/report/studentawards', 'TeacherControllers\MasterSheetController@studentawards');
Route::get('grades/report/studentawards/certificate', 'TeacherControllers\MasterSheetController@studentawardscertificate');
// grades/report/gradingsheet
Route::get('grades/report/gradingsheet', 'TeacherControllers\TeacherGradingV4@grading_sheet');
Route::get('grades/report/gradelevel', 'TeacherControllers\TeacherGradingV4@grading_sheet_gradelevel');
//grading report

//grade posting
Route::get('posting/grade', 'TeacherControllers\TeacherGradingV4@grades_posting');

//principal deportment status
Route::get('/posting/grade/deportment-status', 'PrincipalControllers\DeportmentStatus@show');
Route::get('/posting/grade/get-deportment-details', 'PrincipalControllers\DeportmentStatus@get_deportment_details')->name('get.deportment.details');
Route::get('/posting/grade/get-student-list', 'PrincipalControllers\DeportmentStatus@get_student_list')->name('get.student.list');
Route::get('/posting/grade/get-student-status', 'PrincipalControllers\DeportmentStatus@get_student_status')->name('get.student.status');
Route::get('/posting/grade/filter-student-status', 'PrincipalControllers\DeportmentStatus@filter_student_status')->name('filter.student.status');


Route::get('/posting/grade/load-class-table', 'PrincipalControllers\DeportmentStatus@loadtable')->name('load.class.table');
Route::get('/posting/grade/update-grade-status', 'PrincipalControllers\DeportmentStatus@update_gradestatus')->name('update.grade.status');

Route::get('/posting/grade/update-stud-gradstatus', 'PrincipalControllers\DeportmentStatus@update_specific_gradestatus')->name('update.stud.gradstatus');


//sf9 excel setup
Route::middleware(['auth'])->group(function () {
    Route::view('/setup/sf9/excel', 'superadmin.pages.setup.sf9Excel');
    Route::get('/sf9template/excelletters', 'SuperAdminController\Setup\sf9Template@excel_letters');
    Route::get('/sf9template/list', 'SuperAdminController\Setup\sf9Template@sf9template_list');
    Route::get('/sf9template/create', 'SuperAdminController\Setup\sf9Template@sf9template_create');
    Route::get('/sf9template/create/gradelevel', 'SuperAdminController\Setup\sf9Template@sf9template_create_gradelevel');
    Route::get('/sf9template/create/strand', 'SuperAdminController\Setup\sf9Template@sf9template_create_strand');
    Route::get('/sf9template/update', 'SuperAdminController\Setup\sf9Template@sf9template_update');
    Route::post('/sf9template/upload', 'SuperAdminController\Setup\sf9Template@sf9template_upload');
    Route::get('/sf9template/delete', 'SuperAdminController\Setup\sf9Template@sf9template_delete');
    Route::get('/sf9template/delete/gradelevel', 'SuperAdminController\Setup\sf9Template@sf9template_delete_gradelevel');
    Route::get('/sf9template/delete/strand', 'SuperAdminController\Setup\sf9Template@sf9template_delete_strand');
    Route::get('/sf9templatedetail/list', 'SuperAdminController\Setup\sf9Template@sf9templatedetail_list');
    Route::get('/sf9templatedetail/create', 'SuperAdminController\Setup\sf9Template@sf9templatedetail_create');
    Route::get('/sf9templatedetail/update', 'SuperAdminController\Setup\sf9Template@sf9templatedetail_update');
    Route::get('/sf9templatedetail/delete', 'SuperAdminController\Setup\sf9Template@sf9templatedetail_delete');
    Route::get('/sf9templatedetail/copy', 'SuperAdminController\Setup\sf9Template@sf9templatedetail_copy');
    Route::get('/sf9templatedetail/downloadformat', 'SuperAdminController\Setup\sf9Template@downloadformat');
    Route::get('/sf9templateinfo/list', 'SuperAdminController\Setup\sf9Template@sf9templateinfo_list');
});
//sf9 excel setup

Route::middleware(['auth'])->group(function () {
    Route::get('principal/records/enrolledstudents', 'PrincipalControllers\PrincipalControllerV2@get_enrolled_students');
    Route::get('principal/records/schedule', 'PrincipalControllers\PrincipalControllerV2@get_schedule');
    Route::get('principal/records/section/subjects', 'PrincipalControllers\PrincipalControllerV2@get_section_subjects');
    Route::view('/principal/student/list', 'principalsportal.pages.formsf9');
});

Route::middleware(['auth'])->group(function () {
    Route::get('principal/setup/schedule', 'PrincipalControllers\ScheduleController@get_schedule_ajax');
    Route::get('principal/setup/schedule/plot', 'PrincipalControllers\ScheduleController@sched_plot');
    Route::get('principal/setup/schedule/gshs/add', 'PrincipalControllers\ScheduleController@gshs_insert_sched');
    Route::get('principal/setup/schedule/sh/add', 'PrincipalControllers\ScheduleController@sh_insert_sched');
    Route::get('principal/setup/section/print', 'PrincipalControllers\ScheduleController@print_sched');
    Route::get('principal/setup/schedule/get/sched', 'PrincipalControllers\ScheduleController@get_schedule_2');
    Route::get('principal/setup/schedule/removesched', 'PrincipalControllers\ScheduleController@removesched');
    Route::get('principal/setup/schedule/getsubjects', 'PrincipalControllers\ScheduleController@getsubjects');
});

//Principal
Route::middleware(['auth', 'isDefaultPass'])->group(function () {
    Route::get('posting/grade/post', 'TeacherControllers\TeacherGradingV4@post_student_grade');
    Route::get('posting/grade/unpost', 'TeacherControllers\TeacherGradingV4@unpost_student_grade');
    Route::get('posting/grade/pending', 'TeacherControllers\TeacherGradingV4@pending_student_grade');
    Route::get('posting/grade/approve', 'TeacherControllers\TeacherGradingV4@approve_student_grade');

    Route::get('posting/grade/subject/unpost', 'TeacherControllers\TeacherGradingV4@unpost_subject_grade');
    Route::get('posting/grade/subject/post', 'TeacherControllers\TeacherGradingV4@post_subject_grade');
    Route::get('posting/grade/subject/pending', 'TeacherControllers\TeacherGradingV4@pending_subject_grade');
    Route::get('posting/grade/subject/approve', 'TeacherControllers\TeacherGradingV4@approve_subject_grade');

    Route::get('grades/check/invalid/grades', 'TeacherControllers\TeacherGradingV4@invalid_grades');
    Route::get('grades/check/invalid/finalgrade', 'TeacherControllers\TeacherGradingV4@invalid_final_grades');
    Route::get('grades/check/actualgrade', 'TeacherControllers\TeacherGradingV4@checkActualGrades');
    //grade posting

    Route::get('/dynamic_pdf/{id}', 'PrincipalControllers\DynamicPDFController@pdf');
    Route::get('/prinsf9print/{id}', 'PrincipalControllers\DynamicPDFController@sf9pdf');
    Route::get('/sf6pdf', 'PrincipalControllers\DynamicPDFController@sf6pdf');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/studentawards/printcert', 'PrincipalControllers\PrincipalController@print_award');
    Route::get('/prinsf9getstudent', 'PrincipalControllers\PrincipalController@prinsf9getstudent');
    Route::get('/principalAwardsAndRecognitions', 'PrincipalControllers\PrincipalController@principalAwardsAndRecognitions');
    Route::get('/principal/ps/gradestatus/list', 'PrincipalControllers\PrincipalController@ps_gradestatus_list_list');
    Route::get('/principal/ps/gradestatus/create', 'PrincipalControllers\PrincipalController@ps_gradestatus_create');
    Route::get('/principal/ps/gradestatus/update', 'PrincipalControllers\PrincipalController@ps_gradestatus_update');
    Route::get('/principal/ps/gradestatus/delete', 'PrincipalControllers\PrincipalController@ps_gradestatus_delete');
    Route::get('/searchStudentWithHonors', 'PrincipalControllers\PrincipalController@searchStudentWithHonors');
    Route::get('/setup/signatories/list/sf9', 'PrincipalControllers\PrincipalController@list_sf9_signatory');
    Route::get('/setup/signatories/create/sf9', 'PrincipalControllers\PrincipalController@create_sf9_signatory');
    Route::get('/setup/signatories/update/sf9', 'PrincipalControllers\PrincipalController@update_sf9_signatory');
    Route::get('/setup/signatories/delete/sf9', 'PrincipalControllers\PrincipalController@delete_sf9_signatory');
    Route::get('principal/grades/status', 'PrincipalControllers\PrincipalController@grades_status');
    Route::get('principal/section/students/enrolled', 'PrincipalControllers\PrincipalController@enrolled_students');
    Route::get('/principalPortalSectionProfile/{id}', 'PrincipalControllers\PrincipalController@loadSectionProfile')->name('secprof');
    Route::get('principal/records/blockassignment', 'PrincipalControllers\PrincipalController@block_assignment');
    Route::get('principal/records/section/adviser', 'PrincipalControllers\PrincipalController@section_adviser');
});

Route::middleware(['auth'])->group(function () {
    //ADDED BY EUGZ
    //deportment setup
    Route::get('/setup/deportment-setup', 'SuperAdminController\DeportmentSetupController@show');

    Route::get('/setup/deportment-record/create', 'SuperAdminController\DeportmentSetupController@create_deportment')->name('deportment.create');
    Route::get('/setup/deportment-record/edit-deportment', 'SuperAdminController\DeportmentSetupController@edit_deportment')->name('edit.deportment');
    Route::get('/setup/deportment-record/get-edit-deportment', 'SuperAdminController\DeportmentSetupController@get_deportment')->name('get.edit.deportment');
    Route::get('/setup/deportment-record/delete-deportment', 'SuperAdminController\DeportmentSetupController@delete_deportment')->name('delete.deportment');

    Route::get('/setup/deportment-setup/get-all-deportment', 'SuperAdminController\DeportmentSetupController@get_all_deportment')->name('get.all.deportment');
    Route::get('/setup/deportment-setup/get-specific-values', 'SuperAdminController\DeportmentSetupController@get_specific_values')->name('get.specific.values');

    Route::get('/setup/deportment-record/create-values', 'SuperAdminController\DeportmentSetupController@create_values_item')->name('values.create');
    Route::get('/setup/deportment-record/get-deportment-values', 'SuperAdminController\DeportmentSetupController@get_deportment_values')->name('get.deportment.values');
    Route::get('/setup/deportment-record/edit-deportment-values', 'SuperAdminController\DeportmentSetupController@edit_deportment_values')->name('edit.deportment.values');
    Route::get('/setup/deportment-record/delete-deportment-values', 'SuperAdminController\DeportmentSetupController@delete_deportment_values')->name('delete.deportment.values');

    Route::get('/setup/deportment-record/create-more-values', 'SuperAdminController\DeportmentSetupController@create_more_values')->name('values.more');
    Route::get('/setup/deportment-record/get-values', 'SuperAdminController\DeportmentSetupController@get_values_item')->name('get.values');
    Route::get('/setup/deportment-record/edit-values', 'SuperAdminController\DeportmentSetupController@edit_values_item')->name('edit.values');
    Route::get('/setup/deportment-record/delete-values', 'SuperAdminController\DeportmentSetupController@delete_values_item')->name('values.delete');

});

Route::middleware(['cors'])->group(function () {
    Route::get('/checkconnection', function () {
        return 'Connected';
    });

    Route::get('/cklms/fetchinfo', 'SuperAdminController\CklmsController@fetchinfo');
});

//faculty and staff
Route::middleware(['cors'])->group(function () {
    Route::get('/administrator/setup/accounts/create/account', 'AdministratorControllers\FNSAccountController@create_fas_account');
    Route::get('/administrator/setup/accounts/update/privilege', 'AdministratorControllers\FNSAccountController@update_fas_priv_ajax');
    Route::get('/administrator/setup/accounts/update/fasacadprog', 'AdministratorControllers\FNSAccountController@update_fas_acadprog_ajax');
    Route::get('/administrator/setup/accounts/update/information', 'AdministratorControllers\FNSAccountController@update_fas_info');
    Route::get('/administrator/setup/accounts/update/accountinfo', 'AdministratorControllers\FNSAccountController@update_account_info');
    Route::get('/administrator/setup/accounts/update/active', 'AdministratorControllers\FNSAccountController@update_active');
    Route::get('/administrator/setup/accounts/remove', 'AdministratorControllers\FNSAccountController@remove_sched');
    Route::get('/administrator/setup/accounts/getnewinfo', 'AdministratorControllers\FNSAccountController@get_new_info');
    Route::get('/administrator/setup/accounts/getupdated', 'AdministratorControllers\FNSAccountController@get_updated_info');
    Route::get('/administrator/setup/accounts/getdeleted', 'AdministratorControllers\FNSAccountController@get_deleted_info');
    Route::get('/administrator/setup/accounts/updatestat', 'AdministratorControllers\FNSAccountController@get_updatestat');
    Route::get('/administrator/setup/accounts/getmoreinfo', 'AdministratorControllers\FNSAccountController@get_more_info');

    Route::get('/administrator/setup/accounts/synnew', 'AdministratorControllers\FNSAccountController@sync_insert');
    Route::get('/administrator/setup/accounts/syncupdate', 'AdministratorControllers\FNSAccountController@sync_update');
    Route::get('/administrator/setup/accounts/syncdelete', 'AdministratorControllers\FNSAccountController@sync_delete');

    Route::get('/administrator/setup/accounts/updatepass', 'AdministratorControllers\FNSAccountController@change_password');
    Route::get('/administrator/setup/accounts/list', 'AdministratorControllers\FNSAccountController@list');
});
Route::middleware(['auth', 'cors'])->group(function () {
    Route::get('/fns/account', 'AdministratorControllers\FNSAccountController@fas_account');
    Route::get('/updateaccount/name', 'AdministratorControllers\FNSAccountController@update_fas_account_info');

    Route::get('/administrator/setup/accounts/update/principal', 'AdministratorControllers\FNSAccountController@update_fas_principal_ajax');
    Route::get('/administrator/setup/accounts/update/dean', 'AdministratorControllers\FNSAccountController@update_college_dean');
    Route::get('/administrator/setup/accounts/update/chairperson', 'AdministratorControllers\FNSAccountController@update_course_chairperson');
    Route::get('/fas/teachercad/list', 'AdministratorControllers\FNSAccountController@teacher_acadprog');
});
//faculty and staff


//Calendar by Eugz
Route::middleware(['auth'])->group(function () {
    Route::get('/school-calendar', 'SchoolCalendarController@show');
    Route::get('/school-calendar/getall-event/{type}/{syid}', 'SchoolCalendarController@getall_event')->name('getall.event');
    Route::get('/school-calendar/get-event', 'SchoolCalendarController@get_event')->name('get.event');
    Route::get('/school-calendar/add-event', 'SchoolCalendarController@add_event')->name('add.event');
    Route::get('/school-calendar/update-event', 'SchoolCalendarController@update_event')->name('update.event');
    Route::get('/school-calendar/update-event-details', 'SchoolCalendarController@update_event_details')->name('update.event.details');
    Route::get('/school-calendar/edit-event', 'SchoolCalendarController@edit_event')->name('edit.event');
    Route::get('/school-calendar/delete-event', 'SchoolCalendarController@delete_event')->name('delete.event');
    Route::get('/school-calendar/get-select2-gradelevel', 'SchoolCalendarController@get_select2_gradelvl')->name('get.select2.gradelevel');
    Route::get('/school-calendar/get-select2-faculty', 'SchoolCalendarController@get_select2_faculty')->name('get.select2.faculty');
    Route::get('/school-calendar/add-faculty', 'SchoolCalendarController@add_faculty')->name('add.faculty');
    Route::get('/school-calendar/edit-faculty', 'SchoolCalendarController@edit_faculty')->name('edit.faculty');
    Route::get('/school-calendar/delete-faculty', 'SchoolCalendarController@delete_faculty')->name('delete.faculty');
    Route::get('/school-calendar/pdf/{syid}', 'SchoolCalendarController@generatePDF')->name('generate.pdf');
    Route::get('/school-calendar/excel/{syid}', 'SchoolCalendarController@generateExcel')->name('generate.pdf');
    Route::get('/school-calendar/oad_schoolcaltype', 'SchoolCalendarController@load_schoolcaltype');

    // Additional Gian
    Route::get('/school-calendar/loadallholidaytype', 'SchoolCalendarController@loadallholidaytype')->name('loadallholidaytype');
    Route::get('/school-calendar/add-activity', 'SchoolCalendarController@add_activity')->name('add.activity');
    Route::get('/school-calendar/add-holiday', 'SchoolCalendarController@add_holiday')->name('add.holiday');
    Route::get('/school-calendar/delete-holiday', 'SchoolCalendarController@delete_holiday')->name('delete.holiday');
});




Route::middleware(['auth', 'isDefaultPass', 'withSchoolInfo'])->group(function () {
    Route::get('/adminstudentrfidassign/index', 'AdministratorControllers\RfidAssignmentController@adminstudentrfidassignindex');
    Route::get('/adminstudentrfidassign/update', 'AdministratorControllers\RfidAssignmentController@adminstudentrfidassignupdate');
    Route::post('/adminstudentrfidassign/uploadphoto', 'AdministratorControllers\RfidAssignmentController@adminstudentrfidassignuploadphoto');
    Route::post('/adminemployeerfidassign/uploadphoto', 'AdministratorControllers\RfidAssignmentController@adminemployeerfidassignuploadphoto');
    Route::get('/adminstudentrfidassign/reset', 'AdministratorControllers\RfidAssignmentController@adminstudentrfidassignreset');
    Route::get('/adminemployeesetup/index', 'AdministratorControllers\RfidAssignmentController@adminemployeesetupindex');
    Route::get('/adminemployeesetup/update', 'AdministratorControllers\RfidAssignmentController@adminemployeesetupupdate');
    Route::get('/adminemployeesetup/reset', 'AdministratorControllers\RfidAssignmentController@adminemployeesetupreset');
});


Route::get('/reportcard/grades/gradeschool', 'TeacherControllers\TeacherGradingV3@gradeschoolGrading');
Route::get('/reportcard/grades/highschool', 'TeacherControllers\TeacherGradingV3@highSchoolGrading');
Route::get('/reportcard/grades/seniorhigh', 'TeacherControllers\TeacherGradingV3@seniorHighGrading');

Route::middleware(['auth', 'isSuperAdmin:teacher,principal'])->group(function () {

    Route::get('/gradestudent/preschool', 'TeacherControllers\TeacherGradingV3@preschoolGrading');

    Route::get('/reportcard/coreval/seniorhigh', 'TeacherControllers\TeacherGradingV3@Grading');
    Route::get('/reportcard/coreval/gradeschool', 'TeacherControllers\TeacherGradingV3@gradeschoolcoreval');
    Route::get('/reportcard/coreval/highschool', 'TeacherControllers\TeacherGradingV3@highschoolcoreval');
    Route::get('/reportcard/coreval/seniorhigh', 'TeacherControllers\TeacherGradingV3@seniorhighcoreval');
    Route::get('/reportcard/grade/status', 'TeacherControllers\TeacherGradingV3@gradeStatus');

    Route::get('/reportcard/grade/status/submit', 'TeacherControllers\TeacherGradingV3@submitGrade');

    Route::get('/reportcard/generate/status', 'TeacherControllers\TeacherGradingV3@generateStatus');
    Route::get('student/reportcard', 'TeacherControllers\TeacherGradingV3@studentreportcard');

});

Route::get('/reportcard/grade/status/approve', 'TeacherControllers\TeacherGradingV3@approveGrade');
Route::get('/reportcard/grade/status/post', 'TeacherControllers\TeacherGradingV3@postGrade');
Route::get('/reportcard/grade/status/pending', 'TeacherControllers\TeacherGradingV3@pendingGrade');
Route::get('principal/reportcard', 'TeacherControllers\TeacherGradingV3@principalGrading');


Route::middleware(['auth', 'isSuperAdmin:superadmin'])->group(function () {
    Route::view('rfidblade', 'superadmin.pages.rfid.rfidview');
    Route::get('rfid/list', 'SuperAdminController\RegisterRFIDController@rfidcard_list');
    Route::get('rfid/list/download', 'SuperAdminController\RegisterRFIDController@download_rfid');
    Route::post('rfid/list/upload', 'SuperAdminController\RegisterRFIDController@upload_rfid');
    Route::get('rfid/fetch/assigned', 'SuperAdminController\RegisterRFIDController@fetch_assigned_rfid');
    Route::get('rfid/expire/rfid', 'SuperAdminController\RegisterRFIDController@expire_rfid');
    Route::post('storerfid/{id}/{schoolid}', 'SuperAdminController\RegisterRFIDController@storerfid');
    Route::get('rfidcard/summary/regdate', 'SuperAdminController\RegisterRFIDController@date_reg_count');
    Route::get('rfidcard/delete', 'SuperAdminController\RegisterRFIDController@rfid_delete');
});

Route::middleware(['auth', 'isSuperAdmin:superadmin'])->group(function () {

    Route::get('grading/subject/assignment', 'SuperAdminController\GradingSystemV2@subjectAssignment');
    Route::get('grading/subject/assignment/add', 'SuperAdminController\GradingSystemV2@addSubjectAssignment');
    Route::get('grading/subject/assignment/remove', 'SuperAdminController\GradingSystemV2@removeSubjectAssignment');
    Route::get('lockfees', 'FinanceControllers\UtilityController@lockfees')->name('lockfees');

    Route::get('getv1grades', 'SuperAdminController\GradingSystemV2@getv1grades');
    Route::get('transferv1grades', 'SuperAdminController\GradingSystemV2@transferv1grades');

    Route::get('admin/view/teacherEvaluation', 'TeacherControllers\TeacherEvaluations@admin_view_results');

});


Route::get('/teacherevaluation/schedule', 'TeacherControllers\TeacherEvaluations@teachr_schedule');
Route::get('/teacherevaluation/checkEvaluation', 'TeacherControllers\TeacherEvaluations@check_evaluation');

Route::get('student/enrollment/check/duplication', 'SuperAdminController\SuperAdminController@validate_student_name')->name('validate_student_name');
Route::middleware(['auth', 'isSuperAdmin:admin'])->group(function () {
    Route::get('/superadmin/view/schoolinfo', 'SuperAdminController\SuperAdminController@viewschoolinfo');
    Route::post('/sysadmin/updatemodulestatus ', 'SuperAdminController\SuperAdminController@updatemodulestatus');
    Route::get('/admin/update/school/setup', 'SuperAdminController\SuperAdminController@updatesetup');
    Route::post('/admin/update/school/terms', 'SuperAdminController\SuperAdminController@updateterms');
    Route::get('generateAdminPass', 'SuperAdminController\SuperAdminController@generateAdminPass');
    Route::get('generateAdminAdminPass', 'SuperAdminController\SuperAdminController@generateAdminAdminPass');
    Route::get('generateckpass', 'SuperAdminController\SuperAdminController@generateckpass');

});

Route::middleware(['auth'])->group(function () {
    Route::view('/college/grade/upload', 'ctportal.pages.grade_upload');
    Route::get('/college/grade/ecr/download', 'CollegeECR@download_ecr');
    Route::post('/college/grade/ecr/upload', 'CollegeECR@upload_ecr');
    Route::get('/college/grade/ecr/view', 'CollegeECR@view_grade');
    Route::get('/college/grade/ecr/submit', 'CollegeECR@submit_grade');
    Route::get('/college/grade/ecr/approve', 'CollegeECR@approve_grade');
    Route::get('/college/grade/ecr/pending', 'CollegeECR@pending_grade');
    Route::get('/college/grade/ecr/post', 'CollegeECR@post_grade');
    Route::get('/college/grade/ecr/logs', 'CollegeECR@grade_logs');

    Route::view('/college/grade/ecr/monitoring', 'ctportal.pages.grade_monitoring');
    Route::get('/college/grade/ecr/monitoring/list', 'CollegeECR@grade_monitoring');
    Route::get('/college/grade/ecr/monitoring/count', 'CollegeECR@grade_monitoring_count');
});



Route::middleware(['auth'])->group(function () {
    Route::view('/setup/college/subjects', 'superadmin.pages.setup.college_subjects');
    Route::get('changeUser/{id}', 'SuperAdminController\SuperAdminController@changeUser');
});



Route::middleware(['guest'])->group(function () {
    Route::get('/viewSurveyForm', 'SurveyControllers\SurveyController@viewSurveyForm');

});

Route::get('cloudNewData/{table}/{maxval}', 'SyncController@cloudNewData');
Route::get('cloudUpdatedData/{table}/{date}', 'SyncController@cloudUpdatedData');
Route::get('cloudDeletedData/{table}/{date}', 'SyncController@cloudDeletedData');
Route::get('cloudGetSyncSetup', 'SyncController@cloudGetSyncSetup');
Route::get('storeImage', 'SyncController@storeImage');

Route::middleware(['auth'])->group(function () {
    Route::get('/manageaccounts', 'AdministratorControllers\AdministratorController@manageaccounts');
    Route::get('/viewschoolinfo', 'AdministratorControllers\AdministratorController@viewschoolinfo');
    Route::get('/insertinfo', 'AdministratorControllers\AdministratorController@insertinfo');
    Route::get('/admingetcity', 'AdministratorControllers\AdministratorController@admingetcity');
    Route::post('/updateschoolinfo', 'AdministratorControllers\AdministratorController@updateschoolinfo');
});


Route::middleware(['auth', 'isAdminAdmin'])->group(function () {

    Route::get('/viewschool/{id}', 'AdminadminController\AdminAdminController@viewadmiadmin');
    Route::get('/enrollmentReport', 'AdminadminController\AdminAdminController@enrollmentReport');
    Route::get('/cashtransReport', 'AdminadminController\AdminAdminController@cashtransReport');

    Route::get('/filtercashtrans', 'AdminadminController\AdminAdminController@filtercashtrans');
    Route::get('/filterEnrollmentReport', 'AdminadminController\AdminAdminController@filterEnrollmentReport');

});

Route::get('/studentmasterlist', 'AdminadminController\AdminAdminController@studentmasterlist');
Route::get('/cashtransaction', 'AdminadminController\AdminAdminController@chrngtrans');
Route::get('/targetcollection', 'AdminadminController\AdminAdminController@targetcollection');

Route::get('get/grade/header', 'TeacherControllers\TeacherGradingV2@get_grade_header');

Route::middleware(['auth', 'isDefaultPass'])->group(function () {
    Route::view('teacher/finalgrades', 'teacher\grading\finalgrade');
    Route::get('teacher/get/teacheingload', 'TeacherControllers\TeacherFinalGrade@teachingload');
    Route::get('teacher/get/gradestatus', 'TeacherControllers\TeacherFinalGrade@gradestatus');
    Route::get('teacher/submit/grades', 'TeacherControllers\TeacherFinalGrade@submit_grades');
    Route::get('teacher/get/students', 'TeacherControllers\TeacherFinalGrade@enrolled_learners');
    Route::get('teacher/finalgrades/savegrades', 'TeacherControllers\TeacherFinalGrade@save_grades');
});

Route::middleware(['auth', 'isTeacher', 'isDefaultPass'])->group(function () {

    Route::view('teacher/teachingload', 'teacher.schedule');

    Route::resource('/teachersdashboard', 'TeacherControllers\TeacherDashboardController');
    Route::get('/teacherNotification/{id}', 'TeacherControllers\TeacherDashboardController@updateNotifStatus');

    Route::get('/teacher/classattendance/full/{id}', 'TeacherControllers\ClassAttendanceController@fullattendance');
    Route::get('/classattendance', 'TeacherControllers\ClassAttendanceController@index');
    Route::get('/classattendance/hccsi', 'TeacherControllers\ClassAttendanceController@hccsi');
    Route::get('/classattendance/viewsection_v1', 'TeacherControllers\ClassAttendanceController@viewsection_v1');
    Route::get('/classattendance/viewsection_v2', 'TeacherControllers\ClassAttendanceController@viewsection_v2');
    Route::get('/classattendance/viewsection_v3', 'TeacherControllers\ClassAttendanceController@viewsection_v3');
    Route::get('/classattendance/viewsection_v4', 'TeacherControllers\ClassAttendanceController@viewsection_v4');
    Route::get('/classattendance/showtable', 'TeacherControllers\ClassAttendanceController@showtable');
    Route::get('/classattendance/getcalendar', 'TeacherControllers\ClassAttendanceController@getcalendar');
    Route::get('/classattendance/submit', 'TeacherControllers\ClassAttendanceController@submitattendance');
    Route::get('/classattendance/getremarks', 'TeacherControllers\ClassAttendanceController@getremarks');
    Route::get('/classattendance/updateremarks', 'TeacherControllers\ClassAttendanceController@updateremarks');
    Route::get('/classattendance/changedate', 'TeacherControllers\ClassAttendanceController@changedate');
    Route::get('/classattendance/updateattendance_v1', 'TeacherControllers\ClassAttendanceController@store');
    Route::get('/classattendance/lateattendancecol', 'TeacherControllers\ClassAttendanceController@lateattendancecol');
    Route::get('/classattendance/presentattendancecol', 'TeacherControllers\ClassAttendanceController@presentattendancecol');
    Route::get('/classattendance/absentattendancecol', 'TeacherControllers\ClassAttendanceController@absentattendancecol');
    Route::get('/classattendance/deleteattendancecol', 'TeacherControllers\ClassAttendanceController@deleteattendancecol');
    Route::get('/classattendance/presentattendancerow', 'TeacherControllers\ClassAttendanceController@presentattendancerow');
    Route::get('/classattendance/lateattendancerow', 'TeacherControllers\ClassAttendanceController@lateattendancerow');
    Route::get('/classattendance/absentattendancerow', 'TeacherControllers\ClassAttendanceController@absentattendancerow');
    Route::get('/classattendance/deleteattendancerow', 'TeacherControllers\ClassAttendanceController@deleteattendancerow');

    Route::get('/beadleAttendance', 'TeacherControllers\BeadleAttendanceController@index');
    Route::get('/beadleAttendance/getsections', 'TeacherControllers\BeadleAttendanceController@getsections');
    Route::get('/beadleAttendance/getstrands', 'TeacherControllers\BeadleAttendanceController@getstrands');
    Route::get('/beadleAttendance/getsubjects', 'TeacherControllers\BeadleAttendanceController@getsubjects');
    Route::get('/beadleAttendance/getsections', 'TeacherControllers\BeadleAttendanceController@getsections');
    Route::get('/beadleAttendance/getstudents', 'TeacherControllers\BeadleAttendanceController@getstudents');
    Route::get('/beadleAttendance/getcalendar', 'TeacherControllers\BeadleAttendanceController@getcalendar');
    Route::get('/beadleAttendance/updatecolumn', 'TeacherControllers\BeadleAttendanceController@updatecolumn');
    Route::get('/beadleAttendance/updaterow', 'TeacherControllers\BeadleAttendanceController@updaterow');
    Route::get('/beadleAttendanceUpdate', 'TeacherControllers\BeadleAttendanceController@updateStatus');

    Route::get('/students/advisory', 'TeacherControllers\StudentController@advisory');
    Route::get('/students/advisorygetstudents', 'TeacherControllers\StudentController@advisorygetstudents');
    Route::get('/students/advisorygetstudinfo', 'TeacherControllers\StudentController@advisorygetstudinfo');
    Route::post('/students/advisoryphotosubmit', 'TeacherControllers\StudentController@advisoryphotosubmit')->name('teacherupdateStudPic');
    Route::get('/students/advisorygetstudinfosubmit', 'TeacherControllers\StudentController@advisorygetstudinfosubmit');
    Route::get('/students/bysubject', 'TeacherControllers\StudentController@bysubject');
    Route::get('/students/bysubjectgetsections', 'TeacherControllers\StudentController@bysubjectgetsections');
    Route::get('/students/bysubjectgetsubjects', 'TeacherControllers\StudentController@bysubjectgetsubjects');
    Route::get('/students/bysubjectgetstudents', 'TeacherControllers\StudentController@bysubjectgetstudents');

    // Route::resource('/grades', 'TeacherControllers\GradeController');
    Route::get('/grades/index', 'TeacherControllers\GradeController@index');

    Route::get('/summergrades/{id}', 'TeacherControllers\GradeController@summer');
    Route::get('/sections/{id}/{syid}/{gradelevelid}', 'TeacherControllers\FilterController@showSubjects');
    // Route::get('/subjects/{id}/{syid}/{gradelevelid}/{sectionid}', 'TeacherControllers\FilterController@showQuarters');
    //Route::get('/subjects/{id}/{syid}/{gradelevelid}/{sectionid}', 'TeacherControllers\TeacherGradingV2@showGrades');
    Route::get('/subjects/{id}/{syid}/{gradelevelid}/{sectionid}/{semid}', 'TeacherControllers\TeacherGradingV2@showGrades');
    // Route::get('/teacher/grade/subjects/{id}/{syid}/{gradelevelid}/{sectionid}', 'TeacherControllers\TeacherGradingV2@showGrades');

    // Route::get('/getgrades/{id}', 'TeacherControllers\FilterController@getGrades');
    Route::get('/getgrades/{id}', 'TeacherControllers\TeacherGradingV2@getGrades');
    // Route::get('/getgrades/{id}', 'TeacherControllers\TeacherGradingV2@getGradesv2');
    Route::post('/getgradesdata', 'TeacherControllers\TeacherGradingV2@getGradesData');

    Route::get('/getgradesdetailfromcloud', 'TeacherControllers\TeacherGradingV2@getgradesdetailfromcloud');
    Route::get('/checkgradestatusfromcloud', 'TeacherControllers\TeacherGradingV2@checkgradestatusfromcloud');

    Route::get('/grades/getsections', 'TeacherControllers\GradeController@getsections');
    Route::get('/grades/getsubjects', 'TeacherControllers\GradeController@getsubjects');


    Route::get('/updatedata/{id}', 'TeacherControllers\FilterController@updateData');
    Route::get('/gradesSubmit/{id}', 'TeacherControllers\FilterController@updateGradeStatus');

    Route::get('/unpostrequest/{id}', 'TeacherControllers\GradeController@unpostRequest');
    Route::resource('/form_138', 'TeacherControllers\FormReportCardController');
    Route::get('/form_138/{action}/{id}', 'TeacherControllers\FormReportCardController@viewSchoolForm9');
    Route::get('/observedValues/{id}', 'TeacherControllers\FormReportCardController@updateObservedValues');
    Route::get('/forms/index/{formtype}', 'TeacherControllers\TeacherFormController@index');
    Route::get('/forms/form1', 'TeacherControllers\TeacherFormController@form1');
    Route::get('/forms/form2', 'TeacherControllers\TeacherFormController@form2');
    Route::get('/forms/form2shsindex', 'TeacherControllers\TeacherFormController@form2shsindex');
    Route::get('/forms/form2enrollmentmonth', 'TeacherControllers\TeacherFormController@enrollmentmonth');
    Route::get('/forms/form2summarytable', 'TeacherControllers\TeacherFormController@form2summarytable');
    Route::get('/forms/form9', 'TeacherControllers\TeacherFormController@form9');
    Route::get('/forms/form10', 'TeacherControllers\TeacherFormController@form10');
    Route::get('/schoolForm_4/{id}', 'TeacherControllers\FormReportCardController@viewSchoolForm4');
    Route::get('/classrecord/pdf/{section}/{subject}/{quarter}', 'TeacherControllers\GradeController@toBlade');


    Route::get('/announcements', 'TeacherControllers\TeacherPortalController@announcements');
    Route::get('/post_announcements', 'TeacherControllers\TeacherPortalController@publish_announcements');

    Route::get('/mailbox/inbox/{id}', 'TeacherControllers\TeacherMailBoxController@inbox');
    Route::get('/mailbox/compose/{id}', 'TeacherControllers\TeacherMailBoxController@compose');
    Route::get('/mailbox/sent/{id}', 'TeacherControllers\TeacherMailBoxController@sent');
    Route::get('/mailbox/draft/{id}', 'TeacherControllers\TeacherMailBoxController@draft');
    Route::get('/mailbox/read/{id}', 'TeacherControllers\TeacherMailBoxController@read');
    Route::get('/mailbox/delete/{id}', 'TeacherControllers\TeacherMailBoxController@delete');
    Route::get('/mailbox/trash/{id}', 'TeacherControllers\TeacherMailBoxController@trash');
    Route::get('/mailbox/print', 'TeacherControllers\TeacherMailBoxController@print');

    Route::get('/summary', 'TeacherControllers\SummaryController@index');
    Route::get('/summaryattendancepersubject/{id}', 'TeacherControllers\SummaryAttendanceController@summaryattendancepersubject');
    Route::get('/summaryattendancepersubjectgetattendance', 'TeacherControllers\SummaryAttendanceController@summaryattendancepersubjectgetattendance');
    Route::get('/summaryattendancepersubjectprint', 'TeacherControllers\SummaryAttendanceController@summaryattendancepersubjectprint');
    Route::get('/summaryofloads/{id}', 'TeacherControllers\SummaryOfLoadController@summaryofloads');

    Route::get('/applyleave/{id}', 'TeacherControllers\TeacherRequestController@leave');
    // Route::resource('/addleave', 'TeacherControllers\TeacherLeaveController');

    Route::get('/teacherovertime/{id}', 'TeacherControllers\TeacherRequestController@overtime');
    //shsreports

    Route::get('/checkCloudGradeStatus', 'TeacherControllers\TeacherGradingV2@checkCloudGradeStatus');

    Route::get('/pendinggrades', 'TeacherControllers\TeacherGradingV2@pendinggrades');

    //grade summary
    Route::get('/teacher/get/advisory', 'TeacherControllers\TeacherGradingV4@get_advisory_sections');
    Route::get('/teacher/grade/summary', 'TeacherControllers\TeacherGradingV4@teacher_grade_summary');
    Route::get('/teacher/grade/summary/quarter', 'TeacherControllers\TeacherGradingV4@teacher_grade_summary_quarter');
    Route::get('/teacher/section/all', 'TeacherControllers\TeacherGradingV4@get_section_all');
    //grade summary

    //student ranking
    Route::get('/teacher/student/ranking', 'TeacherControllers\TeacherGradingV3@student_ranking');

});

Route::get('/uploadgradedetailstocloud', 'TeacherControllers\TeacherGradingV2@uploadgradedetailstocloud');
Route::get('/uploadgradestocloud', 'TeacherControllers\TeacherGradingV2@uploadgradestocloud');
Route::get('/returngradesdetailtolocal', 'TeacherControllers\TeacherGradingV2@returngradesdetailtolocal');
Route::get('/getCloudGradeStatus', 'TeacherControllers\TeacherGradingV2@getCloudGradeStatus');

Route::get('/returnstatusfromcloud', 'TeacherControllers\TeacherGradingV2@returnstatusfromcloud');

Route::middleware(['auth', 'isRegistrar', 'isDefaultPass'])->group(function () {

    //registrar reports


    Route::resource('/registrardashboard', 'RegistrarControllers\RegistrarDashboardController');
    Route::get('/entranceexamquestions', 'RegistrarControllers\PreRegistrationController@entranceexamquestions');


    Route::get('/registrar/questions/{gradelevel}', 'RegistrarControllers\PreRegistrationController@getquestions');


    Route::get('/addquestions', 'RegistrarControllers\PreRegistrationController@addquestions');
    Route::get('/editquestion', 'RegistrarControllers\PreRegistrationController@editquestion');
    Route::get('/deletequestion', 'RegistrarControllers\PreRegistrationController@deletequestion');
    Route::get('/entranceexamresults/{action}', 'RegistrarControllers\PreRegistrationController@entranceexamresults');



    Route::get('/registrarschoolforms/{id}', 'RegistrarControllers\ReportsController@schoolhead');
    Route::get('/reports/{id}', 'RegistrarControllers\ReportsController@reports');
    Route::get('/reports_schoolforms/filterstudents', 'RegistrarControllers\ReportsController@filterstudents');
    Route::get('/reports_studentmasterlist/{id}/{syid}/{sectionid}', 'RegistrarControllers\ReportsController@reportstudentmasterlist');
    Route::get('/reports_schoolform4/{id}', 'RegistrarControllers\ReportsController@reportschoolform_4');
    Route::get('/reports_schoolform5', 'RegistrarControllers\ReportsController@reportschoolform_5');
    Route::get('/export/form5', 'TeacherControllers\TeacherFormController@form5');
    Route::get('/export/form5aindex', 'TeacherControllers\ReportsSHSController@form5aindex');
    Route::get('/export/form5bindex', 'TeacherControllers\ReportsSHSController@form5bindex');
    Route::get('/export/form5a', 'TeacherControllers\ReportsSHSController@form5a');
    Route::get('/export/form5b', 'TeacherControllers\ReportsSHSController@form5b');

    Route::get('/reports_schoolform6/{id}', 'RegistrarControllers\ReportsController@reportschoolform_6');
    Route::get('/reports_schoolform9/{id}/{syid}/{sectionid}/{gradelevelid}', 'RegistrarControllers\ReportsController@reports_schoolform9');
    Route::get('/reports_schoolform9/{id}', 'RegistrarControllers\FormReportsController@reportsschoolform9');
    Route::get('/reports/form9/view', 'RegistrarControllers\FormReportsController@reportsschoolform9index');

    Route::get('/registrar/reports/notenrolled', 'RegistrarControllers\ReportsController@notenrolled');
    Route::get('/registrar/reports/enrollment', 'RegistrarControllers\ReportsController@enrollment');
    Route::get('/registrar/reports/promotional', 'RegistrarControllers\ReportsController@promotional');
    Route::get('/registrar/reports/graduationlist', 'RegistrarControllers\ReportsController@graduationlist');

    Route::get('/seniorhigh/{id}', 'RegistrarControllers\SeniorHighController@form10');
    Route::get('/senior/addform10', 'RegistrarControllers\SeniorHighController@addform10');
    Route::get('/senior/editform10/{id}', 'RegistrarControllers\SeniorHighController@editform10');

    Route::get('/juniorhigh/{id}', 'RegistrarControllers\JuniorHighController@form10');
    Route::get('/junior/addform10', 'RegistrarControllers\JuniorHighController@addform10');
    Route::get('/junior/editform10/{id}', 'RegistrarControllers\JuniorHighController@editform10');
    Route::get('/junior/deleteform10', 'RegistrarControllers\JuniorHighController@deleteform10');

    Route::get('/elementary/{id}', 'RegistrarControllers\ElementaryController@form10');
    Route::get('/elem/addform10', 'RegistrarControllers\ElementaryController@addform10');
    Route::get('/elem/editform10/{id}', 'RegistrarControllers\ElementaryController@editform10');
    Route::get('/elem/deleteform10', 'RegistrarControllers\ElementaryController@deleteform10');


    Route::get('/schoolforms/college/index', 'RegistrarControllers\CollegeFormsController@index');
    Route::get('/schoolforms/college/permanentrecordindex', 'RegistrarControllers\CollegeFormsController@permanentrecordindex');
    Route::get('/schoolforms/college/permanentrecordfilter', 'RegistrarControllers\CollegeFormsController@permanentrecordfilter');
    Route::get('/schoolforms/college/permanentrecordgetrecord', 'RegistrarControllers\CollegeFormsController@permanentrecordgetrecord');


    Route::get('/reportssummariesallstudentsnew/{id}', 'RegistrarControllers\SummaryStudentV2Controller@reportssummariesallstudentsnew');

    Route::get('/reportssummariesspecialclass/{id}', 'RegistrarControllers\SummarySpecialClassController@reportssummariesspecialclass');

    Route::get('/preview_student_masterlist/{id}/{sectionid}', 'RegistrarControllers\FormReportsController@previewStudentMasterlist');
    Route::get('/show_enrollees/{action}', 'RegistrarControllers\FormReportsController@showEnrollees');
    Route::get('/registrargoodmoralcertificate', 'RegistrarControllers\RegistrarGoodMoralCertController@goodmoralcertificate');
    // Route::get('/editForm10/{action}/{student_id}/{header_id}','RegistrarControllers\FormReportsController@editForm10');
    Route::get('/registrarNotification/{id}', 'RegistrarControllers\RegistrarDashboardController@updateNotifStatus');

    //other printables

    Route::view('/printable/certifications/index', 'registrar.otherprintables.certifications.index');
    Route::get('/printable/certifications', 'RegistrarControllers\CertificatesController@printable_certifications');
    Route::get('/printable/certifications/downloadtemplate', 'RegistrarControllers\CertificatesController@download_template');

    Route::post('/printable/certificationsupload', 'RegistrarControllers\ReportsController@printable_certifications');
    //Route::get('/printable/certifications', 'RegistrarControllers\ReportsController@printable_certifications');
    //Route::post('/printable/certificationsupload', 'RegistrarControllers\ReportsController@printable_certifications');
    //Route::get('/printable/certification/index', 'RegistrarControllers\ReportsController@printablecertificationindex');
    Route::get('/printable/certification/generate', 'RegistrarControllers\ReportsController@printablecertificationgenerate');
    Route::get('/printable/certification/goodmoral', 'RegistrarControllers\ReportsController@printablecertificationgoodmoral');
    Route::get('/printable/certification/certofgraduation', 'RegistrarControllers\ReportsController@printablecertificationcertofgraduation');
    Route::get('/printable/certification/eligtotransfer', 'RegistrarControllers\ReportsController@printablecertificationeligtotransfer');
    Route::get('/printable/numofstudents/index', 'RegistrarControllers\ReportsController@printablenumofstudentsindex');
    Route::get('/printable/numofstudents/generate', 'RegistrarControllers\ReportsController@printablenumofstudentsgenerate');
    Route::get('/printable/studentvacc/index', 'RegistrarControllers\ReportsController@printablestudentsvaccindex');



    Route::get('/registrar/reports/monthly', 'RegistrarControllers\ReportsController@report_monthlystat');

    Route::get('/printable/cor', 'RegistrarControllers\ReportsController@printablecor');
    Route::get('/printable/gwaranking', 'RegistrarControllers\ReportsController@printablegwaranking');
    Route::get('/printable/coranking', 'RegistrarControllers\ReportsController@printablecoranking');
    Route::get('/printable/studentacademicrecord', 'RegistrarControllers\ReportsController@printablestudentacademicrecord');
    Route::get('/printable/clearance', 'RegistrarControllers\ReportsController@printableclearance');


    //enrollment
    Route::get('/enrollment', 'enrollment\EnrollmentController@registrarIndex')->name('registrarIndex');
    Route::get('/registrar/studentinfo', 'enrollment\EnrollmentController@studentinfo')->name('studentinfo');
    Route::get('/registrar/studentinfo/search', 'enrollment\EnrollmentController@studentsearch')->name('studentsearch');
    Route::get('/registrar/studentinfo/edit/{id}', 'enrollment\EnrollmentController@studentedit')->name('studentedit');
    Route::get('/registrar/studentinfo/update', 'enrollment\EnrollmentController@studentupdate')->name('studentupdate');
    Route::get('/registrar/studentinfo/create', 'enrollment\EnrollmentController@studentcreate')->name('studentcreate');
    Route::get('/registrar/studentinfo/insert', 'enrollment\EnrollmentController@studentinsert')->name('studentinsert');

    Route::get('/registrar/registered', 'enrollment\EnrollmentController@registered')->name('registered');
    Route::get('/registrar/registered/search', 'enrollment\EnrollmentController@searchRegStud')->name('searchRegStud');
    Route::get('/registrar/registered/deletestud', 'enrollment\EnrollmentController@deleteStud')->name('deletestud');
    Route::get('/registrar/enrolled', 'enrollment\EnrollmentController@enrolled')->name('enrolled');
    Route::get('/registrar/enrolled/search', 'enrollment\EnrollmentController@searchEnrolledStud')->name('searchEnrolledStud');
    Route::get('/registrar/preenrolled', 'enrollment\EnrollmentController@preenrolled')->name('preenrolled');
    Route::get('/registrar/searchPreEnrolledStud', 'enrollment\EnrollmentController@searchPreEnrolledStud')->name('searchPreEnrolledStud');

    Route::get('/registrar/preenroll/getstudpaid', 'enrollment\EnrollmentController@getstudpaid')->name('getstudpaid');

    Route::get('/admission', 'enrollment\EnrollmentController@admission')->name('admission');
    Route::get('/admission/search', 'enrollment\EnrollmentController@searchPreReg')->name('searchPreReg');
    Route::get('/admission/edit/{code}', 'enrollment\EnrollmentController@admissionedit')->name('admissionedit');
    Route::get('/admission/reg', 'enrollment\EnrollmentController@admissionregister')->name('admissionregister');
    Route::get('/admission/preregdel', 'enrollment\EnrollmentController@preregdel')->name('preregdel');
    Route::get('/admission/preregreq', 'enrollment\EnrollmentController@preregreq')->name('preregreq');

    Route::get('/admission/sync', 'enrollment\EnrollmentController@sync')->name('sync');

    Route::get('/admission/enroll/getinfo', 'enrollment\EnrollmentController@enrollgetinfo')->name('enrollgetinfo');
    Route::get('/admission/enroll/enrollstud', 'enrollment\EnrollmentController@enrollstud')->name('enrollstud');
    Route::get('/admission/enroll/viewEnrollment', 'enrollment\EnrollmentController@viewEnrollment')->name('viewEnrollment');
    Route::get('/enrollment/studdata', 'enrollment\EnrollmentController@studdata')->name('studdata');

    Route::get('/enrollment/religion/add', 'enrollment\EnrollmentController@addReligion')->name('addReligion');
    Route::get('/enrollment/mt/add', 'enrollment\EnrollmentController@addMT')->name('addMT');
    Route::get('/enrollment/eg/add', 'enrollment\EnrollmentController@addEG')->name('addEG');
    Route::get('/enrollment/saveEnroll', 'enrollment\EnrollmentController@saveEnroll')->name('saveEnroll');

    Route::get('/enrollment/spclass', 'enrollment\EnrollmentController@spclass')->name('spclass');
    Route::get('/enrollment/spsearch', 'enrollment\EnrollmentController@spsearch')->name('spsearch');
    Route::get('/enrollment/LoadLists', 'enrollment\EnrollmentController@LoadLists')->name('LoadLists');
    Route::get('/enrollment/loadStud', 'enrollment\EnrollmentController@loadStud')->name('loadStud');
    Route::get('/enrollment/loadDetail', 'enrollment\EnrollmentController@loadDetail')->name('loadDetail');
    Route::get('/enrollment/appendDetail', 'enrollment\EnrollmentController@appendDetail')->name('appendDetail');
    Route::get('/enrollment/savespClass', 'enrollment\EnrollmentController@savespClass')->name('savespClass');
    Route::get('/enrollment/editspClass', 'enrollment\EnrollmentController@editspClass')->name('editspClass');
    Route::get('/enrollment/editDetail', 'enrollment\EnrollmentController@editDetail')->name('editDetail');
    Route::get('/enrollment/updateDetail', 'enrollment\EnrollmentController@updateDetail')->name('updateDetail');
    Route::get('/enrollment/deleteDetail', 'enrollment\EnrollmentController@deleteDetail')->name('deleteDetail');
    Route::get('/enrollment/loadDtail', 'enrollment\EnrollmentController@loadDtail')->name('loadDtail');
    Route::get('/enrollment/getDP', 'enrollment\EnrollmentController@getDP')->name('getDP');

    Route::get('/enrollment/requirement/view', 'enrollment\EnrollmentController@viewreq')->name('viewreq');

    Route::get('/techvoc/courses/tvsearch', 'enrollment\EnrollmentController@tvsearch')->name('tvsearch');
    Route::get('/techvoc/courses', 'enrollment\EnrollmentController@tvcourses')->name('tvcourses');
    Route::get('/techvoc/courses/saveTVCourse', 'enrollment\EnrollmentController@saveTVCourse')->name('saveTVCourse');
    Route::get('/techvoc/courses/editTVCourse', 'enrollment\EnrollmentController@editTVCourse')->name('editTVCourse');
    Route::get('/techvoc/courses/updateTVCourse', 'enrollment\EnrollmentController@updateTVCourse')->name('updateTVCourse');
    Route::get('/techvoc/courses/deleteTVCourse', 'enrollment\EnrollmentController@deleteTVCourse')->name('deleteTVCourse');

    Route::get('/techvocv2/courses', 'enrollment\EnrollmentController@tvv2courses')->name('tvv2courses');
    Route::get('/techvocv2/batches', 'enrollment\EnrollmentController@tvv2batches')->name('tvv2batches');
    Route::get('/techvocv2/enrollment', 'enrollment\EnrollmentController@tvv2enrollment')->name('tvv2enrollment');
    Route::get('/techvocv2/studinfo', 'enrollment\EnrollmentController@tvv2studinfo')->name('tvv2studinfo');

    Route::get('/techvoc/batch', 'enrollment\EnrollmentsController@tvbatch')->name('tvbatch');
    Route::get('/techvoc/batch/loadbatch', 'enrollment\EnrollmentsController@loadbatch')->name('loadbatch');
    Route::get('/techvoc/batch/createbatch', 'enrollment\EnrollmentsController@createbatch')->name('createbatch');
    Route::get('/techvoc/batch/activatebatch', 'enrollment\EnrollmentsController@activatebatch')->name('activatebatch');

    Route::get('/techvoc/enrollment/tvloadstudinfo', 'enrollment\EnrollmentController@tvloadstudinfo')->name('tvloadstudinfo');
    Route::get('/techvoc/enrollment/tvcreatestudinfo', 'enrollment\EnrollmentsController@tvcreatestudinfo')->name('tvcreatestudinfo');
    Route::get('/techvoc/enrollment/tvenrollstudent', 'enrollment\EnrollmentsController@tvenrollstudent')->name('tvenrollstudent');

    Route::get('/registrar/earlyregistration', 'enrollment\EnrollmentController@earlyregistration')->name('earlyregistration');
    Route::get('/registrar/searchearlyenrollment', 'enrollment\EnrollmentController@searchearlyenrollment')->name('searchearlyenrollment');
    Route::get('/registrar/ee_getstudpaid', 'enrollment\EnrollmentController@ee_getstudpaid')->name('ee_getstudpaid');
    Route::get('/registrar/ee_deleteregisteredstud', 'enrollment\EnrollmentController@ee_deleteregisteredstud')->name('ee_deleteregisteredstud');

    Route::get('/registrar/resitemized', 'enrollment\EnrollmentController@resitemized')->name('resitemized');

    //enrollment

    //studmanagement_start
    Route::get('/registrar/studentmanagement', 'enrollment\StudentManagementController@studentmanagement')->name('studentmanagement');
    Route::get('/registrar/sm_loadstudents', 'enrollment\StudentManagementController@sm_loadstudents')->name('sm_loadstudents');

    Route::get('/registrar/sm_savestudent', 'enrollment\StudentManagementController@sm_savestudent')->name('sm_savestudent');
    Route::get('/registrar/sm_viewstud', 'enrollment\StudentManagementController@sm_viewstud')->name('sm_viewstud');
    Route::get('/registrar/sm_loadenrollmentinfo', 'enrollment\StudentManagementController@sm_loadenrollmentinfo')->name('sm_loadenrollmentinfo');
    Route::get('/registrar/sm_enrollstudent', 'enrollment\StudentManagementController@sm_enrollstudent')->name('sm_enrollstudent');

    Route::get('/registrar/sm_update_studstatus', 'enrollment\StudentManagementController@sm_update_studstatus')->name('sm_update_studstatus');
    Route::get('/registrar/sm_update_studstatdate', 'enrollment\StudentManagementController@sm_update_studstatdate')->name('sm_update_studstatdate');
    Route::get('/registrar/sm_update_studtype', 'enrollment\StudentManagementController@sm_update_studtype')->name('sm_update_studtype');
    Route::get('/registrar/sm_update_mol', 'enrollment\StudentManagementController@sm_update_mol')->name('sm_update_mol');
    Route::get('/registrar/sm_update_grantee', 'enrollment\StudentManagementController@sm_update_grantee')->name('sm_update_grantee');
    Route::get('/registrar/sm_update_level', 'enrollment\StudentManagementController@sm_update_level')->name('sm_update_level');
    Route::get('/registrar/sm_update_strand', 'enrollment\StudentManagementController@sm_update_strand')->name('sm_update_strand');
    Route::get('/registrar/sm_update_section', 'enrollment\StudentManagementController@sm_update_section')->name('sm_update_section');
    //studmanagement_end

    // E A R L Y  B I R D S  E N R O L L M E N T
    Route::get('/earlybirds/index', 'enrollment\EarlyBirdController@index');
    Route::get('/earlybirds/getotherfilter', 'enrollment\EarlyBirdController@getotherfilter');
    Route::get('/earlybirds/generatefilter', 'enrollment\EarlyBirdController@generatefilter');
    Route::get('/earlybirds/getstudents', 'enrollment\EarlyBirdController@getstudents');
    Route::get('/earlybirds/getinfo', 'enrollment\EarlyBirdController@getstudinfo');
    Route::get('/earlybirds/addstudent', 'enrollment\EarlyBirdController@addstudent');
    Route::get('/earlybirds/delete', 'enrollment\EarlyBirdController@delete');
    Route::get('/earlybirds/createstudent', 'enrollment\EarlyBirdController@createstudent');
    //Senior High

    Route::get('/shsetup/track', 'enrollment\EnrollmentController@viewtrack')->name('viewtrack');
    Route::get('/shsetup/searchtrack', 'enrollment\EnrollmentController@searchtrack')->name('searchtrack');
    Route::get('/shsetup/savetrack', 'enrollment\EnrollmentController@savetrack')->name('savetrack');
    Route::get('/shsetup/edittrack', 'enrollment\EnrollmentController@edittrack')->name('edittrack');
    Route::get('/shsetup/updatetrack', 'enrollment\EnrollmentController@updatetrack')->name('updatetrack');

    Route::get('/shsetup/strand', 'enrollment\EnrollmentController@viewstrand')->name('viewstrand');
    Route::get('/shsetup/searchstrand', 'enrollment\EnrollmentController@searchstrand')->name('searchstrand');
    Route::get('/shsetup/loadtrack', 'enrollment\EnrollmentController@loadtrack')->name('loadtrack');
    Route::get('/shsetup/searchstrand', 'enrollment\EnrollmentController@searchstrand')->name('searchstrand');
    Route::get('/shsetup/insertstrand', 'enrollment\EnrollmentController@insertstrand')->name('insertstrand');
    Route::get('/shsetup/loadstrand', 'enrollment\EnrollmentController@loadstrand')->name('loadstrand');
    Route::get('/shsetup/loadblock', 'enrollment\EnrollmentController@loadblock')->name('loadblock');
    Route::get('/shsetup/editstrand', 'enrollment\EnrollmentController@editstrand')->name('editstrand');
    //Senior High

    //College
    Route::get('/college/adddrop/index', 'CollegeControllers\CollegeAddDropController@index')->name('adddropindex');
    Route::get('/college/adddrop/selectcourse', 'CollegeControllers\CollegeAddDropController@selectcourse')->name('selectcourse');
    Route::get('/college/adddrop/viewstudents', 'CollegeControllers\CollegeAddDropController@viewstudents')->name('viewstudents');
    Route::get('/college/adddrop/viewschedule', 'CollegeControllers\CollegeAddDropController@viewschedule')->name('viewschedule');
    Route::get('/college/adddrop/dropsubject', 'CollegeControllers\CollegeAddDropController@dropsubject')->name('dropsubject');
    Route::get('/college/adddrop/getsubjects', 'CollegeControllers\CollegeAddDropController@getsubjects')->name('getsubjects');
    Route::get('/college/adddrop/getavailablescheds', 'CollegeControllers\CollegeAddDropController@getavailablescheds')->name('getavailablescheds');
    Route::get('/college/adddrop/addschedule', 'CollegeControllers\CollegeAddDropController@addschedule')->name('addschedule');
    //College

    //Tech Voc
    Route::get('/techvoc/tvstudinfo', 'enrollment\EnrollmentsController@tvstudinfo')->name('tvstudinfo');
    Route::get('/techvoc/tvstudsearch', 'enrollment\EnrollmentsController@tvstudsearch')->name('tvstudsearch');
    Route::get('/techvoc/tvgetbatch', 'enrollment\EnrollmentsController@tvgetbatch')->name('tvgetbatch');
    Route::get('/techvoc/tvexport', 'enrollment\EnrollmentsController@tvexport')->name('tvexport');
    Route::get('/techvoc/tvgetstudbybatch', 'enrollment\EnrollmentsController@tvgetstudbybatch')->name('tvgetstudbybatch');
    //Tech Voc

    Route::get('/registrar/fillup/students/sf10', 'RegistrarControllers\RegistrarFormsController@fillupsf10');
    Route::get('/registrar/fillup/get/sf10/{student}/{levelid}', 'RegistrarControllers\RegistrarFormsController@getStudentsf10');
    Route::get('/registrar/insert/students/sf10', 'RegistrarControllers\RegistrarFormsController@insertsf10grade');


    Route::get('/registrar/studentrequirements', 'RegistrarControllers\StudentRequirementsController@studentrequirementsindex')->name('studentrequirementsindex');
    Route::get('/registrar/studentrequirementsresults', 'RegistrarControllers\StudentRequirementsController@studentrequirementsresults')->name('studentrequirementsresults');
    Route::get('/registrar/studentrequirementsgetinfo', 'RegistrarControllers\StudentRequirementsController@studentrequirementsgetinfo')->name('studentrequirementsgetinfo');
    Route::get('/registrar/studentrequirementsupdatestat', 'RegistrarControllers\StudentRequirementsController@studentrequirementsupdatestat')->name('studentrequirementsupdatestat');
    Route::get('/registrar/studentrequirementsgetphoto', 'RegistrarControllers\StudentRequirementsController@studentrequirementsgetphoto')->name('studentrequirementsgetphoto');
    Route::get('/registrar/studentrequirementsgetphotos', 'RegistrarControllers\StudentRequirementsController@studentrequirementsgetphotos')->name('studentrequirementsgetphotos');
    Route::post('/registrar/studentrequirementsuploadphoto', 'RegistrarControllers\StudentRequirementsController@studentrequirementsuploadphoto')->name('studentrequirementsuploadphoto');
    Route::get('/registrar/studentrequirementsdeletephoto', 'RegistrarControllers\StudentRequirementsController@studentrequirementsdeletephoto')->name('studentrequirementsdeletephoto');
    // Route::get('/registrar/studentrequirementsexport', 'RegistrarControllers\StudentRequirementsController@studentrequirementsexport')->name('studentrequirementsexport');


    Route::get('/registrar/sla/index', 'RegistrarControllers\SchoolLastAttendedController@slaindex')->name('slaindex');
    Route::get('/registrar/sla/filter', 'RegistrarControllers\SchoolLastAttendedController@slafilter')->name('slafilter');
    Route::get('/registrar/sla/updateschoolatt', 'RegistrarControllers\SchoolLastAttendedController@slaupdateschoolatt')->name('slaupdateschoolatt');

    //Forms

    Route::get('/registrar/leaves/{id}', 'HRControllers\HREmployeesController@leaves');

    Route::get('/registrar/overtime/{id}', 'HRControllers\HREmployeesController@overtimes');
    Route::get('/registrar/summaries/alphaloading/index', 'RegistrarControllers\SummaryController@alphaloadingindex');
    Route::get('/registrar/summaries/alphaloading/getsection', 'RegistrarControllers\SummaryController@alphaloadinggetsection');
    Route::get('/registrar/summaries/alphaloading/filter', 'RegistrarControllers\SummaryController@alphaloadingfilter');

    Route::get('/registrar/studentlist', 'RegistrarControllers\SummaryController@studentlist')->name('studentlist');
    Route::get('/registrar/studentlist/pdf', 'RegistrarControllers\SummaryController@studentlistpdf')->name('studentlistpdf');

    //college schedule
    Route::get('/registrar/college/student/loading', 'RegistrarControllers\RegistrarFunctionController@student_loading');
    Route::get('/registrar/college/schedule/blade', 'RegistrarControllers\RegistrarFunctionController@college_schedule');
    Route::get('/registrar/college/students', 'RegistrarControllers\RegistrarFunctionController@college_students');
    Route::get('/registrar/college/sections', 'RegistrarControllers\RegistrarFunctionController@college_sections');
    Route::get('/student/loading/student/enrollment', 'SuperAdminController\StudentLoading@enrollment_info');
    Route::get('/registrar/college/curriculumprospectus', 'RegistrarControllers\RegistrarFunctionController@curriculum_propectus');
    Route::get('/registrar/college/enrollment/record', 'RegistrarControllers\RegistrarFunctionController@enrollment_record');
    Route::get('/registrar/college/enrollment/record/subject', 'RegistrarControllers\RegistrarFunctionController@subject_enrollment_records');
    Route::get('/student/loading/subjects/all', 'SuperAdminController\StudentLoading@all_subjects');
    Route::get('/registrar/college/section/schedule', 'RegistrarControllers\RegistrarFunctionController@section_schedule');
    Route::get('/registrar/college/add/studentsched', 'RegistrarControllers\RegistrarFunctionController@add_student_sched');
    Route::get('/registrar/college/remove/studentsched', 'RegistrarControllers\RegistrarFunctionController@remove_student_sched');
    Route::get('/registrar/college/grades', 'RegistrarControllers\RegistrarFunctionController@student_grade');
    Route::get('/registrar/college/set/student/section', 'RegistrarControllers\RegistrarFunctionController@set_section');
    Route::get('/registrar/college/courses', 'RegistrarControllers\RegistrarFunctionController@college_courses');
    Route::get('/registrar/college/curriculum', 'RegistrarControllers\RegistrarFunctionController@college_curriculum');
    Route::get('/registrar/shift/student/course', 'RegistrarControllers\RegistrarFunctionController@shift_student_course');
    Route::get('/registrar/college/student/pre-enrolled', 'RegistrarControllers\RegistrarFunctionController@student_preenrolled_college'); //11132020
    // Route::get('/registrar/college/sectiondetails', 'RegistrarControllers\RegistrarFunctionController@college_enrollment');


    //promotional report
    Route::get('/registrar/report/promotional', 'RegistrarControllers\RegistrarFunctionController@promotional_report');
    Route::get('/registrar/report/promotional/generate', 'RegistrarControllers\RegistrarFunctionController@generate_promotional_report');
    Route::get('/registrar/report/promotional/excel', 'RegistrarControllers\RegistrarFunctionController@generate_promotional_excel');

    //Scholarships
    Route::get('/registrar/scholars/index', 'RegistrarControllers\ScholarshipController@index')->name('scholarsindex');
    Route::get('/registrar/scholars/programadd', 'RegistrarControllers\ScholarshipController@addprogram')->name('scholarprogramadd');
    Route::get('/registrar/scholars/programname', 'RegistrarControllers\ScholarshipController@programname')->name('scholarprogramname');
    Route::get('/registrar/scholars/programedit', 'RegistrarControllers\ScholarshipController@programedit')->name('scholarprogramedit');
    Route::get('/registrar/scholars/programdelete', 'RegistrarControllers\ScholarshipController@programdelete')->name('scholarprogramdelete');
    Route::get('/registrar/scholars/programstudents', 'RegistrarControllers\ScholarshipController@programstudents')->name('scholarprogramstudents');
    Route::get('/registrar/scholars/filter', 'RegistrarControllers\ScholarshipController@filter')->name('scholarprogramfilter');
    Route::get('/registrar/scholars/programselect', 'RegistrarControllers\ScholarshipController@programselect')->name('scholarprogramselect');
    Route::get('/registrar/scholars/programsubmitselect', 'RegistrarControllers\ScholarshipController@programsubmitselect')->name('scholarprogramsubmitselect');
    Route::get('/registrar/scholars/getprogstud', 'RegistrarControllers\ScholarshipController@getprogstud')->name('scholargetprogstud');
    Route::get('/registrar/scholars/getamount', 'RegistrarControllers\ScholarshipController@getamount')->name('scholargetamount');
    Route::get('/registrar/scholars/updateamount', 'RegistrarControllers\ScholarshipController@updateamount')->name('scholarupdateamount');
    Route::get('/registrar/scholars/deleteprogstud', 'RegistrarControllers\ScholarshipController@deleteprogstud')->name('scholardeleteprogstud');


});

Route::middleware(['auth'])->group(function () {
    Route::get('/registrar/studentinfo/print', 'enrollment\EnrollmentController@studentprint')->name('studentprint');
});

Route::middleware(['auth', 'isAdmission', 'isDefaultPass'])->group(function () {

    Route::get('/enrollment', 'enrollment\EnrollmentController@registrarIndex')->name('registrarIndex');
    Route::resource('/registrardashboard', 'RegistrarControllers\RegistrarDashboardController');
    Route::get('/admission', 'enrollment\EnrollmentController@admission')->name('admission');
    Route::get('/admission/search', 'enrollment\EnrollmentController@searchPreReg')->name('searchPreReg');
    Route::get('/admission/edit/{code}', 'enrollment\EnrollmentController@admissionedit')->name('admissionedit');
    Route::get('/admission/reg', 'enrollment\EnrollmentController@admissionregister')->name('admissionregister');
    Route::get('/admission/preregdel', 'enrollment\EnrollmentController@preregdel')->name('preregdel');

    Route::get('/registrar/studentinfo', 'enrollment\EnrollmentController@studentinfo')->name('studentinfo');
    Route::get('/registrar/studentinfo/search', 'enrollment\EnrollmentController@studentsearch')->name('studentsearch');
    Route::get('/registrar/studentinfo/edit/{id}', 'enrollment\EnrollmentController@studentedit')->name('studentedit');
    Route::get('/registrar/studentinfo/update', 'enrollment\EnrollmentController@studentupdate')->name('studentupdate');
    Route::get('/registrar/studentinfo/create', 'enrollment\EnrollmentController@studentcreate')->name('studentcreate');
    Route::get('/registrar/studentinfo/insert', 'enrollment\EnrollmentController@studentinsert')->name('studentinsert');
    //Route::get('/registrar/studentinfo/print', 'enrollment\EnrollmentController@studentprint')->name('studentprint');
    Route::get('/registrar/studentinfo/checkEnrolled', 'enrollment\EnrollmentController@checkEnrolled')->name('checkEnrolled');

});

Route::middleware(['auth', 'isFinance'])->group(function () {

    // Route::get('/finance/dashboard','FinanceDashboardController@dashboard');
    // Route::get('/finance/index','FinanceControllers\FinanceController@index')->name('financeindex');
    Route::get('/finance/itemclassification', 'FinanceControllers\FinanceController@itemclassification')->name('itemclassification');
    Route::get('/finance/search_classification', 'FinanceControllers\FinanceController@search_classification')->name('search_classification');
    Route::get('/finance/loadGL', 'FinanceControllers\FinanceController@loadGL')->name('loadGL');
    Route::get('/finance/saveClass', 'FinanceControllers\FinanceController@saveClass')->name('saveClass');
    Route::get('/finance/viewClass', 'FinanceControllers\FinanceController@viewClass')->name('viewClass');
    Route::get('/finance/updateClass', 'FinanceControllers\FinanceController@updateClass')->name('updateClass');
    Route::get('/finance/delClass', 'FinanceControllers\FinanceController@delClass')->name('delClass');

    Route::get('/finance/payitems', 'FinanceControllers\FinanceController@payitems')->name('payitems');
    Route::get('/finance/payitemsearch', 'FinanceControllers\FinanceController@payitemsearch')->name('payitemsearch');
    Route::get('/finance/loadNEW', 'FinanceControllers\FinanceController@loadNEW')->name('loadNEW');
    Route::get('/finance/saveItem', 'FinanceControllers\FinanceController@saveItem')->name('saveItem');
    Route::get('/finance/loadEDIT', 'FinanceControllers\FinanceController@loadEDIT')->name('loadEDIT');
    Route::get('/finance/updateItem', 'FinanceControllers\FinanceController@updateItem')->name('updateItem');
    Route::get('/finance/deleteItem', 'FinanceControllers\FinanceController@deleteItem')->name('deleteItem');

    Route::get('/finance/modeofpayment', 'FinanceControllers\FinanceController@modeofpayment')->name('modeofpayment');
    Route::get('/finance/searchMOP', 'FinanceControllers\FinanceController@searchMOP')->name('searchMOP');
    Route::get('/finance/mopnew', 'FinanceControllers\FinanceController@mopnew')->name('mopnew');
    Route::get('/finance/mopsave', 'FinanceControllers\FinanceController@mopsave')->name('mopsave');
    Route::get('/finance/mopedit/{id}', 'FinanceControllers\FinanceController@mopedit')->name('mopedit');
    Route::get('/finance/mopdetailSave', 'FinanceControllers\FinanceController@mopdetailSave')->name('mopdetailSave');
    Route::get('/finance/mopdetailDel', 'FinanceControllers\FinanceController@mopdetailDel')->name('mopdetailDel');
    Route::get('/finance/mopdetailAdd', 'FinanceControllers\FinanceController@mopdetailAdd')->name('mopdetailAdd');
    Route::get('/finance/mopupdate', 'FinanceControllers\FinanceController@mopupdate')->name('mopupdate');
    Route::get('/finance/mopdel', 'FinanceControllers\FinanceController@mopdel')->name('mopdel');
    Route::get('/finance/dueEdit', 'FinanceControllers\FinanceController@dueEdit')->name('dueEdit');
    Route::get('/finance/percentEdit', 'FinanceControllers\FinanceController@percentEdit')->name('percentEdit');

    Route::get('/finance/fees', 'FinanceControllers\FinanceController@fees')->name('fees');
    Route::get('/finance/searchfees', 'FinanceControllers\FinanceController@searchfees')->name('searchfees');
    Route::get('/finance/feesnew', 'FinanceControllers\FinanceController@feesnew')->name('feesnew');
    Route::get('/finance/feesdelete', 'FinanceControllers\FinanceController@feesdelete')->name('feesdelete');
    Route::get('/finance/loadClass', 'FinanceControllers\FinanceController@loadClass')->name('loadClass');
    Route::get('/finance/savePayClass', 'FinanceControllers\FinanceController@savePayClass')->name('savePayClass');
    Route::get('/finance/loadItems', 'FinanceControllers\FinanceController@loadItems')->name('loadItems');
    Route::get('/finance/getItemInfo', 'FinanceControllers\FinanceController@getItemInfo')->name('getItemInfo');
    Route::get('/finance/saveFCItem', 'FinanceControllers\FinanceController@saveFCItem')->name('saveFCItem');
    Route::get('/finance/getFCItem', 'FinanceControllers\FinanceController@getFCItem')->name('getFCItem');
    Route::get('/finance/saveFC', 'FinanceControllers\FinanceController@saveFC')->name('saveFC');
    Route::get('/finance/feesedit/{id}', 'FinanceControllers\FinanceController@feesedit')->name('feesedit');
    Route::get('/finance/editFC', 'FinanceControllers\FinanceController@editFC')->name('editFC');
    Route::get('/finance/updateFCpayclass', 'FinanceControllers\FinanceController@updateFCpayclass')->name('updateFCpayclass');
    Route::get('/finance/deleteFCpayclass', 'FinanceControllers\FinanceController@deleteFCpayclass')->name('deleteFCpayclass');
    Route::get('/finance/updateFCItem', 'FinanceControllers\FinanceController@updateFCItem')->name('updateFCItem');
    Route::get('/finance/deleteFCItem', 'FinanceControllers\FinanceController@deleteFCItem')->name('deleteFCItem');
    Route::get('/finance/duplicateFC', 'FinanceControllers\FinanceController@duplicateFC')->name('duplicateFC');
    Route::get('/finance/validatedupSY', 'FinanceControllers\FinanceController@validatedupSY')->name('validatedupSY');
    Route::get('/finance/duplicateAll', 'FinanceControllers\FinanceController@duplicateAll')->name('duplicateAll');
    Route::get('/finance/viewpaysched', 'FinanceControllers\FinanceController@viewpaysched')->name('viewpaysched');

    Route::get('/finance/loadreceivables', 'FinanceControllers\FinanceController@loadreceivables')->name('loadreceivables');
    Route::get('/finance/appendFCNewItems', 'FinanceControllers\FinanceController@appendFCNewItems')->name('appendFCNewItems');
    Route::get('/finance/appendcolFC', 'FinanceControllers\FinanceController@appendcolFC')->name('appendcolFC');
    Route::get('/finance/appendcolFCdetail', 'FinanceControllers\FinanceController@appendcolFCdetail')->name('appendcolFCdetail');
    Route::get('/finance/editcolFCdetail', 'FinanceControllers\FinanceController@editcolFCdetail')->name('editcolFCdetail');
    Route::get('/finance/editcolFCitem', 'FinanceControllers\FinanceController@editcolFCitem')->name('editcolFCitem');
    Route::get('/finance/updatecolFCitem', 'FinanceControllers\FinanceController@updatecolFCitem')->name('updatecolFCitem');

    Route::get('/finance/deletecolFCdetail', 'FinanceControllers\FinanceController@deletecolFCdetail')->name('deletecolFCdetail');
    Route::get('/finance/deletecolFCitem', 'FinanceControllers\FinanceController@deletecolFCitem')->name('deletecolFCitem');

    Route::get('/finance/FCHeadInfo', 'FinanceControllers\FinanceController@FCHeadInfo')->name('FCHeadInfo');
    Route::get('/finance/FCClasList', 'FinanceControllers\FinanceController@FCClasList')->name('FCClasList');
    Route::get('/finance/FCItemList', 'FinanceControllers\FinanceController@FCItemList')->name('FCItemList');

    Route::get('/finance/fordev', 'FinanceControllers\FinanceController@fordev')->name('fordev');


    Route::get('/finance/dpsetup', 'FinanceControllers\FinanceController@dpsetup')->name('dpsetup');
    Route::get('/finance/loaddpitems', 'FinanceControllers\FinanceController@loaddpitems')->name('loaddpitems');
    Route::get('/finance/loaddp', 'FinanceControllers\FinanceController@loaddp')->name('loaddp');
    Route::get('/finance/loaddpclass', 'FinanceControllers\FinanceController@loaddpclass')->name('loaddpclass');
    Route::get('/finance/saveDPItem', 'FinanceControllers\FinanceController@saveDPItem')->name('saveDPItem');
    Route::get('/finance/removeDPItem', 'FinanceControllers\FinanceController@removeDPItem')->name('removeDPItem');
    Route::get('/finance/saveNewDPItem', 'FinanceControllers\FinanceController@saveNewDPItem')->name('saveNewDPItem');

    Route::get('/finance/studledger', 'FinanceControllers\FinanceController@studledger')->name('studledger');
    Route::get('/finance/loadSY', 'FinanceControllers\FinanceController@loadSY')->name('loadSY');
    Route::get('/finance/searchStud', 'FinanceControllers\FinanceController@searchStud')->name('searchStud');
    Route::get('/finance/getStudLedger', 'FinanceControllers\FinanceController@getStudLedger')->name('getStudLedger');
    Route::get('/finance/pdfledger', 'FinanceControllers\FinanceController@printledger')->name('printledger');

    Route::get('/finance/loadfees', 'FinanceControllers\FinanceController@loadfees')->name('loadfees');

    // Route::get('/finance/discounts','FinanceControllers\FinanceController@discounts')->name('discounts');
    Route::get('/finance/discnew', 'FinanceControllers\FinanceController@discnew')->name('discnew');
    Route::get('/finance/discountSearch', 'FinanceControllers\FinanceController@discountSearch')->name('discountSearch');
    Route::get('/finance/discountedit', 'FinanceControllers\FinanceController@discountedit')->name('discountedit');
    Route::get('/finance/discountupdate', 'FinanceControllers\FinanceController@discountupdate')->name('discountupdate');
    Route::get('/finance/discountdelete', 'FinanceControllers\FinanceController@discountdelete')->name('discountdelete');
    Route::get('/finance/loadDiscClass', 'FinanceControllers\FinanceController@loadDiscClass')->name('loadDiscClass');
    Route::get('/finance/saveStudDiscount', 'FinanceControllers\FinanceController@saveStudDiscount')->name('saveStudDiscount');
    Route::get('/finance/searchStudDiscount', 'FinanceControllers\FinanceController@searchStudDiscount')->name('searchStudDiscount');
    Route::get('/finance/postStudDiscount', 'FinanceControllers\FinanceController@postStudDiscount')->name('postStudDiscount');
    Route::get('/finance/discountamount', 'FinanceControllers\FinanceController@discountamount')->name('discountamount');
    Route::get('/finance/delstuddiscount', 'FinanceControllers\FinanceController@delstuddiscount')->name('delstuddiscount');
    Route::get('/finance/loaddiscount', 'FinanceControllers\FinanceController@loaddiscount')->name('loaddiscount');

    //Discount V2

    Route::get('/finance/discounts', 'FinanceControllers\DiscountController@discounts')->name('discounts');
    Route::get('/finance/discount_setup', 'FinanceControllers\DiscountController@discount_setup')->name('discount_setup');
    Route::get('/finance/discount_getdiscount', 'FinanceControllers\DiscountController@discount_getdiscount')->name('discount_getdiscount');

    Route::get('/finance/discount_setup_create', 'FinanceControllers\DiscountController@discount_setup_create')->name('discount_setup_create');
    Route::get('/finance/discount_setup_read', 'FinanceControllers\DiscountController@discount_setup_read')->name('discount_setup_read');
    Route::get('/finance/discount_setup_update', 'FinanceControllers\DiscountController@discount_setup_update')->name('discount_setup_update');
    Route::get('/finance/discount_setup_delete', 'FinanceControllers\DiscountController@discount_setup_delete')->name('discount_setup_delete');

    Route::get('/finance/discount_getstudents', 'FinanceControllers\DiscountController@discount_getstudents')->name('discount_getstudents');
    Route::get('/finance/discount_charges', 'FinanceControllers\DiscountController@discount_charges')->name('discount_charges');
    Route::get('/finance/discount_getsetup', 'FinanceControllers\DiscountController@discount_getsetup')->name('discount_getsetup');
    Route::get('/finance/discount_post', 'FinanceControllers\DiscountController@discount_post')->name('discount_post');

    Route::get('/finance/discount_read', 'FinanceControllers\DiscountController@discount_read')->name('discount_read');

    //Discount V2

    Route::get('/finance/tuitionentry', 'FinanceControllers\FinanceController@tuitionentry')->name('tuitionentry');
    Route::get('/finance/loadTsetup', 'FinanceControllers\FinanceController@loadTsetup')->name('loadTsetup');
    Route::get('/finance/loadTstudent', 'FinanceControllers\FinanceController@loadTstudent')->name('loadTstudent');
    Route::get('/finance/procTuition', 'FinanceControllers\FinanceController@procTuition')->name('procTuition');

    Route::get('/finance/balforward', 'FinanceControllers\FinanceController@balforward')->name('balforward');
    Route::get('/finance/studbal', 'FinanceControllers\FinanceController@studbal')->name('studbal');
    Route::get('/finance/loadstud', 'FinanceControllers\FinanceController@loadstud')->name('loadstud');
    Route::get('/finance/savefsetup', 'FinanceControllers\FinanceController@savefsetup')->name('savefsetup');
    Route::get('/finance/loadbalfwdsetup', 'FinanceControllers\FinanceController@loadbalfwdsetup')->name('loadbalfwdsetup');
    Route::get('/finance/checkbalfwdsetup', 'FinanceControllers\FinanceController@checkbalfwdsetup')->name('checkbalfwdsetup');
    Route::get('/finance/fwdbal', 'FinanceControllers\FinanceController@fwdbal')->name('fwdbal');
    Route::get('/finance/fwdVledger', 'FinanceControllers\FinanceController@fwdVledger')->name('fwdVledger');
    Route::get('/finance/checkExist', 'FinanceControllers\FinanceController@checkExist')->name('checkExist');
    Route::get('/finance/listfwdbal', 'FinanceControllers\FinanceController@listfwdbal')->name('listfwdbal');
    Route::get('/finance/fwdbalpdf', 'FinanceControllers\FinanceController@fwdbalpdf')->name('fwdbalpdf');

    // Route::get('/finance/exampermit','FinanceControllers\FinanceController@exampermit')->name('exampermit');
    Route::get('/finance/permit_studfilter', 'FinanceControllers\FinanceController@permit_studfilter')->name('permit_studfilter');
    Route::get('/finance/permit_allowtoexam', 'FinanceControllers\FinanceController@permit_allowtoexam')->name('permit_allowtoexam');
    Route::get('/finance/permit_loadinfo', 'FinanceControllers\FinanceController@permit_loadinfo')->name('permit_loadinfo');
    Route::get('/finance/permit_loadsetup', 'FinanceControllers\FinanceController@permit_loadsetup')->name('permit_loadsetup');
    Route::get('/finance/permit_activequarter', 'FinanceControllers\FinanceController@permit_activequarter')->name('permit_activequarter');

    Route::get('/finance/exampermit', 'FinanceControllers\ExamPermitController@exampermit')->name('exampermit');
    Route::get('/finance/ep_section', 'FinanceControllers\ExamPermitController@ep_section')->name('ep_section');
    Route::get('/finance/ep_gen', 'FinanceControllers\ExamPermitController@ep_gen')->name('ep_gen');
    Route::get('/finance/ep_accounts', 'FinanceControllers\ExamPermitController@ep_accounts')->name('ep_accounts');
    Route::get('/finance/ep_paysched', 'FinanceControllers\ExamPermitController@ep_paysched')->name('ep_paysched');
    Route::get('/finance/ep_changestatus', 'FinanceControllers\ExamPermitController@ep_changestatus')->name('ep_changestatus');


    Route::get('/finance/salaryrateelevation/{id}', 'FinanceControllers\FinanceController@salaryrateelevation')->name('salaryrateelevation', '{id}');

    // Route::get('/finance/expenses','FinanceControllers\FinanceController@expenses')->name('expenses');
    // Route::get('/finance/searchexpenses','FinanceControllers\FinanceController@searchexpenses')->name('searchexpenses');
    // Route::get('/finance/saveexpense','FinanceControllers\FinanceController@saveexpense')->name('saveexpense');
    // Route::get('/finance/saveexpensedetail','FinanceControllers\FinanceController@saveexpensedetail')->name('saveexpensedetail');
    // Route::get('/finance/loadexpensedetail','FinanceControllers\FinanceController@loadexpensedetail')->name('loadexpensedetail');
    // Route::get('/finance/loadexpense','FinanceControllers\FinanceController@loadexpense')->name('loadexpense');
    // Route::get('/finance/loadexpenseitems','FinanceControllers\FinanceController@loadexpenseitems')->name('loadexpenseitems');
    // Route::get('/finance/saveNewItem','FinanceControllers\FinanceController@saveNewItem')->name('saveNewItem');
    // Route::get('/finance/approveexpense','FinanceControllers\FinanceController@approveexpense')->name('approveexpense');
    // Route::get('/finance/disapproveexpense','FinanceControllers\FinanceController@disapproveexpense')->name('disapproveexpense');
    // Route::get('/finance/expenseItemInfo','FinanceControllers\FinanceController@expenseItemInfo')->name('expenseItemInfo');

    Route::get('/finance/onlinepay', 'FinanceControllers\FinanceController@onlinepay')->name('onlinepay');
    Route::get('/finance/onlinepaymentlist', 'FinanceControllers\FinanceController@onlinepaymentlist')->name('onlinepaymentlist');
    Route::get('/finance/paydata', 'FinanceControllers\FinanceController@paydata')->name('paydata');
    Route::get('/finance/approvepay', 'FinanceControllers\FinanceController@approvepay')->name('approvepay');
    Route::get('/finance/saveolAmount', 'FinanceControllers\FinanceController@saveolAmount')->name('saveolAmount');
    Route::get('/finance/saveolDate', 'FinanceControllers\FinanceController@saveolDate')->name('saveolDate');
    Route::get('/finance/saveolpaytype', 'FinanceControllers\FinanceController@saveolpaytype')->name('saveolpaytype');
    Route::get('/finance/saveolrefnum', 'FinanceControllers\FinanceController@saveolrefnum')->name('saveolrefnum');
    Route::get('/finance/saveoldisapprove', 'FinanceControllers\FinanceController@saveoldisapprove')->name('saveoldisapprove');

    Route::get('/finance/olreceipt', 'FinanceControllers\FinanceController@olreceipt')->name('olreceipt');
    Route::get('/finance/searchOLReceipt', 'FinanceControllers\FinanceController@searchOLReceipt')->name('searchOLReceipt');

    Route::get('/finance/reportbalanceforwarding/{id}', 'FinanceControllers\FinanceReportController@reportbalanceforwarding')->name('reportbalanceforwarding');
    Route::get('/finance/reportonlinepayments/{id}', 'FinanceControllers\FinanceReportController@reportonlinepayments')->name('reportonlinepayments');

    Route::get('/finance/adjustment', 'FinanceControllers\FinanceController@adjustment')->name('adjustment');
    Route::get('/finance/adjustment/adjloadglevel', 'FinanceControllers\FinanceController@adjloadglevel')->name('adjloadglevel');
    Route::get('/finance/adjustment/adjfilter', 'FinanceControllers\FinanceController@adjfilter')->name('adjfilter');
    Route::get('/finance/adjustment/searchadj', 'FinanceControllers\FinanceController@searchadj')->name('searchadj');
    Route::get('/finance/adjustment/viewadj', 'FinanceControllers\FinanceController@viewadj')->name('viewadj');
    Route::get('/finance/adjustment/appendADJ', 'FinanceControllers\FinanceController@appendADJ')->name('appendADJ');
    Route::get('/finance/adjustment/deleteADJ', 'FinanceControllers\FinanceController@deleteADJ')->name('deleteADJ');
    Route::get('/finance/adjustment/approveADJ', 'FinanceControllers\FinanceController@approveADJ')->name('approveADJ');
    Route::get('/finance/adjustment/disapproveADJ', 'FinanceControllers\FinanceController@disapproveADJ')->name('disapproveADJ');
    Route::get('/finance/adjustment/seladdstud', 'FinanceControllers\FinanceController@seladdstud')->name('seladdstud');

    //Route::get('/finance/allowdp','FinanceControllers\FinanceController@allowdp')->name('allowdp');
    Route::view('/finance/allowdp', 'superadmin.pages.student.studentpregistration')->name('allowdp');
    Route::get('/finance/allowdp/loadstudnodp', 'FinanceControllers\FinanceController@loadstudnodp')->name('loadstudnodp');
    Route::get('/finance/allowdp/searchnodp', 'FinanceControllers\FinanceController@searchnodp')->name('searchnodp');
    Route::get('/finance/allowdp/appendnodp', 'FinanceControllers\FinanceController@appendnodp')->name('appendnodp');
    Route::get('/finance/allowdp/removenodp', 'FinanceControllers\FinanceController@removenodp')->name('removenodp');

    Route::get('/finance/transactions/cashtrans', 'FinanceControllers\FinanceController@cashtrans')->name('cashtrans');
    Route::get('/finance/transactions/cashtranssearch', 'FinanceControllers\FinanceController@cashtranssearch')->name('cashtranssearch');
    Route::get('/finance/transactions/transviewdetail', 'FinanceControllers\FinanceController@transviewdetail')->name('transviewdetail');
    Route::get('/finance/transactions/printcashtrans/{terminalid}/{dtfrom}/{dtto}/{filter}/{paytype}/{title}', 'FinanceControllers\FinanceController@printcashtrans');

    Route::get('/finance/reports/dailycashcollection', 'FinanceControllers\FinanceController@dailycashcollection')->name('dailycashcollection');
    Route::get('/finance/reports/dailycashcollection/generateTH', 'FinanceControllers\FinanceController@generateTH')->name('generateTH');
    Route::get('/finance/reports/dailycashcollection/generatereport', 'FinanceControllers\FinanceController@generatereport')->name('generatereport');
    Route::get('/finance/reports/dailycashcollection/distitemamount', 'FinanceControllers\FinanceController@distitemamount')->name('distitemamount');
    Route::get('/finance/reports/dailycashcollection/saveDCR', 'FinanceControllers\FinanceController@saveDCR')->name('saveDCR');

    Route::get('/finance/reports/dailycashcollection/dailycashcollectionpdf/{date}/{terminal}/{action}', 'FinanceControllers\FinanceController@dailycashcollectionpdf')->name('dailycashcollectionpdf');
    Route::get('/finance/reports/dailycashcollection/dailycashsummarypdf', 'FinanceControllers\FinanceController@dailycashsummarypdf')->name('dailycashsummarypdf');

    Route::get('/finance/labfees', 'FinanceControllers\LabfeesController@labfees')->name('labfees');
    Route::get('/finance/labfees/labfee_search', 'FinanceControllers\LabfeesController@labfee_search')->name('labfee_search');
    Route::get('/finance/labfees/labfee_append', 'FinanceControllers\LabfeesController@labfee_append')->name('labfee_append');
    Route::get('/finance/labfees/labfee_edit', 'FinanceControllers\LabfeesController@labfee_edit')->name('labfee_edit');
    Route::get('/finance/labfees/labfee_delete', 'FinanceControllers\LabfeesController@labfee_delete')->name('labfee_delete');

    Route::get('/finance/labfee_setup/labfee_setup_load', 'FinanceControllers\LabfeesController@labfee_setup_load')->name('labfee_setup_load');
    Route::get('/finance/labfee_setup/labfee_setup_append', 'FinanceControllers\LabfeesController@labfee_setup_append')->name('labfee_setup_append');
    Route::get('/finance/labfee_setup/labfee_setup_edit', 'FinanceControllers\LabfeesController@labfee_setup_edit')->name('labfee_setup_edit');
    Route::get('/finance/labfee_setup/labfee_setup_delete', 'FinanceControllers\LabfeesController@labfee_setup_delete')->name('labfee_setup_delete');

    Route::get('/finance/labfee_setup/labfee_duplicate', 'FinanceControllers\LabfeesController@labfee_duplicate')->name('labfee_duplicate');


    Route::get('/finance/utilities', 'FinanceControllers\UtilityController@utilities')->name('utilities');
    Route::get('/finance/utilities/genpayinfo', 'FinanceControllers\UtilityController@genpayinfo')->name('genpayinfo');
    Route::get('/finance/utilities/resetpayment', 'FinanceControllers\UtilityController@resetpayment')->name('resetpayment');
    Route::get('/finance/utilities/genstud', 'FinanceControllers\UtilityController@genstud')->name('genstud');
    Route::get('/finance/utilities/calcLedger', 'FinanceControllers\UtilityController@calcLedger')->name('calcLedger');
    Route::get('/finance/utilities/clearledger', 'FinanceControllers\UtilityController@clearledger')->name('clearledger');
    Route::get('/finance/utilities/fixledgerrow', 'FinanceControllers\UtilityController@fixledgerrow')->name('fixledgerrow');
    Route::get('/finance/utilities/removeledgerrow', 'FinanceControllers\UtilityController@removeledgerrow')->name('removeledgerrow');
    Route::get('/finance/utilities/genitemizedinfo', 'FinanceControllers\UtilityController@genitemizedinfo')->name('genitemizedinfo');

    Route::get('/finance/utilities/ledgeritemizedreset', 'FinanceControllers\UtilityController@ledgeritemizedreset')->name('ledgeritemizedreset');
    Route::get('/finance/utilities/transitemsreset', 'FinanceControllers\UtilityController@transitemsreset')->name('transitemsreset');
    Route::get('/finance/utilities/transitemstruncate', 'FinanceControllers\UtilityController@transitemstruncate')->name('transitemstruncate');
    Route::get('/finance/utilities/ledgeritemizedtruncate', 'FinanceControllers\UtilityController@ledgeritemizedtruncate')->name('ledgeritemizedtruncate');

    Route::get('/finance/utilities/adj_search', 'FinanceControllers\UtilityController@adj_search')->name('adj_search');
    Route::get('/finance/utilities/adj_removedetail', 'FinanceControllers\UtilityController@adj_removedetail')->name('adj_removedetail');

    Route::get('/finance/utilities/fwd_gennegstud', 'FinanceControllers\UtilityController@fwd_gennegstud')->name('fwd_gennegstud');
    Route::get('/finance/utilities/fwd_genstudinfo', 'FinanceControllers\UtilityController@fwd_genstudinfo')->name('fwd_genstudinfo');
    Route::get('/finance/utilities/fwd_fixnegativebal', 'FinanceControllers\UtilityController@fwd_fixnegativebal')->name('fwd_fixnegativebal');

    Route::get('/finance/utilities/dpfix_geninfo', 'FinanceControllers\UtilityController@dpfix_geninfo')->name('dpfix_geninfo');
    Route::get('/finance/utilities/dpfix_loadorinfo', 'FinanceControllers\UtilityController@dpfix_loadorinfo')->name('dpfix_loadorinfo');
    Route::get('/finance/utilities/dpfix_pushtoledger', 'FinanceControllers\UtilityController@dpfix_pushtoledger')->name('dpfix_pushtoledger');

    Route::get('/finance/utilities/disbalance_genstud', 'FinanceControllers\UtilityController@disbalance_genstud')->name('disbalance_genstud');
    Route::get('/finance/utilities/disbaltrans_genstud', 'FinanceControllers\UtilityController@disbaltrans_genstud')->name('disbaltrans_genstud');

    Route::get('/finance/utilities/tlf_generate', 'FinanceControllers\UtilityController@tlf_generate')->name('tlf_generate');
    Route::get('/finance/utilities/tlf_fix', 'FinanceControllers\UtilityController@tlf_fix')->name('tlf_fix');

    Route::get('/finance/utilities/dcc_view', 'FinanceControllers\UtilityController@dcc_view')->name('dcc_view');
    Route::get('/finance/utilities/dcc_headinsert', 'FinanceControllers\UtilityController@dcc_headinsert')->name('dcc_headinsert');
    Route::get('/finance/utilities/dcc_bodyinsert', 'FinanceControllers\UtilityController@dcc_bodyinsert')->name('dcc_bodyinsert');
    Route::get('/finance/utilities/dcc_loadmiscitems', 'FinanceControllers\UtilityController@dcc_loadmiscitems')->name('dcc_loadmiscitems');
    Route::get('/finance/utilities/dcc_addmiscitems', 'FinanceControllers\UtilityController@dcc_addmiscitems')->name('dcc_addmiscitems');

    Route::get('/finance/utilities/be_search', 'FinanceControllers\UtilityController@be_search')->name('be_search');
    Route::get('/finance/utilities/be_remove', 'FinanceControllers\UtilityController@be_remove')->name('be_remove');

    Route::get('/finance/utilities/chrng_addclass', 'FinanceControllers\UtilityController@chrng_addclass')->name('chrng_addclass');
    Route::get('/finance/utilities/chrng_loadclass', 'FinanceControllers\UtilityController@chrng_loadclass')->name('chrng_loadclass');
    Route::get('/finance/utilities/resetpayment_v2', 'FinanceControllers\UtilityController@resetpayment_v2')->name('resetpayment_v2');

    Route::get('/finance/utilities/ltid_generate', 'FinanceControllers\UtilityController@ltid_generate')->name('ltid_generate');
    Route::get('/finance/utilities/ltid_copytransid', 'FinanceControllers\UtilityController@ltid_copytransid')->name('ltid_copytransid');

    Route::get('/finance/utilities/ftd_generate', 'FinanceControllers\UtilityController@ftd_generate')->name('ftd_generate');
    Route::get('/finance/utilities/ftd_trans', 'FinanceControllers\UtilityController@ftd_trans')->name('ftd_trans');
    Route::get('/finance/utilities/ftd_bunkertotd', 'FinanceControllers\UtilityController@ftd_bunkertotd')->name('ftd_bunkertotd');
    Route::get('/finance/utilities/ftd_cashiertdetail', 'FinanceControllers\UtilityController@ftd_cashiertdetail')->name('ftd_cashiertdetail');

    Route::get('/finance/utilities/ftd_cashiertdetail_edit', 'FinanceControllers\UtilityController@ftd_cashiertdetail_edit')->name('ftd_cashiertdetail_edit');
    Route::get('/finance/utilities/ftd_cashiertdetail_update', 'FinanceControllers\UtilityController@ftd_cashiertdetail_update')->name('ftd_cashiertdetail_update');

    Route::get('/finance/utilities/tvl_resetpaysched', 'FinanceControllers\UtilityController@tvl_resetpaysched')->name('tvl_resetpaysched');

    Route::get('/finance/utilities/disbalance_genstud_list', 'FinanceControllers\UtilityController@disbalance_genstud_list')->name('disbalance_genstud_list');

    Route::get('/finance/utilities/paysched_loaddetails', 'FinanceControllers\UtilityController@paysched_loaddetails')->name('paysched_loaddetails');
    Route::get('/finance/utilities/paysched_edit', 'FinanceControllers\UtilityController@paysched_edit')->name('paysched_edit');
    Route::get('/finance/utilities/paysched_update', 'FinanceControllers\UtilityController@paysched_update')->name('paysched_update');

    Route::get('/finance/utilities/fwd_vpbf_generate', 'FinanceControllers\UtilityController@fwd_vpbf_generate')->name('fwd_vpbf_generate');

    Route::get('/finance/utilities/fwd_vpbf_generate', 'FinanceControllers\UtilityController@fwd_vpbf_generate')->name('fwd_vpbf_generate');

    Route::get('/finance/utilities/resetpayment_v3', 'FinanceControllers\UtilityController@resetpayment_v3')->name('resetpayment_v3');

    Route::get('/finance/utilities/besetup_save', 'FinanceControllers\UtilityController@besetup_save')->name('besetup_save');

    Route::get('/finance/utilities/trxitemized_generatetrx', 'FinanceControllers\UtilityController@trxitemized_generatetrx')->name('trxitemized_generatetrx');

    Route::get('/finance/utilities/trxitemized_generatetrxitemized', 'FinanceControllers\UtilityController@trxitemized_generatetrxitemized')->name('trxitemized_generatetrxitemized');
    Route::get('/finance/utilities/trxitemized_savetrxitems', 'FinanceControllers\UtilityController@trxitemized_savetrxitems')->name('trxitemized_savetrxitems');

    Route::get('/reportsetup', 'FinanceControllers\ReportSetupController@index')->name('reportsetup');
    Route::get('/reportsetup/createreport', 'FinanceControllers\ReportSetupController@createreport')->name('createreport');
    Route::get('/reportsetup/getaccheaders', 'FinanceControllers\ReportSetupController@getaccheaders')->name('getaccheaders');
    Route::get('/reportsetup/saveheader', 'FinanceControllers\ReportSetupController@saveheader')->name('saveheader');
    Route::get('/reportsetup/getheaders', 'FinanceControllers\ReportSetupController@getheaders')->name('getheaders');
    Route::get('/reportsetup/getsubs', 'FinanceControllers\ReportSetupController@getsubs')->name('getsubs');
    Route::get('/reportsetup/getgroups', 'FinanceControllers\ReportSetupController@getgroups')->name('getgroups');
    Route::get('/reportsetup/savesub', 'FinanceControllers\ReportSetupController@savesub')->name('savesub');
    Route::get('/reportsetup/getdetails', 'FinanceControllers\ReportSetupController@getdetails')->name('getdetails');
    Route::get('/reportsetup/getmaps', 'FinanceControllers\ReportSetupController@getmaps')->name('getmaps');
    Route::get('/reportsetup/savedetail', 'FinanceControllers\ReportSetupController@savedetail')->name('savedetail');
    Route::get('/reportsetup/deleteheader', 'FinanceControllers\ReportSetupController@deleteheader')->name('deleteheader');
    Route::get('/reportsetup/deletesub', 'FinanceControllers\ReportSetupController@deletesub')->name('deletesub');
    Route::get('/reportsetup/deletedetail', 'FinanceControllers\ReportSetupController@deletedetail')->name('deletedetail');
    Route::get('/reportsetup/getdetailinfo', 'FinanceControllers\ReportSetupController@getdetailinfo')->name('getdetailinfo');
    Route::get('/reportsetup/updatedetail', 'FinanceControllers\ReportSetupController@updatedetail')->name('updatedetail');
    Route::get('/reportsetup/setupexport', 'FinanceControllers\ReportSetupController@setupexport')->name('setupexport');

    Route::get('/studentassessment', 'FinanceControllers\StudentAssessmentController@index')->name('studentassessment');
    Route::get('/studentassessment/filter', 'FinanceControllers\StudentAssessmentController@filter')->name('studentassessmentfilter');
    Route::get('/studentassessment/export', 'FinanceControllers\StudentAssessmentController@export')->name('studentassessmentexport');

    Route::get('/cashreceiptsummary/index', 'FinanceControllers\CashReceiptController@index')->name('cashreceiptsummary');
    Route::get('/cashreceiptsummary/filter', 'FinanceControllers\CashReceiptController@filter')->name('cashreceiptfilter');
    Route::get('/cashreceiptsummary/export', 'FinanceControllers\CashReceiptController@export')->name('cashreceiptexport');

    Route::get('/acctreceivable', 'FinanceControllers\AccountsReceivableController@index')->name('acctreceivable');
    Route::get('/acctreceivable/default', 'FinanceControllers\AccountsReceivableController@default')->name('acctreceivabledefault');
    Route::get('/acctreceivable/getsections', 'FinanceControllers\AccountsReceivableController@getsections')->name('acctreceivablegetsections');
    Route::get('/acctreceivable/filter', 'FinanceControllers\AccountsReceivableController@filter')->name('acctreceivablefilter');
    Route::get('/acctreceivable/export', 'FinanceControllers\AccountsReceivableController@export')->name('acctreceivableexport');
    Route::get('/acctreceivable/ar_getlevel', 'FinanceControllers\AccountsReceivableController@ar_getlevel')->name('ar_getlevel');


    Route::get('/statementofacct', 'FinanceControllers\StatementofAccountController@index')->name('statementofacct');
    Route::get('/statementofacctgenerate', 'FinanceControllers\StatementofAccountController@generate')->name('statementofacctgenerate');
    Route::get('/statementofacctgetaccount', 'FinanceControllers\StatementofAccountController@getaccount_v2')->name('statementofacctgetaccount');
    Route::get('/statementofacctexport', 'FinanceControllers\StatementofAccountController@export')->name('statementofacctexport');
    Route::get('/statementofacctexportall', 'FinanceControllers\StatementofAccountController@exportall')->name('statementofacctexportall');
    Route::get('/statementofacctgetnote', 'FinanceControllers\StatementofAccountController@getnote')->name('statementofacctgetnote');
    Route::get('/statementofacctsubmitnotes', 'FinanceControllers\StatementofAccountController@submitnotes')->name('statementofacctsubmitnotes');

    Route::get('/finance/dpv2', 'FinanceControllers\FinanceController@dpv2')->name('dpv2');
    Route::get('/finance/dpv2_loadclass', 'FinanceControllers\FinanceController@dpv2_loadclass')->name('dpv2_loadclass');
    Route::get('/finance/dpv2_loadclassitems', 'FinanceControllers\FinanceController@dpv2_loadclassitems')->name('dpv2_loadclassitems');
    Route::get('/finance/dpv2_appenddpitem', 'FinanceControllers\FinanceController@dpv2_appenddpitem')->name('dpv2_appenddpitem');
    Route::get('/finance/dpv2_loaddpitems', 'FinanceControllers\FinanceController@dpv2_loaddpitems')->name('dpv2_loaddpitems');
    Route::get('/finance/dpv2_loaddp', 'FinanceControllers\FinanceController@dpv2_loaddp')->name('dpv2_loaddp');
    Route::get('/finance/dpv2_removedpitem', 'FinanceControllers\FinanceController@dpv2_removedpitem')->name('dpv2_removedpitem');
    Route::get('/finance/dpv2_updatedpitem', 'FinanceControllers\FinanceController@dpv2_updatedpitem')->name('dpv2_updatedpitem');

    Route::get('/finance/ee_save', 'FinanceControllers\FinanceController@ee_save')->name('ee_save');

    Route::get('/finance/reports/consolidated/index', 'FinanceControllers\ConsolidatedReportController@index')->name('consolidatedindex');
    Route::get('/finance/reports/consolidated/generate', 'FinanceControllers\ConsolidatedReportController@generate_v2')->name('consolidatedgenerate');
    Route::get('/finance/reports/consolidated/consolidated_chktrans', 'FinanceControllers\ConsolidatedReportController@consolidated_chktrans')->name('consolidated_chktrans');
    Route::get('/finance/reports/consolidated/consolidated_trans', 'FinanceControllers\ConsolidatedReportController@consolidated_trans')->name('consolidated_trans');
    Route::get('/finance/reports/consolidated/consolidated_trans_update', 'FinanceControllers\ConsolidatedReportController@consolidated_trans_update')->name('consolidated_trans_update');

    Route::get('/finance/studledger/ledgeradj_loadadjinfo', 'FinanceControllers\FinanceController@ledgeradj_loadadjinfo')->name('ledgeradj_loadadjinfo');
    Route::get('/finance/studledger/ledgeradj_loadcreditinfo', 'FinanceControllers\FinanceController@ledgeradj_loadcreditinfo')->name('ledgeradj_loadcreditinfo');
    Route::get('/finance/studledger/ledgeradj_debitsave', 'FinanceControllers\FinanceController@ledgeradj_debitsave')->name('ledgeradj_debitsave');
    Route::get('/finance/studledger/ledgeradj_creditsave', 'FinanceControllers\FinanceController@ledgeradj_creditsave')->name('ledgeradj_creditsave');

    Route::get('/finance/ledger_reminder', 'FinanceControllers\FinanceController@ledger_reminder')->name('ledger_reminder');

    Route::get('/finance/espsetup', 'FinanceControllers\ESPSetupController@espsetup')->name('espsetup');
    Route::get('/finance/esp_loaddetail', 'FinanceControllers\ESPSetupController@esp_loaddetail')->name('esp_loaddetail');
    Route::get('/finance/esp_update', 'FinanceControllers\ESPSetupController@esp_update')->name('esp_update');

    Route::get('/finance/books', 'FinanceControllers\BookController@books')->name('books');
    Route::get('/finance/book_search', 'FinanceControllers\BookController@book_search')->name('book_search');
    Route::get('/finance/book_append', 'FinanceControllers\BookController@book_append')->name('book_append');

    Route::get('/finance/ledgeradj_delete', 'FinanceControllers\FinanceController@ledgeradj_delete')->name('ledgeradj_delete');
    Route::get('/finance/ledgerdiscount_delete', 'FinanceControllers\FinanceController@ledgerdiscount_delete')->name('ledgerdiscount_delete');
    Route::get('/finance/ledgeroa_delete', 'FinanceControllers\FinanceController@ledgeroa_delete')->name('ledgeroa_delete');

    Route::get('/finance/assessmentunit', 'FinanceControllers\AssessmentUnitController@assessmentunit')->name('assessmentunit');
    Route::get('/finance/assessmentunit/au_search', 'FinanceControllers\AssessmentUnitController@au_search')->name('au_search');
    Route::get('/finance/assessmentunit/au_subjinfo', 'FinanceControllers\AssessmentUnitController@au_subjinfo')->name('au_subjinfo');
    Route::get('/finance/assessmentunit/au_savesubj', 'FinanceControllers\AssessmentUnitController@au_savesubj')->name('au_savesubj');
    Route::get('/finance/assessmentunit/au_edit', 'FinanceControllers\AssessmentUnitController@au_edit')->name('au_edit');
    Route::get('/finance/assessmentunit/au_update', 'FinanceControllers\AssessmentUnitController@au_update')->name('au_update');
    Route::get('/finance/assessmentunit/au_delete', 'FinanceControllers\AssessmentUnitController@au_delete')->name('au_delete');

    Route::get('/finance/studupdateledger', 'FinanceControllers\StudUpdateLedgerController@studupdateledger')->name('studupdateledger');
    Route::get('/finance/studupdateledger/loadsection', 'FinanceControllers\StudUpdateLedgerController@studupdateledgerLoadSections')->name('studupdateledgerLoadSections');
    Route::get('/finance/studupdateledger/loadstudents', 'FinanceControllers\StudUpdateLedgerController@studupdateledgerLoadStudents')->name('studupdateledgerLoadStudents');
    Route::get('/finance/studupdateledger/stud_loadfee', 'FinanceControllers\StudUpdateLedgerController@stud_loadfee')->name('stud_loadfee');

    Route::get('/finance/itemclassification/index', 'FinanceControllers\ItmclsItemClassificationController@itemclassification')->name('itemclassification_v2');
    Route::get('/finance/itemclassification/itmclsgenerate', 'FinanceControllers\ItmclsItemClassificationController@itmclsgenerate')->name('itmclsgenerate');
    Route::get('/finance/itemclassification/itmclscreate', 'FinanceControllers\ItmclsItemClassificationController@itmclscreate')->name('itmclscreate');
    Route::get('/finance/itemclassification/itmclsread', 'FinanceControllers\ItmclsItemClassificationController@itmclsread')->name('itmclsread');
    Route::get('/finance/itemclassification/itmclsupdate', 'FinanceControllers\ItmclsItemClassificationController@itmclsupdate')->name('itmclsupdate');
    Route::get('/finance/itemclassification/itmclsdelete', 'FinanceControllers\ItmclsItemClassificationController@itmclsdelete')->name('itmclsdelete');

    Route::get('/finance/actvglvl/actvglvlload', 'FinanceControllers\FinanceController@actvglvlload')->name('actvglvlload');
    Route::get('/finance/readytoenroll/load', 'FinanceControllers\FinanceController@readytoenroll_load')->name('readytoenroll_load');

    Route::get('/finance/ledger_studyload', 'FinanceControllers\FinanceController@ledger_studyload')->name('ledger_studyload');

    Route::get('/finance/feescollected', 'FinanceControllers\FeesCollectedController@feescollected')->name('feescollected');
    Route::get('/finance/fc_generate', 'FinanceControllers\FeesCollectedController@fc_generate')->name('fc_generate');

    Route::get('/finance/assessedfees', 'FinanceControllers\FeesCollectedController@assessedfees')->name('assessedfees');
    Route::get('/finance/af_generate', 'FinanceControllers\FeesCollectedController@af_generate')->name('af_generate');

    Route::get('/finance/reports/oareceivables', 'FinanceControllers\OAReceivablesController@oareceivables')->name('oareceivables');
    Route::get('/finance/reports/oareceivables_load', 'FinanceControllers\OAReceivablesController@oareceivables_load')->name('oareceivables_load');

    Route::get('/finance/scholarship', 'FinanceControllers\FinanceScholarshipController@index')->name('financescholarship');
    Route::post('/finance/scholarship_setup_create', 'FinanceControllers\FinanceScholarshipController@scholarship_setup_create')->name('scholarship_setup_create');
    Route::get('/finance/scholarship_setup_load', 'FinanceControllers\FinanceScholarshipController@scholarship_setup_load')->name('scholarship_setup_load');
    Route::post('/finance/scholarship_setup_delete', 'FinanceControllers\FinanceScholarshipController@scholarship_setup_delete')->name('scholarship_setup_delete');
    Route::get('/finance/scholarship_setup_read', 'FinanceControllers\FinanceScholarshipController@scholarship_setup_read')->name('scholarship_setup_read');
    Route::post('/finance/scholarship_setup_upload', 'FinanceControllers\FinanceScholarshipController@scholarship_setup_upload')->name('scholarship_setup_upload');

    Route::get('/finance/scholarship_setup_detail_read', 'FinanceControllers\FinanceScholarshipController@scholarship_setup_detail_read')->name('scholarship_setup_detail_read');
    Route::post('/finance/scholarship_setup_detail_delete', 'FinanceControllers\FinanceScholarshipController@scholarship_setup_detail_delete')->name('scholarship_setup_detail_delete');

    Route::get('/finance/scholarship_app_load', 'FinanceControllers\FinanceScholarshipController@scholarship_app_load')->name('scholarship_app_load');
    Route::get('/finance/scholarship_app_read', 'FinanceControllers\FinanceScholarshipController@scholarship_app_read')->name('scholarship_app_read');

    Route::post('/finance/scholarship_app_approve', 'FinanceControllers\FinanceScholarshipController@scholarship_app_approve')->name('scholarship_app_approve');
    Route::post('/finance/scholarship_app_disapprove', 'FinanceControllers\FinanceScholarshipController@scholarship_app_disapprove')->name('scholarship_app_disapprove');
    Route::get('/finance/scholarship_app_adj', 'FinanceControllers\FinanceScholarshipController@scholarship_app_adj')->name('scholarship_app_adj');
});



Route::get('/printable/fees/generate', 'SuperAdminController\Printables\EnrollmentPrintables@print_fess');

Route::middleware(['auth', 'isAccounting'])->group(function () {
    Route::get('/finance/accounting', 'FinanceControllers\AccountingController@financeSetup')->name('financeSetup');

    Route::get('/finance/accounting/setup', 'FinanceControllers\AccountingController@setup')->name('setup');
    Route::get('finance/accounting/reportsetup', 'FinanceControllers\ReportSetupController@index')->name('reportsetup');
    Route::get('finance/accounting/accountingsetup', 'FinanceControllers\AccountingController@accountingsetup')->name('accountingsetup');
    Route::get('/reportsetup/createreport', 'FinanceControllers\ReportSetupController@createreport')->name('createreport');

    Route::get('/finance/coa/chartofaccounts', 'FinanceControllers\FinanceController@chartofaccounts')->name('chartofaccounts');
    Route::get('/finance/coa/loadchart', 'FinanceControllers\FinanceController@loadchart')->name('loadchart');
    Route::get('/finance/coa/loadgroup', 'FinanceControllers\FinanceController@loadgroup')->name('loadgroup');
    Route::get('/finance/coa/loadgroups', 'FinanceControllers\FinanceController@loadgroups')->name('loadgroups');

    Route::get('/finance/coa/saveacctype', 'FinanceControllers\FinanceController@saveacctype')->name('saveacctype');
    Route::get('/finance/coa/editacctype', 'FinanceControllers\FinanceController@editacctype')->name('editacctype');
    Route::get('/finance/coa/updateacctype', 'FinanceControllers\FinanceController@updateacctype')->name('updateacctype');
    Route::get('/finance/coa/deleteacctype', 'FinanceControllers\FinanceController@deleteacctype')->name('deleteacctype');

    Route::get('/finance/coa/loadaccname', 'FinanceControllers\FinanceController@loadaccname')->name('loadaccname');
    Route::get('/finance/coa/saveaccname', 'FinanceControllers\FinanceController@saveaccname')->name('saveaccname');
    Route::get('/finance/coa/editaccname', 'FinanceControllers\FinanceController@editaccname')->name('editaccname');
    Route::get('/finance/coa/updateaccname', 'FinanceControllers\FinanceController@updateaccname')->name('updateaccname');
    Route::get('/finance/coa/deleteaccname', 'FinanceControllers\FinanceController@deleteaccname')->name('deleteaccname');


    Route::get('/finance/coa/loadsubname', 'FinanceControllers\FinanceController@loadsubname')->name('loadsubname');
    Route::get('/finance/coa/savesubname', 'FinanceControllers\FinanceController@savesubname')->name('savesubname');
    Route::get('/finance/coa/editsubname', 'FinanceControllers\FinanceController@editsubname')->name('editsubname');
    Route::get('/finance/coa/updatesubname', 'FinanceControllers\FinanceController@updatesubname')->name('updatesubname');
    Route::get('/finance/coa/deletesubname', 'FinanceControllers\FinanceController@deletesubname')->name('deletesubname');

    Route::get('/finance/coa/savesubitem', 'FinanceControllers\FinanceController@savesubitem')->name('savesubitem');
    Route::get('/finance/coa/editsubitem', 'FinanceControllers\FinanceController@editsubitem')->name('editsubitem');
    Route::get('/finance/coa/updatesubitem', 'FinanceControllers\FinanceController@updatesubitem')->name('updatesubitem');
    Route::get('/finance/coa/deletesubitem', 'FinanceControllers\FinanceController@deletesubitem')->name('deletesubitem');

    Route::get('/finance/coa/switchacc', 'FinanceControllers\FinanceController@switchacc')->name('switchacc');

    Route::get('/finance/coa/coamapping', 'FinanceControllers\FinanceController@coamapping')->name('coamapping');
    Route::get('/finance/coa/loadmapping', 'FinanceControllers\FinanceController@loadmapping')->name('loadmapping');
    Route::get('/finance/coa/savemapping', 'FinanceControllers\FinanceController@savemapping')->name('savemapping');
    Route::get('/finance/coa/editmapping', 'FinanceControllers\FinanceController@editmapping')->name('editmapping');
    Route::get('/finance/coa/updatemapping', 'FinanceControllers\FinanceController@updatemapping')->name('updatemapping');
    Route::get('/finance/coa/deletemapping', 'FinanceControllers\FinanceController@deletemapping')->name('deletemapping');

    Route::get('/finance/accounting/journalentries', 'FinanceControllers\AccountingController@journalentries')->name('journalentries');
    Route::get('/finance/accounting/jeloadcoa', 'FinanceControllers\AccountingController@jeloadcoa')->name('jeloadcoa');

    Route::get('/finance/accounting/loadje', 'FinanceControllers\AccountingController@loadje')->name('loadje');
    Route::get('/finance/accounting/saveje', 'FinanceControllers\AccountingController@saveje')->name('saveje');
    Route::get('/finance/accounting/editje', 'FinanceControllers\AccountingController@editje')->name('editje');
    Route::get('/finance/accounting/deletejedetail', 'FinanceControllers\AccountingController@deletejedetail')->name('deletejedetail');
    Route::get('/finance/accounting/appendeditdetail', 'FinanceControllers\AccountingController@appendeditdetail')->name('appendeditdetail');
    Route::get('/finance/accounting/postje', 'FinanceControllers\AccountingController@postje')->name('postje');

    Route::get('/finance/accounting/journalentries', 'FinanceControllers\AccountingController@journalentries')->name('journalentries');
    Route::get('/finance/accounting/jeloadcoa', 'FinanceControllers\AccountingController@jeloadcoa')->name('jeloadcoa');

    Route::get('/finance/accounting/loadje', 'FinanceControllers\AccountingController@loadje')->name('loadje');
    Route::get('/finance/accounting/saveje', 'FinanceControllers\AccountingController@saveje')->name('saveje');
    Route::get('/finance/accounting/editje', 'FinanceControllers\AccountingController@editje')->name('editje');
    Route::get('/finance/accounting/deletejedetail', 'FinanceControllers\AccountingController@deletejedetail')->name('deletejedetail');
    Route::get('/finance/accounting/appendeditdetail', 'FinanceControllers\AccountingController@appendeditdetail')->name('appendeditdetail');
    Route::get('/finance/accounting/postje', 'FinanceControllers\AccountingController@postje')->name('postje');

    Route::get('/finance/accounting/je_calcdebit', 'FinanceControllers\AccountingController@je_calcdebit')->name('je_calcdebit');

    Route::get('/finance/accounting/reports', 'FinanceControllers\AccountingController@reports')->name('reports');

    Route::get('/finance/purchasing/vendor', 'FinanceControllers\PurchasingController@vendor')->name('vendor');
    Route::get('/finance/purchasing/vendor/create', 'FinanceControllers\PurchasingController@vendor_update')->name('vendor_update');
    Route::get('/finance/purchasing/vendor/load', 'FinanceControllers\PurchasingController@vendor_load')->name('vendor_load');
    Route::get('/finance/purchasing/vendor/edit', 'FinanceControllers\PurchasingController@vendor_edit')->name('vendor_edit');
    Route::get('/finance/purchasing/vendor/delete', 'FinanceControllers\PurchasingController@vendor_delete')->name('vendor_delete');

    Route::get('/finance/purchasing/purchaseorder', 'FinanceControllers\PurchasingController@purchaseorder')->name('purchaseorder');
    Route::get('/finance/purchasing/purchase_load', 'FinanceControllers\PurchasingController@purchase_load')->name('purchase_load');
    Route::get('/finance/purchasing/purchase_loaditems', 'FinanceControllers\PurchasingController@purchase_loaditems')->name('purchase_loaditems');
    Route::get('/finance/purchasing/purchase_create', 'FinanceControllers\PurchasingController@purchase_create')->name('purchase_create');
    Route::get('/finance/purchasing/purchase_read', 'FinanceControllers\PurchasingController@purchase_read')->name('purchase_read');
    Route::get('/finance/purchasing/purchase_deleteitem', 'FinanceControllers\PurchasingController@purchase_deleteitem')->name('purchase_deleteitem');
    Route::get('/finance/purchasing/purchase_delete', 'FinanceControllers\PurchasingController@purchase_delete')->name('purchase_delete');
    Route::get('/finance/purchasing/purchase_post', 'FinanceControllers\PurchasingController@purchase_post')->name('purchase_post');
    Route::get('/finance/purchasing/purchase_print', 'FinanceControllers\PurchasingController@purchase_print')->name('purchase_print');


    Route::get('/finance/purchasing/receiving', 'FinanceControllers\PurchasingController@receiving')->name('receiving');
    Route::get('/finance/purchasing/receiving_load', 'FinanceControllers\PurchasingController@receiving_load')->name('receiving_load');
    Route::get('/finance/purchasing/receiving_read', 'FinanceControllers\PurchasingController@receiving_read')->name('receiving_read');
    Route::get('/finance/purchasing/receiving_save', 'FinanceControllers\PurchasingController@receiving_save')->name('receiving_save');
    Route::get('/finance/purchasing/receiving_post', 'FinanceControllers\PurchasingController@receiving_post')->name('receiving_post');
    Route::get('/finance/purchasing/receiving_print', 'FinanceControllers\PurchasingController@receiving_print')->name('receiving_print');
    Route::get('/finance/purchasing/receiving_removesched', 'FinanceControllers\PurchasingController@receiving_removesched')->name('receiving_removesched');

    Route::get('/finance/purchasing/po_additem', 'FinanceControllers\PurchasingController@po_additem')->name('po_additem');

    Route::get('/reportsetup/getconfigsetup', 'FinanceControllers\ReportSetupController@getconfigsetup')->name('getconfigsetup');
    Route::get('/reportsetup/getaddheader', 'FinanceControllers\ReportSetupController@getaddheader')->name('getaddheader');
    Route::get('/reportsetup/submitaddheader', 'FinanceControllers\ReportSetupController@submitaddheader')->name('submitaddheader');
    Route::get('/reportsetup/getaddsubheader', 'FinanceControllers\ReportSetupController@getaddsubheader')->name('getaddsubheader');
    Route::get('/reportsetup/submitaddsubheader', 'FinanceControllers\ReportSetupController@submitaddsubheader')->name('submitaddsubheader');
    Route::get('/reportsetup/getaddmap', 'FinanceControllers\ReportSetupController@getaddmap')->name('getaddmap');
    Route::get('/reportsetup/submitadddetail', 'FinanceControllers\ReportSetupController@submitadddetail')->name('submitadddetail');
    Route::get('/reportsetup/submiteditdetail', 'FinanceControllers\ReportSetupController@submiteditdetail')->name('submiteditdetail');
    Route::get('/reportsetup/deleteheader', 'FinanceControllers\ReportSetupController@deleteheader')->name('deleteheader');
    Route::get('/reportsetup/deletesubheader', 'FinanceControllers\ReportSetupController@deletesubheader')->name('deletesubheader');
    Route::get('/reportsetup/deletedetail', 'FinanceControllers\ReportSetupController@deletedetail')->name('deletedetail');
    Route::get('/reportsetup/setupexport', 'FinanceControllers\ReportSetupController@setupexport')->name('setupexport');

    //IS GENERATEION GTG
    Route::get('/finance/accounting/is_generate', 'FinanceControllers\AccountingController@is_generate')->name('is_generate');
    //IS GENERATEION GTG

    //BS GENERATEION GTG
    Route::get('/finance/accounting/bs_generate', 'FinanceControllers\AccountingController@bs_generate')->name('bs_generate');
    //BS GENERATEION GTG

    //GL GENERATEION GTG
    Route::get('/finance/accounting/gl_generate', 'FinanceControllers\AccountingController@gl_generate')->name('gl_generate');
    //GL GENERATEION GTG

    //SL GENERATEION GTG
    Route::get('/finance/accounting/sl_generate', 'FinanceControllers\AccountingController@sl_generate')->name('sl_generate');
    //SL GENERATEION GTG

    //JE AUTO GENERATION
    Route::get('/finance/accounting/je_autogenerate', 'FinanceControllers\AccountingController@je_autogenerate')->name('je_autogenerate');
    //JE AUTO GENERATION

    //TB GENERATEION GTG
    Route::get('/finance/accounting/tb_generate', 'FinanceControllers\AccountingController@tb_generate')->name('tb_generate');
    //TB GENERATEION GTG
});


Route::middleware(['auth', 'isFinanceAdmin'])->group(function () {
    Route::get('/finance/setup', 'FinanceControllers\FinanceController@financeSetup')->name('financeSetup');

    Route::get('/finance/loadTerminal', 'FinanceControllers\FinanceController@loadTerminal')->name('loadTerminal');
    Route::get('/finance/clearTerminal', 'FinanceControllers\FinanceController@clearTerminal')->name('clearTerminal');
    Route::get('/finance/createTerminal', 'FinanceControllers\FinanceController@createTerminal')->name('createTerminal');
    Route::get('/finance/removeTerminal', 'FinanceControllers\FinanceController@removeTerminal')->name('removeTerminal');

    Route::get('/finance/loadCOA', 'FinanceControllers\FinanceController@loadCOA')->name('loadCOA');
    Route::get('/finance/appendCOA', 'FinanceControllers\FinanceController@appendCOA')->name('appendCOA');
    Route::get('/finance/editCOA', 'FinanceControllers\FinanceController@editCOA')->name('editCOA');
    Route::get('/finance/updateCOA', 'FinanceControllers\FinanceController@updateCOA')->name('updateCOA');
    Route::get('/finance/deleteCOA', 'FinanceControllers\FinanceController@deleteCOA')->name('deleteCOA');

    Route::get('/finance/loadCOAGroup', 'FinanceControllers\FinanceController@loadCOAGroup')->name('loadCOAGroup');
    Route::get('/finance/appendCOAGroup', 'FinanceControllers\FinanceController@appendCOAGroup')->name('appendCOAGroup');

    // Route::get('/finance/coa/chartofaccounts','FinanceControllers\FinanceController@chartofaccounts')->name('chartofaccounts');
    Route::get('/finance/coa/loadchart', 'FinanceControllers\FinanceController@loadchart')->name('loadchart');
    Route::get('/finance/coa/loadgroup', 'FinanceControllers\FinanceController@loadgroup')->name('loadgroup');
    Route::get('/finance/coa/loadgroups', 'FinanceControllers\FinanceController@loadgroups')->name('loadgroups');

    Route::get('/finance/coa/chartofaccounts', 'FinanceControllers\COAController@chartofaccounts')->name('chartofaccounts');
    Route::get('/finance/coa/coa_group', 'FinanceControllers\COAController@coa_group')->name('coa_group');
    Route::get('/finance/coa/coa_view', 'FinanceControllers\COAController@coa_view')->name('coa_view');
    Route::get('/finance/coa/coa_viewgroup', 'FinanceControllers\COAController@coa_viewgroup')->name('coa_viewgroup');
    Route::get('/finance/coa/coa_saveaccount', 'FinanceControllers\COAController@coa_saveaccount')->name('coa_saveaccount');
    Route::get('/finance/coa/coa_removeaccount', 'FinanceControllers\COAController@coa_removeaccount')->name('coa_removeaccount');
    Route::get('/finance/coa/coa_editaccount', 'FinanceControllers\COAController@coa_editaccount')->name('coa_editaccount');
    Route::get('/finance/coa/coa_addsubaccount', 'FinanceControllers\COAController@coa_addsubaccount')->name('coa_addsubaccount');
    Route::get('/finance/coa/coa_classcreate', 'FinanceControllers\COAController@coa_classcreate')->name('coa_classcreate');

    Route::get('/finance/coa/coa_class_load', 'FinanceControllers\COAController@coa_class_load')->name('coa_class_load');
    Route::get('/finance/coa/coa_class_update', 'FinanceControllers\COAController@coa_class_update')->name('coa_class_update');
    Route::get('/finance/coa/coa_class_create', 'FinanceControllers\COAController@coa_class_create')->name('coa_class_create');
    Route::get('/finance/coa/coa_class_remove', 'FinanceControllers\COAController@coa_class_remove')->name('coa_class_remove');
    Route::get('/finance/coa/coa_coaclass_load', 'FinanceControllers\COAController@coa_coaclass_load')->name('coa_coaclass_load');

    Route::get('/finance/coa/coa_acctype_create', 'FinanceControllers\COAController@coa_acctype_create')->name('coa_acctype_create');
    Route::get('/finance/coa/coa_acctype_read', 'FinanceControllers\COAController@coa_acctype_read')->name('coa_acctype_read');
    Route::get('/finance/coa/coa_acctype_update', 'FinanceControllers\COAController@coa_acctype_update')->name('coa_acctype_update');
    Route::get('/finance/coa/coa_acctype_delete', 'FinanceControllers\COAController@coa_acctype_delete')->name('coa_acctype_delete');

    Route::get('/finance/coa/saveacctype', 'FinanceControllers\FinanceController@saveacctype')->name('saveacctype');
    Route::get('/finance/coa/editacctype', 'FinanceControllers\FinanceController@editacctype')->name('editacctype');
    Route::get('/finance/coa/updateacctype', 'FinanceControllers\FinanceController@updateacctype')->name('updateacctype');
    Route::get('/finance/coa/deleteacctype', 'FinanceControllers\FinanceController@deleteacctype')->name('deleteacctype');

    Route::get('/finance/coa/loadaccname', 'FinanceControllers\FinanceController@loadaccname')->name('loadaccname');
    Route::get('/finance/coa/saveaccname', 'FinanceControllers\FinanceController@saveaccname')->name('saveaccname');
    Route::get('/finance/coa/editaccname', 'FinanceControllers\FinanceController@editaccname')->name('editaccname');
    Route::get('/finance/coa/updateaccname', 'FinanceControllers\FinanceController@updateaccname')->name('updateaccname');
    Route::get('/finance/coa/deleteaccname', 'FinanceControllers\FinanceController@deleteaccname')->name('deleteaccname');


    Route::get('/finance/coa/loadsubname', 'FinanceControllers\FinanceController@loadsubname')->name('loadsubname');
    Route::get('/finance/coa/savesubname', 'FinanceControllers\FinanceController@savesubname')->name('savesubname');
    Route::get('/finance/coa/editsubname', 'FinanceControllers\FinanceController@editsubname')->name('editsubname');
    Route::get('/finance/coa/updatesubname', 'FinanceControllers\FinanceController@updatesubname')->name('updatesubname');
    Route::get('/finance/coa/deletesubname', 'FinanceControllers\FinanceController@deletesubname')->name('deletesubname');

    Route::get('/finance/coa/savesubitem', 'FinanceControllers\FinanceController@savesubitem')->name('savesubitem');
    Route::get('/finance/coa/editsubitem', 'FinanceControllers\FinanceController@editsubitem')->name('editsubitem');
    Route::get('/finance/coa/updatesubitem', 'FinanceControllers\FinanceController@updatesubitem')->name('updatesubitem');
    Route::get('/finance/coa/deletesubitem', 'FinanceControllers\FinanceController@deletesubitem')->name('deletesubitem');

    Route::get('/finance/coa/switchacc', 'FinanceControllers\FinanceController@switchacc')->name('switchacc');

    Route::get('/finance/coa/coamapping', 'FinanceControllers\FinanceController@coamapping')->name('coamapping');
    Route::get('/finance/coa/loadmapping', 'FinanceControllers\FinanceController@loadmapping')->name('loadmapping');
    Route::get('/finance/coa/savemapping', 'FinanceControllers\FinanceController@savemapping')->name('savemapping');
    Route::get('/finance/coa/editmapping', 'FinanceControllers\FinanceController@editmapping')->name('editmapping');
    Route::get('/finance/coa/updatemapping', 'FinanceControllers\FinanceController@updatemapping')->name('updatemapping');
    Route::get('/finance/coa/deletemapping', 'FinanceControllers\FinanceController@deletemapping')->name('deletemapping');

    Route::get('/finance/loadUE', 'FinanceControllers\FinanceController@loadUE')->name('loadUE');
    Route::get('/finance/processUE', 'FinanceControllers\FinanceController@processUE')->name('processUE');

    Route::get('/finance/dploadAcadprog', 'FinanceControllers\FinanceController@dploadAcadprog')->name('dploadAcadprog');
    Route::get('/finance/dploadglevel', 'FinanceControllers\FinanceController@dploadglevel')->name('dploadglevel');
    Route::get('/finance/togglenodp', 'FinanceControllers\FinanceController@togglenodp')->name('togglenodp');
    Route::get('/finance/togglenodpesc', 'FinanceControllers\FinanceController@togglenodpesc')->name('togglenodpesc');
    Route::get('/finance/togglenodpvoucher', 'FinanceControllers\FinanceController@togglenodpvoucher')->name('togglenodpvoucher');

    Route::get('/finance/togglepayplan', 'FinanceControllers\FinanceController@togglepayplan')->name('togglepayplan');

    Route::get('/finance/sigs_load', 'FinanceControllers\FinanceController@sigs_load')->name('sigs_load');
    Route::get('/finance/sigs_update', 'FinanceControllers\FinanceController@sigs_update')->name('sigs_update');

    Route::get('/finance/accsetup', 'FinanceControllers\AccountingSetupController@accsetup')->name('accsetup');
    Route::get('/finance/accsetup_loadlevel', 'FinanceControllers\AccountingSetupController@accsetup_loadlevel')->name('accsetup_loadlevel');
    Route::get('/finance/accsetup_getlevel', 'FinanceControllers\AccountingSetupController@accsetup_getlevel')->name('accsetup_getlevel');
    Route::get('/finance/accsetup_update', 'FinanceControllers\AccountingSetupController@accsetup_update')->name('accsetup_update');

    Route::get('/finance/accsetup_oaload', 'FinanceControllers\AccountingSetupController@accsetup_oaload')->name('accsetup_oaload');
    Route::get('/finance/accsetup_oaupdate', 'FinanceControllers\AccountingSetupController@accsetup_oaupdate')->name('accsetup_oaupdate');

    Route::get('/finance/accsetup_cashiertrans_load', 'FinanceControllers\AccountingSetupController@accsetup_cashiertrans_load')->name('accsetup_cashiertrans_load');
    Route::get('/finance/accsetup_cashiertrans', 'FinanceControllers\AccountingSetupController@accsetup_cashiertrans')->name('accsetup_cashiertrans');
    Route::get('/finance/accsetup_payroll_load', 'FinanceControllers\AccountingSetupController@accsetup_payroll_load')->name('accsetup_payroll_load');
    Route::get('/finance/accsetup_payroll_save', 'FinanceControllers\AccountingSetupController@accsetup_payroll_save')->name('accsetup_payroll_save');

    Route::get('/finance/accsetup_bank_load', 'FinanceControllers\AccountingSetupController@accsetup_bank_load')->name('accsetup_bank_load');
    Route::get('/finance/accsetup_bank_create', 'FinanceControllers\AccountingSetupController@accsetup_bank_create')->name('accsetup_bank_create');
    Route::get('/finance/accsetup_bank_read', 'FinanceControllers\AccountingSetupController@accsetup_bank_read')->name('accsetup_bank_read');
    Route::get('/finance/accsetup_bank_delete', 'FinanceControllers\AccountingSetupController@accsetup_bank_delete')->name('accsetup_bank_delete');

    Route::get('/finance/accsetup_expense_load', 'FinanceControllers\AccountingSetupController@accsetup_expense_load')->name('accsetup_expense_load');
    Route::post('/finance/accsetup_expense_save', 'FinanceControllers\AccountingSetupController@accsetup_expense_save')->name('accsetup_expense_save');



    Route::get('/finance/expenses_supplier', 'FinanceControllers\AccountingSetupController@expenses_supplier')->name('expenses_supplier');
    Route::get('/finance/expenses_supplier_create', 'FinanceControllers\AccountingSetupController@expenses_supplier_create')->name('expenses_supplier_create');
    Route::get('/finance/expenses_supplier_read', 'FinanceControllers\AccountingSetupController@expenses_supplier_read')->name('expenses_supplier_read');
    Route::get('/finance/expenses_supplier_update', 'FinanceControllers\AccountingSetupController@expenses_supplier_update')->name('expenses_supplier_update');
    Route::get('/finance/expenses_supplier_delete', 'FinanceControllers\AccountingSetupController@expenses_supplier_delete')->name('expenses_supplier_delete');

    Route::get('/finance/issetup_add', 'FinanceControllers\AccountingSetupController@issetup_add')->name('issetup_add');
    Route::get('/finance/issetup_load', 'FinanceControllers\AccountingSetupController@issetup_load')->name('issetup_load');
    Route::get('/finance/issetup_read', 'FinanceControllers\AccountingSetupController@issetup_read')->name('issetup_read');
    Route::get('/finance/issetup_delete', 'FinanceControllers\AccountingSetupController@issetup_delete')->name('issetup_delete');

    Route::get('/finance/issetup_revenue_read', 'FinanceControllers\AccountingSetupController@issetup_revenue_read')->name('issetup_revenue_read');

    Route::get('/finance/bssetup_load', 'FinanceControllers\AccountingSetupController@bssetup_load')->name('bssetup_load');
    Route::get('/finance/bssetup_add', 'FinanceControllers\AccountingSetupController@bssetup_add')->name('bssetup_add');
    Route::get('/finance/bssetup_read', 'FinanceControllers\AccountingSetupController@bssetup_read')->name('bssetup_read');
    Route::get('/finance/bssetup_delete', 'FinanceControllers\AccountingSetupController@bssetup_delete')->name('bssetup_delete');

});

Route::middleware(['auth', 'isHumanResource', 'isDefaultPass'])->group(function () {

    Route::resource('/home', 'HRControllers\HRDashboardController');
    Route::get('/hr/employees/index', 'HRControllers\HREmployeesController@index');
    Route::get('/hr/employees/inactiveemployees', 'HRControllers\HREmployeesController@inactiveemployees');
    Route::get('hr/employees/inactiveemployeepdf', 'HRControllers\HREmployeesController@inactiveemployeepdf');
    Route::get('/hr/employees/getprofile', 'HRControllers\HREmployeeProfileController@getprofile');
    Route::get('/hr/employees/getdesignations', 'HRControllers\HREmployeesController@getdesignations');
    Route::get('/hr/employees/getacademicprogram', 'HRControllers\HREmployeesController@getacademicprograms');
    Route::get('/hr/employees/addnewemployee/index', 'HRControllers\HREmployeesController@addnewemployeeindex');
    Route::get('/hr/employees/addnewemployee/save', 'HRControllers\HREmployeesController@addnewemployeesave');
    Route::get('/hr/employees/profile/index', 'HRControllers\HREmployeeProfileController@index');
    Route::get('/hr/employees/profile/changestatus', 'HRControllers\HREmployeeProfileController@changestatus');
    Route::post('/hr/employees/profile/uploadphoto', 'HRControllers\HREmployeeProfileController@uploadphoto');
    Route::get('/hr/employees/profile/updaterfid', 'HRControllers\HREmployeeProfileController@updaterfid');
    Route::get('/hr/employees/profile/deactivateuser', 'HRControllers\HREmployeeProfileController@deactivateuser');
    Route::get('/hr/employees/profile/activateuser', 'HRControllers\HREmployeeProfileController@activateuser');

    Route::get('/hr/employees/profile/tabprofile/index', 'HRControllers\HREmployeeProfileController@tabprofileindex');
    Route::get('/hr/employees/profile/tabprofile/updatepersonalinfo', 'HRControllers\HREmployeeProfileController@tabprofileupdatepersonalinfo');
    Route::get('/hr/employees/profile/tabprofile/updateemercon', 'HRControllers\HREmployeeProfileController@tabprofileupdateemergencycontact');
    Route::get('/hr/employees/profile/tabprofile/updateaccounts', 'HRControllers\HREmployeeProfileController@tabprofileupdateaccounts');
    Route::get('/hr/employees/profile/tabprofile/deleteaccount', 'HRControllers\HREmployeeProfileController@tabprofiledeleteaccount');
    Route::get('/hr/employees/profile/tabprofile/updatefamilyinfo', 'HRControllers\HREmployeeProfileController@tabprofileupdatefamilyinfo');
    Route::get('/hr/employees/profile/tabprofile/deletefamilyinfo', 'HRControllers\HREmployeeProfileController@tabprofiledeletefamilyinfo');
    Route::get('/hr/employees/profile/tabprofile/addeducationinfo', 'HRControllers\HREmployeeProfileController@tabprofileaddeducationinfo');
    Route::get('/hr/employees/profile/tabprofile/updateeducationinfo', 'HRControllers\HREmployeeProfileController@tabprofileupdateeducationinfo');
    Route::get('/hr/employees/profile/tabprofile/deleteeducationinfo', 'HRControllers\HREmployeeProfileController@tabprofiledeleteeducationinfo');
    Route::get('/hr/employees/profile/tabprofile/addworexperience', 'HRControllers\HREmployeeProfileController@tabprofileaddworexperience');
    Route::get('/hr/employees/profile/tabprofile/updateworkexperience', 'HRControllers\HREmployeeProfileController@tabprofileupdateworkexperience');
    Route::get('/hr/employees/profile/tabprofile/deleteworkexperience', 'HRControllers\HREmployeeProfileController@tabprofiledeleteworkexperience');

    Route::get('/hr/employees/profile/tabbasicsalary/index', 'HRControllers\HREmployeeBasicSalaryController@tabbasicsalaryindex');
    Route::get('/hr/employees/profile/tabbasicsalary/selectbasistype', 'HRControllers\HREmployeeBasicSalaryController@tabbasicsalaryselectbasistype');
    Route::get('/hr/employees/profile/tabbasicsalary/updaterate', 'HRControllers\HREmployeeBasicSalaryController@tabbasicsalaryupdaterate');
    Route::get('/hr/employees/profile/tabbasicsalary/updateinfo', 'HRControllers\HREmployeeBasicSalaryController@tabbasicsalaryupdateinfo');
    Route::get('/hr/employees/profile/tabbasicsalary/updatetimesched', 'HRControllers\HREmployeeBasicSalaryController@tabbasicsalaryupdatetimesched');

    Route::get('/hr/employees/profile/tabdeductions/index', 'HRControllers\HREmployeeDeductionsController@tabdeductionsindex');
    Route::get('/hr/employees/profile/tabdeductions/updatesetuptype', 'HRControllers\HREmployeeDeductionsController@tabdeductionsupdatesetuptype');
    Route::get('/hr/employees/profile/tabdeductions/updatedeductions', 'HRControllers\HREmployeeDeductionsController@tabdeductionsupdatedeductions');
    Route::get('/hr/employees/profile/tabdeductions/adddeduction', 'HRControllers\HREmployeeDeductionsController@tabdeductionsadddeduction');
    Route::get('/hr/employees/profile/tabdeductions/editdeduction', 'HRControllers\HREmployeeDeductionsController@tabdeductionseditdeduction');
    Route::get('/hr/employees/profile/tabdeductions/deletededuction', 'HRControllers\HREmployeeDeductionsController@tabdeductionsdeletededuction');

    Route::get('/hr/employees/profile/taballowances/index', 'HRControllers\HREmployeeAllowancesController@taballowancesindex');
    Route::get('/hr/employees/profile/taballowances/updatestandardallowance', 'HRControllers\HREmployeeAllowancesController@taballowancesupdatestandardallowance');
    Route::get('/hr/employees/profile/taballowances/addallowance', 'HRControllers\HREmployeeAllowancesController@taballowancesaddallowance');
    Route::get('/hr/employees/profile/taballowances/updateallowance', 'HRControllers\HREmployeeAllowancesController@taballowancesupdateallowance');
    Route::get('/hr/employees/profile/taballowances/deleteallowance', 'HRControllers\HREmployeeAllowancesController@taballowancesdeleteallowance');

    // Gian Additional
    Route::get('/hr/employees/profile/loadallallowance', 'HRControllers\HREmployeeAllowancesController@loadallallowance');
    Route::get('/hr/employees/profile/addstandardallowance', 'HRControllers\HREmployeeAllowancesController@addstandardallowance');
    Route::get('/hr/employees/profile/loademployeestandardallowance', 'HRControllers\HREmployeeAllowancesController@loademployeestandardallowance');
    Route::get('/hr/employees/profile/getallemployeestandardallowance', 'HRControllers\HREmployeeAllowancesController@getallemployeestandardallowance');
    Route::get('/hr/employees/profile/addemployeestandardallowance', 'HRControllers\HREmployeeAllowancesController@addemployeestandardallowance');
    Route::get('/hr/employees/profile/saveperemployeestandardallowance', 'HRControllers\HREmployeeAllowancesController@saveperemployeestandardallowance');
    Route::get('/hr/employees/profile/deleteemployeestandardallowance', 'HRControllers\HREmployeeAllowancesController@deleteemployeestandardallowance');

    Route::get('/hr/employees/profile/tabcreds/index', 'HRControllers\HREmployeeCredentialsController@tabcredsindex');
    // Route::post('/hr/employees/profile/tabcreds/upload', 'HRControllers\HREmployeeCredentialsController@requirement_upload');
    Route::post('/hr/employees/profile/tabcreds/upload', 'HRControllers\HREmployeeCredentialsController@tabcredsupload');
    Route::get('/hr/employees/profile/tabcreds/delete', 'HRControllers\HREmployeeCredentialsController@tabcredsdelete');

    Route::get('/hr/employees/profile/tabdtr/{id}', 'HRControllers\HREmployeesController@employeedtrtab');

    Route::get('/hr/employeebasicsalaryinfobasisselection', 'HRControllers\HREmployeeProfile\HRBasicSalaryInfoController@employeebasicsalaryinfobasisselection');

    Route::get('/hr/employees/profile/tabothers/index', 'HRControllers\HREmployeeOtherInfoController@tabothersindex');
    Route::get('/hr/employees/profile/tabothers/updatedesignation', 'HRControllers\HREmployeeOtherInfoController@tabothersupdatedesignation');
    Route::get('/hr/employees/profile/tabothers/updatedepartment', 'HRControllers\HREmployeeOtherInfoController@tabothersupdatedepartment');
    Route::get('/hr/employees/profile/tabothers/updateworkshift', 'HRControllers\HREmployeeOtherInfoController@tabothersupdateworkshift');
    Route::get('/hr/employees/profile/tabothers/updateattendancebasedsalary', 'HRControllers\HREmployeeOtherInfoController@tabothersupdateattendancebasedsalary');
    Route::get('/hr/employees/profile/tabothers/updatecustomtimesched', 'HRControllers\HREmployeeOtherInfoController@tabothersupdatecustomtimesched');

    Route::get('/hr/employees/statusindex', 'HRControllers\HREmployeesController@statusindex');
    Route::get('/hr/employees/statustypes', 'HRControllers\HREmployeesController@statustypes');
    Route::get('/hr/employees/empstatusgenerate', 'HRControllers\HREmployeesController@empstatusgenerate');

    Route::get('/hr/employment/requirements', 'HRControllers\SetupController@employment_requirements');
    Route::post('/hr/employment/requirements/upload', 'HRControllers\HREmployeeCredentialsController@requirement_upload');
    Route::get('/hr/employment/requirements/delete', 'HRControllers\HREmployeeCredentialsController@requirement_delete');

    Route::get('/hr/attendance/index', 'HRControllers\HRAttendanceController@index');
    Route::get('/hr/attendance/indexv2', 'HRControllers\HRAttendanceController@indexv2');
    Route::get('/hr/attendance/updatetime', 'HRControllers\HRAttendanceController@updatetime');
    Route::get('/hr/attendance/updateremarks', 'HRControllers\HRAttendanceController@updateremarks');
    Route::get('/hr/attendance/gettimelogs', 'HRControllers\HRAttendanceController@gettimelogs');
    Route::get('/hr/attendance/getapproveleaves', 'HRControllers\HRAttendanceController@getapproveleaves');
    Route::get('/hr/attendance/addtimelog', 'HRControllers\HRAttendanceController@addtimelog');
    Route::get('/hr/attendance/deletetimelog', 'HRControllers\HRAttendanceController@deletetimelog');
    Route::get('/hr/attendance/deletetimelogtapping', 'HRControllers\HRAttendanceController@deletetimelogtapping');
    Route::get('/hr/attendance/summaryindex', 'HRControllers\HRAttendanceController@summaryindex');
    Route::get('/hr/attendance/summarygenerate', 'HRControllers\HRAttendanceController@summarygenerate');

    Route::get('/hr/absences/index', 'HRControllers\HRAttendanceController@absencesindex');
    Route::get('/hr/absences/offense', 'HRControllers\HRAttendanceController@absencesoffense');
    Route::get('/hr/absences/generate', 'HRControllers\HRAttendanceController@absencesgenerate');
    Route::get('/hr/absences/markoffense', 'HRControllers\HRAttendanceController@absencesmarkoffense');

    Route::get('/hr/tardiness/index', 'HRControllers\HRAttendanceController@tardinessindex');
    Route::get('/hr/tardiness/offense', 'HRControllers\HRAttendanceController@tardinessoffense');
    Route::get('/hr/tardiness/generate', 'HRControllers\HRAttendanceController@tardinessgenerate');
    Route::get('/hr/tardiness/markoffense', 'HRControllers\HRAttendanceController@tardinessmarkoffense');

    Route::get('/hr/tardinesscomp/index', 'HRControllers\TardinessComputationController@index');
    Route::get('/hr/tardinesscomp/getbrackets', 'HRControllers\TardinessComputationController@getbrackets');
    Route::get('/hr/tardinesscomp/addbrackets', 'HRControllers\TardinessComputationController@addbrackets');
    Route::get('/hr/tardinesscomp/updatebracket', 'HRControllers\TardinessComputationController@updatebracket');
    Route::get('/hr/tardinesscomp/deletebracket', 'HRControllers\TardinessComputationController@deletebracket');
    Route::get('/hr/tardinesscomp/activation', 'HRControllers\TardinessComputationController@activation');

    Route::get('/hr/overtime/index', 'HRControllers\HROvertimeController@index');
    Route::get('/hr/overtime/fileovertime', 'HRControllers\HROvertimeController@fileovertime');
    Route::get('/hr/overtime/filter', 'HRControllers\HROvertimeController@filter');
    Route::get('/hr/overtime/delete', 'HRControllers\HROvertimeController@delete');
    Route::get('/hr/overtime/pending', 'HRControllers\HROvertimeController@pending');
    Route::get('/hr/overtime/approve', 'HRControllers\HROvertimeController@approve');
    Route::get('/hr/overtime/disapprove', 'HRControllers\HROvertimeController@disapprove');

    // /hr/overtime/dashboard

    // Route::get('/attendance/{id}', 'HRControllers\HREmployeesController@attendance');

    Route::get('/employeebenefits/{id}', 'HRControllers\HREmployeesController@employeebenefits');
    Route::get('/employeeeducation/{id}', 'HRControllers\HREmployeesController@employeeeducationinfo');
    Route::get('/employeeotherdeductionsinfostatusupdate', 'HRControllers\HREmployeesController@employeeotherdeductionsinfostatusupdate');
    // Route::get('/employeecustomtimesched/{id}', 'HRControllers\HREmployeesController@employeecustomtimesched');
    Route::get('/employeerateelevation', 'HRControllers\HREmployeesController@employeerateelevation');
    // Route::post('/employeecredential', 'HRControllers\HREmployeesController@employeecredential');
    Route::get('/employeedtr/{id}', 'HRControllers\HREmployeesController@employeedtr');

    Route::get('/employeestatus/{id}', 'HRControllers\HREmployeesController@employeestatus');
    Route::get('/employeesalaryupdate', 'HRControllers\HREmployeeSalarySettingController@employeesalaryupdate');

    Route::get('/salary/{id}', 'HRControllers\HREmployeesController@salary');

    // Route::get('/hr/leaves/{id}', 'HRControllers\HREmployeesController@leaves');
    // Route::get('/hr/leave/forcepermission', 'HRControllers\HREmployeesController@leaveforcepermission');
    // Route::get('/hr/globalapplyleave', 'HRControllers\HREmployeesController@globalapplyleave');
    // Route::get('/hr/overtime/{id}', 'HRControllers\HREmployeesController@overtimes');
    Route::get('/hr/overtimeforcepermission', 'HRControllers\HREmployeesController@overtimeforcepermission');

    Route::get('/holidays', 'HRControllers\SetupController@holidays');
    Route::get('/addholidaytypes', 'HRControllers\SetupController@addholidaytypes');
    Route::get('/updateholidayrates', 'HRControllers\SetupController@updateholidayrates');
    Route::get('/deleteholidaytype', 'HRControllers\SetupController@deleteholidaytype');


    // Route::get('/leavesettings', 'HRControllers\SetupController@leavesettings');
    // Route::get('/leavesettings/{id}', 'HRControllers\SetupController@leavesettingsupdates');
    // Route::get('/hr/settings/leave/approval/{id}', 'HRControllers\SetupController@leaveapproval');
    Route::get('/hr/setup/deductions', 'HRControllers\HRDeductionSetupController@deductionsetup');
    Route::get('/hr/setup/deductions/bracketing', 'HRControllers\HRDeductionSetupController@bracketing');
    Route::get('/hr/setup/deductions/custom', 'HRControllers\HRDeductionSetupController@customcontribution');
    Route::get('/hr/setup/deductions/otherdeductions', 'HRControllers\HRDeductionSetupController@otherdeductions');

    Route::get('/hr/setup/allowances', 'HRControllers\HRAllowanceSetupController@allowancesetup');
    Route::get('/hr/setup/allowances/custom', 'HRControllers\HRAllowanceSetupController@allowance_customcontribution');
    Route::get('/hr/setup/allowances/otherdeductions', 'HRControllers\HRAllowanceSetupController@otherallowances');

    Route::get('/bracketing', 'HRControllers\SetupController@bracketing');
    Route::get('/hr/settings/leaves', 'HRControllers\SetupController@leavesettings');
    Route::get('/hr/settings/leavesperemployee', 'HRControllers\HREmployeeLeaveController@index');
    Route::get('/hr/settings/overtimes', 'HRControllers\SetupController@overtimesettings');
    Route::get('/hr/settings/approvals', 'HRControllers\SetupController@leaveapprovals');
    Route::get('/hr/settings/signatories', 'HRControllers\SetupController@leavesignatories');
    Route::get('/hr/settings/undertime', 'HRControllers\SetupController@undertime');

    Route::get('/changeattendance', 'HRControllers\HREmployeesController@changeattendance');

    Route::get('/requirements/{id}', 'HRControllers\SetupController@requirementssetup');
    Route::get('/hr/settings/offices/{id}', 'HRControllers\SetupController@officessetup');
    Route::get('/hr/settings/departments/{id}', 'HRControllers\SetupController@departmentssetup');
    // Route::get('/departments/{id}', 'HRControllers\HREmployeesController@departments');
    Route::get('/hr/settings/designations/{id}', 'HRControllers\SetupController@designationssetup');


    Route::get('/hr/payroll/index', 'HRControllers\HRPayrollController@index');
    Route::get('/hr/payroll/setpayrolldate', 'HRControllers\HRPayrollController@setpayrolldate');
    Route::get('/hr/payroll/newpayroll', 'HRControllers\HRPayrollController@newpayroll');
    Route::get('/hr/payroll/changepayroll', 'HRControllers\HRPayrollController@changepayroll');
    Route::get('/hr/payroll/leapyearactivation', 'HRControllers\HRPayrollController@payrollleapyear');
    Route::get('/hr/payroll/getsalarydetails', 'HRControllers\HRPayrollController@getsalarydetails');
    Route::get('/hr/payroll/saveconfiguration', 'HRControllers\HRPayrollController@saveconfiguration');

    Route::get('/hr/payrollv2/index', 'HRControllers\HRPayrollV2Controller@index');
    Route::get('/hr/payrollv3/index', 'HRControllers\HRPayrollV3Controller@index');
    Route::get('/hr/payrollv3/getsalaryinfo', 'HRControllers\HRPayrollV3Controller@getsalaryinfo');
    Route::get('/hr/payrollv3/getallholiday', 'HRControllers\HRPayrollV3Controller@getallholiday');

    Route::get('/hr/payrollv3/addedparticular', 'HRControllers\HRPayrollV3Controller@addedparticular');
    Route::get('/hr/payrollv3/payrolldates', 'HRControllers\HRPayrollV3Controller@payrolldates');
    Route::get('/hr/payrollv3/allreleasedpayrolldates', 'HRControllers\HRPayrollV3Controller@allreleasedpayrolldates');
    Route::get('/hr/payrollv3/allreleasedemployees', 'HRControllers\HRPayrollV3Controller@allreleasedemployees');

    Route::get('/hr/payrollv3/configuration', 'HRControllers\HRPayrollV3Controller@configuration');
    Route::get('/hr/payrollv3/export', 'HRControllers\HRPayrollV3Controller@exportpayslip');
    Route::get('/hr/payrollv3/payrollhistory', 'HRControllers\HRPayrollV3Controller@payrollhistory');
    Route::get('/hr/payrollv3/payrolldetails', 'HRControllers\HRPayrollV3Controller@payrolldetails');
    Route::get('/hr/payrollv3/payrollhistorybydepartment', 'HRControllers\HRPayrollV3Controller@payrollhistorybydepartment');
    Route::get('/hr/payrollv3/loademployeebydepartment', 'HRControllers\HRPayrollV3Controller@loademployeebydepartment');
    Route::get('/hr/payrollv3/exportpayslipbydepartment', 'HRControllers\HRPayrollV3Controller@exportpayslipbydepartment');

    // Gian Additional
    Route::get('/hr/payrollv3/loadnetsalary', 'HRControllers\HRPayrollV3Controller@loadnetsalary');
    Route::get('/hr/payrollv3/salarybasistype', 'HRControllers\HRPayrollV3Controller@salarybasistype');
    Route::get('/hr/payrollv3/allemployees', 'HRControllers\HRPayrollV3Controller@allemployees');
    Route::get('/hr/payrollv3/countemployeerelease', 'HRControllers\HRPayrollV3Controller@countemployeerelease');
    Route::get('/hr/payrollv3/voidpayslip', 'HRControllers\HRPayrollV3Controller@voidpayslip');
    Route::get('/hr/payrollv3/editpayslip', 'HRControllers\HRPayrollV3Controller@editpayslip');
    Route::get('/hr/payrollv3/batchreleaseemployeepayroll', 'HRControllers\HRPayrollV3Controller@batchreleaseemployeepayroll');
    Route::get('/hr/payrollv3/batchgenerateemployeepayroll', 'HRControllers\HRPayrollV3Controller@batchgenerateemployeepayroll');

    // Gian Additional for All Payroll Released in Employee
    Route::get('/hr/payrollv3/emp_allreleasedpayrolldates', 'HRControllers\HRPayrollV3Controller@emp_allreleasedpayrolldates');
    Route::get('/hr/payrollv3/payrolldate_data', 'HRControllers\HRPayrollV3Controller@payrolldate_data');

    // Gian Additional para sa dili na mag save per employee
    Route::get('/hr/payrollv3/listofemployees', 'HRControllers\HRPayrollV3Controller@listofemployees');

    Route::get('/hr/payrollsummary/index', 'HRControllers\HRPayrollController@payrollsummary');
    Route::get('/hr/payrollsummary/setup', 'HRControllers\HRPayrollController@setup');
    Route::get('/hr/payrollsummary/setup-create', 'HRControllers\HRPayrollController@setupcreate');
    Route::get('/hr/payrollsummary/setup-show', 'HRControllers\HRPayrollController@setupshow');
    Route::get('/hr/payrollsummary/setup-delete', 'HRControllers\HRPayrollController@setupdelete');
    Route::get('/hr/payrollsummary/filter', 'HRControllers\HRPayrollController@filterpayrollsummary');
    Route::get('/hr/payrollsummary/releaseslipsingle', 'HRControllers\HRPayrollController@releaseslipsingle');
    Route::get('/hr/payrollsummary/viewslip', 'HRControllers\HRPayrollController@viewslip');
    Route::get('/hr/payrollsummary/exportsummary', 'HRControllers\HRPayrollController@exportsummary');

    Route::get('/hr/printpayrollhistory/{id}', 'HRControllers\HRPayrollController@printpayrollhistory');
    Route::get('/payrollgenerateslip', 'HRControllers\HRController@payrollgenerateslip');
    Route::get('/printfilteredsalary/{id}', 'HRControllers\HRController@printfilteredsalary');

    Route::get('/standarddeductions/{id}', 'HRControllers\SetupController@standarddeductions');
    Route::get('/bracketing', 'HRControllers\SetupController@bracketing');
    Route::get('/bracketedit', 'HRControllers\SetupController@bracketedit');
    Route::get('/updatedeductions/{id}', 'HRControllers\SetupController@updatedeductions');
    Route::get('/standardallowances/{id}', 'HRControllers\SetupController@standardallowances');
    Route::get('/updateallowances/{id}', 'HRControllers\SetupController@updateallowances');
    Route::get('/updatedeductiondetails/{id}', 'HRControllers\SetupController@updatedeductiondetails');

    Route::get('/tardinessdeduction/{id}', 'HRControllers\SetupController@tardinessdeduction');
    Route::get('/addtardinesscomputation', 'HRControllers\SetupController@addtardinesscomputation');
    Route::get('/edittardinesscomputation', 'HRControllers\SetupController@edittardinesscomputation');
    Route::get('/deletetardinesscomputation', 'HRControllers\SetupController@deletetardinesscomputation');

    Route::get('/deleteearning', 'HRControllers\HRController@employeedeleteearning');
    Route::get('/editearning', 'HRControllers\HRController@employeeeditearning');
    Route::get('/employeeotherdeductiondelete', 'HRControllers\HRController@employeeotherdeductiondelete');
    Route::get('/employeeotherdeductionedit', 'HRControllers\HRController@employeeotherdeductionedit');

    Route::resource('/history', 'HRControllers\HRPayrollHistoryController');

    Route::get('/payslip/{id}', 'HRControllers\HRController@payslip');
    Route::get('/payrollitems/{id}', 'HRControllers\HRController@payrollitems');

    Route::get('/summaryofattendance/{id}', 'HRControllers\HRSummaryController@summaryofattendance');
    Route::get('/hrreports/summaryofemployees/{id}', 'HRControllers\HRSummaryController@summaryofemployees');

    // Route::get('/hrreports/thirteenthmonth/{id}', 'HRControllers\HRThirteenthMonthController@thirteenthmonthindex');
    // Route::get('/hrreports/thirteenthmonthpayslip', 'HRControllers\HRThirteenthMonthController@thirteenthmonthpayslip');

    // Gian Additional
    Route::get('/hr/payrollv3/thirteenthmonth/index', 'HRControllers\HREmployeeThirteenthMonthController@index');
    Route::get('/hr/payrollv3/thirteenthmonth/loadrange', 'HRControllers\HREmployeeThirteenthMonthController@loadrange');
    Route::get('/hr/payrollv3/thirteenthmonth/loaddepartments', 'HRControllers\HREmployeeThirteenthMonthController@loaddepartments');

    Route::get('/newdeductionsetup/{id}', 'HRControllers\HRDeductionSetupController@newdeductionsetup');
    Route::get('/hrapplicationofdeduction', 'HRControllers\HRDeductionSetupController@hrapplicationofdeduction');
    Route::get('/hrapplicationdelete', 'HRControllers\HRDeductionSetupController@hrapplicationdelete');

    // Gian Additional
    Route::get('/hr/payrollv3/salarytype', 'HRControllers\HRPayrollV3Controller@salarytype');
    Route::get('/hr/payrollv3/daily_index', 'HRControllers\HRPayrollDailyController@daily_index');
    Route::get('/hr/payrollv3/daily_salaryinfo', 'HRControllers\HRPayrollDailyController@daily_salaryinfo');

    // ========================
    Route::get('/hr/payrollv3/weekly_index', 'HRControllers\HRPayrollWeeklyController@weekly_index');
    // ========================

    // Gian Additional HR
    Route::get('/hr/employees/index_v2', 'HRControllers\profile_v2\HREmployeesController_v2@index_v2');


    // Gian Addition Shifting
    Route::get('/payrollclerk/setup/shifting', 'HRControllers\HREmployeeShiftingController@shifting');
    Route::get('/payrollclerk/setup/load_all_shift', 'HRControllers\HREmployeeShiftingController@load_all_shift');
    Route::get('/payrollclerk/setup/store_shift', 'HRControllers\HREmployeeShiftingController@store_shift');
    Route::get('/payrollclerk/setup/edit_shift', 'HRControllers\HREmployeeShiftingController@edit_shift');
    Route::get('/payrollclerk/setup/update_shift', 'HRControllers\HREmployeeShiftingController@update_shift');
    Route::get('/payrollclerk/setup/delete_shift', 'HRControllers\HREmployeeShiftingController@delete_shift');

    // Payrol Clerk Profile Gian

    Route::get('/payrollclerk/employees/profile/select_designations', 'HRControllers\HREmployeeProfileController@select_designations');
    Route::get('/payrollclerk/employees/profile/select_departments', 'HRControllers\HREmployeeProfileController@select_departments');
    Route::get('/payrollclerk/employees/profile/getdatainfo', 'HRControllers\HREmployeeProfileController@getdatainfo');
    Route::get('/payrollclerk/employees/profile/getdatainfoprintables', 'HRControllers\HREmployeeProfileController@getdatainfoprintables');

    Route::get('/payrollclerk/employees/profile/v2_storepersonalinfo', 'HRControllers\HREmployeeProfileController@v2_storepersonalinfo');
    Route::get('/payrollclerk/employees/profile/v2_storesalaryinfo', 'HRControllers\HREmployeeProfileController@v2_storesalaryinfo');
    Route::get('/payrollclerk/employees/profile/v2_updatesalaryinfo', 'HRControllers\HREmployeeProfileController@v2_updatesalaryinfo');
    Route::get('/payrollclerk/employees/profile/v2_updateflexitime', 'HRControllers\HREmployeeProfileController@v2_updateflexitime');
    Route::get('/payrollclerk/employees/profile/v2_addstandarddeduction', 'HRControllers\HREmployeeProfileController@v2_addstandarddeduction');
    Route::get('/payrollclerk/employees/profile/viewpaidstandarddeduction', 'HRControllers\HREmployeeProfileController@viewpaidstandarddeduction');
    Route::get('/payrollclerk/employees/profile/v2_updateparttimerstatus', 'HRControllers\HREmployeeProfileController@v2_updateparttimerstatus');

    Route::get('/payrollclerk/employees/profile/v2_addemployeestandarddeduction', 'HRControllers\HREmployeeProfileController@v2_addemployeestandarddeduction');
    Route::get('/payrollclerk/employees/profile/v2_emergencycontactlist', 'HRControllers\HREmployeeProfileController@v2_emergencycontactlist');

    Route::get('/payrollclerk/employees/profile/getperemployeelist', 'HRControllers\HREmployeeProfileController@getperemployeelist');
    Route::get('/payrollclerk/employees/profile/getalldepartments', 'HRControllers\HREmployeeProfileController@getalldepartments');
    Route::get('/payrollclerk/employees/profile/getallemployeeotherdeduction', 'HRControllers\HREmployeeProfileController@getallemployeeotherdeduction');
    Route::get('/payrollclerk/employees/profile/savealldepartments', 'HRControllers\HREmployeeProfileController@savealldepartments');
    Route::get('/payrollclerk/employees/profile/saveallperemployee', 'HRControllers\HREmployeeProfileController@saveallperemployee');

    Route::get('/payrollclerk/employees/profile/v2_loadotherdeduction', 'HRControllers\HREmployeeProfileController@v2_loadotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_addotherdeduction', 'HRControllers\HREmployeeProfileController@v2_addotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_editotherdeduction', 'HRControllers\HREmployeeProfileController@v2_editotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_updateotherdeduction', 'HRControllers\HREmployeeProfileController@v2_updateotherdeduction');

    Route::get('/payrollclerk/employees/profile/v2_viewemployeeotherdeduction', 'HRControllers\HREmployeeProfileController@v2_viewemployeeotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_reloanemployeeotherdeduction', 'HRControllers\HREmployeeProfileController@v2_reloanemployeeotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_savereloan', 'HRControllers\HREmployeeProfileController@v2_savereloan');

    Route::get('/payrollclerk/employees/profile/v2_saveemployeeotherdeduction', 'HRControllers\HREmployeeProfileController@v2_saveemployeeotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_savemanualpayment', 'HRControllers\HREmployeeProfileController@v2_savemanualpayment');
    Route::get('/payrollclerk/employees/profile/v2_editmanualpayment', 'HRControllers\HREmployeeProfileController@v2_editmanualpayment');

    Route::get('/payrollclerk/employees/profile/v2_loademployeeotherdeduction', 'HRControllers\HREmployeeProfileController@v2_loademployeeotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_deleteemployeeotherdeduction', 'HRControllers\HREmployeeProfileController@v2_deleteemployeeotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_lockemployeeotherdeduction', 'HRControllers\HREmployeeProfileController@v2_lockemployeeotherdeduction');
    Route::get('/payrollclerk/employees/profile/v2_deductionstatus', 'HRControllers\HREmployeeProfileController@v2_deductionstatus');
    Route::get('/payrollclerk/employees/profile/v2_modal_deductionstatus', 'HRControllers\HREmployeeProfileController@v2_modal_deductionstatus');
    Route::get('/payrollclerk/employees/profile/v2_modal_cutoffstatus', 'HRControllers\HREmployeeProfileController@v2_modal_cutoffstatus');


    // overload setup employee profile
    Route::get('/payrollclerk/employees/profile/profileloadpayrolldates', 'HRControllers\HREmployeeProfileController@profileloadpayrolldates');

    // Gian Interest Setup
    Route::get('/payrollclerk/employees/profile/v2_loadinterest', 'HRControllers\HREmployeeProfileController@v2_loadinterest');
    Route::get('/payrollclerk/employees/profile/v2_deleteemployeestandarddeduction', 'HRControllers\HREmployeeProfileController@v2_deleteemployeestandarddeduction');
    Route::get('/payrollclerk/employees/profile/load_employeestandarddeduction', 'HRControllers\HREmployeeProfileController@load_employeestandarddeduction');
    Route::get('/payrollclerk/employees/profile/v2_getstandarddeductionamountdefault', 'HRControllers\HREmployeeProfileController@v2_getstandarddeductionamountdefault');
    Route::get('/payrollclerk/employees/profile/v2_loadsalaryinfo', 'HRControllers\HREmployeeProfileController@v2_loadsalaryinfo');
    Route::get('/payrollclerk/employees/profile/v2_loadpersonalinfo', 'HRControllers\HREmployeeProfileController@v2_loadpersonalinfo');
    Route::get('/payrollclerk/employees/profile/v2_loadshifts', 'HRControllers\HREmployeeProfileController@v2_loadshifts');
    Route::get('/payrollclerk/employees/profile/v2_loademployeeshifts', 'HRControllers\HREmployeeProfileController@v2_loademployeeshifts');
    Route::get('/payrollclerk/employees/profile/v2_getshift', 'HRControllers\HREmployeeProfileController@v2_getshift');
    Route::get('/payrollclerk/employees/profile/v2_loadstandarddeduction', 'HRControllers\HREmployeeProfileController@v2_loadstandarddeduction');

    // Gian Additional Profile
    Route::get('/payrollclerk/employees/profile/v2_loadworkingday', 'HRControllers\HREmployeeProfileController@v2_loadworkingday');

    // Gian Addition Holiday
    Route::get('/payrollclerk/setup/loadallholidaytype', 'HRControllers\HREmployeeHolidayController@loadallholidaytype');
    Route::get('/payrollclerk/setup/loadallholiday', 'HRControllers\HREmployeeHolidayController@loadallholiday');
    Route::get('/payrollclerk/setup/holidaysetup', 'HRControllers\HREmployeeHolidayController@index');
    Route::get('/payrollclerk/setup/addholidaytype', 'HRControllers\HREmployeeHolidayController@addholidaytype');
    Route::get('/payrollclerk/setup/deleteholidaytype', 'HRControllers\HREmployeeHolidayController@deleteholidaytype');
    Route::get('/payrollclerk/setup/updateholidaytype', 'HRControllers\HREmployeeHolidayController@updateholidaytype');
    Route::get('/payrollclerk/setup/statuswithpay', 'HRControllers\HREmployeeHolidayController@statuswithpay');

    // Gian Additionals Earnings and Deduction
    Route::get('/payrollclerk/setup/additionalearningdeductions/index', 'HRControllers\HREmployeeEarningDeductionController@index');
    Route::get('/payrollclerk/setup/additionalearningdeductions/loademployees', 'HRControllers\HREmployeeEarningDeductionController@loademployees');
    Route::get('/payrollclerk/setup/additionalearningdeductions/loadpayrollperiods', 'HRControllers\HREmployeeEarningDeductionController@loadpayrollperiods');
    Route::get('/payrollclerk/setup/additionalearningdeductions/getearnings', 'HRControllers\HREmployeeEarningDeductionController@getearnings');
    Route::get('/payrollclerk/setup/additionalearningdeductions/saveearnings', 'HRControllers\HREmployeeEarningDeductionController@saveearnings');
    Route::get('/payrollclerk/setup/additionalearningdeductions/getemployeeearnings', 'HRControllers\HREmployeeEarningDeductionController@getemployeeearnings');
    Route::get('/payrollclerk/setup/additionalearningdeductions/updateearning', 'HRControllers\HREmployeeEarningDeductionController@updateearning');
    Route::get('/payrollclerk/setup/additionalearningdeductions/deleteearning', 'HRControllers\HREmployeeEarningDeductionController@deleteearning');
    Route::get('/payrollclerk/setup/additionalearningdeductions/savededuction', 'HRControllers\HREmployeeEarningDeductionController@savededuction');
    Route::get('/payrollclerk/setup/additionalearningdeductions/getdeductions', 'HRControllers\HREmployeeEarningDeductionController@getdeductions');
    Route::get('/payrollclerk/setup/additionalearningdeductions/getemployeedeductions', 'HRControllers\HREmployeeEarningDeductionController@getemployeedeductions');
    Route::get('/payrollclerk/setup/additionalearningdeductions/updatededuction', 'HRControllers\HREmployeeEarningDeductionController@updatededuction');
    Route::get('/payrollclerk/setup/additionalearningdeductions/deletededuction', 'HRControllers\HREmployeeEarningDeductionController@deletededuction');


    // Overload Setup
    Route::get('/payrollclerk/setup/overload/index', 'HRControllers\HREmployeeOverloadController@index');
    Route::get('/payrollclerk/setup/overload/loademployees', 'HRControllers\HREmployeeOverloadController@loademployees');
    Route::post('/payrollclerk/setup/overload/saveallsubjectperemployee', 'HRControllers\HREmployeeOverloadController@saveallsubjectperemployee');
    Route::get('/payrollclerk/setup/overload/loadallemployeeoverload', 'HRControllers\HREmployeeOverloadController@loadallemployeeoverload');
    Route::get('/payrollclerk/setup/overload/loadalloverload', 'HRControllers\HREmployeeOverloadController@loadalloverload');
    Route::get('/payrollclerk/setup/overload/loadallpayrollactive', 'HRControllers\HREmployeeOverloadController@loadallpayrollactive');

    Route::get('/payrollclerk/setup/overload/loadoverloadperiods', 'HRControllers\HREmployeeOverloadController@loadoverloaddateactive');
    Route::get('/payrollclerk/setup/overload/addoverloadperiod', 'HRControllers\HREmployeeOverloadController@addoverloadperiod');
    Route::get('/payrollclerk/setup/overload/activateoverloadperiod', 'HRControllers\HREmployeeOverloadController@activateoverloadperiod');



    // MWSP Setup
    Route::get('/payrollclerk/setup/mwsp/index', 'HRControllers\HREmployeeMwspController@index');
    Route::get('/payrollclerk/setup/mwsp/loademployees', 'HRControllers\HREmployeeMwspController@loademployees');
    Route::get('/payrollclerk/setup/mwsp/saveallsubjectperemployee', 'HRControllers\HREmployeeMwspController@saveallsubjectperemployee');

    // Part Time
    Route::get('/payrollclerk/setup/parttime/index', 'HRControllers\HREmployeeParttimeController@index');
    Route::get('/payrollclerk/setup/parttime/loademployees', 'HRControllers\HREmployeeParttimeController@loademployees');
    Route::get('/payrollclerk/setup/parttime/loading/allsched', 'HRControllers\HREmployeeParttimeController@allsched');
    Route::get('/payrollclerk/setup/parttime/loading/allschedteacher', 'HRControllers\HREmployeeParttimeController@allschedteacher');
    Route::get('/payrollclerk/setup/parttime/loading/loadteachersschedule', 'HRControllers\HREmployeeParttimeController@loadteachersschedule');
    Route::get('/payrollclerk/setup/parttime/loading/changesubjtype', 'HRControllers\HREmployeeParttimeController@changesubjtype');
    Route::get('/payrollclerk/setup/parttime/loading/changesubjtypepersubj', 'HRControllers\HREmployeeParttimeController@changesubjtypepersubj');
    Route::get('/payrollclerk/setup/parttime/adddaterange', 'HRControllers\HREmployeeParttimeController@adddaterange');
    Route::get('/payrollclerk/setup/parttime/closedaterange', 'HRControllers\HREmployeeParttimeController@closedaterange');
    Route::get('/payrollclerk/setup/parttime/loadactivedaterange', 'HRControllers\HREmployeeParttimeController@loadactivedaterange');
    Route::get('/payrollclerk/setup/parttime/savetardy', 'HRControllers\HREmployeeParttimeController@savetardy');
    Route::get('/payrollclerk/setup/parttime/load_totaltardines', 'HRControllers\HREmployeeParttimeController@load_totaltardines');
    Route::get('/payrollclerk/setup/parttime/disapprovepertype', 'HRControllers\HREmployeeParttimeController@disapprovepertype');
    Route::get('/payrollclerk/setup/parttime/approvepertype', 'HRControllers\HREmployeeParttimeController@approvepertype');
    Route::get('/payrollclerk/setup/parttime/changestatus', 'HRControllers\HREmployeeParttimeController@changestatus');


    Route::get('/payrollclerk/setup/parttime/loading/perteacher', 'HRControllers\HREmployeeParttimeController@perteacher');
    Route::get('/payrollclerk/employees/profile/profileloadsemester', 'HRControllers\HREmployeeParttimeController@profileloadsemester');
    Route::get('/payrollclerk/employees/profile/profileloadsy', 'HRControllers\HREmployeeParttimeController@profileloadsy');
    Route::post('/payrollclerk/setup/parttime/saveempsubjects', 'HRControllers\HREmployeeParttimeController@saveempsubjects');
    Route::get('/payrollclerk/setup/parttime/updatesubjecttype', 'HRControllers\HREmployeeParttimeController@updatesubjecttype');
    Route::get('/payrollclerk/employees/profile/loadclloadsubjects', 'HRControllers\HREmployeeParttimeController@loadclloadsubjects');
    Route::get('/payrollclerk/employees/profile/getactivesem', 'HRControllers\HREmployeeParttimeController@getactivesem');
    Route::get('/payrollclerk/setup/parttime/update/persubject', 'HRControllers\HREmployeeParttimeController@persubject');

    // Employee Per Hour Setup
    Route::get('/payrollclerk/setup/collegesubjectperhour/perhour', 'HRControllers\HREmployeeParttimeController@perhour');
    Route::get('/payrollclerk/setup/collegesubjectperhour/loadhourlyrates', 'HRControllers\HREmployeeParttimeController@loadhourlyrates');
    Route::get('/payrollclerk/setup/collegesubjectperhour/addhourlyrate', 'HRControllers\HREmployeeParttimeController@addhourlyrate');
    Route::get('/payrollclerk/setup/collegesubjectperhour/addhourlyrateperemp', 'HRControllers\HREmployeeParttimeController@addhourlyrateperemp');
    Route::post('/payrollclerk/setup/collegesubjectperhour/addhourlyrateperrow', 'HRControllers\HREmployeeParttimeController@addhourlyrateperrow');

    // Semester Date Setup
    Route::get('/payrollclerk/setup/semesterdate', 'HRControllers\HREmployeeParttimeController@semesterdate');
    Route::get('/payrollclerk/setup/loadsemester', 'HRControllers\HREmployeeParttimeController@loadsemester');
    Route::get('/payrollclerk/setup/loadsemesterdate', 'HRControllers\HREmployeeParttimeController@loadsemesterdate');
    Route::get('/payrollclerk/setup/addsemesterdate', 'HRControllers\HREmployeeParttimeController@addsemesterdate');
    Route::get('/payrollclerk/setup/deletesemesterdate', 'HRControllers\HREmployeeParttimeController@deletesemesterdate');
    Route::get('/payrollclerk/setup/activatesemesterdate', 'HRControllers\HREmployeeParttimeController@activatesemesterdate');

    // Printables Deduction and ALlowances
    Route::get('/payrollclerk/setup/allowancesprintables', 'HRControllers\HREmployeePrintablesController@allowancesprintables');
    Route::get('/payrollclerk/setup/deductionsprintables', 'HRControllers\HREmployeePrintablesController@deductionsprintables');
    Route::get('/payrollclerk/setup/deductionslist', 'HRControllers\HREmployeePrintablesController@deductionslist');
    Route::get('/payrollclerk/setup/allowanceslist', 'HRControllers\HREmployeePrintablesController@allowanceslist');
    Route::get('/payrollclerk/setup/printdeduction', 'HRControllers\HREmployeePrintablesController@printdeduction');
    Route::get('/payrollclerk/setup/printallowance', 'HRControllers\HREmployeePrintablesController@printallowance');

    // Notification Setup HR
    Route::get('/hr/settings/notification/index', 'HRControllers\HREmployeeNotificationsController@index');
    Route::post('/hr/settings/notification/sendnotification', 'HRControllers\HREmployeeNotificationsController@sendnotification');
    Route::get('/hr/settings/notification/load_notifications', 'HRControllers\HREmployeeNotificationsController@load_notifications');
    Route::get('/hr/settings/notification/delete_notification', 'HRControllers\HREmployeeNotificationsController@delete_notification');


});

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::get('/applyleave/{id}', 'EmployeeLeavesController@leave');

    Route::get('/applyovertimedashboard/{id}', 'EmployeeOvertimeController@applyovertimedashboard');
    Route::post('/applyovertimerequest', 'EmployeeOvertimeController@applyovertimerequest');
    Route::get('/applyovertimeupdate/{id}', 'EmployeeOvertimeController@applyovertimeupdate');

    Route::get('/employeedailytimerecord/{id}', 'EmployeeDailyTimeRecordController@employeedailytimerecord');
    Route::get('/empdtr/updateremarks', 'EmployeeDailyTimeRecordController@updateremarks');
    Route::get('/employeepayrolldetails', 'EmployeePayrollHistoryController@employeepayrolldetails');

    // I N T R A N E T
    Route::get('/administrator/schoolfolders', 'SchoolFilesController@index');
    Route::get('/administrator/addfolder', 'SchoolFilesController@addfolder');
    Route::get('/administrator/folderview', 'SchoolFilesController@folderview');
    Route::post('/administrator/addfiles', 'SchoolFilesController@addfiles');
    Route::post('/administrator/media', 'SchoolFilesController@storeMedia')->name('projects.storeMedia');
    Route::get('/administrator/updatevisibleto', 'SchoolFilesController@updatevisibility');
    Route::get('/administrator/updatefiletype', 'SchoolFilesController@updatefiletype');
    Route::get('/administrator/updatefoldername', 'SchoolFilesController@updatefoldername');
    Route::get('/administrator/deletefolder', 'SchoolFilesController@deletefolder');
    Route::get('/administrator/updatefoldercolor', 'SchoolFilesController@updatefoldercolor');
    Route::get('/administrator/deletefile', 'SchoolFilesController@deletefile');
    Route::get('/administrator/downloadfile', 'SchoolFilesController@downloadfile');
    Route::get('/administrator/updatevisibilitytype', 'SchoolFilesController@updatevisibilitytype');
    Route::get('/administrator/removeaudience', 'SchoolFilesController@removeaudience');
    Route::get('/administrator/updatefilestatus', 'SchoolFilesController@updatefilestatus');
    Route::get('/administrator/whocanupload', 'SchoolFilesController@whocanupload');
    Route::get('/administrator/whocanuploadget', 'SchoolFilesController@whocanuploadget');
    Route::get('/administrator/whocanuploadgetusers', 'SchoolFilesController@whocanuploadgetusers');
    Route::get('/administrator/whocanuploadsubmit', 'SchoolFilesController@whocanuploadsubmit');
    Route::get('/administrator/getfileview', 'SchoolFilesController@getfileview');


    Route::get('/schoolfolderv2/index', 'SchoolFilesController@indexv2');
    Route::get('/schoolfolderv2/folder', 'SchoolFilesController@folder');
    Route::get('/schoolfolderv2/viewfolder', 'SchoolFilesController@viewfolder');
    Route::post('/schoolfolderv2/upload', 'SchoolFilesController@uploadfile');

    // MY DOCUMENTS
    Route::get('/mydocs/index', 'MyDocumentsController@index')->name('mydocsindex');
    Route::get('/mydocs/createfolder', 'MyDocumentsController@createfolder');
    Route::get('/mydocs/filesindex', 'MyDocumentsController@filesindex');
    Route::get('/mydocs/folderedit', 'MyDocumentsController@folderedit');
    Route::get('/mydocs/folderdelete', 'MyDocumentsController@folderdelete');
    Route::post('/mydocs/uploadfiles', 'MyDocumentsController@uploadfiles');
    Route::get('/mydocs/fileview', 'MyDocumentsController@fileview');
    Route::get('/mydocs/fileedit', 'MyDocumentsController@fileedit');
    Route::get('/mydocs/filedelete', 'MyDocumentsController@filedelete');

    Route::get('/mydocs/sharedfoldergetfiles', 'MyDocumentsController@sharedfoldergetfiles');

    // Apply Leave
    Route::get('/leaves/apply/index', 'EmployeeLeavesController@applyindex');
    Route::post('/leaves/apply/submit', 'EmployeeLeavesController@applysubmit');
    Route::post('/leaves/apply/uploadfiles', 'EmployeeLeavesController@uploadfiles');
    Route::get('/leaves/datesallowed/getinfo', 'EmployeeLeavesController@getdatesallowed');
    Route::get('/leaves/update/remarks', 'EmployeeLeavesController@updateremarks');
    Route::get('/leaves/delete/application', 'EmployeeLeavesController@deleteapplication');
    Route::get('/leaves/delete/ldate', 'EmployeeLeavesController@deleteldate');
    Route::get('/leaves/delete/file', 'EmployeeLeavesController@deletefile');

    // added by gian
    Route::get('/leaves/apply/loademployees', 'HRControllers\HREmployeeLeaveController@loademployees');
    Route::get('/leaves/apply/applyleaveperemp', 'EmployeeLeavesController@applyleaveperemp');
    Route::get('/leaves/apply/loadleaves', 'HRControllers\HREmployeeLeaveController@loadleaves');
    Route::get('/leaves/apply/loadavailableleaves', 'HRControllers\HREmployeeLeaveController@loadavailableleaves');
    Route::get('/leaves/apply/loadleavedetails', 'HRControllers\HREmployeeLeaveController@loadleavedetails');
    Route::get('/leaves/apply/loaddates', 'HRControllers\HREmployeeLeaveController@loaddates');
    Route::get('/leaves/apply/deletedates', 'HRControllers\HREmployeeLeaveController@deletedates');
    Route::get('/leaves/apply/deletedays', 'HRControllers\HREmployeeLeaveController@deletedays');
    Route::post('/leaves/apply/submitleave', 'EmployeeLeavesController@submitleave');
    Route::get('/leaves/apply/loadleavesapplied', 'HRControllers\HREmployeeLeaveController@loadleavesapplied');
    Route::get('/leaves/setdaysyears/add', 'HRControllers\HREmployeeLeaveController@add');
    Route::get('/leaves/setdaysyears/loaddaysyears', 'HRControllers\HREmployeeLeaveController@loaddaysyears');
    Route::get('/leaves/setdaysyears/delete', 'HRControllers\HREmployeeLeaveController@delete');
    Route::get('/leaves/setdaysyears/edityear', 'HRControllers\HREmployeeLeaveController@edityear');
    Route::get('/leaves/setdaysyears/editdays', 'HRControllers\HREmployeeLeaveController@editdays');

    // Filed Leaves
    Route::get('/hr/leaves/index', 'HRControllers\HRLeavesController@index');
    Route::get('/hr/leaves/filter', 'HRControllers\HRLeavesController@filter');
    Route::get('/hr/leaves/fileleave', 'HRControllers\HRLeavesController@fileleave');
    Route::get('/hr/leaves/delete', 'HRControllers\HRLeavesController@delete');
    Route::get('/hr/leaves/changestatus', 'HRControllers\HRLeavesController@changestatus');
    Route::get('/hr/leaves/pending', 'HRControllers\HRLeavesController@pending');
    Route::get('/hr/leaves/approve', 'HRControllers\HRLeavesController@approve');
    Route::get('/hr/leaves/disapprove', 'HRControllers\HRLeavesController@disapprove');
    Route::get('/hr/leaves/updatestatus', 'HRControllers\HRLeavesController@updatestatus');
    // Apply Overtime
    Route::get('/overtime/apply/index', 'EmployeeOvertimeController@applyindex');
    Route::get('/overtime/apply/submit', 'EmployeeOvertimeController@applysubmit');
    Route::get('/overtime/update/remarks', 'EmployeeOvertimeController@updateremarks');
    Route::get('/overtime/delete/overtime', 'EmployeeOvertimeController@deleteovertime');
    // Apply Undertime
    Route::get('/undertime/apply', 'UndertimeController@application');
    //Filed Undertimes
    Route::get('/approval/undertime/index', 'UndertimeController@index');
    Route::get('/approval/undertime/updatestatus', 'UndertimeController@updatestatus');

    // ------------------------- Virtual Classrooms ------------------------
    Route::get('/virtualclassroomindex', 'TeacherControllers\VirtualClassroomController@index');
    Route::get('/virtualclassroomcheckname', 'TeacherControllers\VirtualClassroomController@checkname');
    Route::get('/virtualclassroomgeneratepassword', 'TeacherControllers\VirtualClassroomController@getpassword');
    Route::get('/virtualclassroomvisit', 'TeacherControllers\VirtualClassroomController@visit');
    Route::get('/virtualclassroom/{id}', 'TeacherControllers\VirtualClassroomController@view');
    // Classroomview
    Route::post('/virtualclassroomaddfiles/{id}', 'TeacherControllers\VirtualClassroomController@addfiles');
    Route::get('/virtualclassroomdeleteattachment', 'TeacherControllers\VirtualClassroomController@deleteattachment');
    Route::post('/virtualclassroomcreateassignment', 'TeacherControllers\VirtualClassroomController@createassignment');
    Route::get('/virtualclassroomeditassignment', 'TeacherControllers\VirtualClassroomController@editclassassignment');
    Route::get('/virtualclassroomdeleteassignment', 'TeacherControllers\VirtualClassroomController@deleteassignment');
    Route::get('/virtualclassroomaddstudent', 'TeacherControllers\VirtualClassroomController@addstudent');
    Route::get('/virtualclassroomdeletestudent', 'TeacherControllers\VirtualClassroomController@deletestudent');
    Route::post('/virtualclassroomsubmitassignment', 'TeacherControllers\VirtualClassroomController@submitassignment');
    Route::get('/virtualclassroomdeleteturnedin', 'TeacherControllers\VirtualClassroomController@deleteturnedin');
    Route::get('/virtualclassroomscore/{action}', 'TeacherControllers\VirtualClassroomController@score');

    Route::get('/virtualclassrooms/call', 'TeacherControllers\VirtualClassroomController@call');
    Route::get('/virtualclassrooms/closevirtualclassroom', function () {
        return view('closebrowser');
    });
    //-------------------------- Online Messages ----------------------------
    // Route::get('/messagesindex','MessageController@index');
    // Route::get('/messages/loadmessages','MessageController@loadmessages');
    // Route::get('/messages/sendmessage','MessageController@sendmessage');

    // ------------------------- SCHOOL FORM 10 --------------------------------------------
    Route::get('/reports_schoolform10/index', 'RegistrarControllers\FormReportsController@reportsschoolform10index');
    Route::get('/reports_schoolform10/getgrades', 'RegistrarControllers\FormReportsController@getgrades');
    Route::get('/reports_schoolform10/selectacadprog', 'RegistrarControllers\FormReportsController@reportsschoolform10selectacadprog');
    Route::get('/reports_schoolform10/view', 'RegistrarControllers\FormReportsController@reportsschoolform10view');
    Route::get('/reports_schoolform10/getrecordspreschool', 'RegistrarControllers\FormReportsController@reportsschoolform10getrecords_preschool');
    Route::get('/reports_schoolform10/getrecordselem', 'RegistrarControllers\FormReportsController@reportsschoolform10getrecords_elem');
    Route::get('/reports_schoolform10/getrecordsjunior', 'RegistrarControllers\FormReportsController@reportsschoolform10getrecords_junior');

    Route::get('/reports_schoolform10/updateeligibility', 'RegistrarControllers\FormReportsController@reportsschoolform10updateeligibility');
    Route::get('/reports_schoolform10/submitfooter', 'RegistrarControllers\FormReportsController@reportsschoolform10updatefooter');
    Route::get('/reports_schoolform10/getaddnew', 'RegistrarControllers\FormReportsController@reportsschoolform10getaddnew');
    // Route::get('/reports_schoolform10/getsubjects', 'RegistrarControllers\FormReportsController@reportsschoolform10getsubjects');
    Route::get('/reports_schoolform10/submitnewform', 'RegistrarControllers\FormReportsController@reportsschoolform10submitnewform');
    Route::post('/reports_schoolform10/updateform', 'RegistrarControllers\FormReportsController@reportsschoolform10updateform');
    Route::get('/reports_schoolform10/deleterecord', 'RegistrarControllers\FormReportsController@reportsschoolform10deleterecord');
    Route::get('/reports_schoolform10/updateattendance', 'RegistrarControllers\FormReportsController@reportsschoolform10updateattendance');

    Route::get('/reports_schoolform10/getinfo', 'RegistrarControllers\FormReportsController@reportsschoolform10getinfo');
    Route::get('/reports_schoolform10/updateinfo', 'RegistrarControllers\FormReportsController@reportsschoolform10updateinfo');

    Route::get('/reports_schoolform10/getgradesedit', 'RegistrarControllers\FormReportsController@reportsschoolform10getgradesedit');
    Route::get('/reports_schoolform10/deletesubjectgrades', 'RegistrarControllers\FormReportsController@reportsschoolform10deletesubjectgrades');
    Route::get('/reports_schoolform10/editsubjectgrades', 'RegistrarControllers\FormReportsController@reportsschoolform10editsubjectgrades');
    Route::get('/reports_schoolform10/updateinmapeh', 'RegistrarControllers\FormReportsController@reportsschoolform10updateinmapeh');
    Route::get('/reports_schoolform10/updateintle', 'RegistrarControllers\FormReportsController@reportsschoolform10updateintle');
    Route::get('/reports_schoolform10/addsubjectgrades', 'RegistrarControllers\FormReportsController@reportsschoolform10addsubjectgrades');

    Route::get('/reports_schoolform10/getremedialclass', 'RegistrarControllers\FormReportsController@reportsschoolform10getremedialclass');
    Route::get('/reports_schoolform10/updateremedialheader', 'RegistrarControllers\FormReportsController@reportsschoolform10updateremedialheader');
    Route::get('/reports_schoolform10/addremedial', 'RegistrarControllers\FormReportsController@reportsschoolform10addremedial');
    Route::get('/reports_schoolform10/editremedial', 'RegistrarControllers\FormReportsController@reportsschoolform10editremedial');
    Route::get('/reports_schoolform10/deleteremedial', 'RegistrarControllers\FormReportsController@reportsschoolform10deleteremedial');

    Route::get('/reports_schoolform10/getsubjectsperquarter', 'RegistrarControllers\FormReportsController@reportsschoolform10getsubjectsperquarter');
    Route::get('/reports_schoolform10/submitquartergrades', 'RegistrarControllers\FormReportsController@reportsschoolform10submitquartergrades');

    Route::get('/reports_schoolform10/addinauto', 'RegistrarControllers\FormReportsController@reportsschoolform10addinauto');
    Route::get('/reports_schoolform10/editinauto', 'RegistrarControllers\FormReportsController@reportsschoolform10editinauto');
    Route::get('/reports_schoolform10/addsubjgradesinauto', 'RegistrarControllers\FormReportsController@addsubjgradesinauto');
    Route::get('/reports_schoolform10/updatesubjgradesinauto', 'RegistrarControllers\FormReportsController@updatesubjgradesinauto');
    Route::get('/reports_schoolform10/deletesubjgradesinauto', 'RegistrarControllers\FormReportsController@deletesubjgradesinauto');

    Route::get('/printable/masterlist', 'RegistrarControllers\ReportsController@index');

    // ------------------------- // //--------------------------------------------

    // SCHOOL FORM 2

    Route::get('/forms/form2', 'TeacherControllers\TeacherFormController@form2');
    Route::get('/forms/form2shsindex', 'TeacherControllers\TeacherFormController@form2shsindex');
    Route::get('/forms/form2enrollmentmonth', 'TeacherControllers\TeacherFormController@enrollmentmonth');
    Route::get('/forms/form2summarytable', 'TeacherControllers\TeacherFormController@form2summarytable');

    Route::get('/forms/form3', 'RegistrarControllers\ReportsController@form3');

    // SCHOOL FORM 5
    Route::get('/forms/form5', 'TeacherControllers\TeacherFormController@form5');
    Route::get('/forms/form5aindex', 'TeacherControllers\ReportsSHSController@form5aindex');
    Route::get('/forms/form5a', 'TeacherControllers\ReportsSHSController@form5a');
    Route::get('/forms/form5bindex', 'TeacherControllers\ReportsSHSController@form5bindex');
    Route::get('/forms/form5b', 'TeacherControllers\ReportsSHSController@form5b');

    // SCHOOL FORM 9
    Route::get('/forms/form9', 'TeacherControllers\TeacherFormController@form9');
    // ---------------------- TOR --------------------------------
    Route::get('/schoolform/tor/index', 'RegistrarControllers\TORController@index');
    Route::get('/schoolform/tor/getrecords', 'RegistrarControllers\TORController@getrecords')->name('torgetrecords');
    Route::get('/schoolform/tor/getrecord', 'RegistrarControllers\TORController@getrecord')->name('torgetrecord');
    Route::get('/schoolform/tor/updaterecord', 'RegistrarControllers\TORController@updaterecord')->name('torupdaterecord');
    Route::get('/schoolform/tor/savedetail', 'RegistrarControllers\TORController@savedetail')->name('torsavedetail');
    Route::get('/schoolform/tor/savetext', 'RegistrarControllers\TORController@savetext')->name('torsavetext');
    Route::get('/schoolform/tor/savesignatories', 'RegistrarControllers\TORController@savesignatories')->name('torsavesignatories');
    Route::get('/schoolform/tor/deletetext', 'RegistrarControllers\TORController@deletetext')->name('tordeletetext');
    Route::get('/schoolform/tor/addnewrecord', 'RegistrarControllers\TORController@addnewrecord')->name('toraddnewrecord');
    Route::get('/schoolform/tor/addnewdata', 'RegistrarControllers\TORController@addnewdata')->name('toraddnewdata');
    Route::get('/schoolform/tor/getsubjects', 'RegistrarControllers\TORController@getsubjects')->name('torgetsubjects');
    Route::get('/schoolform/tor/editsubjgrade', 'RegistrarControllers\TORController@editsubjgrade')->name('toreditsubjgrade');
    Route::get('/schoolform/tor/deletesubjgrade', 'RegistrarControllers\TORController@deletesubjgrade')->name('tordeletesubjgrade');
    Route::get('/schoolform/tor/deleterecord', 'RegistrarControllers\TORController@deleterecord')->name('tordeleterecord');
    Route::get('/schoolform/tor/exporttopdf', 'RegistrarControllers\TORController@exporttopdf');

    //------------------------------------------------------------

    // ---------------------- RECORD OF CANDIDATE FOR GRADUATION --------------------------------
    Route::get('/schoolform/rcfg/index', 'RegistrarControllers\RCFGController@index');
    Route::get('/schoolform/rcfggetrecords/getrecords', 'RegistrarControllers\RCFGController@getrecords')->name('rcfggetrecords');
    Route::get('/schoolform/rcfggetrecords/subjgroupunitplot', 'RegistrarControllers\RCFGController@subjgroupunitplot')->name('rcfgsubjgroupunitplot');
    //------------------------------------------------------------


    Route::get('/setup/signatories', 'SignatoriesController@index');
    Route::get('/setup/signatories/getacadprogs', 'SignatoriesController@getacadprogs');
    Route::get('/setup/signatories/getlevelids', 'SignatoriesController@getlevelids');
    Route::get('/setup/signatories/getsignatories', 'SignatoriesController@getsignatories');
    Route::get('/setup/signatories/savechanges', 'SignatoriesController@savechanges');
    Route::get('/setup/signatories/deletesignatory', 'SignatoriesController@deletesignatory');

    Route::get('/setup/forms/sf2', 'RegistrarControllers\RegistrarFunctionController@forms_sf2');

    Route::get('/setup/signatories', 'SignatoriesController@index');
    Route::get('/setup/signatories/getacadprogs', 'SignatoriesController@getacadprogs');
    Route::get('/setup/signatories/getlevelids', 'SignatoriesController@getlevelids');
    Route::get('/setup/signatories/getsignatories', 'SignatoriesController@getsignatories');
    Route::get('/setup/signatories/savechanges', 'SignatoriesController@savechanges');
    Route::get('/setup/signatories/deletesignatory', 'SignatoriesController@deletesignatory');

    Route::get('/setup/subjgrouping', 'RegistrarControllers\CollegeFormsController@subjgrouping');

    Route::get('/setup/studdisplayphoto/index', 'RegistrarControllers\RegistrarFormsController@studdisplayphotoindex');
    Route::get('/setup/studdisplayphoto/getphoto', 'RegistrarControllers\RegistrarFormsController@studdisplayphotoget');
    Route::post('/setup/studdisplayphoto/uploadphoto', 'RegistrarControllers\RegistrarFormsController@studdisplayphotoupload');


    Route::get('/setup/admissiondate', 'RegistrarControllers\RegistrarSetupController@admissiondate');

    Route::get('/schoolform/sf7', 'SchoolFormsController@sf7');
    Route::get('/dtr/attendance/index', 'EmployeeDailyTimeRecordController@dtr_v2');
});

Route::group(['scheme' => 'https'], function () {
    // Route::get(...)->name(...);
});

Route::get('/getpayables/{studentstatus}', 'RegistrarControllers\PreRegistrationController@getpayables');
Route::get('/storeprereg/{studentstatus}', 'RegistrarControllers\PreRegistrationController@storeprereg');

Route::middleware(['guest'])->group(function () {
    Route::get('/coderecovery', function () {
        return view('coderecovery');
    });
    Route::get('/proccess/recoverycode', 'GeneralController@recovercode');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/changepass', 'GeneralController@changePass');
    Route::get('/gotoPortal/{id}', 'GeneralController@gotoPortal');
});

Route::get('/prereg/{studentstatus}', 'RegistrarControllers\PreRegistrationController@prereg');
Route::get('/preregsenior', 'RegistrarControllers\PreRegistrationController@senior');
Route::get('/prereg/questions/{gradelevel}', 'RegistrarControllers\PreRegistrationController@preregquestion');

Route::get('/studentUserDebugger', 'DebuggerController@studentUserDebugger');
Route::get('/fixAccountConflict', 'DebuggerController@fixAccountConflict');







Route::middleware(['isDefaultPass'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');

});



Route::middleware(['auth', 'isDean'])->group(function () {

    Route::get('dean/courses/', 'DeanControllers\DeanController@viewcourses')->name('dean.courses');
    Route::get('dean/prospectus/{course}', 'DeanControllers\DeanController@viewprospectus')->name('dean.prospectus');
    Route::get('dean/faculties', 'DeanControllers\DeanController@viewfaculties')->name('dean.faculties');
    Route::get('dean/store/prospectus', 'DeanControllers\DeanController@storeprospectus')->name('dean.store.prospectus');
    Route::get('dean/remove/prospectussubject/{subject}', 'DeanControllers\DeanController@removeprospectussubject')->name('dean.remove.prospectussubject');

    Route::get('dean/view/submitted/grades', 'DeanControllers\DeanController@deanviewsubmittedgrades');

    Route::get('dean/view/grades', 'DeanControllers\DeanController@viewgrades');
    Route::get('dean/view/all/grades', 'DeanControllers\DeanController@viewallgrades');

});


Route::get('dean/sections', 'DeanControllers\DeanController@viewsections')->name('dean.sections');


//chairperson
Route::get('chairperson/sections/', 'CPControllers\CPController@viewsections');
Route::get('chairperson/courses/', 'CPControllers\CPController@chairperson_courses');
Route::get('chairperson/courses/curriculum', 'CPControllers\CPController@get_curriculum');
Route::get('chairperson/prospectus/', 'CPControllers\CPController@viewprospectus');
Route::get('chairperson/sections/create', 'CPControllers\CPController@storesections');
Route::get('chairperson/sections/createv2', 'CPControllers\CPController@store_section');
Route::get('chairperson/sections/show/{section}', 'CPControllers\CPController@showection');
//Route::get('chairperson/sections/remove/{section}', 'CPControllers\CPController@removesection');
//cp_new

Route::get('/prinsf9print/{id}', 'PrincipalControllers\DynamicPDFController@sf9pdf');
Route::middleware(['auth'])->group(function () {

    Route::get('corprintingblade', 'ScholarshipCoor\ScholarshipCoorController@index');
    Route::get('studentforprintingtable', 'ScholarshipCoor\ScholarshipCoorController@studentforprintingtable');
    Route::get('printcor/{id}', 'ScholarshipCoor\ScholarshipCoorController@printcor');

    Route::get('printcor/{id}', 'ScholarshipCoor\ScholarshipCoorController@printcor');

    Route::get('collegeStudentMasterlist/', 'ScholarshipCoor\ScholarshipCoorController@college_student_masterlist');

    // Route::get('collge/studentsubjects', 'CollegeControllers\CollegeReportController@studentsubjects');

    Route::get('collge/report/enrollment', 'CollegeControllers\CollegeReportController@studentsubjects');
    Route::get('signatory', 'CollegeControllers\CollegeReportController@signatory');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/preschool/gradingsystem/list', 'SuperAdminController\PreSchoolGradingController@grading_setup_list');
    Route::get('/preschool/gradingsystem/create', 'SuperAdminController\PreSchoolGradingController@grading_setup_create');
    Route::get('/preschool/gradingsystem/update', 'SuperAdminController\PreSchoolGradingController@grading_setup_update');
    Route::get('/preschool/gradingsystem/delete', 'SuperAdminController\PreSchoolGradingController@grading_setup_delete');

    Route::get('/preschool/ratingvalue/list', 'SuperAdminController\PreSchoolGradingController@ratingvalue_list');
    Route::get('/preschool/ratingvalue/create', 'SuperAdminController\PreSchoolGradingController@ratingvalue_create');
    Route::get('/preschool/ratingvalue/update', 'SuperAdminController\PreSchoolGradingController@ratingvalue_update');
    Route::get('/preschool/ratingvalue/delete', 'SuperAdminController\PreSchoolGradingController@ratingvalue_delete');
});

Route::middleware(['auth', 'isCP'])->group(function () {

    Route::get('chairperson/scheduling/{location}', 'CPControllers\CPController@scheduling');
    Route::get('chairperson/scheduling/show/{studentid}/{studentname}', 'CPControllers\CPController@studentscheduling');
    Route::get('chairperson/scheduling/sectscshed/{section}', 'CPControllers\CPController@sectscshed');
    Route::get('chairperson/schedule/student/{student}/{section}', 'CPControllers\CPController@storestudentsched');
    Route::get('chairperson/teacher/subject/{subject}', 'CPControllers\CPController@teachersubject');
    Route::get('chairperson/scheddetail/create/{section}', 'CPControllers\CPController@createscheddetail');
    Route::get('chairperson/addtosched/{schedetail}/section/{section}', 'CPControllers\CPController@addtoSched');
    Route::get('chairperson/checkifpassed/{schedetail}/{studid}', 'CPControllers\CPController@checkifpassed');
    Route::get('chairperson/getsched/allcollege/{subject}', 'CPControllers\CPController@allcollegesub');
    Route::get('/chairperson/update/student/course/{id}/{course}', 'CPControllers\CPController@udpatestudencourse');
    Route::get('/chairperson/remove/schedule/{id}', 'CPControllers\CPController@removescheddetail');

    Route::get('/chairperson/update/student/curriculum', 'CPControllers\CPController@studentcurriculum');

    Route::post('unloadedSubjects', 'CPControllers\CPController@unloadedsubjects');
    Route::post('loadSubjetsToSection', 'CPControllers\CPController@loadSubjetsToSection');

    //final_grade_college
    Route::get('chairperson/student/grades', 'CPControllers\CPController@chairperson_student_grades');
    Route::get('chairperson/section/subject', 'CPControllers\CPController@chairperson_section_subjects');
    Route::get('college/student/grade/status/approve', 'CPControllers\CPController@approve_grade_status');
    Route::get('college/student/grade/status/pending', 'CPControllers\CPController@pending_grade_status');
    Route::get('college/student/grade/status/post', 'CPControllers\CPController@post_grade_status');
    //final_grade_college

    //college_sections
    Route::get('college/chairpseron/sections', 'CPControllers\CPController@chairperson_sections');
    Route::get('college/chairpseron/sections/update', 'CPControllers\CPController@update_section');
    Route::get('college/chairpseron/sections/remove', 'CPControllers\CPController@remove_section');
    Route::get('college/chairpseron/addinstructor', 'CPControllers\CPController@add_instructor');
    Route::get('college/section/add/subject', 'CPControllers\CPController@add_section_subject');
    //college_sections
});

//college_data
Route::get('/chairpersoninfo', 'CPControllers\CPController@chairpersoninfo');
Route::get('/college/subjects', 'CPControllers\CPController@college_subjects');
Route::get('/college/techer', 'CPControllers\CPController@college_teacher');
Route::get('/subject/schedule/blade', 'CPControllers\CPController@subject_schedule_blade');
Route::get('/subject/schedule', 'CPControllers\CPController@subject_schedule');
//college_date

Route::get('/chairpersoninfo', 'CPControllers\CPController@chairpersoninfo');

Route::get('tabletest', function () {
    return view('table');
});

Route::get('preregcode', function () {
    return view('registrar.preregistrationgetcode');
});

Route::get('teacher/get/grades/{section}/{quarter}', 'CPControllers\CPController@getGrades');
Route::get('teacher/update/grades', 'CPControllers\CPController@updategrades');

Route::get('teacher/update/igfg', 'CPControllers\CPController@updateigfg');

Route::get('teacher/update/hps', 'CPControllers\CPController@updatehps');



Auth::routes();


Route::get('hasher', function () {
    return Hash::make('123456789');
});

Route::get('crypt/{value}', function ($value) {
    return Crypt::encrypt($value);
});

Route::get('gettime', function () {
    date_default_timezone_set('Asia/Manila');
    $date = date('Y-m-d H:i:s');
    return \Carbon\Carbon::now('Asia/Manila');
});


Route::get('student/enrollment/setup', 'EnrollmentSetupController@check_enrollment_setup')->name('check_enrollment_setup');

Route::get('/early/enrollment/submit', 'RegistrarControllers\PreRegistrationControllerV2@early_enrollment_submit');
Route::get('/pre/enrollment/submit', 'RegistrarControllers\PreRegistrationControllerV2@pre_enrollment_submit');




Route::middleware(['auth', 'isCT', 'isDefaultPass'])->group(function () {

    Route::get('schedule', 'CTController\CTController@schedule');

    Route::get('college/teacher/grading', 'CTController\CTController@ctgrading');

    Route::get('college/teacher/quartersetup', function () {
        return view('ctportal.pages.setup.quartersetup');
    });

    Route::get('/college/teacher/store/grades', 'CTController\CTController@storegrades');
    Route::get('/college/teacher/store/gradesv2', 'CTController\CTController@storegradev2');
    Route::get('/college/teacher/update/hps', 'CTController\CTController@updatehpsv2');

    Route::post('college/teacher/createsetup', 'CTController\CTController@createSetup');

    Route::get('/college/teacher/gradesetup', 'CTController\CTController@gradesetup');


    Route::get('college/teacher/update/student/gradesdetail', 'CTController\CTController@udpatestudentgd');

    Route::get('college/teacher/gradesetuptable', 'CTController\CTController@gradesetuptable');

    // VIRTUAL CLASSROOM
    Route::get('college/teacher/vc/index', 'TeacherControllers\VirtualClassroomController@index');
    Route::get('college/teacher/vc/visit', 'TeacherControllers\VirtualClassroomController@visit');
    Route::get('college/teacher/vc/getstudents', 'TeacherControllers\VirtualClassroomController@getstudents');
    Route::get('college/teacher/vc/getassignments', 'TeacherControllers\VirtualClassroomController@getassignments');
    Route::post('college/teacher/vc/publishass', 'TeacherControllers\VirtualClassroomController@createassignment');
    Route::get('college/teacher/vc/getassignmentinfo', 'TeacherControllers\VirtualClassroomController@getassignmentinfo');
    Route::post('college/teacher/vc/editass', 'TeacherControllers\VirtualClassroomController@editclassassignment');
    Route::get('college/teacher/vc/call/{id}', 'TeacherControllers\VirtualClassroomController@view');


    Route::get('college/student/info', 'CTController\CTController@studentinformation');
    Route::get('college/teacher/sched', 'CTController\CTController@classsched');




});

//final_grade_college
Route::get('college/assignedsubj', 'CTController\CTController@get_assigned_subj');
Route::get('college/student/grade/save', 'CTController\CTController@save_student_grade');
Route::get('college/teacher/gradesubmission', 'CTController\CTController@grade_submission');
Route::get('college/student/grade/status/submit', 'CTController\CTController@submit_grade_status');
//final_grade_college

//final_grade_college
Route::get('college/subject/students', 'CTController\CTController@subject_students');
Route::get('college/student/grade/status', 'CTController\CTController@get_grade_status');
//final_grade_college


Route::post('subjecttermsetup', 'CTController\CTController@viewsubjecttermsetup');




Route::get('tablemaxid/{id}/{count}', 'SyncController@synccheckreturn');
Route::get('tableupdate/{id}/{date}', 'SyncController@syncupdatereturn');
Route::get('tabledeleted/{id}/{date}', 'SyncController@syncdeletedreturn');

Route::get('checktargetmaxtable/{id}', 'SyncController@checktargetmaxtable');

Route::get('insertdatatotable', 'SyncController@insertdatatotable')->name('insertdatatotable');
Route::get('updatetargettable', 'SyncController@updatetargettable')->name('updatetargettable');
Route::get('deletetargettable', 'SyncController@deletetargettable')->name('deletetargettable');
Route::get('querylogsToCloud', 'SyncController@querylogsToCloud')->name('querylogsToCloud');
Route::get('querylogstoLocal', 'SyncController@querylogstoLocal')->name('querylogstoLocal');
Route::get('updatelogid', 'SyncController@updatelogid')->name('updatelogid');
Route::get('getTableFields/{id}', 'SyncController@getTableFields')->name('getTableFields');

Route::get('getOfflinerefIdMax/{tablename}', 'SyncController@getOfflinerefIdMax')->name('getOfflinerefIdMax');

Route::get('fetcthdatafromsource', 'SyncController@fetcthdatafromsource');

Route::get('cloudtesting', 'SyncController@cloudtesting');
Route::get('localCheckConnection', 'SyncController@localCheckConnection');

//--------------API ESSENTIEL SMS BUNKER --------------------------------//

// Route::get('/apismsgetsmsbunker', 'SMSBunkerController@apismsgetsmsbunker')->name('apismsgetsmsbunker');
// Route::get('/apismsgetsmsbunker/apismsgetok/{status}/{id1}/{id2}', 'SMSBunkerController@apismsgetok')->name('apismsgetok');

// Route::get('/apismsblast', 'SMSBunkerController@apismsblast')->name('apismsblast');
// Route::get('/apismsblast/apismsblastok/{status}/{id1}/{id2}', 'SMSBunkerController@apismsblastok')->name('apismsblastok');


// Route::get('/updateStudentInfo', 'ParentControllers\ParentsController@updateStudentInfo')->name('updateStudentInfo');

// Route::get('/viewLoginUsers', 'SchoolMonitoring\SchoolMonitoring@viewLoginUsers')->name('viewLoginUsers');

// Route::get('/cklmsgenerateaccount', 'CKLMSControllers\GenerateAccountsController@index');

//--------------API ESSENTIEL SMS BUNKER --------------------------------//

Route::get('/apismsgetsmsbunker', 'SMSBunkerController@apismsgetsmsbunker')->name('apismsgetsmsbunker');
Route::get('/apismsgetsmsbunker/apismsgetok/{status}/{id1}/{id2}', 'SMSBunkerController@apismsgetok')->name('apismsgetok');

Route::get('/apismsblast', 'SMSBunkerController@apismsblast')->name('apismsblast');
Route::get('/apismsblast/apismsblastok/{status}/{id1}/{id2}', 'SMSBunkerController@apismsblastok')->name('apismsblastok');

Route::get('/apipushtobunker/{msg}/{receiver}/{school}', 'SMSBunkerController@apipushtobunker')->name('apipushtobunker');
Route::get('/apismsgetsmsbunker/tapbunker', 'SMSBunkerController@apismsgettapbunker')->name('apismsgettapbunker');
Route::get('/apismsgetsmsbunker/tapbunker/apismsgetoktapbunker/{status}/{id1}/{id2}', 'SMSBunkerController@apismsgetoktapbunker')->name('apismsgetoktapbunker');
Route::get('/apipushtotapbunker', 'SMSBunkerController@apipushtotapbunker')->name('apipushtotapbunker');

//--------------API ESSENTIEL SMS BUNKER --------------------------------//

//-----------API FETCH INFO----------------//
Route::middleware(['cors'])->group(function () {
    Route::get('/api/student_fetch', 'SMSBunkerController@studentfetch')->name('api_studentfetch');
});
Route::middleware(['cors'])->group(function () {
    Route::get('/api/employees_fetch', 'SMSBunkerController@employeesfetch')->name('api_employeesfetch');
});
//-----------API FETCH INFO----------------//

//-----------API TAPPING----------------//
Route::middleware(['cors'])->group(function () {
    Route::get('/api_pushtap', 'SMSBunkerController@api_pushtap')->name('api_pushtap');
});
//-----------API TAPPING----------------//

//--------------API UPDATE LEDGER --------------------------------//
Route::middleware(['cors'])->group(function () {
    Route::get('/api_updateledger', 'FinanceControllers\UtilityController@resetpayment_v3');
});
//--------------API UPDATE LEDGER --------------------------------//

//-----------API STUDENT INFO----------------//
Route::middleware(['cors'])->group(function () {
    Route::get('/api/student_insert', 'enrollment\EnrollmentController@studentinsert')->name('api_student_insert');
});
//-----------API STUDENT INFO----------------//

//school monitoring
Route::middleware(['cors'])->group(function () {
    Route::get('/monitoring/textblast', 'SchoolMonitoring\SchoolMonitoring@textblast');
    Route::get('/monitoring/synchonization', 'SchoolMonitoring\SchoolMonitoring@synchonization');
    Route::get('/monitoring/tables', 'SchoolMonitoring\SchoolMonitoring@tables');
    Route::get('/monitoring/tablecount', 'SchoolMonitoring\SchoolMonitoring@table_count');
    Route::get('/monitoring/table/data', 'SchoolMonitoring\SchoolMonitoring@table_data');
    Route::get('/monitoring/table/data/updated', 'SchoolMonitoring\SchoolMonitoring@table_data_update');
    Route::get('/monitoring/table/data/deleted', 'SchoolMonitoring\SchoolMonitoring@table_data_deleted');
    Route::get('/monitoring/get/picurl', 'SchoolMonitoring\SchoolMonitoring@get_pic_url');
    Route::get('/monitoring/get/lastdatesync', 'SchoolMonitoring\SchoolMonitoring@last_date_sync');
    Route::get('/monitoring/get/updatelogs', 'SchoolMonitoring\SchoolMonitoring@updatelogs');
});
//school monitoring

//synchronization v2
Route::middleware(['cors'])->group(function () {
    Route::get('/synchornization/insert', 'SyncController\SyncControllerV2@synccreate');
    Route::get('/synchornization/update', 'SyncController\SyncControllerV2@syncupdate');
    Route::get('/synchornization/delete', 'SyncController\SyncControllerV2@syncdelete');
    Route::get('/synchornization/insert/synclogs', 'SyncController\SyncControllerV2@insertsynclogs');
    Route::get('/synchornization/process/updatelogs', 'SyncController\SyncControllerV2@process_updatelogs');
    Route::get('/synchornization/process/updatelogs/status', 'SyncController\SyncControllerV2@process_updatelogs_status');
});
//synchronization v2


Route::middleware(['auth', 'isSuperAdmin:teacher,principal'])->group(function () {
    //grading system v2
    Route::get('/gradestudent/preschool', 'TeacherControllers\TeacherGradingV3@preschoolGrading'); //grading v5
    // Route::get('/reportcard/coreval/seniorhigh', 'TeacherControllers\TeacherGradingV3@Grading');
    Route::get('/reportcard/coreval/gradeschool', 'TeacherControllers\TeacherGradingV3@gradeschoolcoreval');
    Route::get('/reportcard/coreval/highschool', 'TeacherControllers\TeacherGradingV3@highschoolcoreval');
    Route::get('/reportcard/coreval/seniorhigh', 'TeacherControllers\TeacherGradingV3@seniorhighcoreval');

    Route::get('/reportcard/homeroom/gradeschool', 'TeacherControllers\TeacherGradingV3@gradeschoolhomeroom');
    Route::get('/reportcard/homeroom/highschool', 'TeacherControllers\TeacherGradingV3@highschoolhomeroom');
    Route::get('/reportcard/homeroom/seniorhigh', 'TeacherControllers\TeacherGradingV3@seniorhighhomeroom');

    Route::get('/reportcard/grade/status', 'TeacherControllers\TeacherGradingV3@gradeStatus');
    Route::get('/reportcard/grade/status/preschool', 'TeacherControllers\TeacherGradingV3@gradeStatusPreSchool');
    Route::get('/reportcard/grade/status/filter', 'TeacherControllers\TeacherGradingV3@get_grade_status');

    Route::get('/reportcard/grade/sf9/seniorhigh', 'TeacherControllers\TeacherGradingV3@viewsf9sh');
    Route::get('/reportcard/grade/sf9/gradeschool', 'TeacherControllers\TeacherGradingV3@viewsf9gs');
    Route::get('/reportcard/grade/sf9/highschool', 'TeacherControllers\TeacherGradingV3@viewsf9hs');
    Route::get('/reportcard/grade/sf9/preschool', 'TeacherControllers\TeacherGradingV3@viewsf9ps');
    Route::get('/reportcard/grade/sf9/psper', 'TeacherControllers\TeacherGradingV3@viewsf9psper');
    Route::get('/reportcard/grade/status/submit', 'TeacherControllers\TeacherGradingV3@submitGrade');
    Route::get('/reportcard/generate/status', 'TeacherControllers\TeacherGradingV3@generateStatus');
    Route::get('student/reportcard', 'TeacherControllers\TeacherGradingV3@studentreportcard');
    //grading system v2
});


//grading system v2
Route::get('/reportcard/grade/status/approve', 'TeacherControllers\TeacherGradingV3@approveGrade');
Route::get('/reportcard/grade/status/post', 'TeacherControllers\TeacherGradingV3@postGrade');
Route::get('/reportcard/grade/status/pending', 'TeacherControllers\TeacherGradingV3@pendingGrade');
Route::get('principal/reportcard', 'TeacherControllers\TeacherGradingV3@principalGrading');
//grading system v2




Route::middleware(['auth', 'isSuperAdmin:superadmin'])->group(function () {
    Route::get('lockfees', 'FinanceControllers\UtilityController@lockfees')->name('lockfees');
    Route::get('/generate/excel/ms_teams', 'SuperAdminController\ExcelGenerator@ms_team_account');
    Route::get('admin/view/teacherEvaluation', 'TeacherControllers\TeacherEvaluations@admin_view_results');
    Route::get('student/information/profile', 'StudentControllers\StudentInformation@student_profile');
    Route::post('student/information/upload/idpic', 'StudentControllers\StudentInformation@upload_student_id_picture');
    Route::get('superadmin/upload/excel/grades', 'SuperAdminController\UploadExcel@upload_grades');
    Route::post('proccess/excel/file', 'SuperAdminController\UploadExcel@proccess_excel');

    //student promotion v2
    Route::view('student/promotion/uv3', 'superadmin.pages.student_promotion_v3');
    Route::get('student/promotion/uv3/students', 'SuperAdminController\StudentPromotion@students');
    Route::get('student/promotion/uv3/prmotestudent', 'SuperAdminController\StudentPromotion@promote_student');
    Route::get('student/promotion/u3', 'SuperAdminController\StudentPromotion@student_promotion_blade_v3');
    Route::get('student/promotion/v3', 'SuperAdminController\StudentPromotion@student_promotion_v3');
    Route::get('student/promotion/students/v3', 'SuperAdminController\StudentPromotion@students');
    Route::get('student/promotion/promote/v3', 'SuperAdminController\StudentPromotion@student_promote_v3');
    //student promotion v2


    //superadmin subjects
    Route::view('/superadmin/subject', 'superadmin.pages.subject_setup');
    Route::get('/superadmin/subject/gradelevel', 'SuperAdminController\SubjectSetupController@gradelevel_subjects');
    Route::get('/superadmin/subject/update/sort', 'SuperAdminController\SubjectSetupController@subjectsort_update');
    //superadmin subjects

});

//student specialization
Route::middleware(['auth', 'isDefaultPass'])->group(function () {
    Route::view('/student/specialization', 'superadmin.pages.student_specialization');
    Route::get('/superadmin/student/specialization/subjects', 'SuperAdminController\StudentSpecialization@subjects');
    Route::get('/superadmin/student/specialization/students', 'SuperAdminController\StudentSpecialization@all_student_ajax');
    Route::get('/superadmin/student/specialization/data', 'SuperAdminController\StudentSpecialization@subjects_studspec_ajax');
    Route::get('/superadmin/student/specialization/create', 'SuperAdminController\StudentSpecialization@subjects_studspec_create');
    Route::get('/superadmin/student/specialization/create/all', 'SuperAdminController\StudentSpecialization@subjects_studspec_create_all');

    Route::get('/superadmin/student/specialization/delete', 'SuperAdminController\StudentSpecialization@subjects_studspec_delete');
    Route::get('/superadmin/student/specialization/subjects/gradelevel', 'SuperAdminController\StudentSpecialization@gradelevel_list');
    Route::get('/superadmin/student/specialization/section', 'SuperAdminController\StudentSpecialization@sections')->name('sections');
    Route::get('/superadmin/student/specialization/print', 'SuperAdminController\StudentSpecialization@print_report')->name('print_report');
});
//student specialization

Route::get('/teacherevaluation/schedule', 'TeacherControllers\TeacherEvaluations@teachr_schedule');
Route::get('/teacherevaluation/checkEvaluation', 'TeacherControllers\TeacherEvaluations@check_evaluation');

//student enrollment
Route::get('student/enrollment', function () {
    return view('studentPortal.enrollment');
});
Route::get('student/enrollment/setup', 'EnrollmentSetupController@list')->name('check_enrollment_setup');


Route::middleware(['guest'])->group(function () {

    Route::get('/viewSurveyForm', 'SurveyControllers\SurveyController@viewSurveyForm');

});

Route::get('cloudNewData/{table}/{maxval}', 'SyncController@cloudNewData');
Route::get('cloudUpdatedData/{table}/{date}', 'SyncController@cloudUpdatedData');
Route::get('cloudDeletedData/{table}/{date}', 'SyncController@cloudDeletedData');
Route::get('cloudGetSyncSetup', 'SyncController@cloudGetSyncSetup');
Route::get('storeImage', 'SyncController@storeImage');
Route::get('get/item/count/{id}', 'SyncController@getItemcount');



Route::middleware(['auth', 'isAdminAdmin'])->group(function () {

    Route::get('/viewschool/{id}', 'AdminadminController\AdminAdminController@viewadmiadmin');
    Route::get('/enrollmentReport', 'AdminadminController\AdminAdminController@enrollmentReport');
    Route::get('/cashtransReport', 'AdminadminController\AdminAdminController@cashtransReport');
    Route::get('/finance/index', 'AdminadminController\AdminAdminController@financeindex');
    Route::get('/academic/index', 'AdminadminController\AdminAdminController@academicindex');
    Route::get('/academic/students', 'AdminadminController\AdminAdminController@academicstudents');
    Route::get('/hr/index', 'AdminadminController\AdminAdminController@hrindex');

    Route::get('/filtercashtrans', 'AdminadminController\AdminAdminController@filtercashtrans');
    Route::get('/filterEnrollmentReport', 'AdminadminController\AdminAdminController@filterEnrollmentReport');

});

Route::get('/studentmasterlist', 'AdminadminController\AdminAdminController@studentmasterlist');
Route::get('/cashtransaction', 'AdminadminController\AdminAdminController@chrngtrans');
Route::get('/targetcollection', 'AdminadminController\AdminAdminController@targetcollection');

//teacher final grade for basic education
Route::middleware(['auth', 'isDefaultPass'])->group(function () {
    Route::view('teacher/finalgrades', 'teacher.grading.finalgrade');
    Route::get('teacher/get/teacheingload', 'TeacherControllers\TeacherFinalGrade@teachingload');
    Route::get('teacher/get/gradestatus', 'TeacherControllers\TeacherFinalGrade@gradestatus');
    Route::get('teacher/submit/grades', 'TeacherControllers\TeacherFinalGrade@submit_grades');
    Route::get('teacher/get/students', 'TeacherControllers\TeacherFinalGrade@enrolled_learners');
    Route::get('teacher/finalgrades/savegrades', 'TeacherControllers\TeacherFinalGrade@save_grades');

    Route::get('teacher/finalgrades/extract', 'SuperAdminController\FinalGradeUpload@downloadGrades');
    Route::post('teacher/finalgrades/upload', 'SuperAdminController\FinalGradeUpload@uploadGrades');
    Route::view('teacher/finalgrades/excel', 'superadmin.pages.utility.finalgradetosystem');
});
//teacher final grade for basic education

//teacher view pending grades
Route::middleware(['auth', 'isDefaultPass'])->group(function () {
    Route::view('teacher/pending/grades/view', 'teacher.grading.pendinggrade');
    Route::get('teacher/pending/grade/list', 'TeacherControllers\TeacherPendingGrade@peding_student_grades');
    Route::get('teacher/pending/grade/list/getgrades/{id}', 'TeacherControllers\TeacherPendingGrade@getGrades');
    Route::get('teacher/pending/grade/submit/grades', 'TeacherControllers\TeacherPendingGrade@submit_pending_grades');
});
//teache view pending grades

//awards setup
Route::middleware(['auth', 'isDefaultPass'])->group(function () {
    Route::get('/awarsetup/list', 'PrincipalControllers\AwardSetupController@list_award_setup');
    Route::get('/awarsetup/update/lowest', 'PrincipalControllers\AwardSetupController@update_award_setup_lowest');
    Route::get('/awarsetup/create', 'PrincipalControllers\AwardSetupController@create_award_setup');
    Route::get('/awarsetup/update', 'PrincipalControllers\AwardSetupController@update_award_setup');
    Route::get('/awarsetup/delete', 'PrincipalControllers\AwardSetupController@delete_award_setup');
});
//awards setup

Route::get('teacher/get/pending', 'TeacherControllers\TeacherGradingV2@check_pending')->name('check_pending'); // 11132021 - grades

Route::middleware(['auth', 'isTeacher', 'isDefaultPass'])->group(function () {

    //grade posting
    Route::get('posting/grade/subject/submit', 'TeacherControllers\TeacherGradingV4@resubmit_student_grade');
    //grade posting

    Route::resource('/teachersdashboard', 'TeacherControllers\TeacherDashboardController');

    //teacher final grading
    Route::get('/teacher/grading/final', 'TeacherControllers\TeacherGradingV5@teacher_grading_final');
    Route::get('/teacher/grading/final/get/grades', 'TeacherControllers\TeacherGradingV5@teacher_grading_final_getgrades_type1');
    Route::get('/teacher/grading/final/store/grades', 'TeacherControllers\TeacherGradingV5@final_grading_store_grade_type1');
    Route::get('/teacher/grading/final/grade/submit', 'TeacherControllers\TeacherGradingV5@final_grading_grade_submit_type1');


    Route::get('/teacherNotification/{id}', 'TeacherControllers\TeacherDashboardController@updateNotifStatus');

    Route::get('/teacher/classattendance/full/{id}', 'TeacherControllers\ClassAttendanceController@fullattendance');
    Route::get('/classattendance', 'TeacherControllers\ClassAttendanceController@index');
    Route::get('/classattendance/viewsection_v1', 'TeacherControllers\ClassAttendanceController@viewsection_v1');
    Route::get('/classattendance/viewsection_v2', 'TeacherControllers\ClassAttendanceController@viewsection_v2');
    Route::get('/classattendance/showtable', 'TeacherControllers\ClassAttendanceController@showtable');
    Route::get('/classattendance/submit', 'TeacherControllers\ClassAttendanceController@submitattendance');
    Route::get('/classattendance/getremarks', 'TeacherControllers\ClassAttendanceController@getremarks');
    Route::get('/classattendance/updateremarks', 'TeacherControllers\ClassAttendanceController@updateremarks');
    Route::get('/classattendance/changedate', 'TeacherControllers\ClassAttendanceController@changedate');
    Route::get('/classattendance/updateattendance_v1', 'TeacherControllers\ClassAttendanceController@store');

    Route::get('/beadleAttendance', 'TeacherControllers\BeadleAttendanceController@index');
    Route::get('/beadleAttendance/getsections', 'TeacherControllers\BeadleAttendanceController@getsections');
    Route::get('/beadleAttendance/getsubjects', 'TeacherControllers\BeadleAttendanceController@getsubjects');
    Route::get('/beadleAttendance/getstudents', 'TeacherControllers\BeadleAttendanceController@getstudents');
    Route::get('/beadleAttendanceUpdate', 'TeacherControllers\BeadleAttendanceController@updateStatus');

    Route::get('/students/advisory', 'TeacherControllers\StudentController@advisory');
    Route::get('/students/advisorygetstudents', 'TeacherControllers\StudentController@advisorygetstudents');
    Route::get('/students/bysubject', 'TeacherControllers\StudentController@bysubject');
    Route::get('/students/bysubjectgetsections', 'TeacherControllers\StudentController@bysubjectgetsections');
    Route::get('/students/bysubjectgetsubjects', 'TeacherControllers\StudentController@bysubjectgetsubjects');
    Route::get('/students/bysubjectgetstudents', 'TeacherControllers\StudentController@bysubjectgetstudents');

    // Route::resource('/grades', 'TeacherControllers\GradeController');
    Route::get('/grades/index', 'TeacherControllers\GradeController@index');
    Route::get('/grades/getsections', 'TeacherControllers\GradeController@getsections');
    Route::get('/grades/getsubjects', 'TeacherControllers\GradeController@getsubjects');
    Route::get('/summergrades/{id}', 'TeacherControllers\GradeController@summer');
    Route::get('/sections/{id}/{syid}/{gradelevelid}', 'TeacherControllers\FilterController@showSubjects');
    // Route::get('/subjects/{id}/{syid}/{gradelevelid}/{sectionid}', 'TeacherControllers\FilterController@showQuarters');
    Route::get('/subjects/{id}/{syid}/{gradelevelid}/{sectionid}/{semid}', 'TeacherControllers\TeacherGradingV2@showGrades');
    // Route::get('/teacher/grade/subjects/{id}/{syid}/{gradelevelid}/{sectionid}', 'TeacherControllers\TeacherGradingV2@showGrades');

    // Route::get('/getgrades/{id}', 'TeacherControllers\FilterController@getGrades');
    Route::get('/getgrades/{id}', 'TeacherControllers\TeacherGradingV2@getGrades');
    // Route::get('/getgrades/{id}', 'TeacherControllers\TeacherGradingV2@getGradesv2');
    Route::post('/getgradesdata', 'TeacherControllers\TeacherGradingV2@getGradesData');

    Route::get('/getgradesdetailfromcloud', 'TeacherControllers\TeacherGradingV2@getgradesdetailfromcloud');
    Route::get('/checkgradestatusfromcloud', 'TeacherControllers\TeacherGradingV2@checkgradestatusfromcloud');

    //final grade
    Route::get('/getfinalgrades', 'TeacherControllers\TeacherGradingV2@for_final_grading');
    Route::get('/final/grades/save', 'TeacherControllers\TeacherGradingV2@save_final_grade');
    Route::get('/final/grades/submit', 'TeacherControllers\TeacherGradingV2@submit_final_grade');
    Route::get('/final/grades/type/update', 'TeacherControllers\TeacherGradingV2@update_grade_type');
    Route::get('/final/grades/type/check', 'TeacherControllers\TeacherGradingV2@check_grade_type');
    //final grade

    Route::get('/updatedata/{id}', 'TeacherControllers\FilterController@updateData');
    Route::get('/gradesSubmit/{id}', 'TeacherControllers\FilterController@updateGradeStatus');

    Route::get('/unpostrequest/{id}', 'TeacherControllers\GradeController@unpostRequest');
    Route::resource('/form_138', 'TeacherControllers\FormReportCardController');
    Route::get('/form_138/{action}/{id}', 'TeacherControllers\FormReportCardController@viewSchoolForm9');
    Route::get('/observedValues/{id}', 'TeacherControllers\FormReportCardController@updateObservedValues');
    // Route::get('/schoolForm_1/{id}/{sectionid}/{levelid}','TeacherControllers\FormReportCardController@viewSchoolForm1');
    Route::get('/forms/index/{formtype}', 'TeacherControllers\TeacherFormController@index');
    Route::get('/forms/form1', 'TeacherControllers\TeacherFormController@form1');
    Route::get('/forms/form2', 'TeacherControllers\TeacherFormController@form2');
    Route::get('/forms/form2enrollmentmonth', 'TeacherControllers\TeacherFormController@enrollmentmonth');
    Route::get('/forms/form2summarytable', 'TeacherControllers\TeacherFormController@form2summarytable');
    Route::get('/forms/form5', 'TeacherControllers\TeacherFormController@form5');
    Route::get('/forms/form5a', 'TeacherControllers\ReportsSHSController@form5a');
    Route::get('/forms/form5b', 'TeacherControllers\ReportsSHSController@form5b');
    Route::get('/forms/form9', 'TeacherControllers\TeacherFormController@form9');
    Route::get('/schoolForm_4/{id}', 'TeacherControllers\FormReportCardController@viewSchoolForm4');

    //grade summary
    Route::get('/teacher/get/advisory', 'TeacherControllers\TeacherGradingV4@get_advisory_sections');
    Route::get('/teacher/grade/summary', 'TeacherControllers\TeacherGradingV4@teacher_grade_summary');
    Route::get('/teacher/grade/summary/quarter', 'TeacherControllers\TeacherGradingV4@teacher_grade_summary_quarter');
    Route::get('/teacher/section/all', 'TeacherControllers\TeacherGradingV4@get_section_all');
    //grade summary

    //student ranking
    Route::get('/teacher/student/ranking', 'TeacherControllers\TeacherGradingV3@student_ranking');


    // Route::get('/schoolForm_5/{id}','TeacherControllers\FormReportCardController@viewSchoolForm5');
    // Route::get('/schoolForm_6/{id}','TeacherControllers\FormReportCardController@viewSchoolForm6');
    Route::get('/classrecord/pdf/{section}/{subject}/{quarter}', 'TeacherControllers\GradeController@toBlade');




    Route::get('/announcements', 'TeacherControllers\TeacherPortalController@announcements');
    Route::get('/post_announcements', 'TeacherControllers\TeacherPortalController@publish_announcements');

    Route::get('/mailbox/inbox/{id}', 'TeacherControllers\TeacherMailBoxController@inbox');
    Route::get('/mailbox/compose/{id}', 'TeacherControllers\TeacherMailBoxController@compose');
    Route::get('/mailbox/sent/{id}', 'TeacherControllers\TeacherMailBoxController@sent');
    Route::get('/mailbox/draft/{id}', 'TeacherControllers\TeacherMailBoxController@draft');
    Route::get('/mailbox/read/{id}', 'TeacherControllers\TeacherMailBoxController@read');
    Route::get('/mailbox/delete/{id}', 'TeacherControllers\TeacherMailBoxController@delete');
    Route::get('/mailbox/trash/{id}', 'TeacherControllers\TeacherMailBoxController@trash');
    Route::get('/mailbox/print', 'TeacherControllers\TeacherMailBoxController@print');

    Route::get('/summary', 'TeacherControllers\SummaryController@index');
    Route::get('/teacher/summaryattendancepersubject/{id}', 'TeacherControllers\SummaryAttendanceController@summaryattendancepersubject');
    Route::get('/teacher/summaryattendancepermoth/{id}', 'TeacherControllers\SummaryAttendanceController@summaryattendancepermonth');

    Route::get('/summaryattendancepersubjectgetattendance', 'TeacherControllers\SummaryAttendanceController@summaryattendancepersubjectgetattendance');
    Route::get('/summaryattendancepersubjectprint', 'TeacherControllers\SummaryAttendanceController@summaryattendancepersubjectprint');
    Route::get('/summaryofloads/{id}', 'TeacherControllers\SummaryOfLoadController@summaryofloads');

    // Route::get('/applyleave/{id}', 'TeacherControllers\TeacherRequestController@leave');
    // Route::resource('/addleave', 'TeacherControllers\TeacherLeaveController');

    Route::get('/teacherovertime/{id}', 'TeacherControllers\TeacherRequestController@overtime');
    //shsreports

    Route::get('/checkCloudGradeStatus', 'TeacherControllers\TeacherGradingV2@checkCloudGradeStatus');

    Route::get('/pendinggrades', 'TeacherControllers\TeacherGradingV2@pendinggrades');

    Route::get('/teacher/leaves/{id}', 'HRControllers\HREmployeesController@leaves');
    // Route::get('/teacher/forcepermission', 'HRControllers\HREmployeesController@forcepermission');
    // Route::get('/teacher/globalapplyleave', 'HRControllers\HREmployeesController@globalapplyleave');
    Route::get('/teacher/overtime/{id}', 'HRControllers\HREmployeesController@overtimes');

    //grading system v2
    Route::get('student/reportcard/preschool', 'TeacherControllers\TeacherGradingV4@preschool'); //grading v5
    //grading system v2

    //individual Grading
    Route::get('individual/grade/submit', 'TeacherControllers\TeacherGradingV4@individual_submission');




});

//grading system v2
Route::post('/update/gs/grades/v3', 'TeacherControllers\TeacherGradingV3@updategsgrades');
Route::post('/update/hs/grades/v3', 'TeacherControllers\TeacherGradingV3@updatehsgrades');
Route::post('/update/sh/grades/v3', 'TeacherControllers\TeacherGradingV3@updateshgrades');
Route::post('/update/ps/grades/v3', 'TeacherControllers\TeacherGradingV3@updatepsgrades');
//grading system v2

Route::get('/uploadgradedetailstocloud', 'TeacherControllers\TeacherGradingV2@uploadgradedetailstocloud');
Route::get('/uploadgradestocloud', 'TeacherControllers\TeacherGradingV2@uploadgradestocloud');
Route::get('/returngradesdetailtolocal', 'TeacherControllers\TeacherGradingV2@returngradesdetailtolocal');
Route::get('/getCloudGradeStatus', 'TeacherControllers\TeacherGradingV2@getCloudGradeStatus');

Route::get('/returnstatusfromcloud', 'TeacherControllers\TeacherGradingV2@returnstatusfromcloud');

Route::middleware(['auth', 'isRegistrar', 'isDefaultPass'])->group(function () {

    //grade summary
    Route::get('/registrar/grade/summary', 'TeacherControllers\TeacherGradingV4@registrar_grade_summary');
    Route::view('/registrar/student/awards', 'principalsportal.pages.awards.academicexcellenceaward');
    //grade summary

    //registrar reports


    Route::resource('/registrardashboard', 'RegistrarControllers\RegistrarDashboardController');
    Route::get('/entranceexamquestions', 'RegistrarControllers\PreRegistrationController@entranceexamquestions');


    Route::get('/registrar/questions/{gradelevel}', 'RegistrarControllers\PreRegistrationController@getquestions');


    Route::get('/addquestions', 'RegistrarControllers\PreRegistrationController@addquestions');
    Route::get('/editquestion', 'RegistrarControllers\PreRegistrationController@editquestion');
    Route::get('/deletequestion', 'RegistrarControllers\PreRegistrationController@deletequestion');
    Route::get('/entranceexamresults/{action}', 'RegistrarControllers\PreRegistrationController@entranceexamresults');



    Route::get('/registrarschoolforms/{id}', 'RegistrarControllers\ReportsController@schoolhead');
    Route::get('/reports/{id}', 'RegistrarControllers\ReportsController@reports');
    Route::get('/reports_studentmasterlist/{id}/{syid}/{sectionid}', 'RegistrarControllers\ReportsController@reportstudentmasterlist');
    Route::get('/reports_schoolform4/{id}', 'RegistrarControllers\ReportsController@reportschoolform_4');
    Route::get('/reports_schoolform5/{id}/{selectedform}/{syid}/{sectionid}/{gradelevelid}', 'RegistrarControllers\ReportsController@reportschoolform_5');
    Route::get('/reports_schoolform4/{id}', 'RegistrarControllers\ReportsController@reportschoolform_4');
    Route::get('/reports_schoolform6/{id}', 'RegistrarControllers\ReportsController@reportschoolform_6');
    Route::get('/reports_schoolform9/{id}/{syid}/{sectionid}/{gradelevelid}', 'RegistrarControllers\ReportsController@reports_schoolform9');
    Route::get('/reports_schoolform9/{id}', 'RegistrarControllers\FormReportsController@reportsschoolform9');

    // Route::get('/prinsf9print/{id}','PrincipalControllers\DynamicPDFController@sf9pdf');

    Route::get('/seniorhigh/{id}', 'RegistrarControllers\SeniorHighController@form10');
    Route::get('/senior/addform10', 'RegistrarControllers\SeniorHighController@addform10');
    Route::get('/senior/editform10/{id}', 'RegistrarControllers\SeniorHighController@editform10');

    Route::get('/juniorhigh/{id}', 'RegistrarControllers\JuniorHighController@form10');
    Route::get('/junior/addform10', 'RegistrarControllers\JuniorHighController@addform10');
    Route::get('/junior/editform10/{id}', 'RegistrarControllers\JuniorHighController@editform10');
    Route::get('/junior/deleteform10', 'RegistrarControllers\JuniorHighController@deleteform10');

    Route::get('/elementary/{id}', 'RegistrarControllers\ElementaryController@form10');
    Route::get('/elem/addform10', 'RegistrarControllers\ElementaryController@addform10');
    Route::get('/elem/editform10/{id}', 'RegistrarControllers\ElementaryController@editform10');
    Route::get('/elem/deleteform10', 'RegistrarControllers\ElementaryController@deleteform10');

    Route::get('/schoolforms/college/index', 'RegistrarControllers\CollegeFormsController@index');
    Route::get('/schoolforms/college/permanentrecordindex', 'RegistrarControllers\CollegeFormsController@permanentrecordindex');
    Route::get('/schoolforms/college/permanentrecordfilter', 'RegistrarControllers\CollegeFormsController@permanentrecordfilter');
    Route::get('/schoolforms/college/permanentrecordgetrecord', 'RegistrarControllers\CollegeFormsController@permanentrecordgetrecord');


    Route::get('/reportssummariesallstudentsnew/{id}', 'RegistrarControllers\SummaryStudentV2Controller@reportssummariesallstudentsnew');

    Route::get('/registrar/oe', 'RegistrarControllers\SummaryController@oe');

    Route::get('/reportssummariesspecialclass/{id}', 'RegistrarControllers\SummarySpecialClassController@reportssummariesspecialclass');

    Route::get('/preview_student_masterlist/{id}/{sectionid}', 'RegistrarControllers\FormReportsController@previewStudentMasterlist');
    Route::get('/show_enrollees/{action}', 'RegistrarControllers\FormReportsController@showEnrollees');
    Route::get('/registrargoodmoralcertificate', 'RegistrarControllers\RegistrarGoodMoralCertController@goodmoralcertificate');
    // Route::get('/editForm10/{action}/{student_id}/{header_id}','RegistrarControllers\FormReportsController@editForm10');
    Route::get('/registrarNotification/{id}', 'RegistrarControllers\RegistrarDashboardController@updateNotifStatus');



    //enrollment
    Route::get('/enrollment', 'enrollment\EnrollmentController@registrarIndex')->name('registrarIndex');
    Route::get('/registrar/studentinfo', 'enrollment\EnrollmentController@studentinfo')->name('studentinfo');
    Route::get('/registrar/studentinfo/search', 'enrollment\EnrollmentController@studentsearch')->name('studentsearch');
    Route::get('/registrar/studentinfo/edit/{id}', 'enrollment\EnrollmentController@studentedit')->name('studentedit');
    Route::get('/registrar/studentinfo/update', 'enrollment\EnrollmentController@studentupdate')->name('studentupdate');
    Route::get('/registrar/studentinfo/create', 'enrollment\EnrollmentController@studentcreate')->name('studentcreate');
    Route::get('/registrar/studentinfo/insert', 'enrollment\EnrollmentController@studentinsert')->name('studentinsert');
    //Route::get('/registrar/studentinfo/print', 'enrollment\EnrollmentController@studentprint')->name('studentprint');
    Route::get('/registrar/registered', 'enrollment\EnrollmentController@registered')->name('registered');
    Route::get('/registrar/registered/search', 'enrollment\EnrollmentController@searchRegStud')->name('searchRegStud');
    Route::get('/registrar/registered/deletestud', 'enrollment\EnrollmentController@deleteStud')->name('deletestud');
    Route::get('/registrar/enrolled', 'enrollment\EnrollmentController@enrolled')->name('enrolled');
    Route::get('/registrar/enrolled/search', 'enrollment\EnrollmentController@searchEnrolledStud')->name('searchEnrolledStud');
    Route::get('/registrar/preenrolled', 'enrollment\EnrollmentController@preenrolled')->name('preenrolled');
    Route::get('/registrar/searchPreEnrolledStud', 'enrollment\EnrollmentController@searchPreEnrolledStud')->name('searchPreEnrolledStud');

    Route::get('/registrar/preenroll/getstudpaid', 'enrollment\EnrollmentController@getstudpaid')->name('getstudpaid');

    Route::get('/admission', 'enrollment\EnrollmentController@admission')->name('admission');
    Route::get('/admission/search', 'enrollment\EnrollmentController@searchPreReg')->name('searchPreReg');
    Route::get('/admission/edit/{code}', 'enrollment\EnrollmentController@admissionedit')->name('admissionedit');
    Route::get('/admission/reg', 'enrollment\EnrollmentController@admissionregister')->name('admissionregister');
    Route::get('/admission/preregdel', 'enrollment\EnrollmentController@preregdel')->name('preregdel');
    Route::get('/admission/preregreq', 'enrollment\EnrollmentController@preregreq')->name('preregreq');

    Route::get('/admission/sync', 'enrollment\EnrollmentController@sync')->name('sync');

    Route::get('/admission/enroll/getinfo', 'enrollment\EnrollmentController@enrollgetinfo')->name('enrollgetinfo');
    Route::get('/admission/enroll/enrollstud', 'enrollment\EnrollmentController@enrollstud')->name('enrollstud');
    Route::get('/admission/enroll/viewEnrollment', 'enrollment\EnrollmentController@viewEnrollment')->name('viewEnrollment');
    Route::get('/enrollment/studdata', 'enrollment\EnrollmentController@studdata')->name('studdata');

    Route::get('/enrollment/religion/add', 'enrollment\EnrollmentController@addReligion')->name('addReligion');
    Route::get('/enrollment/mt/add', 'enrollment\EnrollmentController@addMT')->name('addMT');
    Route::get('/enrollment/eg/add', 'enrollment\EnrollmentController@addEG')->name('addEG');
    Route::get('/enrollment/saveEnroll', 'enrollment\EnrollmentController@saveEnroll')->name('saveEnroll');

    Route::get('/enrollment/spclass', 'enrollment\EnrollmentController@spclass')->name('spclass');
    Route::get('/enrollment/spsearch', 'enrollment\EnrollmentController@spsearch')->name('spsearch');
    Route::get('/enrollment/LoadLists', 'enrollment\EnrollmentController@LoadLists')->name('LoadLists');
    Route::get('/enrollment/loadStud', 'enrollment\EnrollmentController@loadStud')->name('loadStud');
    Route::get('/enrollment/loadDetail', 'enrollment\EnrollmentController@loadDetail')->name('loadDetail');
    Route::get('/enrollment/appendDetail', 'enrollment\EnrollmentController@appendDetail')->name('appendDetail');
    Route::get('/enrollment/savespClass', 'enrollment\EnrollmentController@savespClass')->name('savespClass');
    Route::get('/enrollment/editspClass', 'enrollment\EnrollmentController@editspClass')->name('editspClass');
    Route::get('/enrollment/editDetail', 'enrollment\EnrollmentController@editDetail')->name('editDetail');
    Route::get('/enrollment/updateDetail', 'enrollment\EnrollmentController@updateDetail')->name('updateDetail');
    Route::get('/enrollment/deleteDetail', 'enrollment\EnrollmentController@deleteDetail')->name('deleteDetail');
    Route::get('/enrollment/loadDtail', 'enrollment\EnrollmentController@loadDtail')->name('loadDtail');
    Route::get('/enrollment/getDP', 'enrollment\EnrollmentController@getDP')->name('getDP');

    Route::get('/enrollment/requirement/view', 'enrollment\EnrollmentController@viewreq')->name('viewreq');

    Route::get('/techvoc/courses/tvsearch', 'enrollment\EnrollmentController@tvsearch')->name('tvsearch');
    Route::get('/techvoc/courses', 'enrollment\EnrollmentController@tvcourses')->name('tvcourses');
    Route::get('/techvoc/courses/saveTVCourse', 'enrollment\EnrollmentController@saveTVCourse')->name('saveTVCourse');
    Route::get('/techvoc/courses/editTVCourse', 'enrollment\EnrollmentController@editTVCourse')->name('editTVCourse');
    Route::get('/techvoc/courses/updateTVCourse', 'enrollment\EnrollmentController@updateTVCourse')->name('updateTVCourse');
    Route::get('/techvoc/courses/deleteTVCourse', 'enrollment\EnrollmentController@deleteTVCourse')->name('deleteTVCourse');

    Route::get('/techvoc/batch', 'enrollment\EnrollmentsController@tvbatch')->name('tvbatch');
    Route::get('/techvoc/batch/loadbatch', 'enrollment\EnrollmentsController@loadbatch')->name('loadbatch');
    Route::get('/techvoc/batch/createbatch', 'enrollment\EnrollmentsController@createbatch')->name('createbatch');
    Route::get('/techvoc/batch/activatebatch', 'enrollment\EnrollmentsController@activatebatch')->name('activatebatch');

    Route::get('/techvoc/enrollment/tvloadstudinfo', 'enrollment\EnrollmentController@tvloadstudinfo')->name('tvloadstudinfo');
    Route::get('/techvoc/enrollment/tvcreatestudinfo', 'enrollment\EnrollmentsController@tvcreatestudinfo')->name('tvcreatestudinfo');
    Route::get('/techvoc/enrollment/tvenrollstudent', 'enrollment\EnrollmentsController@tvenrollstudent')->name('tvenrollstudent');

    //enrollment

    //Senior High

    Route::get('/shsetup/track', 'enrollment\EnrollmentController@viewtrack')->name('viewtrack');
    Route::get('/shsetup/searchtrack', 'enrollment\EnrollmentController@searchtrack')->name('searchtrack');
    Route::get('/shsetup/savetrack', 'enrollment\EnrollmentController@savetrack')->name('savetrack');
    Route::get('/shsetup/edittrack', 'enrollment\EnrollmentController@edittrack')->name('edittrack');
    Route::get('/shsetup/updatetrack', 'enrollment\EnrollmentController@updatetrack')->name('updatetrack');

    Route::get('/shsetup/strand', 'enrollment\EnrollmentController@viewstrand')->name('viewstrand');
    Route::get('/shsetup/searchstrand', 'enrollment\EnrollmentController@searchstrand')->name('searchstrand');
    Route::get('/shsetup/loadtrack', 'enrollment\EnrollmentController@loadtrack')->name('loadtrack');
    Route::get('/shsetup/searchstrand', 'enrollment\EnrollmentController@searchstrand')->name('searchstrand');
    Route::get('/shsetup/insertstrand', 'enrollment\EnrollmentController@insertstrand')->name('insertstrand');
    Route::get('/shsetup/loadstrand', 'enrollment\EnrollmentController@loadstrand')->name('loadstrand');
    Route::get('/shsetup/loadblock', 'enrollment\EnrollmentController@loadblock')->name('loadblock');
    Route::get('/shsetup/editstrand', 'enrollment\EnrollmentController@editstrand')->name('editstrand');
    //Senior High

    //College
    Route::get('/college/adddrop/index', 'CollegeControllers\CollegeAddDropController@index')->name('adddropindex');
    Route::get('/college/adddrop/selectcourse', 'CollegeControllers\CollegeAddDropController@selectcourse')->name('selectcourse');
    Route::get('/college/adddrop/viewstudents', 'CollegeControllers\CollegeAddDropController@viewstudents')->name('viewstudents');
    Route::get('/college/adddrop/viewschedule', 'CollegeControllers\CollegeAddDropController@viewschedule')->name('viewschedule');
    Route::get('/college/adddrop/dropsubject', 'CollegeControllers\CollegeAddDropController@dropsubject')->name('dropsubject');
    Route::get('/college/adddrop/getsubjects', 'CollegeControllers\CollegeAddDropController@getsubjects')->name('getsubjects');
    Route::get('/college/adddrop/getavailablescheds', 'CollegeControllers\CollegeAddDropController@getavailablescheds')->name('getavailablescheds');
    Route::get('/college/adddrop/addschedule', 'CollegeControllers\CollegeAddDropController@addschedule')->name('addschedule');
    //College

    //Tech Voc
    Route::get('/techvoc/tvstudinfo', 'enrollment\EnrollmentsController@tvstudinfo')->name('tvstudinfo');
    Route::get('/techvoc/tvstudsearch', 'enrollment\EnrollmentsController@tvstudsearch')->name('tvstudsearch');
    Route::get('/techvoc/tvgetbatch', 'enrollment\EnrollmentsController@tvgetbatch')->name('tvgetbatch');
    Route::get('/techvoc/tvgetbatches', 'enrollment\EnrollmentsController@tvgetbatches')->name('tvgetbatches');
    Route::get('/techvoc/tvupdateenrolmentinfo', 'enrollment\EnrollmentsController@tvupdateenrolmentinfo')->name('tvupdateenrolmentinfo');
    Route::get('/techvoc/tvdeletebatch', 'enrollment\EnrollmentsController@tvdeletebatch')->name('tvdeletebatch');
    Route::get('/techvoc/tvexport', 'enrollment\EnrollmentsController@tvexport')->name('tvexport');
    Route::get('/techvoc/tvgetstudinfo', 'enrollment\EnrollmentsController@tvgetstudinfo')->name('tvgetstudinfo');
    Route::get('/techvoc/tvgetstudbybatch', 'enrollment\EnrollmentsController@tvgetstudbybatch')->name('tvgetstudbybatch');
    //Tech Voc

    Route::get('/registrar/fillup/students/sf10', 'RegistrarControllers\RegistrarFormsController@fillupsf10');
    Route::get('/registrar/fillup/get/sf10/{student}/{levelid}', 'RegistrarControllers\RegistrarFormsController@getStudentsf10');
    Route::get('/registrar/insert/students/sf10', 'RegistrarControllers\RegistrarFormsController@insertsf10grade');



    Route::get('/registrar/studentrequirements', 'RegistrarControllers\StudentRequirementsController@studentrequirementsindex')->name('studentrequirementsindex');
    Route::get('/registrar/studentrequirementsgetinfo', 'RegistrarControllers\StudentRequirementsController@studentrequirementsgetinfo')->name('studentrequirementsgetinfo');

    //Forms

    // Route::get('/forms/form1','TeacherControllers\TeacherFormController@form1');
    Route::get('/registrar/forms/schoolform1/export', 'TeacherControllers\TeacherFormController@form1');
    // Route::get('/registrar/forms/schoolform1/export', 'RegistrarControllers\ReportsController@exportsf1');

    Route::get('/registrar/leaves/{id}', 'HRControllers\HREmployeesController@leaves');

    Route::get('/registrar/overtime/{id}', 'HRControllers\HREmployeesController@overtimes');
    Route::get('/registrar/summaries/alphaloading/index', 'RegistrarControllers\SummaryController@alphaloadingindex');
    Route::get('/registrar/summaries/alphaloading/getsection', 'RegistrarControllers\SummaryController@alphaloadinggetsection');
    Route::get('/registrar/summaries/alphaloading/filter', 'RegistrarControllers\SummaryController@alphaloadingfilter');


    //Scholarships
    Route::get('/registrar/scholars/index', 'RegistrarControllers\ScholarshipController@index')->name('scholarsindex');
    Route::get('/registrar/scholars/programadd', 'RegistrarControllers\ScholarshipController@addprogram')->name('scholarprogramadd');
    Route::get('/registrar/scholars/programname', 'RegistrarControllers\ScholarshipController@programname')->name('scholarprogramname');
    Route::get('/registrar/scholars/programedit', 'RegistrarControllers\ScholarshipController@programedit')->name('scholarprogramedit');
    Route::get('/registrar/scholars/programdelete', 'RegistrarControllers\ScholarshipController@programdelete')->name('scholarprogramdelete');
    Route::get('/registrar/scholars/programstudents', 'RegistrarControllers\ScholarshipController@programstudents')->name('scholarprogramstudents');
    Route::get('/registrar/scholars/programselect', 'RegistrarControllers\ScholarshipController@programselect')->name('scholarprogramselect');
    Route::get('/registrar/scholars/programsubmitselect', 'RegistrarControllers\ScholarshipController@programsubmitselect')->name('scholarprogramsubmitselect');


    Route::get('/registrar/college/view/grades', 'RegistrarControllers\RegistrarFunctionController@view_student_grades');
    Route::get('/registrar/college/enrollment/record', 'RegistrarControllers\RegistrarFunctionController@college_enrollment');
    // Route::get('/registrar/college/enrollment/record/subject', 'RegistrarControllers\RegistrarFunctionController@subject_enrollment');
    Route::get('/registrar/college/grades', 'RegistrarControllers\RegistrarFunctionController@student_grade');

    //college schedule
    Route::get('/registrar/college/student/loading', 'RegistrarControllers\RegistrarFunctionController@student_loading');
    Route::get('/registrar/college/schedule/blade', 'RegistrarControllers\RegistrarFunctionController@college_schedule');
    Route::get('/registrar/college/students', 'RegistrarControllers\RegistrarFunctionController@college_students');
    Route::get('/registrar/college/sections', 'RegistrarControllers\RegistrarFunctionController@college_sections');
    Route::get('/registrar/college/curriculumprospectus', 'RegistrarControllers\RegistrarFunctionController@curriculum_propectus');
    Route::get('/registrar/college/enrollment/record', 'RegistrarControllers\RegistrarFunctionController@enrollment_record');
    Route::get('/registrar/college/enrollment/record/subject', 'RegistrarControllers\RegistrarFunctionController@subject_enrollment_records');
    Route::get('/registrar/college/section/schedule', 'RegistrarControllers\RegistrarFunctionController@section_schedule');
    Route::get('/registrar/college/add/studentsched', 'RegistrarControllers\RegistrarFunctionController@add_student_sched');
    Route::get('/registrar/college/remove/studentsched', 'RegistrarControllers\RegistrarFunctionController@remove_student_sched');
    Route::get('/registrar/college/grades', 'RegistrarControllers\RegistrarFunctionController@student_grade');
    Route::get('/registrar/college/set/student/section', 'RegistrarControllers\RegistrarFunctionController@set_section');
    Route::get('/registrar/college/courses', 'RegistrarControllers\RegistrarFunctionController@college_courses');
    Route::get('/registrar/college/curriculum', 'RegistrarControllers\RegistrarFunctionController@college_curriculum');
    Route::get('/registrar/shift/student/course', 'RegistrarControllers\RegistrarFunctionController@shift_student_course');
    Route::get('/registrar/college/student/pre-enrolled', 'RegistrarControllers\RegistrarFunctionController@student_preenrolled_college'); //11132020
    // Route::get('/registrar/college/sectiondetails', 'RegistrarControllers\RegistrarFunctionController@college_enrollment');
    Route::get('registrar/section/schedulecoding/list', 'CPControllers\CPControllerV2@section_schedulecoding_list');

    Route::get('registrar/schedule/coding/list', 'CPControllers\CPControllerV2@schedule_coding_list');

});

Route::get('/registrar/college/student/loading/view', 'RegistrarControllers\RegistrarFunctionController@student_loading_view');

Route::middleware(['auth', 'isAdmission', 'isDefaultPass'])->group(function () {

    Route::get('/enrollment', 'enrollment\EnrollmentController@registrarIndex')->name('registrarIndex');
    Route::resource('/registrardashboard', 'RegistrarControllers\RegistrarDashboardController');
    Route::get('/admission', 'enrollment\EnrollmentController@admission')->name('admission');
    Route::get('/admission/search', 'enrollment\EnrollmentController@searchPreReg')->name('searchPreReg');
    Route::get('/admission/edit/{code}', 'enrollment\EnrollmentController@admissionedit')->name('admissionedit');
    Route::get('/admission/reg', 'enrollment\EnrollmentController@admissionregister')->name('admissionregister');
    Route::get('/admission/preregdel', 'enrollment\EnrollmentController@preregdel')->name('preregdel');

    Route::get('/registrar/studentinfo', 'enrollment\EnrollmentController@studentinfo')->name('studentinfo');
    Route::get('/registrar/studentinfo/search', 'enrollment\EnrollmentController@studentsearch')->name('studentsearch');
    Route::get('/registrar/studentinfo/edit/{id}', 'enrollment\EnrollmentController@studentedit')->name('studentedit');
    Route::get('/registrar/studentinfo/update', 'enrollment\EnrollmentController@studentupdate')->name('studentupdate');
    Route::get('/registrar/studentinfo/create', 'enrollment\EnrollmentController@studentcreate')->name('studentcreate');
    Route::get('/registrar/studentinfo/insert', 'enrollment\EnrollmentController@studentinsert')->name('studentinsert');
    //Route::get('/registrar/studentinfo/print', 'enrollment\EnrollmentController@studentprint')->name('studentprint');
    Route::get('/registrar/studentinfo/checkEnrolled', 'enrollment\EnrollmentController@checkEnrolled')->name('checkEnrolled');





});

Route::middleware(['auth', 'isFinance'])->group(function () {

    // Route::get('/finance/dashboard','FinanceDashboardController@dashboard');
    // Route::get('/finance/index','FinanceControllers\FinanceController@index')->name('financeindex');
    Route::get('/finance/itemclassification', 'FinanceControllers\FinanceController@itemclassification')->name('itemclassification');
    Route::get('/finance/search_classification', 'FinanceControllers\FinanceController@search_classification')->name('search_classification');
    Route::get('/finance/loadGL', 'FinanceControllers\FinanceController@loadGL')->name('loadGL');
    Route::get('/finance/saveClass', 'FinanceControllers\FinanceController@saveClass')->name('saveClass');
    Route::get('/finance/viewClass', 'FinanceControllers\FinanceController@viewClass')->name('viewClass');
    Route::get('/finance/updateClass', 'FinanceControllers\FinanceController@updateClass')->name('updateClass');
    Route::get('/finance/delClass', 'FinanceControllers\FinanceController@delClass')->name('delClass');

    Route::get('/finance/payitems', 'FinanceControllers\FinanceController@payitems')->name('payitems');
    Route::get('/finance/payitemsearch', 'FinanceControllers\FinanceController@payitemsearch')->name('payitemsearch');
    Route::get('/finance/loadNEW', 'FinanceControllers\FinanceController@loadNEW')->name('loadNEW');
    Route::get('/finance/saveItem', 'FinanceControllers\FinanceController@saveItem')->name('saveItem');
    Route::get('/finance/loadEDIT', 'FinanceControllers\FinanceController@loadEDIT')->name('loadEDIT');
    Route::get('/finance/updateItem', 'FinanceControllers\FinanceController@updateItem')->name('updateItem');
    Route::get('/finance/deleteItem', 'FinanceControllers\FinanceController@deleteItem')->name('deleteItem');
    Route::get('/finance/item_edit', 'FinanceControllers\FinanceController@item_edit')->name('item_edit');
    Route::get('/finance/item_update', 'FinanceControllers\FinanceController@item_update')->name('item_update');

    Route::get('/finance/modeofpayment', 'FinanceControllers\FinanceController@modeofpayment')->name('modeofpayment');
    Route::get('/finance/searchMOP', 'FinanceControllers\FinanceController@searchMOP')->name('searchMOP');
    Route::get('/finance/mopnew', 'FinanceControllers\FinanceController@mopnew')->name('mopnew');
    Route::get('/finance/mopsave', 'FinanceControllers\FinanceController@mopsave')->name('mopsave');
    Route::get('/finance/mopedit/{id}', 'FinanceControllers\FinanceController@mopedit')->name('mopedit');
    Route::get('/finance/mopdetailSave', 'FinanceControllers\FinanceController@mopdetailSave')->name('mopdetailSave');
    Route::get('/finance/mopdetailDel', 'FinanceControllers\FinanceController@mopdetailDel')->name('mopdetailDel');
    Route::get('/finance/mopdetailAdd', 'FinanceControllers\FinanceController@mopdetailAdd')->name('mopdetailAdd');
    Route::get('/finance/mopupdate', 'FinanceControllers\FinanceController@mopupdate')->name('mopupdate');
    Route::get('/finance/mopdel', 'FinanceControllers\FinanceController@mopdel')->name('mopdel');
    Route::get('/finance/dueEdit', 'FinanceControllers\FinanceController@dueEdit')->name('dueEdit');
    Route::get('/finance/percentEdit', 'FinanceControllers\FinanceController@percentEdit')->name('percentEdit');

    Route::get('/finance/fees', 'FinanceControllers\FinanceController@fees')->name('fees');
    Route::get('/finance/searchfees', 'FinanceControllers\FinanceController@searchfees')->name('searchfees');
    Route::get('/finance/feesnew', 'FinanceControllers\FinanceController@feesnew')->name('feesnew');
    Route::get('/finance/feesdelete', 'FinanceControllers\FinanceController@feesdelete')->name('feesdelete');
    Route::get('/finance/loadClass', 'FinanceControllers\FinanceController@loadClass')->name('loadClass');
    Route::get('/finance/savePayClass', 'FinanceControllers\FinanceController@savePayClass')->name('savePayClass');
    Route::get('/finance/loadItems', 'FinanceControllers\FinanceController@loadItems')->name('loadItems');
    Route::get('/finance/getItemInfo', 'FinanceControllers\FinanceController@getItemInfo')->name('getItemInfo');
    Route::get('/finance/saveFCItem', 'FinanceControllers\FinanceController@saveFCItem')->name('saveFCItem');
    Route::get('/finance/getFCItem', 'FinanceControllers\FinanceController@getFCItem')->name('getFCItem');
    Route::get('/finance/saveFC', 'FinanceControllers\FinanceController@saveFC')->name('saveFC');
    Route::get('/finance/feesedit/{id}', 'FinanceControllers\FinanceController@feesedit')->name('feesedit');
    Route::get('/finance/editFC', 'FinanceControllers\FinanceController@editFC')->name('editFC');
    Route::get('/finance/updateFCpayclass', 'FinanceControllers\FinanceController@updateFCpayclass')->name('updateFCpayclass');
    Route::get('/finance/deleteFCpayclass', 'FinanceControllers\FinanceController@deleteFCpayclass')->name('deleteFCpayclass');
    Route::get('/finance/updateFCItem', 'FinanceControllers\FinanceController@updateFCItem')->name('updateFCItem');
    Route::get('/finance/deleteFCItem', 'FinanceControllers\FinanceController@deleteFCItem')->name('deleteFCItem');
    Route::get('/finance/duplicateFC', 'FinanceControllers\FinanceController@duplicateFC')->name('duplicateFC');
    Route::get('/finance/validatedupSY', 'FinanceControllers\FinanceController@validatedupSY')->name('validatedupSY');
    Route::get('/finance/duplicateAll', 'FinanceControllers\FinanceController@duplicateAll')->name('duplicateAll');
    Route::get('/finance/viewpaysched', 'FinanceControllers\FinanceController@viewpaysched')->name('viewpaysched');

    Route::get('/finance/loadreceivables', 'FinanceControllers\FinanceController@loadreceivables')->name('loadreceivables');
    Route::get('/finance/appendFCNewItems', 'FinanceControllers\FinanceController@appendFCNewItems')->name('appendFCNewItems');
    Route::get('/finance/appendcolFC', 'FinanceControllers\FinanceController@appendcolFC')->name('appendcolFC');
    Route::get('/finance/appendcolFCdetail', 'FinanceControllers\FinanceController@appendcolFCdetail')->name('appendcolFCdetail');
    Route::get('/finance/editcolFCdetail', 'FinanceControllers\FinanceController@editcolFCdetail')->name('editcolFCdetail');
    Route::get('/finance/editcolFCitem', 'FinanceControllers\FinanceController@editcolFCitem')->name('editcolFCitem');
    Route::get('/finance/updatecolFCitem', 'FinanceControllers\FinanceController@updatecolFCitem')->name('updatecolFCitem');

    Route::get('/finance/deletecolFCdetail', 'FinanceControllers\FinanceController@deletecolFCdetail')->name('deletecolFCdetail');
    Route::get('/finance/deletecolFCitem', 'FinanceControllers\FinanceController@deletecolFCitem')->name('deletecolFCitem');

    Route::get('/finance/FCHeadInfo', 'FinanceControllers\FinanceController@FCHeadInfo')->name('FCHeadInfo');
    Route::get('/finance/FCClasList', 'FinanceControllers\FinanceController@FCClasList')->name('FCClasList');
    Route::get('/finance/FCItemList', 'FinanceControllers\FinanceController@FCItemList')->name('FCItemList');

    Route::get('/finance/fordev', 'FinanceControllers\FinanceController@fordev')->name('fordev');


    Route::get('/finance/dpsetup', 'FinanceControllers\FinanceController@dpsetup')->name('dpsetup');
    Route::get('/finance/loaddpitems', 'FinanceControllers\FinanceController@loaddpitems')->name('loaddpitems');
    Route::get('/finance/loaddp', 'FinanceControllers\FinanceController@loaddp')->name('loaddp');
    Route::get('/finance/loaddpclass', 'FinanceControllers\FinanceController@loaddpclass')->name('loaddpclass');
    Route::get('/finance/saveDPItem', 'FinanceControllers\FinanceController@saveDPItem')->name('saveDPItem');
    Route::get('/finance/removeDPItem', 'FinanceControllers\FinanceController@removeDPItem')->name('removeDPItem');
    Route::get('/finance/saveNewDPItem', 'FinanceControllers\FinanceController@saveNewDPItem')->name('saveNewDPItem');

    Route::get('/finance/studledger', 'FinanceControllers\FinanceController@studledger')->name('studledger');
    Route::get('/finance/loadSY', 'FinanceControllers\FinanceController@loadSY')->name('loadSY');
    Route::get('/finance/searchStud', 'FinanceControllers\FinanceController@searchStud')->name('searchStud');
    Route::get('/finance/getStudLedger', 'FinanceControllers\FinanceController@getStudLedger')->name('getStudLedger');
    Route::get('/finance/pdfledger', 'FinanceControllers\FinanceController@printledger')->name('printledger');

    Route::get('/finance/loadfees', 'FinanceControllers\FinanceController@loadfees')->name('loadfees');

    // Route::get('/finance/discounts','FinanceControllers\FinanceController@discounts')->name('discounts');
    Route::get('/finance/discnew', 'FinanceControllers\FinanceController@discnew')->name('discnew');
    Route::get('/finance/discountSearch', 'FinanceControllers\FinanceController@discountSearch')->name('discountSearch');
    Route::get('/finance/discountedit', 'FinanceControllers\FinanceController@discountedit')->name('discountedit');
    Route::get('/finance/discountupdate', 'FinanceControllers\FinanceController@discountupdate')->name('discountupdate');
    Route::get('/finance/discountdelete', 'FinanceControllers\FinanceController@discountdelete')->name('discountdelete');
    Route::get('/finance/loadDiscClass', 'FinanceControllers\FinanceController@loadDiscClass')->name('loadDiscClass');
    Route::get('/finance/saveStudDiscount', 'FinanceControllers\FinanceController@saveStudDiscount')->name('saveStudDiscount');
    Route::get('/finance/searchStudDiscount', 'FinanceControllers\FinanceController@searchStudDiscount')->name('searchStudDiscount');
    Route::get('/finance/postStudDiscount', 'FinanceControllers\FinanceController@postStudDiscount')->name('postStudDiscount');
    Route::get('/finance/discountamount', 'FinanceControllers\FinanceController@discountamount')->name('discountamount');
    Route::get('/finance/delstuddiscount', 'FinanceControllers\FinanceController@delstuddiscount')->name('delstuddiscount');
    Route::get('/finance/loaddiscount', 'FinanceControllers\FinanceController@loaddiscount')->name('loaddiscount');

    Route::get('/finance/tuitionentry', 'FinanceControllers\FinanceController@tuitionentry')->name('tuitionentry');
    Route::get('/finance/loadTsetup', 'FinanceControllers\FinanceController@loadTsetup')->name('loadTsetup');
    Route::get('/finance/loadTstudent', 'FinanceControllers\FinanceController@loadTstudent')->name('loadTstudent');
    Route::get('/finance/procTuition', 'FinanceControllers\FinanceController@procTuition')->name('procTuition');

    Route::get('/finance/balforward', 'FinanceControllers\FinanceController@balforward')->name('balforward');
    Route::get('/finance/studbal', 'FinanceControllers\FinanceController@studbal')->name('studbal');
    Route::get('/finance/loadstud', 'FinanceControllers\FinanceController@loadstud')->name('loadstud');
    Route::get('/finance/loadStudents', 'FinanceControllers\FinanceController@loadStudents')->name('loadStudents');
    Route::get('/finance/savefsetup', 'FinanceControllers\FinanceController@savefsetup')->name('savefsetup');
    Route::get('/finance/loadbalfwdsetup', 'FinanceControllers\FinanceController@loadbalfwdsetup')->name('loadbalfwdsetup');
    Route::get('/finance/checkbalfwdsetup', 'FinanceControllers\FinanceController@checkbalfwdsetup')->name('checkbalfwdsetup');
    Route::get('/finance/fwdbal', 'FinanceControllers\FinanceController@fwdbal')->name('fwdbal');
    Route::get('/finance/fwdVledger', 'FinanceControllers\FinanceController@fwdVledger')->name('fwdVledger');
    Route::get('/finance/checkExist', 'FinanceControllers\FinanceController@checkExist')->name('checkExist');
    Route::get('/finance/listfwdbal', 'FinanceControllers\FinanceController@listfwdbal')->name('listfwdbal');
    Route::get('/finance/fwdbalpdf', 'FinanceControllers\FinanceController@fwdbalpdf')->name('fwdbalpdf');

    // Route::get('/finance/exampermit','FinanceControllers\FinanceController@exampermit')->name('exampermit');
    Route::get('/finance/permit_studfilter', 'FinanceControllers\FinanceController@permit_studfilter')->name('permit_studfilter');
    Route::get('/finance/permit_allowtoexam', 'FinanceControllers\FinanceController@permit_allowtoexam')->name('permit_allowtoexam');
    Route::get('/finance/permit_loadinfo', 'FinanceControllers\FinanceController@permit_loadinfo')->name('permit_loadinfo');
    Route::get('/finance/permit_loadsetup', 'FinanceControllers\FinanceController@permit_loadsetup')->name('permit_loadsetup');
    Route::get('/finance/permit_activequarter', 'FinanceControllers\FinanceController@permit_activequarter')->name('permit_activequarter');


    Route::get('/finance/salaryrateelevation/{id}', 'FinanceControllers\FinanceController@salaryrateelevation')->name('salaryrateelevation', '{id}');

    Route::get('/finance/expenses', 'FinanceControllers\ExpensesController@expenses')->name('expenses');
    Route::get('/finance/searchexpenses', 'FinanceControllers\ExpensesController@searchexpenses')->name('searchexpenses');
    Route::get('/finance/saveexpense', 'FinanceControllers\ExpensesController@saveexpense')->name('saveexpense');
    Route::get('/finance/saveexpensedetail', 'FinanceControllers\ExpensesController@saveexpensedetail')->name('saveexpensedetail');
    Route::get('/finance/loadexpensedetail', 'FinanceControllers\ExpensesController@loadexpensedetail')->name('loadexpensedetail');
    Route::get('/finance/loadexpense', 'FinanceControllers\ExpensesController@loadexpense')->name('loadexpense');
    Route::get('/finance/loadexpenseitems', 'FinanceControllers\ExpensesController@loadexpenseitems')->name('loadexpenseitems');
    Route::get('/finance/saveNewItem', 'FinanceControllers\ExpensesController@saveNewItem')->name('saveNewItem');
    Route::get('/finance/approveexpense', 'FinanceControllers\ExpensesController@approveexpense')->name('approveexpense');
    Route::get('/finance/disapproveexpense', 'FinanceControllers\ExpensesController@disapproveexpense')->name('disapproveexpense');
    Route::get('/finance/expenseItemInfo', 'FinanceControllers\ExpensesController@expenseItemInfo')->name('expenseItemInfo');
    Route::get('/finance/company_create', 'FinanceControllers\ExpensesController@company_create')->name('company_create');
    Route::get('/finance/company_load', 'FinanceControllers\ExpensesController@company_load')->name('company_load');
    Route::get('/finance/expese_deletedetail', 'FinanceControllers\ExpensesController@expese_deletedetail')->name('expese_deletedetail');

    Route::get('/finance/expense_genje', 'FinanceControllers\ExpensesController@expense_genje')->name('expense_genje');
    Route::get('/finance/expense_loadje', 'FinanceControllers\ExpensesController@expense_loadje')->name('expense_loadje');
    Route::get('/finance/expense_saveje', 'FinanceControllers\ExpensesController@expense_saveje')->name('expense_saveje');
    Route::get('/finance/expense_getje', 'FinanceControllers\ExpensesController@expense_getje')->name('expense_getje');
    Route::get('/finance/expense_deleteje', 'FinanceControllers\ExpensesController@expense_deleteje')->name('expense_deleteje');

    // Route::get('/finance/expenses_supplier','FinanceControllers\ExpensesController@expenses_supplier')->name('expenses_supplier');
    // Route::get('/finance/expenses_supplier_create','FinanceControllers\ExpensesController@expenses_supplier_create')->name('expenses_supplier_create');
    // Route::get('/finance/expenses_supplier_read','FinanceControllers\ExpensesController@expenses_supplier_read')->name('expenses_supplier_read');
    // Route::get('/finance/expenses_supplier_update','FinanceControllers\ExpensesController@expenses_supplier_update')->name('expenses_supplier_update');
    // Route::get('/finance/expenses_supplier_delete','FinanceControllers\ExpensesController@expenses_supplier_delete')->name('expenses_supplier_delete');


    // Route::get('/finance/expenses_items','FinanceControllers\ExpensesController@expenses_items')->name('expenses_items');
    // Route::get('/finance/expenses_items_create','FinanceControllers\ExpensesController@expenses_items_create')->name('expenses_items_create');
    // Route::get('/finance/expenses_items_read','FinanceControllers\ExpensesController@expenses_items_read')->name('expenses_items_read');
    // Route::get('/finance/expenses_items_delete','FinanceControllers\ExpensesController@expenses_items_delete')->name('expenses_items_delete');

    Route::get('/finance/expenses_gencoa', 'FinanceControllers\ExpensesController@expenses_gencoa')->name('expenses_gencoa');
    Route::get('/finance/expenses_print', 'FinanceControllers\ExpensesController@expenses_print')->name('expenses_print');
    Route::get('/finance/expenses_gencredit', 'FinanceControllers\ExpensesController@expenses_gencredit')->name('expenses_gencredit');

    Route::get('/finance/disbursement', 'FinanceControllers\DisbursementController@disbursement')->name('disbursement');
    Route::get('/finance/disbursement_loadsupplier', 'FinanceControllers\DisbursementController@disbursement_loadsupplier')->name('disbursement_loadsupplier');
    Route::get('/finance/disbursement_loadrr', 'FinanceControllers\DisbursementController@disbursement_loadrr')->name('disbursement_loadrr');
    Route::get('/finance/disbursement_save', 'FinanceControllers\DisbursementController@disbursement_save')->name('disbursement_save');
    Route::get('/finance/disbursement_load', 'FinanceControllers\DisbursementController@disbursement_load')->name('disbursement_load');
    Route::get('/finance/disbursement_read', 'FinanceControllers\DisbursementController@disbursement_read')->name('disbursement_read');
    Route::get('/finance/disbursement_loadje', 'FinanceControllers\DisbursementController@disbursement_loadje')->name('disbursement_loadje');
    Route::get('/finance/disbursement_post', 'FinanceControllers\DisbursementController@disbursement_post')->name('disbursement_post');
    Route::get('/finance/disbursement_removeje', 'FinanceControllers\DisbursementController@disbursement_removeje')->name('disbursement_removeje');

    Route::get('/finance/onlinepay', 'FinanceControllers\FinanceController@onlinepay')->name('onlinepay');
    Route::get('/finance/onlinepaymentlist', 'FinanceControllers\FinanceController@onlinepaymentlist')->name('onlinepaymentlist');
    Route::get('/finance/paydata', 'FinanceControllers\FinanceController@paydata')->name('paydata');
    Route::get('/finance/approvepay', 'FinanceControllers\FinanceController@approvepay')->name('approvepay');
    Route::get('/finance/saveolAmount', 'FinanceControllers\FinanceController@saveolAmount')->name('saveolAmount');
    Route::get('/finance/saveolDate', 'FinanceControllers\FinanceController@saveolDate')->name('saveolDate');
    Route::get('/finance/saveolpaytype', 'FinanceControllers\FinanceController@saveolpaytype')->name('saveolpaytype');
    Route::get('/finance/saveolrefnum', 'FinanceControllers\FinanceController@saveolrefnum')->name('saveolrefnum');
    Route::get('/finance/saveoldisapprove', 'FinanceControllers\FinanceController@saveoldisapprove')->name('saveoldisapprove');

    Route::get('/finance/olreceipt', 'FinanceControllers\FinanceController@olreceipt')->name('olreceipt');
    Route::get('/finance/searchOLReceipt', 'FinanceControllers\FinanceController@searchOLReceipt')->name('searchOLReceipt');

    Route::get('/finance/reportbalanceforwarding/{id}', 'FinanceControllers\FinanceReportController@reportbalanceforwarding')->name('reportbalanceforwarding');
    Route::get('/finance/reportonlinepayments/{id}', 'FinanceControllers\FinanceReportController@reportonlinepayments')->name('reportonlinepayments');




    Route::get('/finance/adjustment', 'FinanceControllers\FinanceController@adjustment')->name('adjustment');
    Route::get('/finance/adjustment/adjloadglevel', 'FinanceControllers\FinanceController@adjloadglevel')->name('adjloadglevel');
    Route::get('/finance/adjustment/adjfilter', 'FinanceControllers\FinanceController@adjfilter')->name('adjfilter');
    Route::get('/finance/adjustment/searchadj', 'FinanceControllers\FinanceController@searchadj')->name('searchadj');
    Route::get('/finance/adjustment/viewadj', 'FinanceControllers\FinanceController@viewadj')->name('viewadj');
    Route::get('/finance/adjustment/appendADJ', 'FinanceControllers\FinanceController@appendADJ')->name('appendADJ');
    Route::get('/finance/adjustment/deleteADJ', 'FinanceControllers\FinanceController@deleteADJ')->name('deleteADJ');
    Route::get('/finance/adjustment/approveADJ', 'FinanceControllers\FinanceController@approveADJ')->name('approveADJ');
    Route::get('/finance/adjustment/disapproveADJ', 'FinanceControllers\FinanceController@disapproveADJ')->name('disapproveADJ');
    Route::get('/finance/adjustment/seladdstud', 'FinanceControllers\FinanceController@seladdstud')->name('seladdstud');
    Route::get('/finance/adjustment/adj_loadclass', 'FinanceControllers\FinanceController@adj_loadclass')->name('adj_loadclass');

    //Route::get('/finance/allowdp','FinanceControllers\FinanceController@allowdp')->name('allowdp');
    Route::get('/finance/allowdp/loadstudnodp', 'FinanceControllers\FinanceController@loadstudnodp')->name('loadstudnodp');
    Route::get('/finance/allowdp/searchnodp', 'FinanceControllers\FinanceController@searchnodp')->name('searchnodp');
    Route::get('/finance/allowdp/appendnodp', 'FinanceControllers\FinanceController@appendnodp')->name('appendnodp');
    Route::get('/finance/allowdp/removenodp', 'FinanceControllers\FinanceController@removenodp')->name('removenodp');

    Route::get('/finance/transactions/cashtrans', 'FinanceControllers\FinanceController@cashtrans')->name('cashtrans');
    Route::get('/finance/transactions/cashtranssearch', 'FinanceControllers\FinanceController@cashtranssearch')->name('cashtranssearch');
    Route::get('/finance/transactions/transviewdetail', 'FinanceControllers\FinanceController@transviewdetail')->name('transviewdetail');
    Route::get('/finance/transactions/printcashtrans/{terminalid}/{dtfrom}/{dtto}/{filter}/{paytype}/{title}', 'FinanceControllers\FinanceController@printcashtrans');

    Route::get('/finance/reports/dailycashcollection', 'FinanceControllers\FinanceController@dailycashcollection')->name('dailycashcollection');
    Route::get('/finance/reports/dailycashcollection/generateTH', 'FinanceControllers\FinanceController@generateTH')->name('generateTH');
    Route::get('/finance/reports/dailycashcollection/generatereport', 'FinanceControllers\FinanceController@generatereport')->name('generatereport');
    Route::get('/finance/reports/dailycashcollection/distitemamount', 'FinanceControllers\FinanceController@distitemamount')->name('distitemamount');
    Route::get('/finance/reports/dailycashcollection/saveDCR', 'FinanceControllers\FinanceController@saveDCR')->name('saveDCR');

    Route::get('/finance/reports/dailycashcollection/dailycashcollectionpdf/{date}/{terminal}/{action}', 'FinanceControllers\FinanceController@dailycashcollectionpdf')->name('dailycashcollectionpdf');
    Route::get('/finance/reports/dailycashcollection/dailycashsummarypdf', 'FinanceControllers\FinanceController@dailycashsummarypdf')->name('dailycashsummarypdf');

    Route::get('/finance/reports/dcpr', 'FinanceControllers\DCPRController@dcpr')->name('dcpr');
    Route::get('/finance/reports/dcpr_generate', 'FinanceControllers\DCPRController@dcpr_generate')->name('dcpr_generate');
    Route::get('/finance/reports/dcpr_export', 'FinanceControllers\DCPRController@dcpr_export')->name('dcpr_export');

    Route::get('/finance/reports/monthlycollection', 'FinanceControllers\MonthlyCollectionController@monthlycollection')->name('monthlycollection');
    Route::get('/finance/reports/mc_generate', 'FinanceControllers\MonthlyCollectionController@mc_generate')->name('mc_generate');
    Route::get('/finance/reports/mc_export', 'FinanceControllers\MonthlyCollectionController@mc_export')->name('mc_export');

    Route::get('/finance/reports/yearend', 'FinanceControllers\YearEndController@yearend')->name('yearend');
    Route::get('/finance/reports/ye_generate', 'FinanceControllers\YearEndController@ye_generate')->name('ye_generate');
    Route::get('/finance/reports/ye_print', 'FinanceControllers\YearEndController@ye_generate')->name('ye_print');

    Route::get('/finance/oldaccounts', 'FinanceControllers\OldAccountsController@oldaccounts')->name('oldaccounts');
    Route::get('/finance/oa_loadsy', 'FinanceControllers\OldAccountsController@oa_loadsy')->name('oa_loadsy');
    Route::get('/finance/oa_load', 'FinanceControllers\OldAccountsController@oa_load')->name('oa_load');
    Route::get('/finance/oa_forward', 'FinanceControllers\OldAccountsController@oa_forward')->name('oa_forward');
    Route::get('/finance/oa_setup', 'FinanceControllers\OldAccountsController@oa_setup')->name('oa_setup');
    Route::get('/finance/oa_setupsave', 'FinanceControllers\OldAccountsController@oa_setupsave')->name('oa_setupsave');

    Route::get('/finance/old_load/', 'FinanceControllers\OldAccountsController@old_load')->name('old_load');
    Route::get('/finance/old_loadstud/', 'FinanceControllers\OldAccountsController@old_loadstud')->name('old_loadstud');
    Route::get('/finance/old_loadamount/', 'FinanceControllers\OldAccountsController@old_loadamount')->name('old_loadamount');
    Route::get('/finance/old_getsem/', 'FinanceControllers\OldAccountsController@old_getsem')->name('old_getsem');

    Route::get('/finance/old_add_studlist/', 'FinanceControllers\OldAccountsController@old_add_studlist')->name('old_add_studlist');
    Route::get('/finance/old_post/', 'FinanceControllers\OldAccountsController@old_post')->name('old_post');

    Route::get('/finance/utilities', 'FinanceControllers\UtilityController@utilities')->name('utilities');
    Route::get('/finance/utilities/genpayinfo', 'FinanceControllers\UtilityController@genpayinfo')->name('genpayinfo');
    Route::get('/finance/utilities/resetpayment', 'FinanceControllers\UtilityController@resetpayment')->name('resetpayment');
    Route::get('/finance/utilities/genstud', 'FinanceControllers\UtilityController@genstud')->name('genstud');
    Route::get('/finance/utilities/calcLedger', 'FinanceControllers\UtilityController@calcLedger')->name('calcLedger');
    Route::get('/finance/utilities/clearledger', 'FinanceControllers\UtilityController@clearledger')->name('clearledger');
    Route::get('/finance/utilities/fixledgerrow', 'FinanceControllers\UtilityController@fixledgerrow')->name('fixledgerrow');
    Route::get('/finance/utilities/genitemizedinfo', 'FinanceControllers\UtilityController@genitemizedinfo')->name('genitemizedinfo');

    Route::get('/finance/utilities/ledgeritemizedreset', 'FinanceControllers\UtilityController@ledgeritemizedreset')->name('ledgeritemizedreset');
    Route::get('/finance/utilities/transitemsreset', 'FinanceControllers\UtilityController@transitemsreset')->name('transitemsreset');
    Route::get('/finance/utilities/transitemstruncate', 'FinanceControllers\UtilityController@transitemstruncate')->name('transitemstruncate');
    Route::get('/finance/utilities/ledgeritemizedtruncate', 'FinanceControllers\UtilityController@ledgeritemizedtruncate')->name('ledgeritemizedtruncate');

    Route::get('/finance/utilities/adj_search', 'FinanceControllers\UtilityController@adj_search')->name('adj_search');
    Route::get('/finance/utilities/adj_removedetail', 'FinanceControllers\UtilityController@adj_removedetail')->name('adj_removedetail');

    Route::get('/finance/utilities/fwd_gennegstud', 'FinanceControllers\UtilityController@fwd_gennegstud')->name('fwd_gennegstud');
    Route::get('/finance/utilities/fwd_genstudinfo', 'FinanceControllers\UtilityController@fwd_genstudinfo')->name('fwd_genstudinfo');
    Route::get('/finance/utilities/fwd_fixnegativebal', 'FinanceControllers\UtilityController@fwd_fixnegativebal')->name('fwd_fixnegativebal');

    Route::get('/finance/utilities/dpfix_geninfo', 'FinanceControllers\UtilityController@dpfix_geninfo')->name('dpfix_geninfo');
    Route::get('/finance/utilities/dpfix_loadorinfo', 'FinanceControllers\UtilityController@dpfix_loadorinfo')->name('dpfix_loadorinfo');
    Route::get('/finance/utilities/dpfix_pushtoledger', 'FinanceControllers\UtilityController@dpfix_pushtoledger')->name('dpfix_pushtoledger');

    Route::get('/finance/utilities/disbalance_genstud', 'FinanceControllers\UtilityController@disbalance_genstud')->name('disbalance_genstud');
    Route::get('/finance/utilities/disbaltrans_genstud', 'FinanceControllers\UtilityController@disbaltrans_genstud')->name('disbaltrans_genstud');

    Route::get('/finance/utilities/tlf_generate', 'FinanceControllers\UtilityController@tlf_generate')->name('tlf_generate');
    Route::get('/finance/utilities/tlf_fix', 'FinanceControllers\UtilityController@tlf_fix')->name('tlf_fix');

    Route::get('/reportsetup', 'FinanceControllers\ReportSetupController@index')->name('reportsetup');
    Route::get('/reportsetup/createreport', 'FinanceControllers\ReportSetupController@createreport')->name('createreport');
    Route::get('/reportsetup/getaccheaders', 'FinanceControllers\ReportSetupController@getaccheaders')->name('getaccheaders');
    Route::get('/reportsetup/saveheader', 'FinanceControllers\ReportSetupController@saveheader')->name('saveheader');
    Route::get('/reportsetup/getheaders', 'FinanceControllers\ReportSetupController@getheaders')->name('getheaders');
    Route::get('/reportsetup/getsubs', 'FinanceControllers\ReportSetupController@getsubs')->name('getsubs');
    Route::get('/reportsetup/getgroups', 'FinanceControllers\ReportSetupController@getgroups')->name('getgroups');
    Route::get('/reportsetup/savesub', 'FinanceControllers\ReportSetupController@savesub')->name('savesub');
    Route::get('/reportsetup/getdetails', 'FinanceControllers\ReportSetupController@getdetails')->name('getdetails');
    Route::get('/reportsetup/getmaps', 'FinanceControllers\ReportSetupController@getmaps')->name('getmaps');
    Route::get('/reportsetup/savedetail', 'FinanceControllers\ReportSetupController@savedetail')->name('savedetail');
    Route::get('/reportsetup/deleteheader', 'FinanceControllers\ReportSetupController@deleteheader')->name('deleteheader');
    Route::get('/reportsetup/deletesub', 'FinanceControllers\ReportSetupController@deletesub')->name('deletesub');
    Route::get('/reportsetup/deletedetail', 'FinanceControllers\ReportSetupController@deletedetail')->name('deletedetail');
    Route::get('/reportsetup/getdetailinfo', 'FinanceControllers\ReportSetupController@getdetailinfo')->name('getdetailinfo');
    Route::get('/reportsetup/updatedetail', 'FinanceControllers\ReportSetupController@updatedetail')->name('updatedetail');
    Route::get('/reportsetup/setupexport', 'FinanceControllers\ReportSetupController@setupexport')->name('setupexport');

    Route::get('/studentassessment', 'FinanceControllers\StudentAssessmentController@index')->name('studentassessment');
    Route::get('/studentassessment/detail', 'FinanceControllers\StudentAssessmentController@detail')->name('studentassessmentdetail');
    Route::get('/studentassessment/filter', 'FinanceControllers\StudentAssessmentController@filter')->name('studentassessmentfilter');
    Route::get('/studentassessment/export', 'FinanceControllers\StudentAssessmentController@export')->name('studentassessmentexport');

    Route::get('/cashreceiptsummary/index', 'FinanceControllers\CashReceiptController@index')->name('cashreceiptsummary');
    Route::get('/cashreceiptsummary/filter', 'FinanceControllers\CashReceiptController@filter')->name('cashreceiptfilter');
    Route::get('/cashreceiptsummary/export', 'FinanceControllers\CashReceiptController@export')->name('cashreceiptexport');

    Route::get('/dailycashcollectionreport/index', 'FinanceControllers\DailyCashCollectionController@index')->name('dailycashcollectionreport');

    Route::get('/acctreceivable', 'FinanceControllers\AccountsReceivableController@index')->name('acctreceivable');
    Route::get('/acctreceivable/default', 'FinanceControllers\AccountsReceivableController@default')->name('acctreceivabledefault');
    Route::get('/acctreceivable/getsections', 'FinanceControllers\AccountsReceivableController@getsections')->name('acctreceivablegetsections');
    Route::get('/acctreceivable/filter', 'FinanceControllers\AccountsReceivableController@filter')->name('acctreceivablefilter');
    Route::get('/acctreceivable/export', 'FinanceControllers\AccountsReceivableController@export')->name('acctreceivableexport');

    Route::get('/statementofacct', 'FinanceControllers\StatementofAccountController@index')->name('statementofacct');
    Route::get('/statementofacctgetaccount', 'FinanceControllers\StatementofAccountController@getaccount_v2')->name('statementofacctgetaccount');
    Route::get('/statementofacctexport', 'FinanceControllers\StatementofAccountController@export')->name('statementofacctexport');
    Route::get('/statementofacctgetnote', 'FinanceControllers\StatementofAccountController@getnote')->name('statementofacctgetnote');
    Route::get('/statementofacctsubmitnotes', 'FinanceControllers\StatementofAccountController@submitnotes')->name('statementofacctsubmitnotes');

    Route::get('/statementofacctloadsection', 'FinanceControllers\StatementofAccountController@statementofacctloadsection')->name('statementofacctloadsection');
    Route::get('/statementofaccsmsnotify', 'FinanceControllers\StatementofAccountController@statementofaccsmsnotify')->name('statementofaccsmsnotify');
    Route::post('/statementofaccsmssend', 'FinanceControllers\StatementofAccountController@statementofaccsmssend')->name('statementofaccsmssend');
    Route::post('/statementofaccaddflag', 'FinanceControllers\StatementofAccountController@statementofaccaddflag')->name('statementofaccaddflag');


    Route::get('/tesapplication/index', 'FinanceControllers\ProgramBillingController@tesapplication')->name('tesapplication');
    Route::get('/stepebsubilling/index', 'FinanceControllers\ProgramBillingController@stepebsubilling')->name('stepebsubilling');
    Route::get('/ncccbilling/index', 'FinanceControllers\ProgramBillingController@ncccbilling')->name('ncccbilling');
    Route::get('/germanscholarsbilling/index', 'FinanceControllers\ProgramBillingController@germanscholarsbilling')->name('germanscholarsbilling');
    Route::get('/chedbilling/index', 'FinanceControllers\ProgramBillingController@chedbilling')->name('chedbilling');

    Route::get('/statementofacctgetmonthrange', 'FinanceControllers\StatementofAccountController@statementofacctgetmonthrange')->name('statementofacctgetmonthrange');
    Route::get('/statementofacctaddmonthrange', 'FinanceControllers\StatementofAccountController@statementofacctaddmonthrange')->name('statementofacctaddmonthrange');
    Route::get('/statementofacctselectmonthrange', 'FinanceControllers\StatementofAccountController@statementofacctselectmonthrange')->name('statementofacctselectmonthrange');
    Route::get('/statementofacctselectedmonthrange', 'FinanceControllers\StatementofAccountController@statementofacctselectedmonthrange')->name('statementofacctselectedmonthrange');
    Route::get('/statementofacctremovemonthrange', 'FinanceControllers\StatementofAccountController@statementofacctremovemonthrange')->name('statementofacctremovemonthrange');
});


Route::middleware(['auth', 'isAccounting'])->group(function () {
    Route::get('/finance/accounting', 'FinanceControllers\AccountingController@financeSetup')->name('financeSetup');

    // Route::get('/finance/coa/chartofaccounts','FinanceControllers\FinanceController@chartofaccounts')->name('chartofaccounts');
    Route::get('/finance/coa/loadchart', 'FinanceControllers\FinanceController@loadchart')->name('loadchart');
    Route::get('/finance/coa/loadgroup', 'FinanceControllers\FinanceController@loadgroup')->name('loadgroup');
    Route::get('/finance/coa/loadgroups', 'FinanceControllers\FinanceController@loadgroups')->name('loadgroups');

    Route::get('/finance/coa/saveacctype', 'FinanceControllers\FinanceController@saveacctype')->name('saveacctype');
    Route::get('/finance/coa/editacctype', 'FinanceControllers\FinanceController@editacctype')->name('editacctype');
    Route::get('/finance/coa/updateacctype', 'FinanceControllers\FinanceController@updateacctype')->name('updateacctype');
    Route::get('/finance/coa/deleteacctype', 'FinanceControllers\FinanceController@deleteacctype')->name('deleteacctype');

    Route::get('/finance/coa/loadaccname', 'FinanceControllers\FinanceController@loadaccname')->name('loadaccname');
    Route::get('/finance/coa/saveaccname', 'FinanceControllers\FinanceController@saveaccname')->name('saveaccname');
    Route::get('/finance/coa/editaccname', 'FinanceControllers\FinanceController@editaccname')->name('editaccname');
    Route::get('/finance/coa/updateaccname', 'FinanceControllers\FinanceController@updateaccname')->name('updateaccname');
    Route::get('/finance/coa/deleteaccname', 'FinanceControllers\FinanceController@deleteaccname')->name('deleteaccname');


    Route::get('/finance/coa/loadsubname', 'FinanceControllers\FinanceController@loadsubname')->name('loadsubname');
    Route::get('/finance/coa/savesubname', 'FinanceControllers\FinanceController@savesubname')->name('savesubname');
    Route::get('/finance/coa/editsubname', 'FinanceControllers\FinanceController@editsubname')->name('editsubname');
    Route::get('/finance/coa/updatesubname', 'FinanceControllers\FinanceController@updatesubname')->name('updatesubname');
    Route::get('/finance/coa/deletesubname', 'FinanceControllers\FinanceController@deletesubname')->name('deletesubname');

    Route::get('/finance/coa/savesubitem', 'FinanceControllers\FinanceController@savesubitem')->name('savesubitem');
    Route::get('/finance/coa/editsubitem', 'FinanceControllers\FinanceController@editsubitem')->name('editsubitem');
    Route::get('/finance/coa/updatesubitem', 'FinanceControllers\FinanceController@updatesubitem')->name('updatesubitem');
    Route::get('/finance/coa/deletesubitem', 'FinanceControllers\FinanceController@deletesubitem')->name('deletesubitem');

    Route::get('/finance/coa/switchacc', 'FinanceControllers\FinanceController@switchacc')->name('switchacc');

    Route::get('/finance/coa/coamapping', 'FinanceControllers\FinanceController@coamapping')->name('coamapping');
    Route::get('/finance/coa/loadmapping', 'FinanceControllers\FinanceController@loadmapping')->name('loadmapping');
    Route::get('/finance/coa/savemapping', 'FinanceControllers\FinanceController@savemapping')->name('savemapping');
    Route::get('/finance/coa/editmapping', 'FinanceControllers\FinanceController@editmapping')->name('editmapping');
    Route::get('/finance/coa/updatemapping', 'FinanceControllers\FinanceController@updatemapping')->name('updatemapping');
    Route::get('/finance/coa/deletemapping', 'FinanceControllers\FinanceController@deletemapping')->name('deletemapping');

    Route::get('/finance/accounting/journalentries', 'FinanceControllers\AccountingController@journalentries')->name('journalentries');
    Route::get('/finance/accounting/jeloadcoa', 'FinanceControllers\AccountingController@jeloadcoa')->name('jeloadcoa');

    Route::get('/finance/accounting/loadje', 'FinanceControllers\AccountingController@loadje')->name('loadje');
    Route::get('/finance/accounting/saveje', 'FinanceControllers\AccountingController@saveje')->name('saveje');
    Route::get('/finance/accounting/editje', 'FinanceControllers\AccountingController@editje')->name('editje');
    Route::get('/finance/accounting/deletejedetail', 'FinanceControllers\AccountingController@deletejedetail')->name('deletejedetail');
    Route::get('/finance/accounting/appendeditdetail', 'FinanceControllers\AccountingController@appendeditdetail')->name('appendeditdetail');
    Route::get('/finance/accounting/postje', 'FinanceControllers\AccountingController@postje')->name('postje');
});


Route::middleware(['auth', 'isFinanceAdmin'])->group(function () {
    Route::get('/finance/setup', 'FinanceControllers\FinanceController@financeSetup')->name('financeSetup');

    Route::get('/finance/loadTerminal', 'FinanceControllers\FinanceController@loadTerminal')->name('loadTerminal');
    Route::get('/finance/clearTerminal', 'FinanceControllers\FinanceController@clearTerminal')->name('clearTerminal');
    Route::get('/finance/createTerminal', 'FinanceControllers\FinanceController@createTerminal')->name('createTerminal');

    Route::get('/finance/loadCOA', 'FinanceControllers\FinanceController@loadCOA')->name('loadCOA');
    Route::get('/finance/appendCOA', 'FinanceControllers\FinanceController@appendCOA')->name('appendCOA');
    Route::get('/finance/editCOA', 'FinanceControllers\FinanceController@editCOA')->name('editCOA');
    Route::get('/finance/updateCOA', 'FinanceControllers\FinanceController@updateCOA')->name('updateCOA');
    Route::get('/finance/deleteCOA', 'FinanceControllers\FinanceController@deleteCOA')->name('deleteCOA');

    Route::get('/finance/loadCOAGroup', 'FinanceControllers\FinanceController@loadCOAGroup')->name('loadCOAGroup');
    Route::get('/finance/appendCOAGroup', 'FinanceControllers\FinanceController@appendCOAGroup')->name('appendCOAGroup');

    // Route::get('/finance/coa/chartofaccounts','FinanceControllers\FinanceController@chartofaccounts')->name('chartofaccounts');
    Route::get('/finance/coa/loadchart', 'FinanceControllers\FinanceController@loadchart')->name('loadchart');
    Route::get('/finance/coa/loadgroup', 'FinanceControllers\FinanceController@loadgroup')->name('loadgroup');
    Route::get('/finance/coa/loadgroups', 'FinanceControllers\FinanceController@loadgroups')->name('loadgroups');

    Route::get('/finance/coa/saveacctype', 'FinanceControllers\FinanceController@saveacctype')->name('saveacctype');
    Route::get('/finance/coa/editacctype', 'FinanceControllers\FinanceController@editacctype')->name('editacctype');
    Route::get('/finance/coa/updateacctype', 'FinanceControllers\FinanceController@updateacctype')->name('updateacctype');
    Route::get('/finance/coa/deleteacctype', 'FinanceControllers\FinanceController@deleteacctype')->name('deleteacctype');

    Route::get('/finance/coa/loadaccname', 'FinanceControllers\FinanceController@loadaccname')->name('loadaccname');
    Route::get('/finance/coa/saveaccname', 'FinanceControllers\FinanceController@saveaccname')->name('saveaccname');
    Route::get('/finance/coa/editaccname', 'FinanceControllers\FinanceController@editaccname')->name('editaccname');
    Route::get('/finance/coa/updateaccname', 'FinanceControllers\FinanceController@updateaccname')->name('updateaccname');
    Route::get('/finance/coa/deleteaccname', 'FinanceControllers\FinanceController@deleteaccname')->name('deleteaccname');


    Route::get('/finance/coa/loadsubname', 'FinanceControllers\FinanceController@loadsubname')->name('loadsubname');
    Route::get('/finance/coa/savesubname', 'FinanceControllers\FinanceController@savesubname')->name('savesubname');
    Route::get('/finance/coa/editsubname', 'FinanceControllers\FinanceController@editsubname')->name('editsubname');
    Route::get('/finance/coa/updatesubname', 'FinanceControllers\FinanceController@updatesubname')->name('updatesubname');
    Route::get('/finance/coa/deletesubname', 'FinanceControllers\FinanceController@deletesubname')->name('deletesubname');

    Route::get('/finance/coa/savesubitem', 'FinanceControllers\FinanceController@savesubitem')->name('savesubitem');
    Route::get('/finance/coa/editsubitem', 'FinanceControllers\FinanceController@editsubitem')->name('editsubitem');
    Route::get('/finance/coa/updatesubitem', 'FinanceControllers\FinanceController@updatesubitem')->name('updatesubitem');
    Route::get('/finance/coa/deletesubitem', 'FinanceControllers\FinanceController@deletesubitem')->name('deletesubitem');

    Route::get('/finance/coa/switchacc', 'FinanceControllers\FinanceController@switchacc')->name('switchacc');

    Route::get('/finance/coa/coamapping', 'FinanceControllers\FinanceController@coamapping')->name('coamapping');
    Route::get('/finance/coa/loadmapping', 'FinanceControllers\FinanceController@loadmapping')->name('loadmapping');
    Route::get('/finance/coa/savemapping', 'FinanceControllers\FinanceController@savemapping')->name('savemapping');
    Route::get('/finance/coa/editmapping', 'FinanceControllers\FinanceController@editmapping')->name('editmapping');
    Route::get('/finance/coa/updatemapping', 'FinanceControllers\FinanceController@updatemapping')->name('updatemapping');
    Route::get('/finance/coa/deletemapping', 'FinanceControllers\FinanceController@deletemapping')->name('deletemapping');

    Route::get('/finance/loadUE', 'FinanceControllers\FinanceController@loadUE')->name('loadUE');
    Route::get('/finance/processUE', 'FinanceControllers\FinanceController@processUE')->name('processUE');

    Route::get('/finance/dploadAcadprog', 'FinanceControllers\FinanceController@dploadAcadprog')->name('dploadAcadprog');
    Route::get('/finance/dploadglevel', 'FinanceControllers\FinanceController@dploadglevel')->name('dploadglevel');
    Route::get('/finance/togglenodp', 'FinanceControllers\FinanceController@togglenodp')->name('togglenodp');
    Route::get('/finance/togglenodpesc', 'FinanceControllers\FinanceController@togglenodpesc')->name('togglenodpesc');
    Route::get('/finance/togglenodpvoucher', 'FinanceControllers\FinanceController@togglenodpvoucher')->name('togglenodpvoucher');

    Route::get('/finance/togglepayplan', 'FinanceControllers\FinanceController@togglepayplan')->name('togglepayplan');

});

Route::middleware(['auth'])->group(function () {


    //Finance Items
    Route::get('/finance/expenses_items', 'FinanceControllers\ExpensesController@expenses_items')->name('expenses_items');
    Route::get('/finance/expenses_items_create', 'FinanceControllers\AccountingSetupController@expenses_items_create')->name('expenses_items_create');
    Route::get('/finance/expenses_items_read', 'FinanceControllers\AccountingSetupController@expenses_items_read')->name('expenses_items_read');
    Route::get('/finance/expenses_items_delete', 'FinanceControllers\AccountingSetupController@expenses_items_delete')->name('expenses_items_delete');

    Route::get('/finance/purchasing/vendor', 'FinanceControllers\PurchasingController@vendor')->name('vendor');
    Route::get('/finance/purchasing/vendor/create', 'FinanceControllers\PurchasingController@vendor_update')->name('vendor_update');
    Route::get('/finance/purchasing/vendor/load', 'FinanceControllers\PurchasingController@vendor_load')->name('vendor_load');
    Route::get('/finance/purchasing/vendor/edit', 'FinanceControllers\PurchasingController@vendor_edit')->name('vendor_edit');
    Route::get('/finance/purchasing/vendor/delete', 'FinanceControllers\PurchasingController@vendor_delete')->name('vendor_delete');

    Route::get('/finance/purchasing/purchaseorder', 'FinanceControllers\PurchasingController@purchaseorder')->name('purchaseorder');
    Route::get('/finance/purchasing/purchase_load', 'FinanceControllers\PurchasingController@purchase_load')->name('purchase_load');
    Route::get('/finance/purchasing/purchase_loaditems', 'FinanceControllers\PurchasingController@purchase_loaditems')->name('purchase_loaditems');
    Route::get('/finance/purchasing/purchase_create', 'FinanceControllers\PurchasingController@purchase_create')->name('purchase_create');
    Route::get('/finance/purchasing/purchase_read', 'FinanceControllers\PurchasingController@purchase_read')->name('purchase_read');
    Route::get('/finance/purchasing/purchase_deleteitem', 'FinanceControllers\PurchasingController@purchase_deleteitem')->name('purchase_deleteitem');
    Route::get('/finance/purchasing/purchase_delete', 'FinanceControllers\PurchasingController@purchase_delete')->name('purchase_delete');
    Route::get('/finance/purchasing/purchase_post', 'FinanceControllers\PurchasingController@purchase_post')->name('purchase_post');
    Route::get('/finance/purchasing/purchase_print', 'FinanceControllers\PurchasingController@purchase_print')->name('purchase_print');


    Route::get('/finance/purchasing/receiving', 'FinanceControllers\PurchasingController@receiving')->name('receiving');
    Route::get('/finance/purchasing/receiving_load', 'FinanceControllers\PurchasingController@receiving_load')->name('receiving_load');
    Route::get('/finance/purchasing/receiving_read', 'FinanceControllers\PurchasingController@receiving_read')->name('receiving_read');
    Route::post('/finance/purchasing/receiving_save', 'FinanceControllers\PurchasingController@receiving_save')->name('receiving_save');
    Route::get('/finance/purchasing/receiving_post', 'FinanceControllers\PurchasingController@receiving_post')->name('receiving_post');
    Route::get('/finance/purchasing/receiving_print', 'FinanceControllers\PurchasingController@receiving_print')->name('receiving_print');
    Route::get('/finance/purchasing/receiving_removesched', 'FinanceControllers\PurchasingController@receiving_removesched')->name('receiving_removesched');
    Route::post('/finance/purchasing/receiving_removeje', 'FinanceControllers\PurchasingController@receiving_removeje')->name('receiving_removeje');

    Route::get('/finance/purchasing/po_additem', 'FinanceControllers\PurchasingController@po_additem')->name('po_additem');

    //stock card
    Route::view('/finance/stockcard/view', 'finance/stock_card/stock_card');
    Route::get('/finance/inventory/getStockCard', 'FinanceControllers\StockcardController@getStockCard');
    Route::get('/finance/stock_card/print', 'FinanceControllers\StockcardController@print');

    //Department
    Route::post('/inventory/addDepartment', 'Inventory\inventoryController@saveDepartment');

    //inventory
    Route::view('/inventory/inventory/view', 'inventory/pages/inventory');
    Route::get('/inventory/getItems', 'Inventory\inventoryController@getItems');
    Route::get('/inventory/saveItems', 'Inventory\inventoryController@saveItems')->name("saveItems");

    //assignItem
    Route::post('/inventory/saveAssigned', 'Inventory\inventoryController@saveAssigned');

    //dashboard
    Route::get('/inventory/dashboard/getcardNumbers', 'Inventory\inventoryController@getcardNumbers');
    Route::get('/inventory/dashboard/getStocks', 'Inventory\inventoryController@getStocks');
    Route::get('/inventory/dashboard/getDepartment', 'Inventory\inventoryController@getDepartment');
    Route::get('/inventory/dashboard/getStockSummary', 'Inventory\inventoryController@getStockSummary');
    Route::get('/inventory/dashboard/getReceivingSummary', 'Inventory\inventoryController@getReceivingSummary');


});


Route::middleware(['auth', 'isHumanResource', 'isDefaultPass'])->group(function () {

    Route::resource('/home', 'HRControllers\HRDashboardController');

    Route::get('/employeeslist/{id}', 'HRControllers\HREmployeesController@employeelist');

    Route::get('/addnewemployee/{id}', 'HRControllers\HREmployeesController@addnewemployee');

    Route::get('/hr/attendance/index', 'HRControllers\HRAttendanceController@index');
    Route::get('/hr/attendance/updatetime', 'HRControllers\HRAttendanceController@updatetime');
    Route::get('/hr/attendance/gettimelogs', 'HRControllers\HRAttendanceController@gettimelogs');
    Route::get('/hr/attendance/addtimelog', 'HRControllers\HRAttendanceController@addtimelog');
    Route::get('/hr/attendance/deletetimelog', 'HRControllers\HRAttendanceController@deletetimelog');
    Route::get('/hr/attendance/updatetapstate', 'HRControllers\HRAttendanceController@updatetapstate');

    Route::get('/attendance/{id}', 'HRControllers\HREmployeesController@attendance');

    Route::get('/hr/employeeprofile', 'HRControllers\HREmployeeInfoController@employeeprofile');
    Route::post('/hr/employeeprofilechangepic', 'HRControllers\HREmployeeInfoController@employeeprofilechangepic');
    Route::get('/hr/employeeprofileupdaterfid', 'HRControllers\HREmployeeInfoController@employeeprofileupdaterfid');
    Route::get('/hr/employeeprofiletab', 'HRControllers\HREmployeeInfoController@tabprofile');
    Route::get('/hr/updatepersonalinfo', 'HRControllers\HREmployeeInfoController@updatepersonalinfo');
    Route::get('/hr/updateemergencycontact', 'HRControllers\HREmployeeInfoController@updateemergencycontact');
    Route::get('/hr/getdesignations', 'HRControllers\HREmployeeInfoController@getdesignations');
    Route::get('/hr/updatedesignation', 'HRControllers\HREmployeeInfoController@updatedesignation');
    Route::get('/hr/updateaccounts', 'HRControllers\HREmployeeInfoController@updateaccounts');
    Route::get('/hr/deleteaccount', 'HRControllers\HREmployeeInfoController@deleteaccount');
    Route::get('/hr/updatefamilyinfo', 'HRControllers\HREmployeeInfoController@updatefamilyinfo');
    Route::get('/hr/deletefamilyinfo', 'HRControllers\HREmployeeInfoController@deletefamilyinfo');
    Route::get('/hr/updateeducationinfo', 'HRControllers\HREmployeeInfoController@updateeducationinfo');
    Route::get('/hr/employeeexperience/{action}', 'HRControllers\HREmployeeInfoController@employeeexperience');

    Route::get('/hr/employeebasicsalarytab', 'HRControllers\HREmployeeProfile\HRBasicSalaryInfoController@index');
    Route::get('/hr/employeebasicsalaryinfo', 'HRControllers\HREmployeeProfile\HRBasicSalaryInfoController@employeebasicsalaryinfo');
    Route::get('/hr/employeebasicsalaryinfotimesched', 'HRControllers\HREmployeeProfile\HRBasicSalaryInfoController@employeebasicsalaryinfotimesched');
    Route::get('/hr/employeebasicsalaryinfobasisselection', 'HRControllers\HREmployeeProfile\HRBasicSalaryInfoController@employeebasicsalaryinfobasisselection');
    Route::get('/hr/employeerateelevation', 'HRControllers\HREmployeeProfile\HRBasicSalaryInfoController@employeerateelevation');
    // Route::get('/hr/employeeworkshift', 'HRControllers\HREmployeeProfile\HRBasicSalaryInfoController@employeeworkshift');

    Route::get('/hr/employeedeductionstab', 'HRControllers\HREmployeeProfile\HRDeductionInfoController@index');
    Route::get('/hr/employeecontributions', 'HRControllers\HREmployeeProfile\HRDeductionInfoController@employeecontributions');
    Route::get('/hr/updatedeductionsetuptype', 'HRControllers\HREmployeeProfile\HRDeductionInfoController@updatedeductionsetuptype');
    Route::get('/hr/employeeotherdeductionsinfo', 'HRControllers\HREmployeeProfile\HRDeductionInfoController@employeeotherdeductionsinfo');
    Route::get('/hr/employeeotherdeductionsinfoedit', 'HRControllers\HREmployeeProfile\HRDeductionInfoController@employeeotherdeductionsinfoedit');
    Route::get('/hr/employeeotherdeductionsinfodelete', 'HRControllers\HREmployeeProfile\HRDeductionInfoController@employeeotherdeductionsinfodelete');

    Route::get('/hr/employeeallowancetab', 'HRControllers\HREmployeeProfile\HRAllowanceInfoController@index');
    Route::get('/hr/employeestandardallowances', 'HRControllers\HREmployeeProfile\HRAllowanceInfoController@employeestandardallowances');
    Route::get('/hr/employeeallowanceinfo', 'HRControllers\HREmployeeProfile\HRAllowanceInfoController@employeeallowanceinfo');
    Route::get('/hr/employeeotherallowanceinfoedit', 'HRControllers\HREmployeeProfile\HRAllowanceInfoController@employeeotherallowanceinfoedit');
    Route::get('/hr/employeeotherallowanceinfodelete', 'HRControllers\HREmployeeProfile\HRAllowanceInfoController@employeeotherallowanceinfodelete');

    Route::get('/hr/employeecredentialstab', 'HRControllers\HREmployeeProfile\HRCredentialInfoController@index');
    Route::get('/hr/employeecredentialdelete', 'HRControllers\HREmployeeProfile\HRCredentialInfoController@employeecredentialdelete');

    Route::get('/hr/employeedtrtab/{action}', 'HRControllers\HREmployeesController@employeedtrtab');
    Route::get('/hr/employeeotherstab/index', 'HRControllers\HREmployeeProfile\HROtherInfoController@employeeotherstabindex');
    // Route::get('/hr/employeeotherstab/getdesignations', 'HRControllers\HREmployeeProfile\HROtherInfoController@getdesignations');
    Route::get('/hr/employeeotherstab/updatedesignation', 'HRControllers\HREmployeeProfile\HROtherInfoController@updatedesignation');
    Route::get('/hr/employeeotherstab/updatedepartment', 'HRControllers\HREmployeeProfile\HROtherInfoController@updatedepartment');
    Route::get('/hr/employeeotherstab/updateworkshift', 'HRControllers\HREmployeeProfile\HROtherInfoController@employeeworkshift');
    Route::get('/hr/employeeotherstab/updatecustomtimesched', 'HRControllers\HREmployeeProfile\HROtherInfoController@employeecustomtimesched');
    Route::get('/hr/employeeotherstab/updateattendancebasedsalary', 'HRControllers\HREmployeeProfile\HROtherInfoController@employeeattendancebasedsalary');

    Route::get('/employeebenefits/{id}', 'HRControllers\HREmployeesController@employeebenefits');
    Route::get('/employeeeducation/{id}', 'HRControllers\HREmployeesController@employeeeducationinfo');
    Route::get('/employeeotherdeductionsinfostatusupdate', 'HRControllers\HREmployeesController@employeeotherdeductionsinfostatusupdate');
    // Route::get('/employeecustomtimesched/{id}', 'HRControllers\HREmployeesController@employeecustomtimesched');
    Route::get('/employeerateelevation', 'HRControllers\HREmployeesController@employeerateelevation');
    Route::post('/employeecredential', 'HRControllers\HREmployeesController@employeecredential');
    Route::get('/employeedtr/{id}', 'HRControllers\HREmployeesController@employeedtr');

    Route::get('/employeestatus/{id}', 'HRControllers\HREmployeesController@employeestatus');
    Route::get('/employeesalaryupdate', 'HRControllers\HREmployeeSalarySettingController@employeesalaryupdate');

    Route::get('/salary/{id}', 'HRControllers\HREmployeesController@salary');

    Route::get('/hr/leaves/{id}', 'HRControllers\HREmployeesController@leaves');
    Route::get('/hr/leave/forcepermission', 'HRControllers\HREmployeesController@leaveforcepermission');
    Route::get('/hr/globalapplyleave', 'HRControllers\HREmployeesController@globalapplyleave');
    Route::get('/hr/overtime/{id}', 'HRControllers\HREmployeesController@overtimes');
    Route::get('/hr/overtimeforcepermission', 'HRControllers\HREmployeesController@overtimeforcepermission');

    Route::get('/holidays', 'HRControllers\SetupController@holidays');
    Route::get('/addholidaytypes', 'HRControllers\SetupController@addholidaytypes');
    Route::get('/updateholidayrates', 'HRControllers\SetupController@updateholidayrates');
    Route::get('/deleteholidaytype', 'HRControllers\SetupController@deleteholidaytype');


    Route::get('/leavesettings', 'HRControllers\SetupController@leavesettings');
    Route::get('/leavesettings/{id}', 'HRControllers\SetupController@leavesettingsupdates');
    Route::get('/hr/settings/leave/approval/{id}', 'HRControllers\SetupController@leaveapproval');

    Route::get('/changeattendance', 'HRControllers\HREmployeesController@changeattendance');

    Route::get('/requirements/{id}', 'HRControllers\SetupController@requirementssetup');
    Route::get('/hr/settings/offices/{id}', 'HRControllers\SetupController@officessetup');
    Route::get('/hr/settings/departments/{id}', 'HRControllers\SetupController@departmentssetup');
    // Route::get('/departments/{id}', 'HRControllers\HREmployeesController@departments');
    Route::get('/hr/settings/designations/{id}', 'HRControllers\SetupController@designationssetup');
    Route::post('/hr/setup/designations/updatesortid', 'HRControllers\SetupController@updatesortid');
    Route::get('/hr/setup/designations/updatedesignation', 'HRControllers\SetupController@updatedesignation');


    // Route::get('/payroll/{id}', 'HRControllers\HRController@payroll');
    // Route::get('/hr/payroll/{id}', 'HRControllers\HRPayrollController@payroll');
    Route::get('/hr/payroll/index', 'HRControllers\HRPayrollController@index');
    Route::get('/hr/payroll/setpayrolldate', 'HRControllers\HRPayrollController@setpayrolldate');
    Route::get('/hr/payroll/newpayroll', 'HRControllers\HRPayrollController@newpayroll');
    Route::get('/hr/payroll/changepayroll', 'HRControllers\HRPayrollController@changepayroll');
    Route::get('/hr/payroll/leapyearactivation', 'HRControllers\HRPayrollController@payrollleapyear');
    Route::get('/hr/payroll/getsalarydetails', 'HRControllers\HRPayrollController@getsalarydetails');
    Route::get('/hr/payroll/saveconfiguration', 'HRControllers\HRPayrollController@saveconfiguration');

    Route::get('/hr/payrollsummary/index', 'HRControllers\HRPayrollController@payrollsummary');
    Route::get('/hr/payrollsummary/setup', 'HRControllers\HRPayrollController@setup');
    Route::get('/hr/payrollsummary/setup-create', 'HRControllers\HRPayrollController@setupcreate');
    Route::get('/hr/payrollsummary/setup-show', 'HRControllers\HRPayrollController@setupshow');
    Route::get('/hr/payrollsummary/setup-delete', 'HRControllers\HRPayrollController@setupdelete');
    Route::get('/hr/payrollsummary/filter', 'HRControllers\HRPayrollController@filterpayrollsummary');
    Route::get('/hr/payrollsummary/releaseslipsingle', 'HRControllers\HRPayrollController@releaseslipsingle');
    Route::get('/hr/payrollsummary/viewslip', 'HRControllers\HRPayrollController@viewslip');
    Route::get('/hr/payrollsummary/exportsummary', 'HRControllers\HRPayrollController@exportsummary');

    Route::get('/hr/printpayrollhistory/{id}', 'HRControllers\HRPayrollController@printpayrollhistory');
    Route::get('/payrollgenerateslip', 'HRControllers\HRController@payrollgenerateslip');
    // Route::get('/printpayrollhistory/{id}', 'HRControllers\HRController@printpayrollhistory');
    Route::get('/printfilteredsalary/{id}', 'HRControllers\HRController@printfilteredsalary');

    Route::get('/standarddeductions/{id}', 'HRControllers\SetupController@standarddeductions');
    Route::get('/bracketing', 'HRControllers\SetupController@bracketing');
    Route::get('/bracketedit', 'HRControllers\SetupController@bracketedit');
    Route::get('/updatedeductions/{id}', 'HRControllers\SetupController@updatedeductions');
    Route::get('/standardallowances/{id}', 'HRControllers\SetupController@standardallowances');
    Route::get('/updateallowances/{id}', 'HRControllers\SetupController@updateallowances');
    Route::get('/updatedeductiondetails/{id}', 'HRControllers\SetupController@updatedeductiondetails');

    Route::get('/tardinessdeduction/{id}', 'HRControllers\SetupController@tardinessdeduction');
    Route::get('/addtardinesscomputation', 'HRControllers\SetupController@addtardinesscomputation');
    Route::get('/edittardinesscomputation', 'HRControllers\SetupController@edittardinesscomputation');
    Route::get('/deletetardinesscomputation', 'HRControllers\SetupController@deletetardinesscomputation');

    Route::get('/deleteearning', 'HRControllers\HRController@employeedeleteearning');
    Route::get('/editearning', 'HRControllers\HRController@employeeeditearning');
    Route::get('/employeeotherdeductiondelete', 'HRControllers\HRController@employeeotherdeductiondelete');
    Route::get('/employeeotherdeductionedit', 'HRControllers\HRController@employeeotherdeductionedit');

    Route::resource('/history', 'HRControllers\HRPayrollHistoryController');

    Route::get('/payslip/{id}', 'HRControllers\HRController@payslip');
    Route::get('/payrollitems/{id}', 'HRControllers\HRController@payrollitems');

    Route::get('/summaryofattendance/{id}', 'HRControllers\HRSummaryController@summaryofattendance');
    Route::get('/hrreports/summaryofemployees/{id}', 'HRControllers\HRSummaryController@summaryofemployees');

    Route::get('/hrreports/thirteenthmonth/{id}', 'HRControllers\HRThirteenthMonthController@thirteenthmonthindex');
    Route::get('/hrreports/thirteenthmonthpayslip', 'HRControllers\HRThirteenthMonthController@thirteenthmonthpayslip');


    Route::get('/newdeductionsetup/{id}', 'HRControllers\HRDeductionSetupController@newdeductionsetup');
    Route::get('/hrapplicationofdeduction', 'HRControllers\HRDeductionSetupController@hrapplicationofdeduction');
    Route::get('/hrapplicationdelete', 'HRControllers\HRDeductionSetupController@hrapplicationdelete');


});

Route::middleware(['auth'])->group(function () {

    Route::get('/hrreports/teacherevaluation', 'HRControllers\HRTeacherEvaluationController@admin_view_results');
    Route::get('/hrreports/evaluation/monitoring', 'HRControllers\HRTeacherEvaluationController@evaluation_monitoring');
    // Route::get('/hrreports/viewevaluation', 'HRControllers\HRTeacherEvaluationController@teacher_schedule');
    Route::get('/hrreports/viewcomments', 'HRControllers\HRTeacherEvaluationController@viewcomment');
    Route::get('/hrreports/viewevaluation', 'HRControllers\HRTeacherEvaluationController@check_evaluation');


    Route::get('/teacherevaluation/evalsetup', 'HRControllers\HRTeacherEvaluationController@getTeacherEvlStp');
    Route::get('/teacherevaluation/evalsetup/update', 'HRControllers\HRTeacherEvaluationController@updateTeacherEvlStp');

});

Route::group(['middleware' => ['auth', 'web']], function () {
    // ------------------------- SCHOOL FORM 10 --------------------------------------------
    Route::get('/schoolform10/index', 'Form10Controller@index');
    Route::get('/schoolform10/getrecordselem', 'Form10Controller@getrecords_elem');
    Route::get('/schoolform10/getrecordsjunior', 'Form10Controller@getrecords_jhs');
    Route::get('/schoolform10/getrecordssenior', 'Form10Controller@getrecords_shs');
    Route::post('/schoolform10/submitnewform', 'Form10Controller@submitnewform');
    Route::post('/schoolform10/updateeligibility', 'Form10Controller@eligibility_update');
    Route::post('/schoolform10/updateform', 'Form10Controller@updateform');
    Route::post('/schoolform10/updateattendance', 'Form10Controller@updateattendance');
    Route::post('/schoolform10/updatesigneachsem', 'Form10Controller@updatesigneachsem');
    Route::get('/schoolform10/deleterecord', 'Form10Controller@record_delete');
    Route::get('/schoolform10/updateremedialheader', 'Form10Controller@remedial_updateheader');
    Route::get('/schoolform10/addremedial', 'Form10Controller@remedial_add');
    Route::get('/schoolform10/editremedial', 'Form10Controller@remedial_edit');
    Route::get('/schoolform10/deleteremedial', 'Form10Controller@remedial_delete');
    Route::post('/schoolform10/certification', 'Form10Controller@certification');
    Route::post('/schoolform10/addsubjgradesinauto', 'Form10Controller@addsubjgradesinauto');
    Route::post('/schoolform10/updatesubjgradesinauto', 'Form10Controller@updatesubjgradesinauto');
    Route::get('/schoolform10/deletesubjgradesinauto', 'Form10Controller@deletesubjgradesinauto');
    Route::post('/schoolform10/addinauto', 'Form10Controller@reportsschoolform10addinauto');
    Route::post('/schoolform10/editinauto', 'Form10Controller@reportsschoolform10editinauto');
    Route::post('/schoolform10/addsubjgradesinauto', 'Form10Controller@addsubjgradesinauto');
});
Route::group(['middleware' => ['auth', 'web']], function () {
    // Route::get('/overtime/{id}', 'EmployeeOvertimeController@overtime');
    Route::get('/applyleave/{id}', 'EmployeeLeavesController@leave');

    Route::get('/applyovertimedashboard/{id}', 'EmployeeOvertimeController@applyovertimedashboard');
    Route::post('/applyovertimerequest', 'EmployeeOvertimeController@applyovertimerequest');
    Route::get('/applyovertimeupdate/{id}', 'EmployeeOvertimeController@applyovertimeupdate');

    Route::get('/employeedailytimerecord/{id}', 'EmployeeDailyTimeRecordController@employeedailytimerecord');
    Route::get('/employeepayrolldetails', 'EmployeePayrollHistoryController@employeepayrolldetails');


    Route::get('/administrator/schoolfolders', 'SchoolFilesController@index');
    Route::get('/administrator/addfolder', 'SchoolFilesController@addfolder');
    Route::get('/administrator/folderview', 'SchoolFilesController@folderview');
    Route::post('/administrator/addfiles', 'SchoolFilesController@addfiles');
    Route::post('/administrator/media', 'SchoolFilesController@storeMedia')
        ->name('projects.storeMedia');
    Route::get('/administrator/updatevisibleto', 'SchoolFilesController@updatevisibility');
    Route::get('/administrator/updatefiletype', 'SchoolFilesController@updatefiletype');
    Route::get('/administrator/updatefoldername', 'SchoolFilesController@updatefoldername');
    Route::get('/administrator/deletefolder', 'SchoolFilesController@deletefolder');
    Route::get('/administrator/updatefoldercolor', 'SchoolFilesController@updatefoldercolor');
    Route::get('/administrator/deletefile', 'SchoolFilesController@deletefile');
    Route::get('/administrator/downloadfile', 'SchoolFilesController@downloadfile');
    Route::get('/administrator/updatevisibilitytype', 'SchoolFilesController@updatevisibilitytype');
    Route::get('/administrator/removeaudience', 'SchoolFilesController@removeaudience');
    Route::get('/administrator/updatefilestatus', 'SchoolFilesController@updatefilestatus');
    Route::get('/administrator/whocanupload', 'SchoolFilesController@whocanupload');
    Route::get('/administrator/whocanuploadget', 'SchoolFilesController@whocanuploadget');
    Route::get('/administrator/whocanuploadgetusers', 'SchoolFilesController@whocanuploadgetusers');
    Route::get('/administrator/whocanuploadsubmit', 'SchoolFilesController@whocanuploadsubmit');

    // ------------------------- Virtual Classrooms ------------------------
    Route::get('/virtualclassroomindex', 'TeacherControllers\VirtualClassroomController@index');
    Route::get('/virtualclassroomcheckname', 'TeacherControllers\VirtualClassroomController@checkname');
    Route::get('/virtualclassroomgeneratepassword', 'TeacherControllers\VirtualClassroomController@getpassword');
    Route::get('/virtualclassroomvisit', 'TeacherControllers\VirtualClassroomController@visit');
    Route::get('/virtualclassroom/{id}', 'TeacherControllers\VirtualClassroomController@view');
    // Classroomview
    Route::post('/virtualclassroomaddfiles/{id}', 'TeacherControllers\VirtualClassroomController@addfiles');
    Route::get('/virtualclassroomdeleteattachment', 'TeacherControllers\VirtualClassroomController@deleteattachment');
    Route::post('/virtualclassroomcreateassignment', 'TeacherControllers\VirtualClassroomController@createassignment');
    Route::get('/virtualclassroomeditassignment', 'TeacherControllers\VirtualClassroomController@editclassassignment');
    Route::get('/virtualclassroomdeleteassignment', 'TeacherControllers\VirtualClassroomController@deleteassignment');
    Route::get('/virtualclassroomaddstudent', 'TeacherControllers\VirtualClassroomController@addstudent');
    Route::get('/virtualclassroomdeletestudent', 'TeacherControllers\VirtualClassroomController@deletestudent');
    Route::post('/virtualclassroomsubmitassignment', 'TeacherControllers\VirtualClassroomController@submitassignment');
    Route::get('/virtualclassroomdeleteturnedin', 'TeacherControllers\VirtualClassroomController@deleteturnedin');
    Route::get('/virtualclassroomscore/{action}', 'TeacherControllers\VirtualClassroomController@score');

    Route::get('/virtualclassrooms/call', 'TeacherControllers\VirtualClassroomController@call');
    Route::get('/virtualclassrooms/closevirtualclassroom', function () {
        return view('closebrowser');
    });
    //-------------------------- Online Messages ----------------------------
    // Route::get('/messagesindex','MessageController@index');
    // Route::get('/messages/loadmessages','MessageController@loadmessages');
    // Route::get('/messages/sendmessage','MessageController@sendmessage');
    Route::get('/gradesmasterlist', 'GradesMasterlistController@pdf');


    Route::get('/registar/schoolforms/index', 'RegistrarControllers\ReportsController@index');
    // ------------------------- SCHOOL FORM 10 --------------------------------------------
    Route::get('/reports_schoolform10/index', 'RegistrarControllers\FormReportsController@reportsschoolform10index');
    Route::get('/reports_schoolform10/getgrades', 'RegistrarControllers\FormReportsController@getgrades');
    Route::get('/reports_schoolform10/selectacadprog', 'RegistrarControllers\FormReportsController@reportsschoolform10selectacadprog');
    Route::get('/reports_schoolform10/view', 'RegistrarControllers\FormReportsController@reportsschoolform10view');
    Route::get('/reports_schoolform10/getrecordspreschool', 'RegistrarControllers\FormReportsController@reportsschoolform10getrecords_preschool');
    Route::get('/reports_schoolform10/getrecordselem', 'RegistrarControllers\FormReportsController@reportsschoolform10getrecords_elem');
    Route::get('/reports_schoolform10/getrecordsjunior', 'RegistrarControllers\FormReportsController@reportsschoolform10getrecords_junior');
    Route::get('/reports_schoolform10/getrecordssenior', 'RegistrarControllers\FormReportsController@reportsschoolform10getrecords_senior');


    Route::get('/reports_schoolform10v2/index', 'Form10Controller@index');
    Route::get('/reports_schoolform10v2/getrecords', 'Form10Controller@getrecords');
    Route::get('/reports_schoolform10v2/addnewrecord', 'Form10Controller@addnewrecord');
    Route::get('/reports_schoolform10v2/addnewheader', 'Form10Controller@addnewheader');
    Route::get('/reports_schoolform10v2/updategrades', 'Form10Controller@updategrades');
    Route::get('/reports_schoolform10v2/deletesubject', 'Form10Controller@deletesubject');
    Route::get('/reports_schoolform10v2/updatesubject', 'Form10Controller@updatesubject');
    Route::get('/reports_schoolform10v2/gettemplate', 'Form10Controller@gettemplate');
    Route::get('/reports_schoolform10v2/template', 'Form10Controller@template');
    Route::post('/reports_schoolform10v2/fileupload', 'Form10Controller@fileupload');
    Route::get('/reports_schoolform10v2/uploadrecord', 'Form10Controller@uploadrecord');


    Route::get('/reports_schoolform10v3/index', 'Form10Controller@index');
    Route::get('/reports_schoolform10v3/getrecordselem', 'Form10Controller@getrecords_v3elem');
    Route::get('/reports_schoolform10v3/getrecordsjunior', 'Form10Controller@getrecords_v3junior');
    Route::post('/reports_schoolform10v3/submitnewform', 'Form10Controller@submitnewform');
    Route::post('/reports_schoolform10v3/updateform', 'Form10Controller@updateform');
    Route::post('/reports_schoolform10v3/updatesigneachsem', 'Form10Controller@updatesigneachsem');

    Route::get('/reports_schoolform10/updateeligibility', 'RegistrarControllers\FormReportsController@reportsschoolform10updateeligibility');
    Route::get('/reports_schoolform10/submitfooter', 'RegistrarControllers\FormReportsController@reportsschoolform10updatefooter');
    Route::get('/reports_schoolform10/getaddnew', 'RegistrarControllers\FormReportsController@reportsschoolform10getaddnew');
    // Route::get('/reports_schoolform10/getsubjects', 'RegistrarControllers\FormReportsController@reportsschoolform10getsubjects');
    Route::get('/reports_schoolform10/submitnewform', 'RegistrarControllers\FormReportsController@reportsschoolform10submitnewform');
    Route::post('/reports_schoolform10/updateform', 'RegistrarControllers\FormReportsController@reportsschoolform10updateform');
    Route::get('/reports_schoolform10/deleterecord', 'RegistrarControllers\FormReportsController@reportsschoolform10deleterecord');
    Route::get('/reports_schoolform10/updateattendance', 'RegistrarControllers\FormReportsController@reportsschoolform10updateattendance');

    Route::get('/reports_schoolform10/getinfo', 'RegistrarControllers\FormReportsController@reportsschoolform10getinfo');
    Route::get('/reports_schoolform10/updateinfo', 'RegistrarControllers\FormReportsController@reportsschoolform10updateinfo');

    Route::get('/reports_schoolform10/getgradesedit', 'RegistrarControllers\FormReportsController@reportsschoolform10getgradesedit');
    Route::get('/reports_schoolform10/deletesubjectgrades', 'RegistrarControllers\FormReportsController@reportsschoolform10deletesubjectgrades');
    Route::get('/reports_schoolform10/editsubjectgrades', 'RegistrarControllers\FormReportsController@reportsschoolform10editsubjectgrades');
    Route::get('/reports_schoolform10/updateinmapeh', 'RegistrarControllers\FormReportsController@reportsschoolform10updateinmapeh');
    Route::get('/reports_schoolform10/updateintle', 'RegistrarControllers\FormReportsController@reportsschoolform10updateintle');
    Route::get('/reports_schoolform10/addsubjectgrades', 'RegistrarControllers\FormReportsController@reportsschoolform10addsubjectgrades');

    Route::get('/reports_schoolform10/getremedialclass', 'RegistrarControllers\FormReportsController@reportsschoolform10getremedialclass');
    Route::get('/reports_schoolform10/updateremedialheader', 'RegistrarControllers\FormReportsController@reportsschoolform10updateremedialheader');
    Route::get('/reports_schoolform10/addremedial', 'RegistrarControllers\FormReportsController@reportsschoolform10addremedial');
    Route::get('/reports_schoolform10/editremedial', 'RegistrarControllers\FormReportsController@reportsschoolform10editremedial');
    Route::get('/reports_schoolform10/deleteremedial', 'RegistrarControllers\FormReportsController@reportsschoolform10deleteremedial');

    Route::get('/reports_schoolform10/getsubjectsperquarter', 'RegistrarControllers\FormReportsController@reportsschoolform10getsubjectsperquarter');
    Route::get('/reports_schoolform10/submitquartergrades', 'RegistrarControllers\FormReportsController@reportsschoolform10submitquartergrades');

    Route::get('/reports_schoolform10/addinauto', 'RegistrarControllers\FormReportsController@reportsschoolform10addinauto');
    Route::get('/reports_schoolform10/editinauto', 'RegistrarControllers\FormReportsController@reportsschoolform10editinauto');
    Route::get('/reports_schoolform10/addsubjgradesinauto', 'RegistrarControllers\FormReportsController@addsubjgradesinauto');
    Route::get('/reports_schoolform10/updatesubjgradesinauto', 'RegistrarControllers\FormReportsController@updatesubjgradesinauto');
    Route::get('/reports_schoolform10/deletesubjgradesinauto', 'RegistrarControllers\FormReportsController@deletesubjgradesinauto');

    // ------------------------- // //--------------------------------------------

    // SCHOOL FORM 2

    Route::get('/forms/form2', 'TeacherControllers\TeacherFormController@form2');
    Route::get('/forms/form2shsindex', 'TeacherControllers\TeacherFormController@form2shsindex');
    Route::get('/forms/form2enrollmentmonth', 'TeacherControllers\TeacherFormController@enrollmentmonth');
    Route::get('/forms/form2summarytable', 'TeacherControllers\TeacherFormController@form2summarytable');


    // SCHOOL FORM 5
    Route::get('/forms/form5', 'TeacherControllers\TeacherFormController@form5');
    Route::get('/forms/form5aindex', 'TeacherControllers\ReportsSHSController@form5aindex');
    Route::get('/forms/form5a', 'TeacherControllers\ReportsSHSController@form5a');
    Route::get('/forms/form5bindex', 'TeacherControllers\ReportsSHSController@form5bindex');
    Route::get('/forms/form5b', 'TeacherControllers\ReportsSHSController@form5b');


    // ---------------------- TOR --------------------------------
    Route::get('/schoolform/tor/index', 'RegistrarControllers\TORController@index');
    Route::get('/schoolform/tor/getrecords', 'RegistrarControllers\TORController@getrecords')->name('torgetrecords');
    Route::get('/schoolform/tor/savedetail', 'RegistrarControllers\TORController@savedetail')->name('torsavedetail');
    Route::get('/schoolform/tor/addnewrecord', 'RegistrarControllers\TORController@addnewrecord')->name('toraddnewrecord');
    Route::get('/schoolform/tor/addnewdata', 'RegistrarControllers\TORController@addnewdata')->name('toraddnewdata');
    Route::get('/schoolform/tor/editsubjgrade', 'RegistrarControllers\TORController@editsubjgrade')->name('toreditsubjgrade');
    Route::get('/schoolform/tor/deletesubjgrade', 'RegistrarControllers\TORController@deletesubjgrade')->name('tordeletesubjgrade');
    Route::get('/schoolform/tor/deleterecord', 'RegistrarControllers\TORController@deleterecord')->name('tordeleterecord');
    Route::get('/schoolform/tor/exporttopdf', 'RegistrarControllers\TORController@exporttopdf');

    //------------------------------------------------------------


});

Route::group(['scheme' => 'https'], function () {
    // Route::get(...)->name(...);
});

Route::get('/getpayables/{studentstatus}', 'RegistrarControllers\PreRegistrationController@getpayables');
Route::get('/storeprereg/{studentstatus}', 'RegistrarControllers\PreRegistrationController@storeprereg');

Route::get('/coderecovery', function () {
    return view('coderecovery');
});





Route::get('/prereg/{studentstatus}', 'RegistrarControllers\PreRegistrationController@prereg');
Route::get('/preregsenior', 'RegistrarControllers\PreRegistrationController@senior');
Route::get('/prereg/questions/{gradelevel}', 'RegistrarControllers\PreRegistrationController@preregquestion');



Route::get('/early/enrollment/submit', 'RegistrarControllers\PreRegistrationControllerV2@early_enrollment_submit');
Route::get('/pre/enrollment/submit', 'RegistrarControllers\PreRegistrationControllerV2@pre_enrollment_submit');



Route::get('/studentUserDebugger', 'DebuggerController@studentUserDebugger');
Route::get('/fixAccountConflict', 'DebuggerController@fixAccountConflict');





Route::get('/prereg/inquiry/form', function () {
    return view('othertransactions.prereginquiry.prereginquirydetails');

});





Route::get('/backupdb', 'DatabaseController@backup');
Route::get('/database/import/view', 'DatabaseController@dbview');
Route::post('/database/import/import', 'DatabaseController@dbimport');

Route::middleware(['isDefaultPass'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');

});

Route::middleware(['auth', 'isDean'])->group(function () {

    Route::get('dean/courses/', 'DeanControllers\DeanController@viewcourses')->name('dean.courses');
    Route::get('dean/prospectus/{course}', 'DeanControllers\DeanController@viewprospectus')->name('dean.prospectus');
    Route::get('dean/faculties', 'DeanControllers\DeanController@viewfaculties')->name('dean.faculties');
    Route::get('dean/store/prospectus', 'DeanControllers\DeanController@storeprospectus')->name('dean.store.prospectus');
    Route::get('dean/remove/prospectussubject/{subject}', 'DeanControllers\DeanController@removeprospectussubject')->name('dean.remove.prospectussubject');
    // Route::get('dean/store/sections', 'DeanControllers\DeanController@storeprospectus')->name('dean.store.prospectus');


    Route::get('dean/view/grades', 'DeanControllers\DeanController@viewgrades');
    Route::get('dean/view/all/grades', 'DeanControllers\DeanController@viewallgrades');


    //college subjects
    Route::get('dean/collegesubjects/list', 'DeanControllers\DeanController@college_subject_list');
    //college subjects

    Route::view('dean/preregistered', 'deanportal.pages.preregistered_student');
    Route::get('dean/preregistered/students', 'DeanControllers\StudentEvaluation@student_list');
    Route::get('/dean/preregistered/students/update/course', 'DeanControllers\StudentEvaluation@update_student_course');
    Route::get('/dean/preregistered/students/mark/interviewed', 'DeanControllers\StudentEvaluation@mark_as_interviewed');




});

Route::get('dean/sections', 'DeanControllers\DeanController@viewsections')->name('dean.sections');

//chairperson
Route::get('chairperson/sections/', 'CPControllers\CPController@viewsections');
Route::get('chairperson/courses/', 'CPControllers\CPController@chairperson_courses');
Route::get('chairperson/courses/curriculum', 'CPControllers\CPController@get_curriculum');
Route::get('chairperson/prospectus/', 'CPControllers\CPController@viewprospectus');
Route::get('chairperson/sections/create', 'CPControllers\CPController@storesections');
Route::get('chairperson/sections/createv2', 'CPControllers\CPController@store_section');
Route::get('chairperson/sections/show/{section}', 'CPControllers\CPController@showection');
//Route::get('chairperson/sections/remove/{section}', 'CPControllers\CPController@removesection');
//cp_new


Route::get('/prinsf9print/{id}', 'PrincipalControllers\DynamicPDFController@sf9pdf');


Route::middleware(['auth'])->group(function () {

    Route::get('corprintingblade', 'ScholarshipCoor\ScholarshipCoorController@index');
    Route::get('studentforprintingtable', 'ScholarshipCoor\ScholarshipCoorController@studentforprintingtable');
    Route::get('printcor/{id}', 'ScholarshipCoor\ScholarshipCoorController@printcor');
    Route::get('printcor/{id}', 'ScholarshipCoor\ScholarshipCoorController@printcor');
    Route::get('collegeStudentMasterlist/', 'ScholarshipCoor\ScholarshipCoorController@college_student_masterlist');
    Route::get('collge/report/enrollment', 'CollegeControllers\CollegeReportController@studentsubjects');
    Route::get('signatory', 'CollegeControllers\CollegeReportController@signatory');

    //promotional report
    Route::get('/sc/report/promotional', 'ScholarshipCoor\ScholarshipCoorController@promotional_report');
    Route::get('/sc/report/promotional/generate', 'ScholarshipCoor\ScholarshipCoorController@generate_promotional_report');
    Route::get('/sc/report/promotional/excel', 'ScholarshipCoor\ScholarshipCoorController@generate_promotional_excel');
    Route::get('/sc/report/beginning/excel', 'ScholarshipCoor\ScholarshipCoorController@generate_beginning_excel');
});

Route::middleware(['auth', 'isCP'])->group(function () {

    Route::get('chairperson/scheduling/{location}', 'CPControllers\CPController@scheduling');
    Route::get('chairperson/scheduling/show/{studentid}/{studentname}', 'CPControllers\CPController@studentscheduling');
    Route::get('chairperson/scheduling/sectscshed/{section}', 'CPControllers\CPController@sectscshed');
    Route::get('chairperson/schedule/student/{student}/{section}', 'CPControllers\CPController@storestudentsched');
    Route::get('chairperson/teacher/subject/{subject}', 'CPControllers\CPController@teachersubject');
    Route::get('chairperson/scheddetail/create/{section}', 'CPControllers\CPController@createscheddetail');
    Route::get('chairperson/addtosched/{schedetail}/section/{section}', 'CPControllers\CPController@addtoSched');
    Route::get('chairperson/checkifpassed/{schedetail}/{studid}', 'CPControllers\CPController@checkifpassed');
    Route::get('chairperson/getsched/allcollege/{subject}', 'CPControllers\CPController@allcollegesub');
    Route::get('/chairperson/update/student/course/{id}/{course}', 'CPControllers\CPController@udpatestudencourse');
    Route::get('/chairperson/remove/schedule/{id}', 'CPControllers\CPController@removescheddetail');
    ;
    Route::get('/chairperson/update/student/curriculum', 'CPControllers\CPController@studentcurriculum');

    Route::post('unloadedSubjects', 'CPControllers\CPController@unloadedsubjects');
    Route::post('loadSubjetsToSection', 'CPControllers\CPController@loadSubjetsToSection');

    //final_grade_college
    Route::get('chairperson/student/grades', 'CPControllers\CPController@chairperson_student_grades');
    Route::get('chairperson/section/subject', 'CPControllers\CPController@chairperson_section_subjects');
    Route::get('college/student/grade/status/approve', 'CPControllers\CPController@approve_grade_status');
    Route::get('college/student/grade/status/pending', 'CPControllers\CPController@pending_grade_status');
    Route::get('college/student/grade/status/post', 'CPControllers\CPController@post_grade_status');
    //final_grade_college

    //college_sections
    Route::get('college/chairpseron/sections', 'CPControllers\CPController@chairperson_sections');
    Route::get('college/chairpseron/sections/update', 'CPControllers\CPController@update_section');
    Route::get('college/chairpseron/sections/remove', 'CPControllers\CPController@remove_section');
    Route::get('college/chairpseron/addinstructor', 'CPControllers\CPController@add_instructor');
    Route::get('college/section/add/subject', 'CPControllers\CPController@add_section_subject');
    Route::get('college/section/remove/schedule', 'CPControllers\CPController@removeSched');
    //college_sections

    //subject_coding
    Route::get('chairperson/schedule/coding', 'CPControllers\CPControllerV2@schedule_coding');
    Route::get('chairperson/schedule/coding/list', 'CPControllers\CPControllerV2@schedule_coding_list');
    Route::get('chairperson/schedule/coding/create', 'CPControllers\CPControllerV2@schedule_coding_create');
    Route::get('chairperson/schedule/coding/update', 'CPControllers\CPControllerV2@schedule_coding_update');
    Route::get('chairperson/schedule/coding/delete', 'CPControllers\CPControllerV2@schedule_coding_delete');
    Route::get('chairperson/schedule/coding/update/cl', 'CPControllers\CPControllerV2@schedule_coding_update_cl');
    //subject_coding

    //subject_coding details
    Route::get('chairperson/schedule/coding/details/create', 'CPControllers\CPControllerV2@schedule_coding_details_create');
    Route::get('chairperson/schedule/coding/details/update', 'CPControllers\CPControllerV2@schedule_coding_details_update');
    Route::get('chairperson/schedule/coding/details/delete', 'CPControllers\CPControllerV2@schedule_coding_details_delete');
    //subject_coding details

    //subject_coding details
    Route::get('chairperson/section/schedulecoding/list', 'CPControllers\CPControllerV2@section_schedulecoding_list');
    Route::get('chairperson/section/schedulecoding/create', 'CPControllers\CPControllerV2@section_schedulecoding_create');
    Route::get('chairperson/section/schedulecoding/delete', 'CPControllers\CPControllerV2@section_schedulecoding_delete');

    //subject_coding details

    //college subjects
    Route::get('chairperson/collegesubjects/list', 'CPControllers\CPControllerV2@chairperson_collegesubjects_list');
    //college subjects
});



//college_data
Route::get('/chairpersoninfo', 'CPControllers\CPController@chairpersoninfo');
Route::get('/college/subjects', 'CPControllers\CPController@college_subjects');
Route::get('/college/techer', 'CPControllers\CPController@college_teacher');
Route::get('/subject/schedule/blade', 'CPControllers\CPController@subject_schedule_blade');
Route::get('/subject/schedule', 'CPControllers\CPController@subject_schedule');
//college_data

Route::get('tabletest', function () {
    return view('table');
});

Route::get('preregcode', function () {
    return view('registrar.preregistrationgetcode');
});

Route::get('teacher/get/grades/{section}/{quarter}', 'CPControllers\CPController@getGrades');
Route::get('teacher/update/grades', 'CPControllers\CPController@updategrades');

Route::get('teacher/update/igfg', 'CPControllers\CPController@updateigfg');

Route::get('teacher/update/hps', 'CPControllers\CPController@updatehps');



Auth::routes();



Route::get('trycrop', function () {

    return view('trycrop');

});



Route::get('hasher', function () {

    return Hash::make('123456789');

});

Route::get('crypt/{value}', function ($value) {

    return Crypt::encrypt($value);

});

Route::get('gettime', function () {

    date_default_timezone_set('Asia/Manila');
    $date = date('Y-m-d H:i:s');

    return \Carbon\Carbon::now('Asia/Manila');

});



Route::middleware(['checkModule:preregistration', 'guest'])->group(function () {

    Route::get('preregv2', function () {

        if (config('app.type') == 'Online' || config('app.type') != 'Offline') {

            // return "Pre enrollment is not yet available";

            $gradelevel = DB::table('gradelevel')->where('deleted', '0')->orderBy('sortid')->get();

            return view('preregistrationV2.preregistrationv3')->with('gradelevel', $gradelevel);

        } else {

            return redirect('/');

        }

    });

    Route::get('/preregistration/get/qcode/{qcode}/{name}/{status}', 'RegistrarControllers\PreRegistrationControllerV2@getqcode');
    Route::get('/preregistration/get/preenrollmentinfo/{name}/{gradelevel}/{status}', 'RegistrarControllers\PreRegistrationControllerV2@preenrollmentinfo');
    Route::post('preregistration/submit', 'RegistrarControllers\PreRegistrationControllerV2@submitPrereg');

});



//general blades






Route::middleware(['auth', 'isCT', 'isDefaultPass'])->group(function () {

    Route::get('schedule', 'CTController\CTController@schedule');

    Route::get('college/teacher/grading', 'CTController\CTController@ctgrading');

    Route::get('college/teacher/quartersetup', function () {
        return view('ctportal.pages.setup.quartersetup');
    });

    Route::get('/college/teacher/store/grades', 'CTController\CTController@storegrades');
    Route::get('/college/teacher/store/gradesv2', 'CTController\CTController@storegradev2');
    Route::get('/college/teacher/update/hps', 'CTController\CTController@updatehpsv2');

    Route::post('college/teacher/createsetup', 'CTController\CTController@createSetup');

    Route::get('/college/teacher/gradesetup', 'CTController\CTController@gradesetup');


    Route::get('college/teacher/update/student/gradesdetail', 'CTController\CTController@udpatestudentgd');

    Route::get('college/teacher/gradesetuptable', 'CTController\CTController@gradesetuptable');

    // VIRTUAL CLASSROOM
    Route::get('college/teacher/vc/index', 'TeacherControllers\VirtualClassroomController@index');
    Route::get('college/teacher/vc/visit', 'TeacherControllers\VirtualClassroomController@visit');
    Route::get('college/teacher/vc/getstudents', 'TeacherControllers\VirtualClassroomController@getstudents');
    Route::get('college/teacher/vc/getassignments', 'TeacherControllers\VirtualClassroomController@getassignments');
    Route::post('college/teacher/vc/publishass', 'TeacherControllers\VirtualClassroomController@createassignment');
    Route::get('college/teacher/vc/getassignmentinfo', 'TeacherControllers\VirtualClassroomController@getassignmentinfo');
    Route::post('college/teacher/vc/editass', 'TeacherControllers\VirtualClassroomController@editclassassignment');
    Route::get('college/teacher/vc/call/{id}', 'TeacherControllers\VirtualClassroomController@view');


    Route::get('college/student/info', 'CTController\CTController@studentinformation');
    Route::get('college/teacher/sched', 'CTController\CTController@classsched');


    //final_grade_college
    Route::get('college/assignedsubj', 'CTController\CTController@get_assigned_subj');
    Route::get('college/student/grade/save', 'CTController\CTController@save_student_grade');
    Route::get('college/teacher/gradesubmission', 'CTController\CTController@grade_submission');
    Route::get('college/student/grade/status/submit', 'CTController\CTController@submit_grade_status');
    //final_grade_college

    //schedule
    Route::get('college/teacher/schedule', 'CTController\CTController@ci_schedule');
    //schedule

});

//final_grade_college
Route::get('college/subject/students', 'CTController\CTController@subject_students');
Route::get('college/student/grade/status', 'CTController\CTController@get_grade_status');
//final_grade_college

Route::post('subjecttermsetup', 'CTController\CTController@viewsubjecttermsetup');

Route::get('tablemaxid/{id}/{count}', 'SyncController@synccheckreturn');
Route::get('tableupdate/{id}/{date}', 'SyncController@syncupdatereturn');
Route::get('tabledeleted/{id}/{date}', 'SyncController@syncdeletedreturn');

Route::get('checktargetmaxtable/{id}', 'SyncController@checktargetmaxtable');

Route::get('insertdatatotable', 'SyncController@insertdatatotable')->name('insertdatatotable');
Route::get('updatetargettable', 'SyncController@updatetargettable')->name('updatetargettable');
Route::get('deletetargettable', 'SyncController@deletetargettable')->name('deletetargettable');
Route::get('querylogsToCloud', 'SyncController@querylogsToCloud')->name('querylogsToCloud');
Route::get('querylogstoLocal', 'SyncController@querylogstoLocal')->name('querylogstoLocal');
Route::get('updatelogid', 'SyncController@updatelogid')->name('updatelogid');
Route::get('getTableFields/{id}', 'SyncController@getTableFields')->name('getTableFields');

Route::get('getOfflinerefIdMax/{tablename}', 'SyncController@getOfflinerefIdMax')->name('getOfflinerefIdMax');

Route::get('fetcthdatafromsource', 'SyncController@fetcthdatafromsource');

Route::get('cloudtesting', 'SyncController@cloudtesting');
Route::get('localCheckConnection', 'SyncController@localCheckConnection');

//--------------API ESSENTIEL SMS BUNKER --------------------------------//

Route::get('/apismsgetsmsbunker', 'SMSBunkerController@apismsgetsmsbunker')->name('apismsgetsmsbunker');
Route::get('/apismsgetsmsbunker/apismsgetok/{status}/{id1}/{id2}', 'SMSBunkerController@apismsgetok')->name('apismsgetok');

Route::get('/apismsblast', 'SMSBunkerController@apismsblast')->name('apismsblast');
Route::get('/apismsblast/apismsblastok/{status}/{id1}/{id2}', 'SMSBunkerController@apismsblastok')->name('apismsblastok');

//--------------API ESSENTIEL SMS BUNKER --------------------------------//

//--------------API UPDATE LEDGER --------------------------------//
Route::middleware(['cors'])->group(function () {
    Route::get('/api_updateledger', 'FinanceControllers\UtilityController@resetpayment_v3');
});
//--------------API UPDATE LEDGER --------------------------------//



Route::get('/updateStudentInfo', 'ParentControllers\ParentsController@updateStudentInfo')->name('updateStudentInfo');

Route::get('/viewLoginUsers', 'SchoolMonitoring\SchoolMonitoring@viewLoginUsers')->name('viewLoginUsers');

Route::get('/cklmsgenerateaccount', 'CKLMSControllers\GenerateAccountsController@index');


//school monitoring
Route::middleware(['cors'])->group(function () {
    Route::get('/monitoring/textblast', 'SchoolMonitoring\SchoolMonitoring@textblast');
    Route::get('/monitoring/synchonization', 'SchoolMonitoring\SchoolMonitoring@synchonization');
    Route::get('/monitoring/tables', 'SchoolMonitoring\SchoolMonitoring@tables');
    Route::get('/monitoring/tablecount', 'SchoolMonitoring\SchoolMonitoring@table_count');
    Route::get('/monitoring/table/data', 'SchoolMonitoring\SchoolMonitoring@table_data');
    Route::get('/monitoring/table/data/updated', 'SchoolMonitoring\SchoolMonitoring@table_data_update');
    Route::get('/monitoring/table/data/deleted', 'SchoolMonitoring\SchoolMonitoring@table_data_deleted');
    Route::get('/monitoring/get/picurl', 'SchoolMonitoring\SchoolMonitoring@get_pic_url');
    Route::get('/monitoring/get/lastdatesync', 'SchoolMonitoring\SchoolMonitoring@last_date_sync');
    Route::get('/monitoring/get/updatelogs', 'SchoolMonitoring\SchoolMonitoring@updatelogs');
});
//school monitoring

//synchronization v2
Route::middleware(['cors'])->group(function () {
    Route::get('/synchornization/insert', 'SyncController\SyncControllerV2@synccreate');
    Route::get('/synchornization/update', 'SyncController\SyncControllerV2@syncupdate');
    Route::get('/synchornization/delete', 'SyncController\SyncControllerV2@syncdelete');
    Route::get('/synchornization/insert/synclogs', 'SyncController\SyncControllerV2@insertsynclogs');
    Route::get('/synchornization/process/updatelogs', 'SyncController\SyncControllerV2@process_updatelogs');
    Route::get('/synchornization/process/updatelogs/status', 'SyncController\SyncControllerV2@process_updatelogs_status');
});
//synchronization v2









//evaluation
Route::view('/superadmin/student/grade/evaluation', 'superadmin.pages.student_grade_evalution');
Route::get('/superadmin/student/grade/evaluation/gradesdetail', 'SuperAdminController\StudentGradeEvaluation@grades_detail');
Route::get('/superadmin/student/grade/evaluation/students', 'SuperAdminController\StudentGradeEvaluation@students');
Route::get('/superadmin/student/grade/evaluation/sf9', 'SuperAdminController\StudentGradeEvaluation@sf9');
Route::get('/superadmin/student/grade/evaluation/sf9/grades', 'SuperAdminController\StudentGradeEvaluation@sf9_grades_request');
Route::get('/superadmin/student/grade/evaluation/subjsetup', 'SuperAdminController\StudentGradeEvaluation@subjsetup');
//evluation


Route::view('/view/defaultlayout', 'default_layout.pages.home');

//next version

Route::middleware(['auth'])->group(function () {
    Route::view('setup/document', 'superadmin.pages.setup.documentssetup');
    Route::view('/superadmin/setup/document/logs', 'SuperAdminController\DocumentsController@logs');
    Route::get('/superadmin/setup/document/create', 'SuperAdminController\DocumentsController@create');
    Route::get('/superadmin/setup/document/update', 'SuperAdminController\DocumentsController@update');
    Route::get('/superadmin/setup/document/delete', 'SuperAdminController\DocumentsController@delete');
    Route::get('/superadmin/setup/document/copy', 'SuperAdminController\DocumentsController@copy');
});

Route::middleware(['auth'])->group(function () {
    Route::view('setup/track', 'registrar.setup.shtrack');
    Route::get('/setup/track/list', 'RegistrarControllers\RegistrarSetupController@list_sh_track');
    Route::get('/setup/track/create', 'RegistrarControllers\RegistrarSetupController@create_sh_track');
    Route::get('/setup/track/update', 'RegistrarControllers\RegistrarSetupController@update_sh_track');
    Route::get('/setup/track/delete', 'RegistrarControllers\RegistrarSetupController@delete_sh_track');
});


Route::middleware(['auth'])->group(function () {
    Route::view('setup/strand', 'registrar.setup.shstrand');
    Route::get('/setup/strand/list', 'RegistrarControllers\RegistrarSetupController@list_sh_strand');
    Route::get('/setup/strand/create', 'RegistrarControllers\RegistrarSetupController@create_sh_strand');
    Route::get('/setup/strand/update', 'RegistrarControllers\RegistrarSetupController@update_sh_strand');
    Route::get('/setup/strand/delete', 'RegistrarControllers\RegistrarSetupController@delete_sh_strand');
});

Route::middleware(['auth'])->group(function () {
    Route::view('setup/course', 'registrar.setup.course');
    Route::get('/setup/course/list', 'RegistrarControllers\RegistrarSetupController@list_course');
    Route::get('/setup/course/select', 'RegistrarControllers\RegistrarSetupController@courses_select');
    Route::get('/setup/course/create', 'RegistrarControllers\RegistrarSetupController@create_course');
    Route::get('/setup/course/update', 'RegistrarControllers\RegistrarSetupController@update_course');
    Route::get('/setup/course/delete', 'RegistrarControllers\RegistrarSetupController@delete_course');
});

Route::middleware(['auth'])->group(function () {
    Route::view('setup/college', 'registrar.setup.college');
    Route::get('/setup/college/list', 'RegistrarControllers\RegistrarSetupController@list_college');
    Route::get('/setup/college/list/select2', 'RegistrarControllers\RegistrarSetupController@colleges_select2');
    Route::get('/setup/college/list/datatable', 'RegistrarControllers\RegistrarSetupController@listdatatable');
    Route::get('/setup/college/create', 'RegistrarControllers\RegistrarSetupController@create_college');
    Route::get('/setup/college/update', 'RegistrarControllers\RegistrarSetupController@update_college');
    Route::get('/setup/college/delete', 'RegistrarControllers\RegistrarSetupController@delete_college');
});




//observed values
Route::middleware(['auth'])->group(function () {

    //setup
    Route::view('/setup/observed/values', 'superadmin.pages.setup.observedvalues');
    Route::get('/superadmin/setup/observed/values/create', 'SuperAdminController\ObservedValuesController@observedvalues_create_ajax');
    Route::get('/superadmin/setup/observed/values/update', 'SuperAdminController\ObservedValuesController@observedvalues_update_ajax');
    Route::get('/superadmin/setup/observed/values/delete', 'SuperAdminController\ObservedValuesController@observedvalues_delete_ajax');
    Route::get('/superadmin/setup/observed/values/list', 'SuperAdminController\ObservedValuesController@observedvalues_list_ajax');
    Route::get('/superadmin/setup/observed/values/copy', 'SuperAdminController\ObservedValuesController@copy_to_ajax');
    Route::get('/superadmin/setup/observed/values/getgradelevel', 'SuperAdminController\ObservedValuesController@get_gradelevel');

    Route::get('/superadmin/setup/ratingvalue/list', 'SuperAdminController\ObservedValuesController@ratingvalue_list_ajax');
    Route::get('/superadmin/setup/ratingvalue/create', 'SuperAdminController\ObservedValuesController@ratingvalue_create_ajax');
    Route::get('/superadmin/setup/ratingvalue/update', 'SuperAdminController\ObservedValuesController@ratingvalue_update_ajax');
    Route::get('/superadmin/setup/ratingvalue/delete', 'SuperAdminController\ObservedValuesController@ratingvalue_delete_ajax');
    //setup

    //grading
    Route::view('/grade/observedvalues', 'teacher.grading.observevalues');
    Route::get('/grade/observedvalues/advisory', 'SuperAdminController\ObservedValuesController@teacher_class');
    Route::get('/grade/observedvalues/advisory/grades', 'SuperAdminController\ObservedValuesController@get_student_grades_ajax');
    Route::get('/grade/observedvalues/advisory/grades/save', 'SuperAdminController\ObservedValuesController@store_grades');
    //grading

});
//observed values


Route::middleware(['auth'])->group(function () {
    Route::view('/reportcard/quarterremarks', 'teacher.grading.quarterremarks');
    Route::get('/reportcard/quarterremarks/advisory', 'SuperAdminController\QuarterRemarksController@teacher_class');
    Route::get('/reportcard/quarterremarks/advisory/grades', 'SuperAdminController\QuarterRemarksController@get_student_grades');
    Route::get('/reportcard/quarterremarks/advisory/grades/save', 'SuperAdminController\QuarterRemarksController@store_grades');
});
//observed values


Route::middleware(['cors'])->group(function () {
    Route::view('/setup/attendance', 'superadmin.pages.setup.schooldays');
    Route::view('/superadmin/attendancproject/setue/logs', 'SuperAdminController\SchoolDaysController@logs');
    Route::get('/superadmin/attendance/create', 'SuperAdminController\SchoolDaysController@create');
    Route::get('/superadmin/attendance/update', 'SuperAdminController\SchoolDaysController@update');
    Route::get('/superadmin/attendance/delete', 'SuperAdminController\SchoolDaysController@delete');
    Route::get('/superadmin/attendance/list', 'SuperAdminController\SchoolDaysController@list');
    Route::get('/superadmin/attendance/getgradelevel', 'SuperAdminController\SchoolDaysController@get_gradelevel');
    Route::get('/superadmin/setup/schooldays/copy', 'SuperAdminController\SchoolDaysController@schooldayscopy');
    Route::get('/superadmin/setup/summary', 'SuperAdminController\SchoolDaysController@attendance_setup_summary');
    Route::get('/superadmin/setup/settoactive', 'SuperAdminController\SchoolDaysController@set_month_to_active');
});


//student promotion
Route::middleware(['auth'])->group(function () {
    Route::view('/student/promotion', 'superadmin.pages.student.promotion');
    Route::get('/superadmin/student/promotion/students', 'SuperAdminController\StudentPromotionController@list');
    Route::get('/superadmin/student/promotion/students/promote', 'SuperAdminController\StudentPromotionController@promote_student');
    Route::get('/superadmin/student/promotion/students/unpromote', 'SuperAdminController\StudentPromotionController@unpromote_student');
});
//student promotion

//scheduling by teacher
Route::middleware(['auth'])->group(function () {
    Route::view('/teacher/profile', 'superadmin.pages.teacher.teacherprofile');
    Route::get('/teacher/profile/list', 'SuperAdminController\TeacherProfileController@teacher_list');
    Route::get('/scheduling/teacher/schedule', 'SuperAdminController\TeacherProfileController@schedule');
    Route::get('/scheduling/teacher/subjects', 'SuperAdminController\TeacherProfileController@subjects');
    Route::get('/scheduling/teacher/subjects/sched', 'SuperAdminController\TeacherProfileController@collegesched_plot');
    Route::get('/scheduling/teacher/add/sched', 'SuperAdminController\TeacherProfileController@college_add_sched');
    Route::get('/scheduling/teacher/remove/sched', 'SuperAdminController\TeacherProfileController@remove_teacher_sched');
    Route::get('/scheduling/teacher/getsections', 'SuperAdminController\TeacherProfileController@getsections');
    Route::get('/scheduling/teacher/getsubjects', 'SuperAdminController\TeacherProfileController@getsubjects');
});
//scheduling by teacher

Route::middleware(['auth'])->group(function () {
    Route::view('/grade/monitoring', 'superadmin.pages.utility.grademonitoring');
    Route::get('api/grade/log/monitoring', 'SuperAdminController\MonitoringController@gradeLogsMonitoring');
    Route::get('api/grade/hps/monitoring', 'SuperAdminController\MonitoringController@gradeHPSMonitoring');
    Route::get('api/grade/detail/monitoring', 'SuperAdminController\MonitoringController@gradeDetailMonitoring');
});


Route::middleware(['auth'])->group(function () {
    Route::view('/student/requirements', 'superadmin.pages.student.studentrequirements');
    Route::get('/student/requirements/students', 'SuperAdminController\SubmittedRequirements@students');
    Route::post('/student/requirements/upload', 'SuperAdminController\SubmittedRequirements@upload');
    Route::get('/student/requirements/downloadall', 'SuperAdminController\SubmittedRequirements@downloadall');
    Route::get('/student/requirements/uploadeddocs', 'SuperAdminController\SubmittedRequirements@uploadeddocs');
    Route::get('/student/requirements/deleteuploadeddocs', 'SuperAdminController\SubmittedRequirements@deleteuploadeddocs');
    // Route::get('api/student/remedialclass/','StudentControllers\EnrollmentInformation@remedial_class');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/sadtr', 'superadmin.pages.student.sadtr');
    Route::get('sadtr/students', 'SuperAdminController\SADTRController@SADTRstudents');
    Route::get('sadtr/attendancelogs', 'SuperAdminController\SADTRController@SADTRattendancelogs');
    Route::get('sadtr/taphistory', 'SuperAdminController\SADTRController@get_taphistory');
    Route::get('sadtr/print', 'SuperAdminController\SADTRController@SADTRprint');
    Route::get('sadtr/print/late', 'SuperAdminController\SADTRController@SADTRLateStudent');
});


//student information
Route::middleware(['auth'])->group(function () {
    Route::view('/student/information', 'superadmin.pages.student.studentinformation');
    Route::get('/superadmin/student/information/all', 'SuperAdminController\StudentInformationController@all_student_ajax');
    Route::get('/superadmin/student/information/info', 'SuperAdminController\StudentInformationController@student_info_ajax');
    Route::get('/superadmin/student/information/enrollment', 'SuperAdminController\StudentInformationController@enrollment_record');
    Route::get('/superadmin/student/information/grades', 'SuperAdminController\StudentInformationController@enrollment_grades');
    Route::get('/superadmin/student/information/grades/transfer', 'SuperAdminController\StudentInformationController@transfer_grades');
});
//student information

//subject setup
Route::middleware(['auth'])->group(function () {
    Route::view('/setup/subject', 'superadmin.pages.setup.subject');
    Route::get('/superadmin/setup/subject/create', 'SuperAdminController\SubjectSetupController@create_ajax')->name('create_aja');
    Route::get('/superadmin/setup/subject/update', 'SuperAdminController\SubjectSetupController@update_ajax')->name('enrollmentsetup_update');
    Route::get('/superadmin/setup/subject/delete', 'SuperAdminController\SubjectSetupController@delete_ajax')->name('enrollmentsetup_delete');
});
//subject setup

//prospectus setup
Route::middleware(['auth'])->group(function () {
    Route::view('/setup/prospectus', 'superadmin.pages.setup.prospectus');
    Route::get('/setup/prospectus/courses', 'SuperAdminController\College\ProspectusSetupController@courses');
    Route::get('/setup/prospectus/subjets/all', 'SuperAdminController\College\ProspectusSetupController@available_subject');
    Route::get('/setup/prospectus/subjets/remove', 'SuperAdminController\College\ProspectusSetupController@subjectremove');
    Route::get('/setup/prospectus/subjets/new', 'SuperAdminController\College\ProspectusSetupController@add_new_subject');
    Route::get('/setup/prospectus/subjets/update', 'SuperAdminController\College\ProspectusSetupController@update_subject');
    Route::get('/setup/prospectus/courses/curriculum', 'SuperAdminController\College\ProspectusSetupController@course_curriculum');
    Route::get('/setup/prospectus/subjects/select', 'SuperAdminController\College\ProspectusSetupController@collegesubject_select');

    Route::get('/setup/prospectus/update/subjgroup', 'SuperAdminController\College\ProspectusSetupController@update_subjectgroup');

    Route::get('/setup/prospectus/courses/print', 'SuperAdminController\College\ProspectusSetupController@printable');

    Route::get('/setup/prospectus/delete', 'SuperAdminController\College\ProspectusSetupController@delete_prospectus');
    Route::get('/setup/prospectus/add', 'SuperAdminController\College\ProspectusSetupController@add_subject_to_prospectus');


    Route::get('/setup/prospectus/courses/curriculum/create', 'SuperAdminController\College\ProspectusSetupController@create_curriculum');
    Route::get('/setup/prospectus/courses/curriculum/update', 'SuperAdminController\College\ProspectusSetupController@update_curriculum');
    Route::get('/setup/prospectus/courses/curriculum/delete', 'SuperAdminController\College\ProspectusSetupController@delete_curriculum');
    Route::get('/setup/prospectus/courses/curriculum/subjects', 'SuperAdminController\College\ProspectusSetupController@curriculum_subjects');
});
//prospectus setup


Route::middleware(['auth'])->group(function () {
    Route::get('/setup/prospectus/subjgroup', 'SuperAdminController\Setup\SubjGroup@subjgroup');
    Route::get('/setup/prospectus/subjgroup/datatable', 'SuperAdminController\Setup\SubjGroup@subjgroup_datatable');
    Route::get('/setup/prospectus/subjgroup/create', 'SuperAdminController\Setup\SubjGroup@subjgroup_create');
    Route::get('/setup/prospectus/subjgroup/update', 'SuperAdminController\Setup\SubjGroup@subjgroup_update');
    Route::get('/setup/prospectus/subjgroup/delete', 'SuperAdminController\Setup\SubjGroup@subjgroup_delete');

});

//section setup
Route::middleware(['auth'])->group(function () {
    Route::view('/setup/sections', 'superadmin.pages.setup.sections');
    Route::get('/section/delete', 'SuperAdminController\SectionsController@delete_section');
    Route::get('/sections/info/list', 'SuperAdminController\SectionsController@list_ajax');
    Route::get('/section/create', 'SuperAdminController\SectionsController@create_ajax');
    Route::get('/section/update', 'SuperAdminController\SectionsController@update_ajax');
    Route::get('/section/detial/update', 'SuperAdminController\SectionsController@update_section_info');

    Route::get('/superadmin/setup/sections/list', 'SuperAdminController\SectionsController@list');

    Route::get('/sections/list', 'SuperAdminController\SectionsController@all_sections');
    Route::get('/sections/teachers', 'SuperAdminController\SectionsController@teachers_list');
    Route::get('/sections/gradelevel', 'SuperAdminController\SectionsController@gradelevel_list');
    Route::get('/section/detail/create', 'SuperAdminController\SectionsController@add_section_detail');
    Route::get('/section/detail/delete', 'SuperAdminController\SectionsController@delete_ajax');
    Route::get('/section/detail/copy', 'SuperAdminController\SectionsController@copy_section');
    Route::get('/section/detail/enrolled', 'SuperAdminController\SectionsController@enrolled_learners');
    Route::get('/sections/info/select', 'SuperAdminController\SectionsController@sctnSelect')->name('sctnSelect');

    Route::get('/section/printable/sid', 'SuperAdminController\SectionsController@print_sid');
});
//section setup

Route::middleware(['auth'])->group(function () {
    Route::view('/admission/setup', 'superadmin.pages.setup.enrollmentsetup');
    //Route::get('enrollmentsetup/list', 'EnrollmentSetupController@list')->name('list');
    Route::get('enrollmentsetup/type/create', 'EnrollmentSetupController@admission_type_create')->name('admission_type_create');
    Route::get('enrollmentsetup/type/update', 'EnrollmentSetupController@admission_type_update')->name('admission_type_update');
    Route::get('enrollmentsetup/type/delete', 'EnrollmentSetupController@admission_type_delete')->name('admission_type_delete');
    Route::get('/enrollmentsetup/create', 'EnrollmentSetupController@create')->name('enrollmentsetup_create');
    Route::get('/enrollmentsetup/update', 'EnrollmentSetupController@update')->name('enrollmentsetup_update');
    Route::get('/enrollmentsetup/update/active', 'EnrollmentSetupController@update_active')->name('enrollmentsetup_update_active');
    Route::get('/enrollmentsetup/update/end', 'EnrollmentSetupController@update_end')->name('enrollmentsetup_end_active');
    Route::get('/enrollmentsetup/delete', 'EnrollmentSetupController@delete')->name('enrollmentsetup_delete');
    Route::get('/enrollmentsetup/getacad', 'EnrollmentSetupController@get_acad')->name('get_acad');
});




//leaf
Route::middleware(['auth'])->group(function () {
    Route::view('/registrar/leaf', 'superadmin.pages.leaf');
    Route::view('/superadmin/leaf', 'superadmin.pages.leaf');
    Route::get('/superadmin/leaf/students', 'SuperAdminController\LEAFController@students_list');
    Route::get('/superadmin/leaf/get_details', 'SuperAdminController\LEAFController@leaf_blade');
    Route::get('/superadmin/leaf/submit', 'SuperAdminController\LEAFController@submit_leaf');
});
//leaf

//school year
Route::middleware(['cors'])->group(function () {
    Route::get('/setup/schoolyear/activatesem', 'SuperAdminController\SYSetupController@activatesem');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/setup/schoolyear/list', 'SuperAdminController\SYSetupController@schoolyear');
    Route::get('/setup/schoolyear/semester', 'SuperAdminController\SYSetupController@semester_list');
    Route::get('/setup/schoolyear/update', 'SuperAdminController\SYSetupController@update_sy');
    Route::get('/setup/schoolyear/create', 'SuperAdminController\SYSetupController@create_sy');
    Route::get('/setup/schoolyear/endsy', 'SuperAdminController\SYSetupController@syEnd');
    Route::get('/setup/schoolyear/activatesy', 'SuperAdminController\SYSetupController@activatesy');
    Route::get('/setup/schoolyear/enrollment', 'SuperAdminController\SYSetupController@enrollment_information');
    Route::view('/setup/schoolyear', 'registrar.setup.schoolyear');
});
//school year



Route::middleware(['auth'])->group(function () {
    //observe values
    Route::view('/setup/observed/values', 'superadmin.pages.setup.observedvalues');
    Route::get('/superadmin/setup/observed/values/create', 'SuperAdminController\ObservedValuesController@observedvalues_create_ajax');
    Route::get('/superadmin/setup/observed/values/update', 'SuperAdminController\ObservedValuesController@observedvalues_update_ajax');
    Route::get('/superadmin/setup/observed/values/delete', 'SuperAdminController\ObservedValuesController@observedvalues_delete_ajax');
    Route::get('/superadmin/setup/observed/values/list', 'SuperAdminController\ObservedValuesController@observedvalues_list_ajax');
    Route::get('/superadmin/setup/observed/values/copy', 'SuperAdminController\ObservedValuesController@copy_to_ajax');
    //observe values

    //rating value
    Route::get('/superadmin/setup/ratingvalue/create', 'SuperAdminController\ObservedValuesController@ratingvalue_create_ajax');
    Route::get('/superadmin/setup/ratingvalue/update', 'SuperAdminController\ObservedValuesController@ratingvalue_update_ajax');
    Route::get('/superadmin/setup/ratingvalue/delete', 'SuperAdminController\ObservedValuesController@ratingvalue_delete_ajax');
    Route::get('/superadmin/setup/ratingvalue/list', 'SuperAdminController\ObservedValuesController@ratingvalue_list_ajax');
    //rating value

});


// ADDED BY EUGZ

Route::middleware(['auth'])->group(function () {

    //deportment report
    Route::get('/grade/deportment-record', 'SuperAdminController\DeportmentRecordController@show');

    Route::get('/grade/deportment-record/search', 'SuperAdminController\DeportmentRecordController@search_deportment')->name('search.deportment');

    Route::get('/grade/deportment-record/grading', 'SuperAdminController\DeportmentRecordController@grading_deportment')->name('grading.deportment');
    Route::get('/grade/deportment-record/transmute', 'SuperAdminController\DeportmentRecordController@transmute_deportment_grade')->name('transmute.grade');

    //hps
    Route::get('/grade/deportment-record/hps', 'SuperAdminController\DeportmentRecordController@hps')->name('deportment.hps');

    //get submit grade
    Route::get('/grade/deportment-record/get-submit-grade', 'SuperAdminController\DeportmentRecordController@get_submit_grade')->name('get.student.submit');
    Route::get('/grade/deportment-record/submit-student-grade', 'SuperAdminController\DeportmentRecordController@submit_student_grade')->name('submit.student.grade');


    //signatory
    Route::get('/grade/deportment-record/add-signatory', 'SuperAdminController\DeportmentRecordController@add_signatory')->name('add.signatory');
    Route::get('/grade/deportment-record/edit-signatory', 'SuperAdminController\DeportmentRecordController@edit_signatory')->name('edit.signatory');
    Route::get('/grade/deportment-record/delete-signatory', 'SuperAdminController\DeportmentRecordController@delete_signatory')->name('delete.signatory');

    //deportment generate pdf

    Route::get('/grade/deportment-record/generate-pdf/{syid}/{sectionid}/{quarter_ID}/{deportment_setupID}', 'SuperAdminController\DeportmentRecordController@generatePDF')->name('generate.pdf');

    Route::get('/grade/deportment-record/generate-excel/{syid}/{sectionid}/{quarter_ID}/{deportment_setupID}', 'SuperAdminController\DeportmentRecordController@generate_excel')->name('generate.excel');



});

Route::get('/superadmin/setup/document/list', 'SuperAdminController\DocumentsController@list');

//subject plot
Route::middleware(['auth'])->group(function () {

    Route::view('/setup/subject/plot', 'superadmin.pages.setup.subjectplot');
    Route::get('/superadmin/setup/subject/plot/list', 'SuperAdminController\SubjectPlotController@list_ajax');
    Route::get('/superadmin/setup/subject/plot/subjects', 'SuperAdminController\SubjectPlotController@all_subjects_ajax');
    Route::get('/superadmin/setup/subject/plot/create', 'SuperAdminController\SubjectPlotController@create_ajax');
    Route::get('/superadmin/setup/subject/plot/update', 'SuperAdminController\SubjectPlotController@update_ajax');
    Route::get('/superadmin/setup/subject/plot/delete', 'SuperAdminController\SubjectPlotController@delete_ajax');
    Route::get('/superadmin/setup/subject/plot/copyto', 'SuperAdminController\SubjectPlotController@copy_to');
    Route::get('/superadmin/setup/subject/plot/getgradelevel', 'SuperAdminController\SubjectPlotController@get_gradelevel');
    Route::get('/superadmin/setup/subject/plot/subjcoor', 'SuperAdminController\SubjectPlotController@get_subjcoor');

    Route::get('/setup/subject/componentpercentage', 'SuperAdminController\SubjectPlotController@get_component_percentage');
    Route::get('/setup/subject/componentpercentage/create', 'SuperAdminController\SubjectPlotController@create_component_percentage');
    Route::get('/setup/subject/componentpercentage/delete', 'SuperAdminController\SubjectPlotController@delete_component_percentage');
    Route::get('/setup/subject/componentpercentage/update', 'SuperAdminController\SubjectPlotController@update_component_percentage');

});
//subject plot




//All API

Route::get('enrollmentsetup/list', 'EnrollmentSetupController@list')->name('list');
Route::get('/superadmin/setup/subject/list', 'SuperAdminController\SubjectSetupController@list_ajax')->name('list');
Route::get('gradesdetail/update', 'TeacherControllers\TeacherGradingV2@udpate_grade_detail')->name('udpate_grade_detial');
Route::get('gradesheader/update', 'TeacherControllers\TeacherGradingV2@udpate_grade_header')->name('udpate_grade_header');
//All API

Route::middleware(['auth'])->group(function () {
    Route::get('/collegescheduleschedgroup', 'SuperAdminController\Setup\cllgschdgrpCollegeScheduleGroupController@cllgschdgrpList')->name('cllgschdgrpList');
    Route::get('/collegescheduleschedgroup/select', 'SuperAdminController\Setup\cllgschdgrpCollegeScheduleGroupController@cllgschdgrpSelect')->name('cllgschdgrpSelect');
    Route::get('/collegescheduleschedgroup/datatable', 'SuperAdminController\Setup\cllgschdgrpCollegeScheduleGroupController@cllgschdgrpDatatable')->name('cllgschdgrpDatatable');
    Route::get('/collegescheduleschedgroup/create', 'SuperAdminController\Setup\cllgschdgrpCollegeScheduleGroupController@cllgschdgrpCreate')->name('cllgschdgrpCreate');
    Route::get('/collegescheduleschedgroup/update', 'SuperAdminController\Setup\cllgschdgrpCollegeScheduleGroupController@cllgschdgrpUpdate')->name('cllgschdgrpUpdate');
    Route::get('/collegescheduleschedgroup/delete', 'SuperAdminController\Setup\cllgschdgrpCollegeScheduleGroupController@cllgschdgrpDelete')->name('cllgschdgrpDelete');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/college/schedule/list/rooms', 'SuperAdminController\College\CollegeSchedList@rooms');
    Route::get('/college/schedule/list/teachers', 'SuperAdminController\College\CollegeSchedList@teachers');
    Route::get('/college/schedule/list/update/capacity', 'SuperAdminController\College\CollegeSchedList@updatecapacity');
    Route::get('/college/schedule/list/update/teacher', 'SuperAdminController\College\CollegeSchedList@updateteacher');

    Route::get('/college/schedule/list/create/sched', 'SuperAdminController\College\CollegeSchedList@createsched');
    Route::get('/college/schedule/list/update/sched', 'SuperAdminController\College\CollegeSchedList@updatesched');
    Route::get('/college/schedule/list/remove/sched', 'SuperAdminController\College\CollegeSchedList@removesched');
    Route::get('/college/schedule/list/remove', 'SuperAdminController\College\CollegeSchedList@removescheddetail');

    Route::get('/college/schedule/list/update/scheddetail', 'SuperAdminController\College\CollegeSchedList@updatescheddetail');

    Route::get('/college/schedule/list/schedgroup', 'SuperAdminController\College\CollegeSchedList@updatescheddetail');

    Route::get('/college/section/list', 'SuperAdminController\College\CollegeSectionsController@collegesection_list_ajax');
    Route::get('/college/section/courses', 'SuperAdminController\College\CollegeSectionsController@courses');
    Route::get('/college/section/courses/subjects', 'SuperAdminController\College\CollegeSectionsController@get_subjects');
    Route::get('/college/section/subjects/all', 'SuperAdminController\College\CollegeSectionsController@get_allsubjects');

    Route::get('/chairperson/sections/add/subject', 'SuperAdminController\College\CollegeSectionsController@add_subject');
    Route::get('/chairperson/sections/remove/subject', 'SuperAdminController\College\CollegeSectionsController@remove_subject');

    Route::get('/chairperson/sections/create', 'SuperAdminController\College\CollegeSectionsController@create_section');
    Route::get('/chairperson/sections/update', 'SuperAdminController\College\CollegeSectionsController@update_section');
    Route::get('/chairperson/sections/delete', 'SuperAdminController\College\CollegeSectionsController@delete_section');

    Route::get('/college/section/schedule/schedenrolledlearners', 'SuperAdminController\College\CollegeSectionsController@sched_enrolled_learners');
    Route::get('/college/section/schedule/schedloadedlearners', 'SuperAdminController\College\CollegeSectionsController@sched_loaded_learners');

    Route::get('/college/section/schedule/list', 'SuperAdminController\College\CollegeSchedController@collegesched_plot_ajax');
    Route::get('/college/section/schedule/list/plot', 'SuperAdminController\College\CollegeSchedController@collegesched_plot');
    Route::get('/college/section/schedule/remove', 'SuperAdminController\College\CollegeSchedController@collegesched_remove_sched');
    Route::get('/college/section/schedule/create', 'SuperAdminController\College\CollegeSchedController@collegesched_create_sched');
    Route::get('/college/section/schedule/update', 'SuperAdminController\College\CollegeSchedController@collegesched_update_sched');
    Route::get('/college/section/schedule/teacher', 'SuperAdminController\College\CollegeSchedController@teachers');
    Route::get('/college/section/schedule/print', 'SuperAdminController\College\CollegeSchedController@collegesched_print');
    Route::get('/college/section/schedule/updatecapacity', 'SuperAdminController\College\CollegeSchedController@update_sched_capacity');
    Route::get('/college/section/schedule/print', 'SuperAdminController\College\CollegeSchedController@print_sched');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/student/loading/allsched/filter', 'SuperAdminController\StudentLoading@all_sched_filter');
});

//college student loading
Route::middleware(['auth'])->group(function () {
    Route::view('/college/section', 'superadmin.pages.college.collegesection');
    Route::view('/student/loading', 'superadmin.pages.student.studentloading');
    Route::get('/student/loading/subjects', 'SuperAdminController\StudentLoading@prospectus_subjects');
    Route::get('/student/loading/subjects/all', 'SuperAdminController\StudentLoading@all_subjects');
    Route::get('/student/loading/students', 'SuperAdminController\StudentLoading@students');
    Route::get('/student/loading/student/enrollment', 'SuperAdminController\StudentLoading@enrollment_info');
    Route::get('/student/loading/courses', 'SuperAdminController\StudentLoading@courses');
    Route::get('/student/loading/availablesched', 'SuperAdminController\StudentLoading@availablesched_plot_ajax');
    Route::get('/student/loading/allsched', 'SuperAdminController\StudentLoading@all_sched');
    Route::get('/student/loading/allsched/printable', 'SuperAdminController\StudentLoading@printable');
    Route::get('/student/loading/student/schedule', 'SuperAdminController\StudentLoading@collegestudentsched_plot_ajax');
    Route::get('/student/loading/student/schedule/remove', 'SuperAdminController\StudentLoading@remove_shedule_ajax');
    Route::get('/student/loading/student/schedule/add/all', 'SuperAdminController\StudentLoading@add_all');
    Route::get('/student/loading/student/schedule/add', 'SuperAdminController\StudentLoading@add_shedule_ajax');
    Route::get('/student/loading/student/schedule/unload/all', 'SuperAdminController\StudentLoading@unload_all');
    Route::get('/student/loading/student/set/section', 'SuperAdminController\StudentLoading@set_section');
    Route::get('/student/loading/student/set/course', 'SuperAdminController\StudentLoading@set_course');
    Route::get('/student/loading/sections', 'SuperAdminController\StudentLoading@sections');
    Route::get('/student/loading/report/enrollment', 'SuperAdminController\StudentLoading@enrollment_report');
    Route::get('/student/loading/report/preenrolled', 'SuperAdminController\StudentLoading@pre_enrolled');
    Route::get('/student/loading/getcourse', 'SuperAdminController\StudentLoading@get_course');
    Route::get('/student/loading/getActiveEnrollmentSetup', 'SuperAdminController\StudentLoading@getActiveEnrollmentSetup');
});
//college student loading

//subject schedule
Route::middleware(['auth'])->group(function () {
    Route::get('/college/subject/schedule/rooms', 'SuperAdminController\Setup\SubjectSchedule@rooms');
    Route::get('/college/subject/schedule/teachers', 'SuperAdminController\Setup\SubjectSchedule@teachers');
    Route::get('/college/subject/schedule/subjects', 'SuperAdminController\Setup\SubjectSchedule@subjects');
    Route::get('/college/subject/schedule/schedgroup', 'SuperAdminController\Setup\SubjectSchedule@schedgroup');
    Route::get('/college/subject/schedule/sy', 'SuperAdminController\Setup\SubjectSchedule@sy');
    Route::get('/college/subject/schedule/semester', 'SuperAdminController\Setup\SubjectSchedule@semester');
    Route::get('/college/subject/schedule/addsched', 'SuperAdminController\Setup\SubjectSchedule@addsched');
    Route::get('/college/subject/schedule/detail', 'SuperAdminController\Setup\SubjectSchedule@scheduledetail');
});
//subject schedule



//college grade
Route::middleware(['auth'])->group(function () {

    // Route::get('/college/grades/sections','SuperAdminController\College\CollegeGradingController@section_ajax');
    Route::get('/college/grades/student', 'SuperAdminController\College\CollegeGradingController@enrolled_students');
    Route::get('/college/grades/gradesched/info', 'SuperAdminController\College\CollegeGradingController@college_studsched');
    Route::get('/college/grades/sections', 'SuperAdminController\College\CollegeGradingController@college_sections');
    Route::get('/college/grades/teachers', 'SuperAdminController\College\CollegeGradingController@college_teachers');
    Route::get('/college/grades/subjects', 'SuperAdminController\College\CollegeGradingController@college_subjects');
    Route::get('/college/grades/get', 'SuperAdminController\College\CollegeGradingController@student_grades');

    Route::post('/college/grades/approve/ph', 'SuperAdminController\College\CollegeGradingController@approve_grades_ph');
    Route::post('/college/grades/approve/dean', 'SuperAdminController\College\CollegeGradingController@approve_grades_dean');
    Route::post('/college/grades/post', 'SuperAdminController\College\CollegeGradingController@post_grades_dean');
    Route::get('/college/grades/student/grades', 'SuperAdminController\College\CollegeGradingController@all_grades');
    Route::post('/college/grades/pending/ph', 'SuperAdminController\College\CollegeGradingController@pending_grades_ph');
    Route::post('/college/grades/unpost/ph', 'SuperAdminController\College\CollegeGradingController@unpost_grades_ph');
    Route::view('/college/grade', 'superadmin.pages.college.collegrading');

    Route::view('/college/grade/monitoring/teacher', 'superadmin.pages.college.ctgrademonitoring');
    Route::get('/college/grades/monitoring/teacher/subjects', 'SuperAdminController\College\CollegeTeacherGradeMonitoringController@grade_subjects_ajax');
    Route::get('/college/grades/monitoring/teachers', 'SuperAdminController\College\CollegeTeacherGradeMonitoringController@teachers');
    Route::get('/college/grades/monitoring/teacher/subject/grade', 'SuperAdminController\College\CollegeTeacherGradeMonitoringController@view_grades');

    Route::view('/college/grade/monitoring/teacher', 'superadmin.pages.college.ctgrademonitoring');
    Route::get('/college/grades/monitoring/teacher/subjects', 'SuperAdminController\College\CollegeTeacherGradeMonitoringController@grade_subjects_ajax');
    Route::get('/college/grades/monitoring/teachers', 'SuperAdminController\College\CollegeTeacherGradeMonitoringController@teachers');
    Route::get('/college/grades/monitoring/teacher/subject/grade', 'SuperAdminController\College\CollegeTeacherGradeMonitoringController@view_grades');
});


//ecr
Route::middleware(['auth'])->group(function () {
    Route::view('/classschedule', 'superadmin.pages.teacher.teacherinformation');
    Route::view('/grades', 'superadmin.pages.teacher.gradeposting');
    Route::get('/teacher/list', 'SuperAdminController\TeacherECRController@teacher_list');
    Route::get('/teacher/schedule', 'SuperAdminController\TeacherECRController@schedule');
    Route::get('/ecr/download', 'SuperAdminController\TeacherECRController@download_ecr');
    Route::post('/ecr/upload', 'SuperAdminController\TeacherECRController@upload_ecr');
    Route::get('/ecr/view', 'SuperAdminController\TeacherECRController@view_ecr');
    Route::get('/ecr/submit', 'SuperAdminController\TeacherECRController@submit_ecr');
    Route::get('/ecr/approve', 'SuperAdminController\TeacherECRController@approve_ecr');
    Route::get('/ecr/pending', 'SuperAdminController\TeacherECRController@pending_ecr');
    Route::get('/ecr/pending/student', 'SuperAdminController\TeacherECRController@pending_student');
    Route::get('/ecr/post/student', 'SuperAdminController\TeacherECRController@post_student');
    Route::get('/ecr/approve/student', 'SuperAdminController\TeacherECRController@approve_student');
    Route::get('/ecr/unpost/student', 'SuperAdminController\TeacherECRController@unpost_student');
    Route::get('/ecr/post', 'SuperAdminController\TeacherECRController@post_ecr');
    Route::get('/ecr/unpost', 'SuperAdminController\TeacherECRController@unpost_ecr');
    Route::get('/grades/list', 'SuperAdminController\GradePostingController@all_grades');
    Route::get('/grades/list/students', 'SuperAdminController\GradePostingController@per_student_grades_status');



    Route::post('/grade/preschool/upload/detail', 'SuperAdminController\PreSchoolGradingController@upload_detail');
    Route::post('/grade/preschool/upload/template', 'SuperAdminController\PreSchoolGradingController@upload_template');
    Route::get('/grade/preschool/download/detail', 'SuperAdminController\PreSchoolGradingController@download_detail');

    Route::view('/grade/preschool/setup', 'superadmin.pages.setup.preschoolgrading');
    Route::get('/grade/preschool/sections', 'SuperAdminController\PreSchoolGradingController@teacher_class');
    Route::get('/grade/preschool/pdf', 'SuperAdminController\PreSchoolGradingController@pdf_format');
    Route::get('/grade/preschool/savegrades', 'SuperAdminController\PreSchoolGradingController@store_grades');
    Route::get('/grade/preschool/getgrades', 'SuperAdminController\PreSchoolGradingController@get_student_grades');
    Route::get('/grade/preschool/setup/list', 'SuperAdminController\PreSchoolGradingController@get_preschool_setup_ajax');
    Route::get('/grade/preschool/setup/remarks/list', 'SuperAdminController\PreSchoolGradingController@get_preschool_setup_remarks_ajax');
    Route::get('/grade/preschool/setup/age/list', 'SuperAdminController\PreSchoolGradingController@get_preschool_setup_age_ajax');
    Route::get('/grade/preschool/setup/create', 'SuperAdminController\PreSchoolGradingController@create_setup_ajax');
    Route::get('/grade/preschool/setup/delete', 'SuperAdminController\PreSchoolGradingController@delete_setup_ajax');
    Route::get('/grade/preschool/setup/update', 'SuperAdminController\PreSchoolGradingController@update_setup_ajax');
    Route::view('/grade/preschool', 'teacher.grading.preschoolgrading');

    Route::view('/grade/prekinder/setup', 'superadmin.pages.setup.prekinder');
    Route::get('/grade/prekinder/pdf', 'SuperAdminController\PreKinderGradingController@pdf_format');
    Route::get('/grade/prekinder/sections', 'SuperAdminController\PreKinderGradingController@teacher_class');
    Route::get('/grade/prekinder/savegrades', 'SuperAdminController\PreKinderGradingController@store_grades');
    Route::get('/grade/prekinder/getgrades', 'SuperAdminController\PreKinderGradingController@get_student_grades');
    Route::get('/grade/prekinder/setup/list', 'SuperAdminController\PreKinderGradingController@get_preschool_setup_ajax');
    Route::get('/grade/prekinder/cl/setup/list', 'SuperAdminController\PreKinderGradingController@get_preschool_cl_setup');
    Route::get('/grade/prekinder/ageevaldate/setup/list', 'SuperAdminController\PreKinderGradingController@get_preschool_ageevaldate_setup');
    Route::get('/grade/prekinder/summary/setup/list', 'SuperAdminController\PreKinderGradingController@get_preschool_summary_setup');
    Route::get('/grade/prekinder/setup/create', 'SuperAdminController\PreKinderGradingController@create_setup_ajax');
    Route::get('/grade/prekinder/setup/delete', 'SuperAdminController\PreKinderGradingController@delete_setup_ajax');
    Route::get('/grade/prekinder/setup/update', 'SuperAdminController\PreKinderGradingController@update_setup_ajax');
    Route::view('/grade/prekinder', 'teacher.grading.prekindergrading');
});
//ecr


//student special subject
Route::middleware(['auth', 'isDefaultPass'])->group(function () {
    Route::view('basiced/student/specialclass', 'superadmin.pages.student.studentspecialclass');
    Route::get('basiced/student/specialclass/list', 'SuperAdminController\StudentSpecialClass@student_specialclas');
    Route::get('basiced/student/specialclass/students', 'SuperAdminController\StudentSpecialClass@students');
    Route::get('basiced/student/specialclass/gradelevel', 'SuperAdminController\StudentSpecialClass@gradelevel');
    Route::get('basiced/student/specialclass/sections', 'SuperAdminController\StudentSpecialClass@sections');
    Route::get('basiced/student/specialclass/subjects', 'SuperAdminController\StudentSpecialClass@subjects');
    Route::get('basiced/student/specialclass/create', 'SuperAdminController\StudentSpecialClass@student_specialclas_create');
    Route::get('basiced/student/specialclass/update', 'SuperAdminController\StudentSpecialClass@student_specialclas_update');
    Route::get('basiced/student/specialclass/delete', 'SuperAdminController\StudentSpecialClass@student_specialclas_delete');
});
//student special subject



Route::middleware(['auth', 'isDefaultPass'])->group(function () {
    Route::view('college/teacher/student/grades', 'ctportal.pages.grading');
    Route::view('college/teacher/student/information', 'ctportal.pages.studentinformation');
    Route::get('college/teacher/student/information/pdf', 'CTController\CTController@student_list_pdf');
    Route::get('college/teacher/student/information/excell', 'CTController\CTController@student_list_excell');
    Route::view('college/teacher/profile', 'ctportal.pages.profile');
    Route::get('college/teacher/student/grades/subject', 'CTController\CTController@grade_subjects_ajax');
    Route::post('college/teacher/student/grades/save', 'CTController\CTController@save_grades');
    Route::get('college/teacher/student/grades/get', 'CTController\CTController@get_grades');
    Route::get('college/teacher/student/grades/print', 'CTController\CTController@print_grading_sheet');
    Route::get('college/teacher/profile/get', 'CTController\CTController@my_profile');
    Route::get('college/teacher/schedule/get', 'CTController\CTController@get_schedule_ajax');
    Route::get('college/teacher/student/exampermit', 'CTController\CTController@get_exam_permit_ajax');
    Route::post('college/teacher/student/grades/submit', 'CTController\CTController@submit_grades');
    Route::get('college/teacher/student/grades/status/get', 'CTController\CTController@get_grade_status');
});

//cor printing
Route::middleware(['auth'])->group(function () {
    Route::get('/student/cor/printing/enrolled', 'SuperAdminController\College\CORPrintingController@enrolled');
    Route::view('/student/cor/printing', 'superadmin.pages.student.corprinting');
});
//cor printing

//teacher profile
Route::middleware(['auth'])->group(function () {
    Route::view('/user/profile', 'superadmin.pages.teacher.profile');
    Route::get('/teacher/profile/get', 'SuperAdminController\TeacherProfileController@my_profile');
    Route::post('/teacher/profile/update/photo', 'SuperAdminController\TeacherProfileController@update_photo');
    Route::get('/teacher/profile/update/profile', 'SuperAdminController\TeacherProfileController@update_profile');
});
//teacher profile

//transfered in grades
Route::middleware(['auth'])->group(function () {
    Route::get('/transferedin/grades/students', 'SuperAdminController\StudentTransfereInGrades@students');
    Route::get('/transferedin/grades/subjects', 'SuperAdminController\StudentTransfereInGrades@subjects');
    Route::get('/transferedin/grades/gradelevel', 'SuperAdminController\StudentTransfereInGrades@gradelevel');
    Route::get('/transferedin/grades/sections', 'SuperAdminController\StudentTransfereInGrades@sections');
    Route::get('/transferedin/grades/create', 'SuperAdminController\StudentTransfereInGrades@student_trasferedin_grade_create');
    Route::get('/transferedin/grades/update', 'SuperAdminController\StudentTransfereInGrades@student_trasferedin_grade_update');
    Route::get('/transferedin/grades/delete', 'SuperAdminController\StudentTransfereInGrades@student_trasferedin_grade_delete');
    Route::get('/transferedin/grades/list', 'SuperAdminController\StudentTransfereInGrades@student_transferein_grade_list');
    Route::view('/transferedin/grades', 'superadmin.pages.student.studenttransgrade');
});
//transfered in grades


Route::middleware(['auth'])->group(function () {
    Route::view('sp/credentials', 'superadmin.pages.fixer.credentials');
    Route::get('sp/credentials/list', 'SuperAdminController\FixerCredsController@student_list_ajax');
    Route::get('sp/credentials/send', 'SuperAdminController\FixerCredsController@send_credentials');
    Route::get('sp/credentials/generate/student/credentials', 'SuperAdminController\FixerCredsController@generate_student_account');
    Route::get('sp/credentials/generate/parent/credentials', 'SuperAdminController\FixerCredsController@generate_parent_account');
    Route::get('sp/credentials/update/student/password', 'SuperAdminController\FixerCredsController@update_password');
    Route::get('sp/credentials/update/parent/password', 'SuperAdminController\FixerCredsController@update_password');
    Route::get('sp/credentials/remove/multiple/student', 'SuperAdminController\FixerCredsController@remove_duplicate_student');
    Route::get('sp/credentials/remove/multiple/parent', 'SuperAdminController\FixerCredsController@remove_duplicate_parent');
    Route::get('sp/credentials/udpate', 'SuperAdminController\FixerCredsController@fix_student_contact');
});



Route::view('student/credentials', 'superadmin.pages.fixer.parentscredential');
Route::get('student/credentials/list', 'SuperAdminController\FixerController@student_credentials');
Route::get('student/credentials/fix', 'SuperAdminController\FixerController@fix_credentials');



Route::view('student/contactnumber', 'superadmin.pages.fixer.contactnumber');
Route::get('student/contactnumber/list', 'SuperAdminController\FixerController@contact_number_list_ajax');
Route::get('student/contactnumber/update', 'SuperAdminController\FixerController@update_contact');

Route::middleware(['auth', 'isSuperAdmin'])->group(function () {
    Route::view('sp/credentials', 'superadmin.pages.fixer.credentials');
    Route::get('sp/credentials/list', 'SuperAdminController\FixerCredsController@student_list_ajax');
    Route::get('sp/credentials/send', 'SuperAdminController\FixerCredsController@send_credentials');
    Route::get('sp/credentials/generate/student/credentials', 'SuperAdminController\FixerCredsController@generate_student_account');
    Route::get('sp/credentials/generate/parent/credentials', 'SuperAdminController\FixerCredsController@generate_parent_account');
    Route::get('sp/credentials/update/student/password', 'SuperAdminController\FixerCredsController@update_password');
    Route::get('sp/credentials/update/parent/password', 'SuperAdminController\FixerCredsController@update_password');
    Route::get('sp/credentials/remove/multiple/student', 'SuperAdminController\FixerCredsController@remove_duplicate_student');
    Route::get('sp/credentials/remove/multiple/parent', 'SuperAdminController\FixerCredsController@remove_duplicate_parent');
    Route::get('sp/credentials/udpate', 'SuperAdminController\FixerCredsController@fix_student_contact');
});


//truncate v2
Route::middleware(['auth', 'isSuperAdmin:admin'])->group(function () {

    Route::view('/truncanator/v2', 'superadmin.pages.truncanatorV2');
    Route::get('/truncanator/v2/process', 'SuperAdminController\TruncateControllerV2@truncate');
    Route::get('/truncanator/v2/clear', 'SuperAdminController\TruncateControllerV2@clear');
    Route::get('/truncanator/v2/check', 'SuperAdminController\TruncateControllerV2@check');
    Route::get('/truncanator/nottrunctable', 'SuperAdminController\TruncateControllerV2@not_truncated_tables');
    Route::get('/truncanator/tableinfo', 'SuperAdminController\TruncateControllerV2@get_table_information');

    Route::get('/truncanator/module/list', 'SuperAdminController\TruncateControllerV2@module_list');
    Route::get('/truncanator/module/create', 'SuperAdminController\TruncateControllerV2@create_module');
    Route::get('/truncanator/module/update', 'SuperAdminController\TruncateControllerV2@update_module');
    Route::get('/truncanator/module/delete', 'SuperAdminController\TruncateControllerV2@delete_module');


    Route::view('/truncanator/setup', 'superadmin.pages.truncanatorsetup');
    Route::get('/truncanator/module/table/create', 'SuperAdminController\TruncateControllerV2@create_module_table');
    Route::get('/truncanator/module/table/inserttogroup', 'SuperAdminController\TruncateControllerV2@insert_module_to_group');
});
//truncate v2

Route::middleware(['cors'])->group(function () {
    Route::get('api/schoolinfo/projectsetup', 'AdministratorControllers\SchoolInformationController@getSchoolInfoProjectSetup');
});


Route::middleware(['cors'])->group(function () {
    Route::view('/textblast', 'superadmin.pages.utility.textblast');
    Route::get('/textblast/send', 'SuperAdminController\TextBlastController@send_message');
    Route::get('/textblast/contactnumber', 'SuperAdminController\TextBlastController@contact_number_list_ajax');
    Route::get('/textblast/list', 'SuperAdminController\TextBlastController@message_list');
    Route::get('api/textblast/datatable', 'SuperAdminController\TextBlastController@textblastmonitoringDatatable');
});


Route::middleware(['auth'])->group(function () {
    Route::view('/blade/notification', 'general.notification.notification');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/utilities/u_loadtuitionheader', 'FinanceControllers\UtilityController@u_loadtuitionheader')->name('u_loadtuitionheader');
    Route::get('/utilities/u_viewtuitiondetails', 'FinanceControllers\UtilityController@u_viewtuitiondetails')->name('u_viewtuitiondetails');
    Route::get('/utilities/u_temp_studportal', 'FinanceControllers\UtilityController@u_temp_studportal')->name('u_temp_studportal');
    Route::get('/utilities/u_loadlevel', 'FinanceControllers\UtilityController@u_loadlevel')->name('u_loadlevel');
});

//medical info
Route::middleware(['auth'])->group(function () {
    Route::view('/student/medinfo', 'superadmin.pages.student.studentmedinfo');
    Route::get('/student/medinfo/list', 'SuperAdminController\StudentMedInfoController@list');
    Route::get('/student/medinfo/create', 'SuperAdminController\StudentMedInfoController@create');
    Route::get('/student/medinfo/update', 'SuperAdminController\StudentMedInfoController@update');
    Route::get('/student/medinfo/delete', 'SuperAdminController\StudentMedInfoController@delete');
});
//medical info


Route::middleware(['cors'])->group(function () {
    Route::get('/student/preregistration/addstudenttoprereg', 'SuperAdminController\StudentPregistration@add_student_to_prereg');
    Route::get('/student/preregistration/createnewstudent', 'SuperAdminController\StudentPregistration@create_student_information');
    Route::get('/student/preregistration/updatestudinfo', 'SuperAdminController\StudentPregistration@update_student_information');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/student/preregistration', 'superadmin.pages.student.studentpregistration');
    Route::get('/student/preregistration/list', 'SuperAdminController\StudentPregistration@preenrolledstudents');
    Route::get('/student/preregistration/studinfo', 'SuperAdminController\StudentPregistration@get_student_information');
    Route::get('/student/preregistration/readytoenroll', 'SuperAdminController\StudentPregistration@readytoenroll');
    Route::get('/student/preregistration/readytoenroll/cancel', 'SuperAdminController\StudentPregistration@cancel_readytoenroll');
    Route::get('/student/preregistration/allownodp', 'SuperAdminController\StudentPregistration@allownodp');
    Route::get('/student/preregistration/allownodp/cancel', 'SuperAdminController\StudentPregistration@allownodp_cancel');
    Route::get('/student/preregistration/admissiontype', 'SuperAdminController\StudentPregistration@preregistration_admission_type');
    Route::get('/student/preregistration/admissionsetup', 'SuperAdminController\StudentPregistration@preregistration_admission_setup');
    Route::get('/student/preregistration/student/enrollmenthistory', 'SuperAdminController\StudentPregistration@enrollment_history');
    Route::get('/student/preregistration/student/balancehistory', 'SuperAdminController\StudentPregistration@student_balance_info');
    Route::get('/student/preregistration/student/updatehistory', 'SuperAdminController\StudentPregistration@update_history');
    Route::get('/student/preregistration/student/collegeinfo', 'SuperAdminController\StudentPregistration@student_college_info');
    Route::get('/student/preregistration/student/documents', 'SuperAdminController\StudentPregistration@documents_list');
    Route::get('/student/preregistration/student/enroll', 'SuperAdminController\StudentPregistration@enroll_student');
    Route::get('/student/preregistration/student/enroll/update', 'SuperAdminController\StudentPregistration@update_enroll_student');
    Route::get('/student/preregistration/student/updateinfo', 'SuperAdminController\StudentPregistration@update_info');
    Route::get('/student/preregistration/student/update/contactinfo', 'SuperAdminController\StudentPregistration@update_student_contact_info');
    Route::get('/student/preregistration/allstudents', 'SuperAdminController\StudentPregistration@all_students');
    Route::get('/student/preregistration/sections', 'SuperAdminController\StudentPregistration@get_sections');
    Route::get('/student/preregistration/student/collegesubjectload', 'SuperAdminController\StudentPregistration@collegesubjectload');
    Route::get('/student/preregistration/vacinfo', 'SuperAdminController\StudentPregistration@vac_info');
    Route::get('/student/preregistration/enrollmentinfo', 'SuperAdminController\StudentPregistration@enrollment_summary');
    Route::get('/student/preregistration/removelearner', 'SuperAdminController\StudentPregistration@remove_student');
    Route::get('/student/preregistration/markActiveStatus', 'SuperAdminController\StudentPregistration@markActiveStatus');
    Route::get('/student/preregistration/getgradelevel', 'SuperAdminController\StudentPregistration@get_gradelevel');
    Route::get('/student/preregistration/collegesection', 'SuperAdminController\StudentPregistration@getstudentsection');

    Route::get('/student/preregistration/print/enrollmentbyreligiousaffiliation', 'SuperAdminController\Printables\EnrollmentPrintables@enrollment_by_religious_affiliation');
    Route::get('/student/preregistration/print/enrollmentbyethnicgroup', 'SuperAdminController\Printables\EnrollmentPrintables@enrollmentbyethnicgroup');
    Route::get('/student/preregistration/print/enrollmentunenrolled', 'SuperAdminController\Printables\EnrollmentPrintables@enrollmentunenrolled');
});
//student preregistration


Route::middleware(['auth'])->group(function () {
    Route::get('/student/enrollment/report/mol', 'SuperAdminController\StudentEnrollmentReport@mol_report');
    Route::get('/student/enrollment/report/contact', 'SuperAdminController\FixerController@contact_printable');

});

//project setup
Route::middleware(['cors'])->group(function () {
    Route::view('/project/setup', 'superadmin.pages.setup.projectsetup');
    Route::get('/project/setup/update/projectsetup', 'SuperAdminController\ProjectSetupController@update_projectsetup');
    Route::get('/project/setup/update/essentiellink', 'SuperAdminController\ProjectSetupController@update_essentiellink');
    Route::get('/project/setup/update/processsetup', 'SuperAdminController\ProjectSetupController@update_processsetup');
    Route::get('/project/setup/usertypes', 'SuperAdminController\ProjectSetupController@usertypes');
    Route::get('/project/setup/update/onlinelink', 'SuperAdminController\ProjectSetupController@update_olinelink');
});


//project setup
Route::middleware(['auth'])->group(function () {
    Route::view('/sf1/tosystem', 'superadmin.pages.utility.sf1tosytem');
    Route::post('/sf1/tosystem/upload', 'SuperAdminController\SF1ToSystemController@generate_student_from_excel');
    Route::get('/sf1/tosystem/savecolumns', 'SuperAdminController\SF1ToSystemController@savecolumns');
    Route::get('/sf1/tosystem/saveinfo', 'SuperAdminController\SF1ToSystemController@saveinfo');
});

//local to cloud
Route::middleware(['cors'])->group(function () {
    Route::view('/data/localtocloud', 'superadmin.pages.utility.datafromonline');
    Route::get('/localclouddata/get/updated', 'SuperAdminController\LocalCloudDataController@get_updated');
    Route::get('/localclouddata/get/deleted', 'SuperAdminController\LocalCloudDataController@get_deleted');
    Route::get('/localclouddata/localtables', 'SuperAdminController\LocalCloudDataController@local_tables');
    Route::get('/localclouddata/cloudtables', 'SuperAdminController\LocalCloudDataController@cloud_tables');
});


Route::middleware(['cors'])->group(function () {
    Route::view('setup/modeoflearning', 'superadmin.pages.setup.modeoflearning');
    Route::get('setup/modeoflearning/list', 'SuperAdminController\Setup\ModeofLearningController@modeoflearning_list');
    Route::get('setup/modeoflearning/create', 'SuperAdminController\Setup\ModeofLearningController@modeoflearning_create');
    Route::get('setup/modeoflearning/update', 'SuperAdminController\Setup\ModeofLearningController@modeoflearning_update');
    Route::get('setup/modeoflearning/delete', 'SuperAdminController\Setup\ModeofLearningController@modeoflearning_delete');
    Route::get('setup/modeoflearning/schoolinfo/activate', 'SuperAdminController\Setup\ModeofLearningController@update_schoolinfo_mol');
});

//sched classification
Route::middleware(['cors'])->group(function () {
    Route::view('/setup/scheduleclassification', 'superadmin.pages.utility.datafromonline');
    Route::get('/setup/scheduleclassification/list', 'SuperAdminController\Setup\ScheduleClassificationController@schedclassification_list');
    Route::get('/setup/scheduleclassification/create', 'SuperAdminController\Setup\ScheduleClassificationController@schedclassification_create');
    Route::get('/setup/scheduleclassification/update', 'SuperAdminController\Setup\ScheduleClassificationController@schedclassification_update');
    Route::get('/setup/scheduleclassification/delete', 'SuperAdminController\Setup\ScheduleClassificationController@schedclassification_delete');
});

//Time template
Route::middleware(['cors'])->group(function () {
    Route::get('/setup/scheduletimetemp/list', 'PrincipalControllers\ScheduleTimeTempController@scheduletimetemp_list');
    Route::get('/setup/scheduletimetemp/create', 'PrincipalControllers\ScheduleTimeTempController@scheduletimetemp_create');
    Route::get('/setup/scheduletimetemp/update', 'PrincipalControllers\ScheduleTimeTempController@scheduletimetemp_update');
    Route::get('/setup/scheduletimetemp/delete', 'PrincipalControllers\ScheduleTimeTempController@scheduletimetemp_delete');

    Route::get('/setup/timetempdetails/list', 'PrincipalControllers\ScheduleTimeTempController@timetempdetails_list');
    Route::get('/setup/timetempdetails/create', 'PrincipalControllers\ScheduleTimeTempController@timetempdetails_create');
    Route::get('/setup/timetempdetails/update', 'PrincipalControllers\ScheduleTimeTempController@timetempdetails_update');
    Route::get('/setup/timetempdetails/delete', 'PrincipalControllers\ScheduleTimeTempController@timetempdetails_delete');
});


//sched classification
Route::middleware(['cors'])->group(function () {
    Route::get('/setup/vaccinetype/list', 'SuperAdminController\Setup\VaccineSetupController@vaccine_list');
    Route::get('/setup/vaccinetype/create', 'SuperAdminController\Setup\VaccineSetupController@vaccine_create');
    Route::get('/setup/vaccinetype/update', 'SuperAdminController\Setup\VaccineSetupController@vaccine_update');
    Route::get('/setup/vaccinetype/delete', 'SuperAdminController\Setup\VaccineSetupController@vaccine_delete');
});

//religion
Route::middleware(['cors'])->group(function () {
    Route::get('/setup/religion/list', 'SuperAdminController\Setup\ReligionSetupController@religion_list');
    Route::get('/setup/religion/create', 'SuperAdminController\Setup\ReligionSetupController@religion_create');
    Route::get('/setup/religion/update', 'SuperAdminController\Setup\ReligionSetupController@religion_update');
    Route::get('/setup/religion/delete', 'SuperAdminController\Setup\ReligionSetupController@religion_delete');
});

//bloodtype
Route::middleware(['cors'])->group(function () {
    Route::get('/setup/bloodtype/list', 'SuperAdminController\Setup\BloodtypeController@bloodtype_list');
    Route::get('/setup/bloodtype/create', 'SuperAdminController\Setup\BloodtypeController@bloodtype_create');
    Route::get('/setup/bloodtype/update', 'SuperAdminController\Setup\BloodtypeController@bloodtype_update');
    Route::get('/setup/bloodtype/delete', 'SuperAdminController\Setup\BloodtypeController@bloodtype_delete');
});

//mothertongue
Route::middleware(['cors'])->group(function () {
    Route::get('/setup/mothertongue/list', 'SuperAdminController\Setup\MotherTongueSetupController@mothertongue_list');
    Route::get('/setup/mothertongue/create', 'SuperAdminController\Setup\MotherTongueSetupController@mothertongue_create');
    Route::get('/setup/mothertongue/update', 'SuperAdminController\Setup\MotherTongueSetupController@mothertongue_update');
    Route::get('/setup/mothertongue/delete', 'SuperAdminController\Setup\MotherTongueSetupController@mothertongue_delete');
});

//ethnicgroup
Route::middleware(['cors'])->group(function () {
    Route::get('/setup/ethnicgroup/list', 'SuperAdminController\Setup\EthnicGroupSetupController@ethnicgroup_list');
    Route::get('/setup/ethnicgroup/create', 'SuperAdminController\Setup\EthnicGroupSetupController@ethnicgroup_create');
    Route::get('/setup/ethnicgroup/update', 'SuperAdminController\Setup\EthnicGroupSetupController@ethnicgroup_update');
    Route::get('/setup/ethnicgroup/delete', 'SuperAdminController\Setup\EthnicGroupSetupController@ethnicgroup_delete');
});

//College student grade evaluation
Route::middleware(['auth'])->group(function () {

    Route::get('/student/grade/evaluation', 'StudentControllers\StudentGradeEvaluation@student_grade_evaluation');
    Route::get('/student/grade/evaluation/print', 'StudentControllers\StudentGradeEvaluation@student_grade_evaluation_print');
});


//useracadprog
Route::middleware(['cors'])->group(function () {
    Route::view('/setup/useracadprog', 'superadmin.pages.teacher.useracadprog');
    Route::get('/setup/useracadprog/list', 'SuperAdminController\Teacher\FASAcadProgController@fasaacadprog');
    Route::get('/setup/useracadprog/teachers', 'SuperAdminController\Teacher\FASAcadProgController@teachers');
    Route::get('/setup/useracadprog/create', 'SuperAdminController\Teacher\FASAcadProgController@add_fas');
    Route::get('/setup/useracadprog/copy', 'SuperAdminController\Teacher\FASAcadProgController@fasaacadprog_copy');
});


//useracadprog
Route::middleware(['cors'])->group(function () {
    Route::view('/setup/usertype', 'superadmin.pages.setup.usertype');
    Route::get('/setup/usertype/list', 'SuperAdminController\Setup\UserTypeController@usertype');
    Route::get('/setup/usertype/create', 'SuperAdminController\Setup\UserTypeController@create_usertype');
    Route::get('/setup/usertype/delete', 'SuperAdminController\Setup\UserTypeController@remove_usertype');
    Route::get('/setup/usertype/update', 'SuperAdminController\Setup\UserTypeController@update_usertype');
});


Route::middleware(['cors'])->group(function () {
    Route::view('setup/payment/options', 'superadmin.pages.setup.paymentoptions');
    Route::get('setup/payment/options/list', 'SuperAdminController\Setup\PaymentOptionsController@payment_option_list');
    Route::get('setup/payment/options/create', 'SuperAdminController\Setup\PaymentOptionsController@payment_option_create');
    Route::get('setup/payment/options/update', 'SuperAdminController\Setup\PaymentOptionsController@payment_option_update');
    Route::get('setup/payment/options/delete', 'SuperAdminController\Setup\PaymentOptionsController@payment_option_delete');
    Route::post('setup/payment/options/image/upload', 'SuperAdminController\Setup\PaymentOptionsController@payment_option_image_upload');
});

//college grade summary
Route::middleware(['auth'])->group(function () {
    Route::get('/college/grades/summary/students/grade', 'SuperAdminController\College\CollegeGradingSummaryController@student_grades');
    Route::view('/college/grades/summary', 'superadmin.pages.college.collegegradesummary');
    Route::get('/college/grades/summary/students/enrolled', 'SuperAdminController\College\CollegeGradingSummaryController@students_enrolled');
    Route::get('/college/grades/summary/subjects', 'SuperAdminController\College\CollegeGradingSummaryController@college_subjects');
    Route::get('/college/grades/summary/subjects/grades', 'SuperAdminController\College\CollegeGradingSummaryController@subject_grade');
    Route::get('/college/grades/summary/print/grades', 'SuperAdminController\College\CollegeGradingSummaryController@print_grades');
    Route::get('/college/grades/summary/print/pdf', 'SuperAdminController\College\CollegeGradingSummaryController@generate_grade_pdf');
});
//college grade summary

Route::middleware(['cors'])->group(function () {
    Route::view('/rooms', 'adminPortal.pages.rooms');
    Route::get('/rooms/get', 'AdministratorControllers\RoomsController@rooms');
    Route::get('/rooms/update', 'AdministratorControllers\RoomsController@udpate_room');
    Route::get('/rooms/create', 'AdministratorControllers\RoomsController@create_room');
    Route::get('/rooms/delete', 'AdministratorControllers\RoomsController@delete_room');
    Route::get('/rooms/getsections', 'AdministratorControllers\RoomsController@getsections');
    Route::get('/rooms/getsubjects', 'AdministratorControllers\RoomsController@getsubjects');
});

Route::middleware(['auth'])->group(function () {
    Route::view('/teacher/student/credential', 'teacher.grading.student_credentials');
    Route::get('/teacher/student/credential/advisory', 'TeacherControllers\TeacherStudentCredentials@teacher_class');
    Route::get('/teacher/student/credential/list', 'TeacherControllers\TeacherStudentCredentials@student_creadentials');
    Route::get('/teacher/student/generate/password', 'TeacherControllers\TeacherStudentCredentials@update_password');
    Route::get('/teacher/student/generate/parentaccount', 'TeacherControllers\TeacherStudentCredentials@generate_parent_account');
    Route::get('/teacher/student/generate/studentaccount', 'TeacherControllers\TeacherStudentCredentials@generate_student_account');
    Route::get('/teacher/student/reset/all', 'TeacherControllers\TeacherStudentCredentials@update_all_password');
});
Route::get('/section/report/reportcard', 'PrincipalControllers\DynamicPDFController@section_reportcard');

//adminadmin
Route::middleware(['cors'])->group(function () {
    Route::get('/passData', 'AdminadminController\AdminAdminController@passData');

    Route::get('/viewschool/{id}', 'AdminadminController\AdminAdminController@viewadmiadmin');
    Route::get('/finance/index', 'AdminadminController\AdminAdminController@financeindex');
    Route::get('/academic/index', 'AdminadminController\AdminAdminController@academicindex');
    Route::get('/academic/students', 'AdminadminController\AdminAdminController@academicstudents');
    Route::get('/hr/index', 'AdminadminController\AdminAdminController@hrindex');
    Route::get('/aadmin/enrollment', 'AdminadminController\AdminAdminController@enrollment');

    Route::get('/enrollmentReport', 'AdminadminController\AdminAdminController@enrollmentReport');
    // Route::get('/enrollmentReport','AdminadminController\AdminAdminController@enrollmentReport');
    Route::get('/cashtransReport', 'AdminadminController\AdminAdminController@cashtransReport');

    Route::get('/filtercashtrans', 'AdminadminController\AdminAdminController@filtercashtrans');
    Route::get('/filterEnrollmentReport', 'AdminadminController\AdminAdminController@filterEnrollmentReport');
});

//student remidial class
Route::middleware(['auth'])->group(function () {
    Route::view('/student/remedialclass', 'studentPortal.pages.s_spsubj');
    Route::get('api/student/remedialclass/', 'StudentControllers\EnrollmentInformation@remedial_class');
});

// EUGZ QUEUING
Route::middleware(['auth'])->group(function () {

    //setup
    Route::get('/queuing-setup', 'QueuingController\QueuingSetupController@show');
    Route::get('/queuing-setup/setup-setactive', 'QueuingController\QueuingSetupController@setup_setactive')->name('setup.setactive');
    Route::get('/queuing-setup/create-setup', 'QueuingController\QueuingSetupController@create_setup')->name('queuing.create');
    Route::get('/queuing-setup/create-department', 'QueuingController\QueuingSetupController@create_department')->name('queuing.department.create');
    Route::get('/queuing-setup/get-department', 'QueuingController\QueuingSetupController@get_department')->name('get.select2.department');
    Route::get('/queuing-setup/department-window-create', 'QueuingController\QueuingSetupController@create_window')->name('department.window.create');
    Route::get('/queuing-setup/get-included-window', 'QueuingController\QueuingSetupController@get_included_window')->name('get.included.window');
    Route::get('/queuing-setup/get-queuingsetup', 'QueuingController\QueuingSetupController@get_queuingsetup')->name('get.queuingsetup');
    Route::get('/queuing-setup/get-queuingsetup-data', 'QueuingController\QueuingSetupController@get_queuingsetup_data')->name('get.queuingsetup.data');
    Route::get('/queuing-setup/included-window-revert', 'QueuingController\QueuingSetupController@included_window_revert')->name('included.window.revert');
    Route::get('/queuing-setup/get-windows', 'QueuingController\QueuingSetupController@get_windows')->name('get.windows');
    Route::get('/queuing-setup/get-setup-windows', 'QueuingController\QueuingSetupController@get_setup_windows')->name('get.setup.windows');
    Route::get('/queuing-setup/delete-windows', 'QueuingController\QueuingSetupController@delete_windows')->name('delete.windows');
    Route::get('/queuing-setup/edit-window-label', 'QueuingController\QueuingSetupController@edit_window_label')->name('edit.window.label');
    Route::get('/queuing-setup/assign-window-user', 'QueuingController\QueuingSetupController@assign_window_user')->name('assign.window.user');
    Route::get('/queuing-setup/get-users', 'QueuingController\QueuingSetupController@get_users')->name('get.users');
    Route::get('/queuing-setup/edit-setup-window', 'QueuingController\QueuingSetupController@edit_setup_window')->name('edit.setup.window');
    Route::get('/queuing-setup/delete-setup-window', 'QueuingController\QueuingSetupController@delete_setup_window')->name('delete.setup.window');
    Route::get('/queuing-setup/delete-queuing-setup', 'QueuingController\QueuingSetupController@delete_queuing_setup')->name('delete.queuing.setup');
    Route::get('/queuing-setup/edit-queuing-setup', 'QueuingController\QueuingSetupController@edit_queuing_setup')->name('edit.queuing.setup');
    Route::get('/queuing-setup/add-new-window', 'QueuingController\QueuingSetupController@add_new_window')->name('add.new.window');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/on-load', 'QueuingController\QueuingMainController@on_load')->name('on.load');
    Route::get('/get-current-serving', 'QueuingController\QueuingMainController@get_current_serving')->name('get.current.serving');

    Route::get('/get-next-que', 'QueuingController\QueuingMainController@get_next_que')->name('get.next.que');
    Route::get('/annouce-next-que', 'QueuingController\QueuingMainController@annouce_next_que')->name('annouce.next.que');

    Route::get('/get-window', 'QueuingController\QueuingMainController@get_window')->name('get.window');
    Route::get('/assign-window', 'QueuingController\QueuingMainController@assign_window')->name('assign.window');

    Route::get('/get-waitlist', 'QueuingController\QueuingMainController@get_waitlist')->name('get.waitlist');
    Route::get('/waitlist-markdone', 'QueuingController\QueuingMainController@waitlist_markdone')->name('waitlist.markdone');

});

Route::middleware(['cors'])->group(function () {
    Route::view('/gradelevel', 'superadmin.pages.setup.gradelevel');
    Route::get('/gradelevel/update', 'SuperAdminController\Setup\glvlGradelevelController@glvlUpdate')->name('glvlUpdate');
    Route::get('/gradelevel/list', 'SuperAdminController\Setup\glvlGradelevelController@glvlList')->name('glvlList');
    Route::get('/gradelevel/list/datatable', 'SuperAdminController\Setup\glvlGradelevelController@glvlListDatatable')->name('glvlListDatatable');

    Route::get('/acadprog/update', 'SuperAdminController\Setup\acdprgAcadProgController@acdprgUpdate')->name('acdprgUpdate');
    Route::get('/acadprog/list/datatable', 'SuperAdminController\Setup\acdprgAcadProgController@acdprgListDatatable')->name('acdprgListDatatable');
    Route::get('/acadprog/list/datatable', 'SuperAdminController\Setup\acdprgAcadProgController@acdprgListDatatable')->name('acdprgListDatatable');
});

// EUGZ QUEUING

//college grade summary
Route::middleware(['auth'])->group(function () {
    Route::view('/college/completiongrades', 'superadmin.pages.college.collegecompletiongrades');
    Route::get('/college/completiongrades/getteachers', 'SuperAdminController\College\GradeCompletion@getteachers');
    Route::get('/college/completiongrades/getstudents', 'SuperAdminController\College\GradeCompletion@getstudents');
    Route::get('/college/completiongrades/getsubjects', 'SuperAdminController\College\GradeCompletion@getsubjects');
});
//college grade summary

//college attendance
Route::middleware(['auth'])->group(function () {

    Route::get('/college/attendance-showpage', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcShowPage')->name('clgattndcShowPage');
    Route::get('/college/attendance/generateatttendance', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcGenerateAttendance')->name('clgattndcGenerateAttendance');
    Route::get('/college/attendance/setstatus', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcSetStatus')->name('clgattndcSetStatus');
    Route::get('/college/attendance/bulkrow-setstatus', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcBulkSetRowStatus')->name('clgattndcBulkSetRowStatus');
    Route::get('/college/attendance/bulkcol-setstatus', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcBulkSetColStatus')->name('clgattndcBulkSetColStatus');
    Route::get('/college/attendance/generate-month', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcGeneratePerMonth')->name('clgattndcGeneratePerMonth');
    Route::get('/college/attendance/get-students', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcGetSelect')->name('clgattndcGetSelect');
    Route::get('/college/attendance/get-columns', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcGetColumns')->name('clgattndcGetColumns');
    Route::get('/college/attendance/filtersubject', 'CollegeControllers\ClgCollegeAttendanceController@getFilterSubject')->name('getFilterSubject');

    Route::get('/college/attendance/generate-pdf/{syid}/{subjectid}/{sectionid}/{semid}/{arraymonth}', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcGeneratePDF')->name('clgattndcGeneratePDF');
    Route::get('/college/attendance/generate-excel/{syid}/{subjectid}/{sectionid}/{semid}/{arraymonth}', 'CollegeControllers\ClgCollegeAttendanceController@clgattndcGenerateExcel')->name('clgattndcGenerateExcel');

});

//college attendance

Route::middleware(['auth'])->group(function () {
    Route::view('/teacherevaluation/setup', 'superadmin.pages.setup.tchrEvlSetup');
    Route::get('/teacherevaluation/setup/list', 'SuperAdminController\tchrEvlSetupController@gettchrEvlSetup')->name('gettchrEvlSetup');
    Route::get('/teacherevaluation/setup/generate', 'SuperAdminController\tchrEvlSetupController@tchrEvlgnrteSetup')->name('tchrEvlgnrteSetup');
    // Route::get('/student/viewaccess/access', 'SuperAdminController\StudentViewAccess@viewaccess');
    // Route::get('/student/viewaccess/students', 'SuperAdminController\StudentViewAccess@students');
});



//sched classification
Route::middleware(['cors'])->group(function () {
    Route::view('/college/inputperiods/', 'superadmin.pages.utility.datafromonline');
    Route::get('/college/inputperiods/list', 'SuperAdminController\College\InputPeriodController@inputperiodslist');
    Route::get('/college/inputperiods/create', 'SuperAdminController\College\InputPeriodController@inputperiodscreate');
    Route::get('/college/inputperiods/update', 'SuperAdminController\College\InputPeriodController@inputperiodsupdate');
    Route::get('/college/inputperiods/delete', 'SuperAdminController\College\InputPeriodController@inputperiodsdelete');
    Route::get('/college/inputperiods/get/active', 'SuperAdminController\College\InputPeriodController@getActiveSetup');
});



//SEMESTER SETUP
Route::middleware(['auth'])->group(function () {
    Route::get('/semester-setup', 'CollegeControllers\SemesterSetupController@show');
    Route::get('/semester-setup/search', 'CollegeControllers\emesterSetupController@search')->name('semester-setup.search');
    Route::get('/semester-setup/setactive', 'CollegeControllers\SemesterSetupController@setactive')->name('semester.setactive');
    Route::get('/semester-setup/getsetupdata', 'CollegeControllers\SemesterSetupController@getsetupdata')->name('semester.getsetupdata');
    Route::get('/semester-setup/createtransmute', 'CollegeControllers\SemesterSetupController@createTransmute')->name('create.transmute');
    Route::get('/semester-setup/get-transmutation', 'CollegeControllers\SemesterSetupController@getTranmutation')->name('get.transmutation');

    Route::get('/semester-setup/add', 'CollegeControllers\SemesterSetupController@store')->name('semester-setup.add');
    Route::get('/semester-setup/get-edit', 'CollegeControllers\SemesterSetupController@semesterSetupGetEdit')->name('semester-setup.getEdit');
    Route::get('/semester-setup/edit', 'CollegeControllers\SemesterSetupController@edit')->name('semester-setup.edit');
    Route::get('/semester-setup/delete', 'CollegeControllers\SemesterSetupController@destroy')->name('semester-setup.destroy');

    Route::get('/semester-setup/getactive-setup', 'CollegeControllers\SemesterSetupController@getActiveSetup')->name('getactive.setup');
});

//student special subject
Route::middleware(['cors'])->group(function () {
    Route::view('basiced/student/excludedsubj', 'superadmin.pages.student.studentexclsubj');
    Route::get('basiced/student/excludedsubj/list', 'SuperAdminController\StudentExclSubj@student_exclsubj');
    Route::get('basiced/student/excludedsubj/students', 'SuperAdminController\StudentExclSubj@students');
    Route::get('basiced/student/excludedsubj/gradelevel', 'SuperAdminController\StudentExclSubj@gradelevel');
    Route::get('basiced/student/excludedsubj/sections', 'SuperAdminController\StudentExclSubj@sections');
    Route::get('basiced/student/excludedsubj/subjects', 'SuperAdminController\StudentExclSubj@subjects');
    Route::get('basiced/student/excludedsubj/create', 'SuperAdminController\StudentExclSubj@student_exclsubj_create');
    Route::get('basiced/student/excludedsubj/update', 'SuperAdminController\StudentExclSubj@student_exclsubj_update');
    Route::get('basiced/student/excludedsubj/delete', 'SuperAdminController\StudentExclSubj@student_exclsubj_delete');

    //building sync
    Route::get('api/basiced/student/excludedsubj/syncnew', 'SuperAdminController\StudentExclSubj@syncNew');
    Route::get('api/basiced/student/excludedsubj/syncupdate', 'SuperAdminController\StudentExclSubj@syncUpdate');
    Route::get('api/basiced/student/excludedsubj/syncdelete', 'SuperAdminController\StudentExclSubj@syncDelete');
    Route::get('/api/basiced/student/excludedsubj/getnewinfo', 'SuperAdminController\StudentExclSubj@getNewInfo');
    Route::get('/api/basiced/student/excludedsubj/getupdated', 'SuperAdminController\StudentExclSubj@getUpdateInfo');
    Route::get('/api/basiced/student/excludedsubj/getdeleted', 'SuperAdminController\StudentExclSubj@getDeleteInfo');
    Route::get('/api/basiced/student/excludedsubj/updatestat', 'SuperAdminController\StudentExclSubj@getUpdateStat');

});
//student special subject

//adminadmin
Route::middleware(['cors'])->group(function () {
    Route::get('/passData', 'AdminadminController\AdminAdminController@passData');

    Route::get('/director/finance/cashiertransactions', 'AdminadminController\AdminAdminController@passData');
    Route::get('/director/finance/cashiertransactionsindex', 'DirectorControllers\DirectorFinanceReportsController@cashiertransactionsindex')->name('cashiertransactionsindex');
    Route::get('/director/finance/collectionsindex', 'DirectorControllers\DirectorFinanceReportsController@collectionsindex')->name('collectionsindex');
    Route::get('/director/finance/accountreceivablesindex', 'DirectorControllers\DirectorFinanceReportsController@accountreceivablesindex')->name('accountreceivablesindex');
    Route::get('/director/finance/expensesindex', 'DirectorControllers\DirectorFinanceReportsController@expensesindex')->name('expensesindex');

    Route::get('/viewschool/{id}', 'AdminadminController\AdminAdminController@viewadmiadmin');
    Route::get('/finance/index', 'AdminadminController\AdminAdminController@financeindex');
    Route::get('/academic/index', 'AdminadminController\AdminAdminController@academicindex');
    Route::get('/academic/students', 'AdminadminController\AdminAdminController@academicstudents');
    Route::get('/hr/index', 'AdminadminController\AdminAdminController@hrindex');
    Route::get('/aadmin/enrollment', 'AdminadminController\AdminAdminController@enrollment');

    Route::get('/enrollmentReport', 'AdminadminController\AdminAdminController@enrollmentReport');
    // Route::get('/enrollmentReport','AdminadminController\AdminAdminController@enrollmentReport');
    Route::get('/cashtransReport', 'AdminadminController\AdminAdminController@cashtransReport');

    Route::get('/filtercashtrans', 'AdminadminController\AdminAdminController@filtercashtrans');
    Route::get('/filterEnrollmentReport', 'AdminadminController\AdminAdminController@filterEnrollmentReport');
});


//clinic
Route::middleware(['auth'])->group(function () {

    Route::get('/clinic/doctor/availablity/index', 'ClinicControllers\SchoolDoctorSchedController@index');
    Route::get('/clinic/doctor/availablity/getschedavailability', 'ClinicControllers\SchoolDoctorSchedController@getschedavailability');
    Route::get('/clinic/doctor/availablity/submittime', 'ClinicControllers\SchoolDoctorSchedController@submittime');
    Route::get('/clinic/doctor/availablity/deletetime', 'ClinicControllers\SchoolDoctorSchedController@deletetime');
    Route::get('/clinic/doctor/availablity/getappointments', 'ClinicControllers\SchoolDoctorSchedController@getappointments');
    Route::get('/clinic/doctor/profile/index', 'ClinicControllers\ClinicDoctorController@index');
    Route::post('/doctors', 'ClinicControllers\ClinicDoctorController@saveDoctor');

    Route::get('/clinic/appointment/index', 'ClinicControllers\ClinicAppointmentController@index');
    Route::get('/clinic/appointment/getappointments', 'ClinicControllers\ClinicAppointmentController@getappointments');
    Route::get('/clinic/appointment/getexperiences', 'ClinicControllers\ClinicAppointmentController@getexperiences');
    Route::get('/clinic/appointment/createexperience', 'ClinicControllers\ClinicAppointmentController@createexperience');
    Route::get('/clinic/appointment/deleteexperience', 'ClinicControllers\ClinicAppointmentController@deleteexperience');
    Route::get('/clinic/personnel/index', 'ClinicControllers\PersonnelController@index');
    // Route::get('/clinic/personnel/getemployees', 'ClinicControllers\PersonnelController@getemployees');

    Route::get('/clinic/inventory/index', 'ClinicControllers\MedicineController@index');
    Route::get('/clinic/inventory/add', 'ClinicControllers\MedicineController@add');
    Route::get('/clinic/inventory/showmedicines', 'ClinicControllers\MedicineController@showmedicines');
    Route::get('/clinic/inventory/delete', 'ClinicControllers\MedicineController@delete');
    Route::get('/clinic/inventory/getmedinfo', 'ClinicControllers\MedicineController@getmedinfo');
    Route::get('/clinic/inventory/edit', 'ClinicControllers\MedicineController@edit');

    Route::get('/clinic/complaints/index', 'ClinicControllers\ComplaintController@index');
    Route::get('/clinic/complaints/getallusers', 'ClinicControllers\ComplaintController@getallusers');
    Route::get('/clinic/complaints/getausertype', 'ClinicControllers\ComplaintController@getallusertype');
    Route::get('/clinic/complaints/add', 'ClinicControllers\ComplaintController@add');
    Route::get('/clinic/complaints/getcomplaints', 'ClinicControllers\ComplaintController@getcomplaints');
    Route::get('/clinic/complaints/getusercomplaints', 'ClinicControllers\ComplaintController@getuserComplaints');
    Route::get('/clinic/complaints/getinfo', 'ClinicControllers\ComplaintController@getinfo');
    Route::get('/clinic/complaints/getdrugs', 'ClinicControllers\ComplaintController@getdrugs');
    Route::get('/clinic/complaints/addmed', 'ClinicControllers\ComplaintController@addmed');
    Route::get('/clinic/complaints/edit', 'ClinicControllers\ComplaintController@edit');
    Route::get('/clinic/complaints/delete', 'ClinicControllers\ComplaintController@delete');
    Route::get('/clinic/complaints/editmed', 'ClinicControllers\ComplaintController@editmed');
    Route::get('/clinic/complaints/deletemed', 'ClinicControllers\ComplaintController@deletemed');
    Route::get('/clinic/complaints/addPres', 'ClinicControllers\ComplaintController@addPrescription');
    Route::get('/clinic/complaints/viewPres', 'ClinicControllers\ComplaintController@viewPrescription');
    Route::get('/clinic/complaints/getadoc', 'ClinicControllers\ComplaintController@getDoctor');
    Route::get('/clinic/complaints/update', 'ClinicControllers\ComplaintController@statusUpdate');
    Route::get('/clinic/complaints/delpres', 'ClinicControllers\ComplaintController@deletePres');
    Route::get('/pdf/download', 'ClinicControllers\ComplaintController@generatePDF')->name('pdf.download');

    Route::get('/clinic/patientdashboard/index', 'ClinicControllers\ClinicPatientController@index');
    Route::get('/clinic/patientdashboard/createapp', 'ClinicControllers\ClinicPatientController@createapp');
    Route::get('/clinic/patientdashboard/editappointments', 'ClinicControllers\ClinicPatientController@updateappointment');
    Route::get('/clinic/patientdashboard/getappointments', 'ClinicControllers\ClinicPatientController@getappointments');
    Route::get('/clinic/patientdashboard/deleteappointment', 'ClinicControllers\ClinicPatientController@deleteappointment');


    Route::get('/clinic/records/submitform1a', 'ClinicControllers\SHFormController@submitform1a');
    Route::get('/clinic/records/submitform1b', 'ClinicControllers\SHFormController@submitform1b');

    Route::get('/clinic/records/form4/addnewfamhisoption', 'ClinicControllers\SHFormController@form4_addnewfamhisoption');
    Route::get('/clinic/records/form4/deletefamhisoption', 'ClinicControllers\SHFormController@form4_deletefamhisoption');
    Route::get('/clinic/records/form4/addnewpastmedhisoption', 'ClinicControllers\SHFormController@form4_addnewpastmedhisoption');
    Route::get('/clinic/records/form4/deletepastmedhisoption', 'ClinicControllers\SHFormController@form4_deletepastmedhisoption');
    Route::get('/clinic/records/form4/addlasttaken', 'ClinicControllers\SHFormController@form4_addlasttaken');
    Route::get('/clinic/records/form4/deletelasttaken', 'ClinicControllers\SHFormController@form4_deletelasttaken');

    Route::get('/clinic/records/emptyform', 'ClinicControllers\SHFormController@emptyform');

    // Route::get('/clinic/records/getform1a', 'ClinicControllers\SHFormController@getform1a');
    // Route::get('/clinic/records/getform1b', 'ClinicControllers\SHFormController@getform1b');
    // Route::get('/clinic/records/getform1c', 'ClinicControllers\SHFormController@getform1c');
    // Route::get('/clinic/records/getform1d', 'ClinicControllers\SHFormController@getform1d');
    // Route::get('/clinic/records/getform1da', 'ClinicControllers\SHFormController@getform1da');
    // Route::get('/clinic/records/getform1db', 'ClinicControllers\SHFormController@getform1db');
    // Route::get('/clinic/records/getform3a', 'ClinicControllers\SHFormController@getform3a');
    // Route::get('/clinic/records/getform3b', 'ClinicControllers\SHFormController@getform3b');
    // Route::get('/clinic/records/getform4', 'ClinicControllers\SHFormController@getform4');
    // Route::get('/clinic/records/getform4a', 'ClinicControllers\SHFormController@getform4a');

    Route::get('/clinic/forms/index', 'ClinicControllers\SHFormController@index');

    //DOCTOR
    Route::get('/clinic/appointment/admitaccept', 'ClinicControllers\ClinicAppointmentController@admitaccept');
    Route::get('/clinic/appointment/admitcancel', 'ClinicControllers\ClinicAppointmentController@admitcancel');
    Route::get('/clinic/appointment/markdone', 'ClinicControllers\ClinicAppointmentController@markdone');

    //MEDICAL HISTORY
    Route::get('/clinic/medicalhistory/index', 'ClinicControllers\MedicalHistoryController@index');
    Route::get('/clinic/medicalhistory/gethistory', 'ClinicControllers\MedicalHistoryController@gethistory');
    Route::get('/clinic/medicalhistory/gethistory2', 'ClinicControllers\MedicalHistoryController@gethistory2');
    Route::get('/clinic/medicalhistory/getcomplaint2', 'ClinicControllers\MedicalHistoryController@getuserComplaints');
    Route::get('/clinic/medicalhistory/update', 'ClinicControllers\MedicalHistoryController@update');
    Route::get('/clinic/medicalhistory/get', 'ClinicControllers\MedicalHistoryController@get');
});

Route::view('/clinic/records/index', 'clinic.records.index');
Route::get('/clinic/records/getform', 'ClinicControllers\SHFormController@getform');



Route::middleware(['cors'])->group(function () {

    Route::view('/buildings', 'adminPortal.pages.buildings');
    Route::get('api/buildings', 'AdministratorControllers\BuildingController@getBuildingsDatatable');
    Route::get('api/buildings/datatable', 'AdministratorControllers\BuildingController@getBuildingsDatatable');
    Route::get('api/buildings/select', 'AdministratorControllers\BuildingController@getBuildingsSelect');
    Route::get('api/building/update', 'AdministratorControllers\BuildingController@buildingUpdate');
    Route::get('api/building/create', 'AdministratorControllers\BuildingController@buildingCreate');
    Route::get('api/building/delete', 'AdministratorControllers\BuildingController@buildingDelete');

    // JAM: custom routes
    Route::get('api/building/rooms', 'AdministratorControllers\BuildingController@getBuildingRooms');
    Route::get('api/building/all-rooms-except', 'AdministratorControllers\BuildingController@getAllRoomsExcept');
    Route::get('api/buildings-rooms/datatable', 'AdministratorControllers\BuildingController@getBuildingsRoomsDatatable');
    Route::get('api/rooms/assign', 'AdministratorControllers\BuildingController@assignRoomsToBuilding');
    // JAM: custom routes

    //building sync
    Route::get('api/building/syncnew', 'AdministratorControllers\BuildingController@syncNew');
    Route::get('api/building/syncupdate', 'AdministratorControllers\BuildingController@syncUpdate');
    Route::get('api/building/syncdelete', 'AdministratorControllers\BuildingController@syncDelete');
    Route::get('/api/building/getnewinfo', 'AdministratorControllers\BuildingController@getNewInfo');
    Route::get('/api/building/getupdated', 'AdministratorControllers\BuildingController@getUpdateInfo');
    Route::get('/api/building/getdeleted', 'AdministratorControllers\BuildingController@getDeleteInfo');
    Route::get('/api/building/updatestat', 'AdministratorControllers\BuildingController@getUpdateStat');

});

// GIAN
// teacher portal scheduling (create their own scheduling)
Route::middleware(['auth'])->group(function () {
    Route::get('/principal/setup/schedule/get/teachersview', 'TeacherControllers\tchrTeacherSchedulingController@tchrViewSchedules');
    Route::get('/principal/setup/schedule/get/viewallsched', 'TeacherControllers\tchrTeacherSchedulingController@tchrViewAllSched');
    Route::get('/principal/setup/schedule/get/getallsections', 'TeacherControllers\tchrTeacherSchedulingController@tchrGetAllSections');
    Route::get('/principal/setup/schedule/get/allsectionchedsules', 'TeacherControllers\tchrTeacherSchedulingController@tchrAllSectionSchedules');
    Route::get('/principal/setup/schedule/get/shssubjectbysem', 'TeacherControllers\tchrTeacherSchedulingController@tchrShsSubjectBySem');
    Route::get('/principal/setup/schedule/get/sched', 'PrincipalControllers\ScheduleController@get_schedule_2');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/scheduling/teacher/loadteachersubjcom', 'TeacherControllers\tchrTeacherSchedulingController@loadteachersubjcom');
    Route::get('/scheduling/teacher/editeachersubjcom', 'TeacherControllers\tchrTeacherSchedulingController@editeachersubjcom');
    Route::get('/scheduling/teacher/deleteteachersubjcom', 'TeacherControllers\tchrTeacherSchedulingController@deleteteachersubjcom');
    Route::get('/scheduling/teacher/getteachersubjectplot', 'TeacherControllers\tchrTeacherSchedulingController@getteachersubjectplot');
    Route::get('/scheduling/teacher/teacherdeletedsubjcom', 'TeacherControllers\tchrTeacherSchedulingController@teacherdeletedsubjcom');

    Route::get('/principal/setup/tchrgetallpercentagecomponent', 'TeacherControllers\tchrTeacherSchedulingController@tchrgetallpercentagecomponent');
    Route::get('/principal/setup/tchrgetallpercom', 'TeacherControllers\tchrTeacherSchedulingController@tchrgetallpercom');
    Route::get('/principal/setup/tchraddpercentagecomponent', 'TeacherControllers\tchrTeacherSchedulingController@tchraddpercentagecomponent');
    Route::get('/principal/setup/tchreditpercentagecomponent', 'TeacherControllers\tchrTeacherSchedulingController@tchreditpercentagecomponent');
    Route::get('/principal/setup/tchrdeletepercentagecomponent', 'TeacherControllers\tchrTeacherSchedulingController@tchrdeletepercentagecomponent');
    Route::get('/principal/setup/tchraddsubjectcomponent', 'TeacherControllers\tchrTeacherSchedulingController@tchraddsubjectcomponent');
});
// teacher portal scheduling (create their own scheduling)
Route::middleware(['auth'])->group(function () {
    Route::get('/college/setup/schedule/get/tchrcollegeviewschedules', 'CTController\tchrCtTeacherSchedulingController@tchrCollegeViewSchedules');
});

// Report Card Layout Automate
Route::middleware(['auth'])->group(function () {
    // Route::get('/setup/reportcard/layouts', 'SuperAdminController\ReportCardLayoutsController@index');
    Route::view('/setup/reportcard/layouts', 'superadmin.pages.reportcard.index');
    Route::get('/setup/reportcard/gettemplates', 'SuperAdminController\ReportCardLayoutsController@gettemplates');
    Route::get('/setup/reportcard/activatetemplate', 'SuperAdminController\ReportCardLayoutsController@activatetemplate');
    Route::get('/setup/reportcard/addtemplate', 'SuperAdminController\ReportCardLayoutsController@addtemplate');
    Route::get('/setup/reportcard/viewblanktemplate/{id}{acadid}', 'SuperAdminController\ReportCardLayoutsController@viewblanktemplate');
    Route::get('/setup/reportcard/activetemplatepdf', 'SuperAdminController\ReportCardLayoutsController@activetemplatepdf');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/sf9template/blank', 'PrincipalControllers\DynamicPDFController@blanksf9pdf');
});

// FAQ's
Route::middleware(['auth'])->group(function () {
    Route::view('/faqs/setup', 'superadmin.pages.setup.FAQs.faqs');
    Route::post('/faqs/setup/createfaq', 'SuperAdminController\setup\FaqsSetupController@createfaq');
    Route::post('/faqs/setup/update_details', 'SuperAdminController\setup\FaqsSetupController@update_details');

    Route::get('/faqs/setup/loadtopdesc', 'SuperAdminController\setup\FaqsSetupController@loadtopdesc');
    Route::get('/faqs/setup/assignfaqs', 'SuperAdminController\setup\FaqsSetupController@assignfaqs');
    Route::get('/faqs/setup/loadassignfaqs', 'SuperAdminController\setup\FaqsSetupController@loadassignfaqs');
    Route::get('/faqs/setup/loadassignmanual', 'SuperAdminController\setup\FaqsSetupController@loadassignmanual');

    Route::get('/faqs/setup/loadmanuals', 'SuperAdminController\setup\FaqsSetupController@loadmanuals');
    Route::get('/faqs/setup/assignmanual', 'SuperAdminController\setup\FaqsSetupController@assignmanual');
    Route::get('/faqs/setup/assignmanualv2', 'SuperAdminController\setup\FaqsSetupController@assignmanualv2');
    Route::get('/faqs/setup/deletemanualv2', 'SuperAdminController\setup\FaqsSetupController@deletemanualv2');
    Route::get('/faqs/setup/deletefaqs', 'SuperAdminController\setup\FaqsSetupController@deletefaqs');

    Route::get('/faqs/setup/loaduserguidedetail', 'SuperAdminController\setup\FaqsSetupController@loaduserguidedetail');
    Route::get('/faqs/setup/loadusertype', 'SuperAdminController\setup\FaqsSetupController@loadusertype');


    Route::get('/faqs/setup/deletetopdesc', 'SuperAdminController\setup\FaqsSetupController@deletetopdesc');

});

Route::middleware(['auth'])->group(function () {
    Route::view('/userguide/setup', 'superadmin.pages.setup.FAQs.userguideperportal');
    Route::get('/userguide/loadfaqs', 'SuperAdminController\setup\FaqsSetupController@loadfaqs');
    Route::get('/userguide/loadusersmanual', 'SuperAdminController\setup\FaqsSetupController@loadusersmanual');

    Route::get('/userguide/generatepdf', 'SuperAdminController\setup\FaqsSetupController@generatepdf')->name('generatepdf');

});

// School List
Route::middleware(['auth'])->group(function () {
    Route::view('/schoolist/setup', 'superadmin.pages.setup.schoollist.schoollist');

    Route::post('/schoollist/setup/addschoolinfo', 'SuperAdminController\setup\SchoollistSetupController@addschoolinfo');
    Route::get('/schoollist/setup/loadschools', 'SuperAdminController\setup\SchoollistSetupController@loadschools');
    Route::get('/schoollist/setup/delete_school', 'SuperAdminController\setup\SchoollistSetupController@delete_school');
    Route::get('/schoollist/setup/edit_school', 'SuperAdminController\setup\SchoollistSetupController@edit_school');
    Route::post('/schoollist/setup/update_schoolinfo', 'SuperAdminController\setup\SchoollistSetupController@update_schoolinfo');
    Route::post('/schoollist/setup/updatelogo', 'SuperAdminController\setup\SchoollistSetupController@updatelogo');

});

Route::middleware(['auth'])->group(function () {
    Route::view('setup/document', 'superadmin.pages.setup.documentssetup');
    Route::view('/superadmin/setup/document/logs', 'SuperAdminController\DocumentsController@logs');
    Route::get('/superadmin/setup/document/create', 'SuperAdminController\DocumentsController@create');
    Route::get('/superadmin/setup/document/update', 'SuperAdminController\DocumentsController@update');
    Route::get('/superadmin/setup/document/delete', 'SuperAdminController\DocumentsController@delete');
    Route::get('/superadmin/setup/document/copy', 'SuperAdminController\DocumentsController@copy');

    Route::get('/superadmin/setup/docdesc/create', 'SuperAdminController\DocumentsDescController@create');
    Route::get('/superadmin/setup/docdesc/update', 'SuperAdminController\DocumentsDescController@update');
    Route::get('/superadmin/setup/docdesc/delete', 'SuperAdminController\DocumentsDescController@delete');
    Route::get('/superadmin/setup/docdesc/list', 'SuperAdminController\DocumentsDescController@list');

});
//Student Clearance
// Registrar Portal
Route::get('/setup/student/clearance/systatus', 'ClearanceController@systatus');
Route::get('/setup/student/clearance/signatories', 'ClearanceController@index');
Route::get('/setup/student/clearance/signatory/list', 'ClearanceController@signatories_list');
Route::get('/setup/student/clearance/signatory/acadprog', 'ClearanceController@acadprogid_list');
Route::get('/setup/student/clearance/save', 'ClearanceController@save_signatory');
Route::get('/setup/student/clearance/delete', 'ClearanceController@delete_signatory');
Route::get('/setup/student/clearance/activation', 'ClearanceController@activate_clearance');
Route::get('/setup/students/clearance/getacadterm', 'ClearanceController@getacadterm');
Route::get('/setup/students/clearance/getenablestatus', 'ClearanceController@getenablestatus');
Route::get('/setup/students/clearance/activate/acadterm', 'ClearanceController@activate_acadterm');
Route::get('/setup/student/clearance/approval', 'ClearanceController@approval');
Route::get('/setup/student/clearance/getacadprog', 'ClearanceController@getacadprog');
Route::get('/setup/student/clearance/monitoring', 'ClearanceController@monitoring');

// Teacher Portal Advisory
Route::get('/students/clearance', 'ClearanceController@clearance');
Route::get('/students/clearance/getacadterm', 'ClearanceController@getacadterm_teacher');
Route::get('/students/clearance/getgradelevel', 'ClearanceController@getgradelevel_teacher');
Route::get('/students/clearance/getsection', 'ClearanceController@getsection_teacher');
Route::get('/students/clearance/getstudents', 'ClearanceController@getclearancestud_teacher');
Route::get('/students/clearance/approve', 'ClearanceController@approve_clearance_teacher');
Route::get('/students/clearance/approveall', 'ClearanceController@approve_allclearance_teacher');
Route::get('/students/clearance/approve_allclearanceadvisory', 'ClearanceController@approve_allclearanceadvisory');
Route::get('/students/clearance/getsubjects', 'ClearanceController@getsubjects');
// Teacher Portal By Subject
Route::get('/students/clearance/bysubject', 'ClearanceController@clearancebysubject');

// Departments Portal
Route::get('/students/clearance/approval/getgradelevel', 'ClearanceController@getgradelevel');
Route::get('/students/clearance/approval/getallgradelevel', 'ClearanceController@getallgradelevel');
Route::get('/students/clearance/approval/getsection', 'ClearanceController@getsection');
Route::get('/students/clearance/approval/getclearancestud', 'ClearanceController@getclearancestud');
Route::get('/students/clearance/approval/approve', 'ClearanceController@approve_clearance');
Route::get('/students/clearance/approval/approveall', 'ClearanceController@approve_allclearance');
Route::get('/students/clearance/approval/getallclearancestat', 'ClearanceController@getallclearancestat');
Route::get('/students/clearance/approval/count', 'ClearanceController@countclearedstatus');

// Student & Parent View
Route::get('/setup/student/clearance/view', 'ClearanceController@view');
Route::get('/setup/student/clearance/gethistory', 'ClearanceController@gethistory');
Route::get('/setup/student/clearance/getdata', 'ClearanceController@getclearancedata');

//term setup
Route::middleware(['auth'])->group(function () {
    Route::view('/setup/acadterm', 'clearance.acadterm');
    Route::get('/acadterm/list', 'ClearanceController@acadterm_list');
    Route::get('/acadterm/save', 'ClearanceController@save_term');
    Route::get('/acadterm/delete', 'ClearanceController@delete_term');
});

//student quarter
Route::middleware(['auth'])->group(function () {
    Route::view('/college/grade/access', 'superadmin.pages.college.gradeaccess');
    Route::get('/college/grade/access/list', 'SuperAdminController\College\CollegeGradeAccessController@list');
    Route::get('/college/grade/access/updatestatus', 'SuperAdminController\College\CollegeGradeAccessController@updatestatus');
    Route::get('/college/grade/access/adduser', 'SuperAdminController\College\CollegeGradeAccessController@adduser');
    Route::get('/college/grade/access/removeuser', 'SuperAdminController\College\CollegeGradeAccessController@removeuser');
});
//student quarter


//student quarter
Route::middleware(['auth'])->group(function () {
    Route::view('/student/quarter', 'superadmin.pages.student.studentquarter');
    Route::get('/student/quarter/students', 'SuperAdminController\StudentQuarterController@students');
    Route::get('/student/quarter/updatestatus', 'SuperAdminController\StudentQuarterController@updatequarterstatus');
});
//student quarter

Route::middleware(['auth'])->group(function () {
    Route::view('/url', 'superadmin.pages.url_setup');
    Route::get('/url/list', 'URLController@url_list');
    Route::get('/url/create', 'URLController@url_create');
    Route::get('/url/update', 'URLController@url_update');
    Route::get('/url/delete', 'URLController@url_delete');

    Route::get('/url/setup/list', 'URLController@url_setup_list');
    Route::get('/url/setup/create', 'URLController@url_setup_create');
    Route::get('/url/setup/update', 'URLController@url_setup_update');
    Route::get('/url/setup/delete', 'URLController@url_setup_delete');
});



Route::middleware(['auth'])->group(function () {
    Route::view('/student/scholarship/view', 'studentPortal.pages.scholarship.scholarshipindex');
    Route::get('/student/scholarship', 'StudentControllers\ScholarshipController@getScholarship');
    Route::get('/student/scholarship/getRequirement', 'StudentControllers\ScholarshipController@getRequirement');
    Route::post('/student/scholarship/saveScholarship', 'StudentControllers\ScholarshipController@saveScholarship');
    Route::post('/uploadrequirement', 'StudentControllers\ScholarshipController@uploadrequirement');
    Route::post('/student/delete/scholarship', 'StudentControllers\ScholarshipController@delscholarship');
});




//izakahrt added here

Route::middleware(['auth'])->group(function () {


    //Izakahr Added Here




    //Admission Test
    Route::get('/admissiontest/index', 'AdministratorControllers\AdmissionTestController@index');
    Route::get('/admissiontest/createblade', 'AdministratorControllers\AdmissionTestController@createBladeTest');
    Route::get('/admissiontest/table', 'AdministratorControllers\AdmissionTestController@testTable');


    //Test Creation
    Route::get('/admissiontest/create', 'AdministratorControllers\AdmissionTestController@createTest');
    Route::get('/admissiontest/delete', 'AdministratorControllers\AdmissionTestController@deleteTest');
    Route::get('/admissiontest/quiz/description', 'AdministratorControllers\AdmissionTestController@saveDescription');
    Route::get('/admissiontest/quiz/title', 'AdministratorControllers\AdmissionTestController@saveTitle');

    //TEST

    Route::get('/admissiontest/test/{id}', 'AdministratorControllers\AdmissionTestController@Test');
    Route::get('/admissiontest/test2/{id}', 'AdministratorControllers\AdmissionTestController@Test2');
    Route::get('/admissiontest/addquestion', 'AdministratorControllers\AdmissionTestController@addQuestion');
    Route::get('/admissiontest/quiz/addcategory', 'AdministratorControllers\AdmissionTestController@addCategory');
    Route::get('/admissiontest/quiz/getcategory', 'AdministratorControllers\AdmissionTestController@getCategory');
    Route::get('/admissiontest/quiz/category/sorting', 'AdministratorControllers\AdmissionTestController@sortCategory');
    Route::get('/admissiontest/category/delcategory', 'AdministratorControllers\AdmissionTestController@delCategory');
    Route::get('/adminviewbook/category/select', 'AdministratorControllers\AdmissionTestController@categorySelect')->name('categorySelect');
    Route::get('/admissiontest/category/asssignCategory', 'AdministratorControllers\AdmissionTestController@asssignCategory');
    Route::get('/admissiontest/delquestion', 'AdministratorControllers\AdmissionTestController@delQuestion');
    Route::POST('/admissiontest/createquestion', 'AdministratorControllers\AdmissionTestController@createQuestion');
    Route::POST('/admissiontest/createquestion2', 'AdministratorControllers\AdmissionTestController@createQuestion2');
    Route::get('admissiontest/createquestionitem', 'AdministratorControllers\AdmissionTestController@createquestionitem');
    Route::get('/admissiontest/createchoices', 'AdministratorControllers\AdmissionTestController@createChoices');
    Route::get('/admissiontest/del-choices', 'AdministratorControllers\AdmissionTestController@delOption');
    Route::get('/admissiontest/createfillquestion', 'AdministratorControllers\AdmissionTestController@createFillquestion');
    Route::get('/admissiontest/createdragoption', 'AdministratorControllers\AdmissionTestController@createdragoption');
    Route::get('/admissiontest/createdropquestion', 'AdministratorControllers\AdmissionTestController@createdropquestion');



    //Answer key
    Route::get('/admissiontest/getquestion', 'AdministratorControllers\AdmissionTestController@getquestion');
    Route::get('/admissiontest/save-answer-key', 'AdministratorControllers\AdmissionTestController@setAnswerKey');
    Route::get('/admissiontest/returneditquiz', 'AdministratorControllers\AdmissionTestController@returneditquiz');
    Route::get('/admissiontest/getenumquestion', 'AdministratorControllers\AdmissionTestController@getEnum');
    Route::get('/admissiontest/returneditquizenum', 'AdministratorControllers\AdmissionTestController@returnEditenum');
    Route::get('/admissiontest/getfillquestion', 'AdministratorControllers\AdmissionTestController@getFillQuestion');
    Route::get('/admissiontest/returneditfill', 'AdministratorControllers\AdmissionTestController@returnEditfill');
    Route::get('/admissiontest/getdropquestion', 'AdministratorControllers\AdmissionTestController@getDropQuestion');
    Route::get('/admissiontest/save-answer-drop', 'AdministratorControllers\AdmissionTestController@setAnswerdrop');
    Route::get('/admissiontest/returneditquizdrag', 'AdministratorControllers\AdmissionTestController@returnEditdrag');


    Route::get('/admissiontest/setpoints', 'AdministratorControllers\AdmissionTestController@setPoints');
    Route::get('/admissiontest/quiz/fill/setpoints', 'AdministratorControllers\AdmissionTestController@setFillPoints');
    Route::get('/admissiontest/quiz/drag/setpoints', 'AdministratorControllers\AdmissionTestController@setDragPoints');



    //Activate Test
    Route::get('/admissiontest/activate', 'AdministratorControllers\AdmissionTestController@applicantIndex');

    Route::get('/admissionTable', 'AdministratorControllers\AdmissionTestController@ActivateTable');



    Route::get('/admissiontest/activation', 'AdministratorControllers\AdmissionTestController@testActivation');



    //take test
    // Route::get('/admission/{link}', 'AdministratorControllers\AdmissionTestController@testView');



    Route::get('/admissiontest/take/{poolnum}/{testcode}/{schedid}', 'AdministratorControllers\AdmissionTestController@takeTest');


    Route::get('/admissiontest/save-answer', 'AdministratorControllers\AdmissionTestController@saveAnswer');


    Route::get('/admissiontest/save-timeremaining', 'AdministratorControllers\AdmissionTestController@saveTime');
    Route::get('/admissiontest/submitanswers', 'AdministratorControllers\AdmissionTestController@submitAnswer');





    //Applicant Register
    Route::get('/applicants/get', 'AdministratorControllers\AdmissionTestController@applicantGet');
    Route::get('/admissiontest/updateApplicant', 'AdministratorControllers\AdmissionTestController@updateApplicant');
    Route::get('/admissiontest/addApplicant', 'AdministratorControllers\AdmissionTestController@addApplicant');
    Route::get('/applicants/confirmPool', 'AdministratorControllers\AdmissionTestController@confirmPool');
    Route::get('/applicants/score/category', 'AdministratorControllers\AdmissionTestController@getScoreCategory');
    Route::get('/applicants/delete', 'AdministratorControllers\AdmissionTestController@applicantDelete');
    Route::get('/applicants/eligibilty', 'AdministratorControllers\AdmissionTestController@applicantEligible');
    Route::get('/applicants/result', 'AdministratorControllers\AdmissionTestController@applicantResult');





    //view response
    Route::get('/admission/viewtestresponse/{recordid}/{testid}', 'AdministratorControllers\AdmissionTestController@viewResponse');
    Route::get('/updatescore', 'AdministratorControllers\AdmissionTestController@updatescore');
    Route::get('/donecheck', 'AdministratorControllers\AdmissionTestController@doneCheck');


    //Admission SetUP
    Route::get('/admissiontest/setupTable', 'AdministratorControllers\AdmissionTestController@setupTable');
    Route::get('/admissiontest/setup/test', 'AdministratorControllers\AdmissionTestController@testSelect')->name('testSelect');


    Route::get('/admissiontest/setupTable', 'AdministratorControllers\AdmissionTestController@setupTable');
    Route::get('/admissiontest/addSetup', 'AdministratorControllers\AdmissionTestController@setupSave');
    Route::get('/admissiontest/addSubjectSetup', 'AdministratorControllers\AdmissionTestController@subjectSetupSave');
    Route::get('/admissiontest/setup/delete', 'AdministratorControllers\AdmissionTestController@setupDel');
    Route::get('/admissiontest/getSubject', 'AdministratorControllers\AdmissionTestController@geSubject');
    Route::view('/admissiontake/doneTake', 'adminPortal.pages.admissiontest.donetake');


    //izakahr latest 02 12 23
    Route::get('/admissiontest/addGeneralSetup', 'AdministratorControllers\AdmissionTestController@generalSetupSave');
    Route::get('/getgeneralsetup', 'AdministratorControllers\AdmissionTestController@getGeneralSetup');
    Route::get('/getsubgeneralsetup', 'AdministratorControllers\AdmissionTestController@getsubgeneralsetup');
    Route::get('/admissiontest/addGeneralSetupPercentage', 'AdministratorControllers\AdmissionTestController@generalSetupSavePercentage');
    Route::get('/admissiontest/addGeneralSetupOveralPercentage', 'AdministratorControllers\AdmissionTestController@generalSetupSaveOverall');
    Route::get('/applicants/setup/input', 'AdministratorControllers\AdmissionTestController@saveApplicantsInput');


    //Guidance Behavior
    Route::view('/guidance/behavior', 'admission.pages.behavior');
    Route::get('/guidance/reportTable', 'TeacherControllers\ObservableBahaviorController@reportTable');
    Route::get('/guidance/reportTable2', 'TeacherControllers\ObservableBahaviorController@reportTable2');
    Route::get('/guidance/reportresolve', 'TeacherControllers\ObservableBahaviorController@reportResolve');
    Route::get('/guidance/report/resolve', 'TeacherControllers\ObservableBahaviorController@resolveReport');
    Route::post('/guidance/behavior/summon', 'TeacherControllers\ObservableBahaviorController@summonReport');

    Route::get('/guidance/getAbsence', 'TeacherControllers\ObservableBahaviorController@getAbsence');
    Route::get('/guidance/getAbsence/dates', 'TeacherControllers\ObservableBahaviorController@getabsenceDate');


    //get student
    Route::get('/guidance/getstudenrs/', 'TeacherControllers\ObservableBahaviorController@studentSelect')->name('studentSelect');



    //acceptApplicant
    Route::post('/admissiontest/applicant/accept', 'AdministratorControllers\AdmissionTestController@acceptApplicant');
    Route::view('/admissiontest/view/accepted', 'guidance.pages.acceptedapplicant');
    Route::get('/admissiontest/applicant/getaccepted', 'AdministratorControllers\AdmissionTestController@getAcceptApplicant');
    Route::get('/guidance/getprogram/', 'AdministratorControllers\AdmissionTestController@programSelect')->name('programSelect');
    Route::get('/acceptedapplicants/delete', 'AdministratorControllers\AdmissionTestController@deleteAccepted');

    Route::get('/parent/student/guidance/index/', 'StudentControllers\StudentBehaviorController@parentIndex');
    Route::post('/teacher/behavior/submitBehavior', 'TeacherControllers\ObservableBahaviorController@submitBehavior');

});



Route::middleware(['auth'])->group(function () {
    // User Notifications
    Route::get('/user/notification/userview_notifications', 'HRControllers\HREmployeeNotificationsController@userview_notifications');
    Route::get('/user/notification/load_user_notifications', 'HRControllers\HREmployeeNotificationsController@load_user_notifications');
    Route::get('/user/notification/acknowledge_notification', 'HRControllers\HREmployeeNotificationsController@acknowledge_notification');
});






Route::view('/applicant/form', 'guidance.pages.admissiontest.applicantform');
Route::view('/ineligible/', 'guidance.pages.admissiontest.test.ineligible');
Route::post('/admissiontest/submitApplicant', 'AdministratorControllers\AdmissionTestController@submitaddApplicant')->name('submit_applicant');
Route::get('/applicant/form/success', 'AdministratorControllers\AdmissionTestController@submitaddApplicant')->name('submit_applicant');
Route::get('/applicant/form/success', 'AdministratorControllers\AdmissionTestController@successview');



Route::middleware(['auth'])->group(function () {
    Route::view('/teacher/evaluation/setup', 'superadmin.pages.teachereval_setup');
    Route::view('/teacher/evaluation', 'studentPortal.pages.teacherevaluation');
    Route::view('/teacher/evaluation/teacher', 'studentPortal.pages.teacherevaluation_teacher');
    Route::view('/teacher/evaluation/monitoring', 'superadmin.pages.teachereval_monitoring');
    Route::view('/teacher/evaluation/monitoring/teacher', 'superadmin.pages.teachereval_monitoring_teacher');

    Route::get('/teacher/evaluation/monitoring/list', 'TeacherEvaluationController@teval_monitoring');
    Route::get('/teacher/evaluation/monitoring/print', 'TeacherEvaluationController@teval_print');

    Route::get('/teacher/evaluation/monitoring/teacher/list', 'TeacherEvaluationController@teval_monitoring_teacher');
    Route::get('/teacher/evaluation/monitoring/teacher/print', 'TeacherEvaluationController@teval_print_teacher');


    Route::get('/teval/evaluation/submit', 'TeacherEvaluationController@teval_submit_answer');
    Route::get('/teval/evaluation/answer', 'TeacherEvaluationController@teval_answer');

    Route::get('/teval/evaluation/answer/teacher', 'TeacherEvaluationController@teval_answer_teacher');
    Route::get('/teval/evaluation/submit/teacher', 'TeacherEvaluationController@teval_submit_answer_teacher');

    Route::get('/teval/list', 'TeacherEvaluationController@teval_list');
    Route::get('/teval/create', 'TeacherEvaluationController@teval_create');
    Route::get('/teval/update', 'TeacherEvaluationController@teval_update');
    Route::get('/teval/delete', 'TeacherEvaluationController@teval_delete');

    Route::get('/teval/question/option/list', 'TeacherEvaluationController@teval_question_option_list');
    Route::get('/teval/question/option/create', 'TeacherEvaluationController@teval_question_option_create');
    Route::get('/teval/question/option/update', 'TeacherEvaluationController@teval_question_option_update');
    Route::get('/teval/question/option/delete', 'TeacherEvaluationController@teval_question_option_delete');

    Route::get('/teval/question/option/detail/list', 'TeacherEvaluationController@teval_question_option_detail_list');
    Route::get('/teval/question/option/detail/create', 'TeacherEvaluationController@teval_question_option_detail_create');
    Route::get('/teval/question/option/detail/update', 'TeacherEvaluationController@teval_question_option_detail_update');
    Route::get('/teval/question/option/detail/delete', 'TeacherEvaluationController@teval_question_option_detail_delete');

    Route::get('/teval/group/list', 'TeacherEvaluationController@teval_group_list');
    Route::get('/teval/group/create', 'TeacherEvaluationController@teval_group_create');
    Route::get('/teval/group/update', 'TeacherEvaluationController@teval_group_update');
    Route::get('/teval/group/delete', 'TeacherEvaluationController@teval_group_delete');

    Route::get('/teval/question/list', 'TeacherEvaluationController@teval_question_list');
    Route::get('/teval/question/create', 'TeacherEvaluationController@teval_question_create');
    Route::get('/teval/question/update', 'TeacherEvaluationController@teval_question_update');
    Route::get('/teval/question/delete', 'TeacherEvaluationController@teval_question_delete');

    Route::get('/teval/sy/list', 'TeacherEvaluationController@teval_sy_list');
    Route::get('/teval/sy/create', 'TeacherEvaluationController@teval_sy_create');
    Route::get('/teval/sy/update', 'TeacherEvaluationController@teval_sy_update');
    Route::get('/teval/sy/delete', 'TeacherEvaluationController@teval_sy_delete');
    Route::get('/teval/sy/activate', 'TeacherEvaluationController@teval_sy_activate');
    Route::get('/teval/sy/end', 'TeacherEvaluationController@teval_sy_end');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/send-email', 'Email\EmailController@sendWelcomeEmail');

});

