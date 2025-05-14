<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

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

/* clear cache start */
// Clear all cache:
Route::get('/all-cache', function () {
    Artisan::call('optimize:clear');
    return 'All cache has been cleared';
});
/* clear cache end */

Route::redirect('/', '/login');

Route::get('/login', function () {
    // Log out the user
    Auth::logout();

    // Flush the session data
    session()->flush();

    // Regenerate the session ID
    session()->regenerate();

    return view('login');
})->name('login');

Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout']);

// register
Route::get('/register', function () {

    return view('register');

})->name('register');

Route::post('register', [UserController::class, 'register']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // users
    Route::resource('users', UserController::class)->except(['edit']);
    Route::put('/users/{user}/change_password', [UserController::class, 'change_password']);
    Route::delete('/users/{user}/delete_image', [UserController::class, 'delete_image'])->name('users.delete_image');

    Route::get('/profile/{user}', [UserController::class, 'profile'])->name('profile');

    // clients
    Route::resource('clients', ClientController::class)->except(['edit']);

    Route::resource('projects', ProjectController::class)->except(['edit']);

    Route::resource('invoices', InvoiceController::class)->except(['edit']);
    Route::get('/invoices-get-project/{client}', [InvoiceController::class, 'get_project'])->name('invoices.get_project');
    Route::post('/invoice-projects/{invoice}', [InvoiceController::class, 'add_project'])->name('invoice-projects.add');
    Route::delete('/invoice-projects/{invoiceProject}', [InvoiceController::class, 'delete_project'])->name('invoice-projects.delete');

    Route::get('/invoices/{invoice}/PDF', [InvoiceController::class, 'invoicePDF'])->name('invoices.pdf');

    Route::post('/invoices/{invoice}/email', [InvoiceController::class, 'email_invoice'])->name('invoices.email');

});
