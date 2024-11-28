<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/test', [TransactionController::class, 'test']);
Route::get('/payment', function () {
    return view('payments.hpp');
});
//Route::any('payment/{token}', [\App\Http\Controllers\Payment\TransactionController::class, 'payment']);
Route::get('payment/{token}', [TransactionController::class, 'loadPaymentPage']);
Route::post('makePayment', [TransactionController::class, 'makePayment'])->name('makePayment');
Route::post('/trust-flow-pay-redirect', [TransactionController::class, 'trustFlowPayRedirect']);
Route::get('trustflow-tranx-status', [TransactionController::class, 'trustflowTranxStatus']);

Route::post('getIgCallback', [TransactionController::class, 'getIgCallback'])->name('getIgCallback');
