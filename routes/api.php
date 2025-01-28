<?php

use App\Http\Controllers\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-number', [SmsController::class, 'getNumber']);
Route::get('/get-sms', [SmsController::class, 'getSms']);
Route::get('/cancel-number', [SmsController::class, 'cancelNumber']);
Route::get('/get-status', [SmsController::class, 'getStatus']);
