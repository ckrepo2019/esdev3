<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Route::view('/documenttracking', 'general.documenttracking.pages.DocumentTracking-creator', []);
    Route::get('/getalldoctype', 'DocumentTrackingController\TrackingController@getalldoctype')->name('getalldoctype');
    Route::post('/store-document', 'DocumentTrackingController\TrackingController@store_document')->name('store.document');
    Route::get('/getallteacherbyID', 'DocumentTrackingController\TrackingController@getAllTeacherByID')->name('get.teachers');
    Route::get('/getAllDocuments', 'DocumentTrackingController\TrackingController@getAllDocuments');
    Route::get('/get-documenttracking', 'DocumentTrackingController\TrackingController@getDocumentTracking')->name('get.documenttracking');
    Route::get('/receive-document', 'DocumentTrackingController\TrackingController@receiveDocument')->name('receive.document');
    Route::get('/forward-document', 'DocumentTrackingController\TrackingController@forwardDocument')->name('forward.document');
    Route::get('/get-available-signee', 'DocumentTrackingController\TrackingController@getAvailableSignee')->name('get.Available.Signee');
    Route::get('/reject-document', 'DocumentTrackingController\TrackingController@rejectDocument')->name('reject.document');
    Route::get('/close-document', 'DocumentTrackingController\TrackingController@closeDocument')->name('close.document');
    Route::get('/docutrackview', 'DocumentTrackingController\TrackingController@docutrackview');
    Route::get('/get-all-docs', 'DocumentTrackingController\TrackingController@getAllDocsForTruncate');
    Route::post('/updatedocument', 'DocumentTrackingController\TrackingController@updateDocument')->name('update.document');

});
