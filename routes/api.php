<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/createTransaction', [TransactionController::class, 'createTransaction']);
Route::get('trust-flow-pay-transaction-status/{id}', [\App\Http\Controllers\Payment\TrustFlowPayController::class, 'checkTransactionStatus']);


