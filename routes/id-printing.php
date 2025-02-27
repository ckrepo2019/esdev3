<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    // ID PRINTING ROUTES
    Route::view('/idprinting/home', 'id_creator_system.mainpage');
    Route::get('/idprinting/students', 'IDPrintingController\StudentController@students')->name('students');
    Route::get('/idprinting/update-status', 'IDPrintingController\StudentController@update_status')->name('update.status');
    // Route::get('/idprinting/get-users', 'IDPrintingController\UserController@index')->name('get.users');
    // Route::get('/idprinting/add-user', 'IDPrintingController\UserController@store')->name('add.user');
    // Route::get('/idprinting/edit-user', 'IDPrintingController\UserController@edit_user')->name('edit.user');
    // Route::get('/idprinting/update-user', 'IDPrintingController\UserController@update_user')->name('update.user');
    // Route::get('/idprinting/delete-user', 'IDPrintingController\UserController@destroy')->name('delete.user');
    Route::get('/idprinting/get-student', 'IDPrintingController\StudentController@get_student')->name('get.student');
    Route::get('/idprinting/addfullname', 'IDPrintingController\StudentController@addfullname')->name('add.fullname');
    Route::get('/idprinting/update-student', 'IDPrintingController\StudentController@update_student')->name('update.student');
    Route::get('/idprinting/delete-student', 'IDPrintingController\StudentController@destroy')->name('delete.student');
    Route::match(['get', 'post'], '/idprinting/add-template', 'IDPrintingController\TemplateController@saveTemplate')->name('add.template');
    Route::get('/idprinting/get-template', 'IDPrintingController\TemplateController@get_template')->name('get.template');
    Route::get('/idprinting/getbyname-template', 'IDPrintingController\TemplateController@get_template_byname')->name('get.byname.template');
    Route::get('/idprinting/delete-template', 'IDPrintingController\TemplateController@destroy')->name('delete.template');
    Route::get('/idprinting/alltemplates', 'IDPrintingController\TemplateController@templates')->name('templates');
    Route::match(['get', 'post'], '/idprinting/upload', 'IDPrintingController\FileController@upload')->name('file.upload');
    Route::get('/idprinting/loadimages', 'IDPrintingController\FileController@loadimages')->name('file.load');
    Route::view('/idprinting/canva', 'id_creator_system.canva');
    Route::get('/idprinting/edit-template', 'IDPrintingController\TemplateController@edit_template');
    Route::match(['get', 'post'], '/idprinting/update-template', 'IDPrintingController\TemplateController@update_template')->name('update.template');
    Route::get('/idprinting/set-default', 'IDPrintingController\TemplateController@set_default')->name('set.default');
    Route::view('/idprinting/view/templates', 'id_creator_system.template');
    // Route::view('/idprinting/manageuser', 'id_creator_system.manageuser');

    Route::get('/idprinting/teachers', 'IDPrintingController\TeacherController@teachers')->name('teachers');
    Route::get('/idprinting/get-teacher', 'IDPrintingController\TeacherController@get_teacher')->name('get.teacher');
    Route::get('/idprinting/update-teacher', 'IDPrintingController\TeacherController@update_teacher')->name('update.teacher');
    Route::view('/idprinting/view/teachers', 'id_creator_system.teachers');
    // ID PRINTING ROUTES END

});
