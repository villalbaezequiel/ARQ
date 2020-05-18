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

Route::get('/', function () {
    return view('welcome');
});

// redirect temporally
Route::get('/', function(){
    return redirect('/clients');
});

Route::get('/home', function(){
    return redirect('/clients');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('/projects', 'Backend\ProjectController');
    Route::resource('/project_documents', 'Backend\ProjectDocumentController');
    Route::resource('/clients', 'Backend\ClientController');
    Route::resource('/providers', 'Backend\ProviderController');
    Route::resource('/user_files', 'Backend\UserFileController');
    Route::resource('/items', 'Backend\ItemController');
    Route::resource('/sub_items', 'Backend\SubItemController');
});

Route::get('/registro-proveedor', function(){
    return view('frontend.provider.create');
})->name('register_provider_guest');
