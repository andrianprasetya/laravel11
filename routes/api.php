<?php

use App\Http\Controllers\Api\BriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'bri.', 'prefix' => 'bri'], function () {
    Route::post('/get-token-b2b', [BriController::class, 'getTokenB2B'])->name('getTokenB2B');
    Route::post('/get-balance-inquiry', [BriController::class, 'getBalanceInquiry'])->name('getBalanceInquiry');
});
