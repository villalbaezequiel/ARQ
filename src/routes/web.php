<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ClientController;
use App\Http\Controllers\Backend\ExportController;
use App\Http\Controllers\Backend\ItemController;
use App\Http\Controllers\Backend\JsonController;
use App\Http\Controllers\Backend\ProjectCashFlowController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\ProjectDocumentController;
use App\Http\Controllers\Backend\ProviderController;
use App\Http\Controllers\Backend\RolController;
use App\Http\Controllers\Backend\SubItemController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserFileController;
use App\Http\Controllers\HomeController;

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

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('/projects', ProjectController::class);
    Route::resource('/project_documents', ProjectDocumentController::class);
    Route::resource('/clients', ClientController::class);
    Route::resource('/providers', ProviderController::class);
    Route::resource('/user_files', UserFileController::class);
    Route::resource('/items', ItemController::class);
    Route::resource('/sub_items', SubItemController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RolController::class);
    Route::resource('/project_cash_flow', ProjectCashFlowController::class);

    Route::get('/json/budgets/delete-item', [JsonController::class, 'deleteBudgetItem']);
    Route::get('/json/budgets/delete-subitem', [JsonController::class, 'deleteBudgetSubitem']);
    Route::get('/json/budgets/open', [JsonController::class, 'setOpenBudget']);
    Route::get('/json/costreal/delete-item', [JsonController::class, 'deleteRealCostItem']);
    Route::get('/json/costreal/delete-subitem', [JsonController::class, 'deleteRealCostSubitem']);
    Route::get('/json/providers/item/{iditem}', [JsonController::class, 'getProvidersFromItem']);
    Route::get('/json/items/sub_items', [JsonController::class, 'getSubItemsFromItems']);
    Route::get('/json/project_providers/item/{idProject}/{idItem}', [JsonController::class, 'searchProviders']);
    Route::get('/json/project_provider_history/{idProject}/{idProvider}', [JsonController::class, 'getHistoryProvider']);

    Route::get('/json/check_cash_flow', [JsonController::class, 'checkCashFlow']);
    Route::get('/json/projects/budget/{id}', [JsonController::class, 'processDataBudget']);
    Route::get('/json/projects/real-cost/{id}', [JsonController::class, 'processDataRealCost']);

    Route::post('/json/send-email-realcost', [JsonController::class, 'sendEmailRealCost']);
    
    Route::get('/export/project_budgets/{id}', [ExportController::class, 'projectBudgets']);
    Route::get('/export/project_provider', [ExportController::class, 'projectHistoryProvider']);
    Route::get('/export/providers', [ExportController::class, 'providers']);
    Route::get('/export/clients', [ExportController::class, 'clients']);

});

Route::get('/export/project_realcost/{id}', [ExportController::class, 'projectRealCost'])->name('export.project_realcost');

Route::get('/registro-proveedor', function(){
    return view('frontend.provider.create');
})->name('register_provider_guest');
