<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::redirect("/","dashboard");

Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');

    // Product creation routes
    Route::get('/registerProduct', [ProductController::class, 'registerProduct'])->name('register.product');
    Route::post('/registerProduct', [ProductController::class, 'registerProduct'])->name('register.product');

    // Product update routes
    Route::get('/updateProduct/{id}', [ProductController::class, 'updateProduct'])->name('update.product');
    Route::put('/updateProduct/{id}', [ProductController::class, 'updateProduct'])->name('update.product');

    Route::delete('/delete', [ProductController::class, 'delete'])->name('products.delete');
});

Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');

    // Client creation routes
    Route::get('/registerClient', [ClientController::class, 'registerClient'])->name('register.client');
    Route::post('/registerClient', [ClientController::class, 'registerClient'])->name('register.client');

    // Client update routes
    Route::get('/updateClient/{id}', [ClientController::class, 'updateClient'])->name('update.client');
    Route::put('/updateClient/{id}', [ClientController::class, 'updateClient'])->name('update.client');

    Route::delete('/delete', [ClientController::class, 'delete'])->name('clients.delete');
});

Route::prefix('salles')->group(function () {
    Route::get('/', [SalleController::class, 'index'])->name('salles.index');

    // Client creation routes
    Route::get('/registerSalle', [SalleController::class, 'registerSalle'])->name('register.salle');
    Route::post('/registerSalle', [SalleController::class, 'registerSalle'])->name('register.salle');

    Route::get('/SendEmailRecipt/{id}', [SalleController::class, 'SendEmailRecipt'])->name('SendEmailRecipt.salle');
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');

    // User  creation routes
    Route::get('/registerUser', [UserController::class, 'registerUser'])->name('register.user');
    Route::post('/registerUser', [UserController::class, 'registerUser'])->name('register.user');

    // User update routes
    Route::get('/updateUser/{id}', [UserController::class, 'updateUser'])->name('update.user');
    Route::put('/updateUser/{id}', [UserController::class, 'updateUser'])->name('update.user');

    Route::delete('/delete', [UserController::class, 'delete'])->name('users.delete');
});