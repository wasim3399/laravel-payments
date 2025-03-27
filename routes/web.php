<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;

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


Route::get('/upload', [FileUploadController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload', [FileUploadController::class, 'handleUpload'])->name('upload.handle');
Route::get('/store-data', [FileUploadController::class, 'store'])->name('upload.store');

// API Doc
Route::get('/documentation/login', [\App\Http\Controllers\ApiDoc\ApiDocController::class, 'login']);
Route::post('/check-password', [\App\Http\Controllers\ApiDoc\ApiDocController::class, 'checkPassword'])->name('checkPassword');
Route::get('api/documentation', [\App\Http\Controllers\ProfileController::class, 'docIndex']);

