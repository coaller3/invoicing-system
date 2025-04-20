<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login_api']);

Route::middleware('auth:api')->post('logout', [LoginController::class, 'logout_api']);

Route::middleware('auth:api')->group(function () {

    Route::resource('clients', ClientController::class)->except(['index', 'create', 'edit']);
    Route::get('/clients', [ClientController::class, 'index_api'])->name('clients.index_api');

    Route::resource('projects', ProjectController::class)->except(['index', 'create', 'edit']);
    Route::get('/projects', [ProjectController::class, 'index_api'])->name('projects.index_api');

    Route::resource('invoices', InvoiceController::class)->except(['index', 'create', 'edit']);
    Route::get('/invoices', [InvoiceController::class, 'index_api'])->name('invoices.index_api');
    Route::get('/invoices-get-project/{client}', [InvoiceController::class, 'get_project'])->name('invoices.get_project');
    Route::post('/invoice-projects/{invoice}', [InvoiceController::class, 'add_project'])->name('invoice-projects.add');
    Route::delete('/invoice-projects/{invoiceProject}', [InvoiceController::class, 'delete_project'])->name('invoice-projects.delete');

});
