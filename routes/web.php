<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\PaymentProviderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TopingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\userInterfaceController;
use App\Models\PaymentProvider;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('category', CategoryController::class)->middleware('auth');
Route::resource('table', TableController::class)->middleware('auth');
Route::resource('toping', TopingController::class)->middleware('auth');
Route::resource('userInterface', userInterfaceController::class)->middleware('auth');

Route::post('/confirm-payment', [TransactionController::class, 'confirmPayment'])
    ->middleware('auth')
    ->name('confirm.payment');

// File: routes/web.php
Route::resource('payment_providers', PaymentProviderController::class)
    ->middleware('auth');
    
Route::put('/payment_providers/{payment_provider}/toggle-status', 
    [PaymentProviderController::class, 'toggleStatus'])
    ->name('payment_providers.toggle-status')
    ->middleware('auth');

Route::get('/beranda', function () {
    return view('beranda');
})->middleware(['auth', 'verified'])->name('beranda');

Route::resource('transaction', TransactionController::class);
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/table/{id}', [TableController::class, 'destroy'])->name('table.destroy');

Route::put('/transaction/{id}/process', [TransactionController::class, 'process'])
    ->name('transaction.process');
Route::get('/category/check-name/{name}', [CategoryController::class, 'checkName'])->name('category.checkName');
Route::get('/table/check-number/{number}', [TableController::class, 'checkNumber'])->name('table.checkNumber');
Route::get('/toping/check-name/{name}', [TopingController::class, 'checkName']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('error', ErrorController::class);
});

require __DIR__ . '/auth.php';
