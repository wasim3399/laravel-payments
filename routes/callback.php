<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('getIgCallback', [TransactionController::class, 'getIgCallback'])->name('getIgCallback');
