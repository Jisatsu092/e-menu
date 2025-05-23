<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\PaymentProviderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TopingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\userInterfaceController;
use App\Models\PaymentProvider;
use App\Models\Table;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('category', CategoryController::class)->middleware('auth');
Route::resource('transaction_details', TransactionDetailController::class)->middleware('auth');
Route::resource('table', TableController::class)->middleware('auth');
Route::resource('toping', TopingController::class)->middleware('auth');
Route::resource('userInterface', userInterfaceController::class)->middleware('auth');

Route::post('/confirm-payment', [TransactionController::class, 'confirmPayment'])
    ->middleware('auth')
    ->name('confirm.payment');

// Pastikan route untuk update status ada
Route::put('/transaction/{transaction}/status', [TransactionController::class, 'updateStatus'])
    ->name('transaction.status');

Auth::routes(['verify' => true]);
// File: routes/web.php
Route::resource('payment_providers', PaymentProviderController::class)
    ->middleware('auth');

    Route::get('/tables', function () {
        $tables = \App\Models\Table::select('id', 'number', 'status')
            ->orderBy('number')
            ->get();
        
        return response()->json($tables);
    });

Route::put(
    '/payment_providers/{payment_provider}/toggle-status',
    [PaymentProviderController::class, 'toggleStatus']
)
    ->name('payment_providers.toggle-status')
    ->middleware('auth');
Route::get('/transaction-details/report', [TransactionDetailController::class, 'report'])->name('transaction_details.report');
Route::delete('/transaction-details/clear-all', [TransactionDetailController::class, 'destroyAll'])
    ->name('transaction_details.destroyAll');
Route::put('/table/{id}', [TableController::class, 'update'])->name('table.update');
// routes/web.php
Route::get('/tables', function () {
    return response()->json(App\Models\Table::all());
});

Route::post('/transactions', [TransactionController::class, 'store']);
// web.php
Route::get(
    '/transaksi/print/{transaction}',
    [TransactionController::class, 'print']
)
    ->name('transaksi.print');

Route::get('/transaksi/print/{transaction}', [TransactionController::class, 'print'])
    ->name('transaksi.print');

Route::get('/beranda', function () {
    $availableTables = Table::where('status', 'available')->count();
    $occupiedTables = Table::where('status', 'occupied')->count();
    $totalTables = Table::count();

    $totalUsers = User::count();

    // Data transaksi
    $dailyTransactions = Transaction::whereDate('created_at', Carbon::today())->count();
    $weeklyTransactions = Transaction::whereBetween('created_at', [
        Carbon::now()->startOfWeek(),
        Carbon::now()->endOfWeek()
    ])->count();
    $monthlyTransactions = Transaction::whereMonth('created_at', Carbon::now()->month)->count();

    return view('beranda', compact(
        'availableTables',
        'occupiedTables',
        'totalTables',
        'totalUsers',
        'dailyTransactions',
        'weeklyTransactions',
        'monthlyTransactions'
    ));
})->middleware(['auth', 'verified'])->name('beranda');

Route::get('/transactions/{transaction}/details', [TransactionDetailController::class, 'show'])
    ->name('transaction_details.show')
    ->middleware('auth');
Route::get('/check-status/{transaction}', [TransactionController::class, 'checkStatus']);

Route::get('/transaction/report', [TransactionController::class, 'report'])
    ->name('transaction.report');

Route::get('/transaction/{id}/print', [TransactionController::class, 'print'])->name('transaction.print');

Route::get('/transaction/print-all', [TransactionController::class, 'printAll'])
    ->name('transaction.print.all');

Route::put('/table/{id}', [TableController::class, 'update'])->name('table.update');
Route::resource('transaction', TransactionController::class);
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/table/{id}', [TableController::class, 'destroy'])->name('table.destroy');

Route::get('/user_interface', [userInterfaceController::class, 'index'])
    ->middleware('auth');

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
