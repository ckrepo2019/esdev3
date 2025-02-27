<?php
// use Illuminate\Support\Facades\Route;

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


Route::get('/request-reset', 'UserController@request_reset_password')->name('request.reset');

Route::view('/catalogue-view', 'library.pages.catalogue');

Route::get('/books', 'LibraryController\LibraryBookController@getLibraryBooks')->name('books');
Route::get('/get-book', 'LibraryController\CirculationController@getBook')->name('get.book');
Route::get('/masterlist/get-book', 'LibraryController\LibraryBookController@getBook')->name('get.masterlist.book');
Route::get('/pinbook', 'LibraryController\LibraryBookController@pin_book')->name('request.pinbook');
Route::get('/clear-session', 'LibraryController\LibraryBookController@clear_session');
Route::get('/catalogue-signin', 'LibraryController\UserController@catalogue_signin')->name('catalogue.signin');

Route::middleware(['auth'])->group(function () {

    // Route::get('/', 'HomeController@index')->name('home');
    Route::view('/change-pass', 'library.pages.changepass');
    // CHANGE PASSWORD
    Route::match(['get', 'post'], '/update-password', 'LibraryController\UserController@updatePassword')->name('update.password');

    // CATALOGING
    Route::view('/cataloging', 'library.pages.cataloging');
    // Route::get('/library/books', 'LibraryBookController@getLibraryBooks')->name('books');
    // Route::get('/library/get-book', 'CirculationController@getBook')->name('get.book');
    // Route::get('/masterlist/get-book', 'LibraryBookController@getBook')->name('get.masterlist.book');
    Route::get('/getbyborrower', 'LibraryController\CirculationController@getCirculationByBorrower')->name('getCirculationByBorrower');
    Route::view('/view/profile', 'library.pages.profile');
    Route::post('/profile-update', 'LibraryController\UserController@update_profile')->name('update.profile');
    Route::get('/pinbooks', 'LibraryController\LibraryBookController@get_all_pinned')->name('request.allpin');
    Route::get('/deletepin', 'LibraryController\LibraryBookController@delete_pin')->name('delete.pin');

    // Routes accessible only for users with 'admin' usertype
    Route::middleware(['admin'])->group(function () {

        // Route::get('/admin/home', 'LibraryController\HomeController@index')->name('home');

        Route::get('/load_graphs', 'LibraryController\GenreController@load_graphs')->name('load_graphs');

        // USERTYPE
        Route::get('/all-usertype', 'LibraryController\UsertypeController@all_usertype')->name('usertypes');
        Route::get('/get-usertype', 'LibraryController\UsertypeController@get_usertype')->name('get.usertype');
        Route::get('/add-usertype', 'LibraryController\UsertypeController@add_usertype')->name('add.usertype');
        Route::get('/delete-usertype', 'LibraryController\UsertypeController@delete_usertype')->name('delete.usertype');

        // MASTERLIST
        Route::view('/admin/masterlist', 'library.pages.masterlist');

        // PROCUREMENTS
        // Route::view('/admin/procurements/{any}', 'pages.procurements');
        Route::get('/admin/procurements/{any}', 'LibraryController\ProcurementController@get_procurement');
        Route::match(['get', 'post'], '/store-procurements', 'LibraryController\ProcurementController@storeProcurement')->name('store.procurement');
        // Route::get('/all-procurements', 'LibraryController\ProcurementController@get_procurement')->name('get.procurement');

        // USER
        Route::get('/reset-password', 'LibraryController\UserController@reset_password')->name('reset.password');
        Route::get('/admin/all-users', 'LibraryController\UserController@users')->name('users');
        Route::get('/admin/saveUser', 'LibraryController\UserController@saveUser')->name('saveUser');
        Route::get('/admin/delete-user', 'LibraryController\UserController@deleteUser')->name('delete.user');

        Route::match(['get', 'post'], 'library/store-book', 'LibraryController\LibraryBookController@storeBook')->name('store.book');
        Route::match(['get', 'post'], 'library/update-book', 'LibraryController\LibraryBookController@updateBook')->name('update.book');
        Route::get('/delete-book', 'LibraryController\LibraryBookController@deleteBook')->name('delete.book');
        Route::get('/dropdowns', 'LibraryController\LibraryBookController@getDropdowns')->name('dropdowns');

        // SETUP
        Route::get('/admin/setup/{any}', 'LibraryController\SetupController@setup');
        // LIBRARY
        Route::get('libraries', 'LibraryController\LibraryController@index')->name('libraries');
        Route::get('get-library', 'LibraryController\LibraryController@get_library')->name('get.library');
        Route::get('update-library', 'LibraryController\LibraryController@update')->name('update.library');
        Route::get('add-library', 'LibraryController\LibraryController@store')->name('add.library');
        Route::get('delete-library', 'LibraryController\LibraryController@destroy')->name('delete.library');
        // CATEGORIES
        Route::get('categories', 'LibraryController\CategoryController@index')->name('categories');
        Route::get('get-category', 'LibraryController\CategoryController@get_category')->name('get.category');
        Route::get('update-category', 'LibraryController\CategoryController@update')->name('update.category');
        Route::get('add-category', 'LibraryController\CategoryController@store')->name('add.category');
        Route::get('delete-category', 'LibraryController\CategoryController@destroy')->name('delete.category');
        // GENRES
        Route::get('genres', 'LibraryController\GenreController@index')->name('genres');
        Route::get('get-genre', 'LibraryController\GenreController@get_genre')->name('get.genre');
        Route::get('update-genre', 'LibraryController\GenreController@update')->name('update.genre');
        Route::get('add-genre', 'LibraryController\GenreController@store')->name('add.genre');
        Route::get('delete-genre', 'LibraryController\GenreController@destroy')->name('delete.genre');
        // BORROWERS
        Route::get('borrowers', 'LibraryController\BorrowerController@index')->name('borrowers');
        Route::get('get-borrower', 'LibraryController\BorrowerController@get_borrower')->name('get.borrower');
        Route::get('update-borrower', 'LibraryController\BorrowerController@update')->name('update.borrower');
        Route::get('add-borrower', 'LibraryController\BorrowerController@store')->name('add.borrower');
        Route::get('delete-borrower', 'LibraryController\BorrowerController@destroy')->name('delete.borrower');

        // CIRCULATIONS
        Route::get('/admin/circulation/{any}', 'LibraryController\CirculationController@circulation');
        Route::get('/circulation/get-borrower', 'LibraryController\CirculationController@get_borrower')->name('get.circulation.borrower');
        Route::get('circulations', 'LibraryController\CirculationController@circulations')->name('circulations');
        Route::get('get-circulation', 'LibraryController\CirculationController@getCirculation')->name('get.circulation');
        Route::get('update-circulation', 'LibraryController\CirculationController@updateCirculation')->name('update.circulation');
        Route::get('store-circulation', 'LibraryController\CirculationController@storeCirculation')->name('store.circulation');
        Route::get('delete-circulation', 'LibraryController\CirculationController@deleteCirculation')->name('delete.circulation');
        Route::get('/view/borrowerdetail', 'LibraryController\CirculationController@viewborrower');

        // REPORTS
        Route::get('/admin/report/{any}', 'LibraryController\ReportController@reports');
    });

});


