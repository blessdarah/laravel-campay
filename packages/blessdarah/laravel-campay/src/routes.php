<?php

use BlessDarah\LaravelCampay\CampayController;
use Illuminate\Support\Facades\Route;

// Campay endpoints
Route::post('campay/withdraw', [CampayController::class, 'withdraw'])->name('campay.withdraw');
Route::post('campay/collect', [CampayController::class, 'collect'])->name('campay.collect');
Route::get('campay/status/{reference}', [CampayController::class, 'checkTransactionStatus'])->name('campay.transaction.status');
Route::get('campay/user-transactions/{id?}', [CampayController::class, 'userTransactions'])->name('campay.user.transactions');
Route::get('campay/balance', [CampayController::class, 'balance'])->name('campay.balance');
Route::get('campay/callback', [CampayController::class, 'callback'])->name('campay.callback');
