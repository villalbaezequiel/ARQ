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
    return redirect('/projects');
});

Route::get('/home', function(){
    return redirect('/projects');
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
    Route::resource('/users', 'Backend\UserController');
    Route::resource('/project_cash_flow', 'Backend\ProjectCashFlowController');

    Route::get('/json/budgets/delete-item', 'Backend\JsonController@deleteBudgetItem');
    Route::get('/json/budgets/delete-subitem', 'Backend\JsonController@deleteBudgetSubitem');
    Route::get('/json/budgets/open', 'Backend\JsonController@setOpenBudget');
    Route::get('/json/costreal/delete-item', 'Backend\JsonController@deleteRealCostItem');
    Route::get('/json/costreal/delete-subitem', 'Backend\JsonController@deleteRealCostSubitem');
    Route::get('/json/providers/item/{iditem}', 'Backend\JsonController@getProvidersFromItem');
    Route::get('/json/items/sub_items', 'Backend\JsonController@getSubItemsFromItems');
    Route::get('/json/project_providers/item/{idProject}/{idItem}', 'Backend\JsonController@searchProviders');
    Route::get('/json/project_provider_history/{idProject}/{idProvider}', 'Backend\JsonController@getHistoryProvider');
    
});

Route::get('/registro-proveedor', function(){
    return view('frontend.provider.create');
})->name('register_provider_guest');
