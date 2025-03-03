<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('category', CategoryController::class)->middleware('auth');
Route::resource('table', TableController::class)->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::delete('/table/{id}', [TableController::class, 'destroy'])->name('table.destroy');


Route::get('/category/check-name/{name}', [CategoryController::class, 'checkName'])->name('category.checkName');
Route::get('/table/check-number/{number}', [TableController::class, 'checkNumber']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
