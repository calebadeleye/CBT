<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\UtmeWebhookController;
use App\Http\Controllers\UtmePaystackWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/admin/login', [AdminController::class, 'login']);
Route::resource('signin',LoginController::class);
Route::resource('signup',SignupController::class);
Route::resource('reset',ResetController::class);

Route::get('leaderboard/show',[ExamController::class,'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('question',QuestionController::class);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::resource('practice',PracticeController::class);
    Route::resource('leaderboard',ExamController::class);
    Route::resource('bank',PaymentController::class);
});

// webhook processed from NAITALK
Route::post('/webhook/flutterwave', [UtmeWebhookController::class, 'handleWebhook']);
Route::post('/webhook/paystack', [UtmePaystackWebhookController::class, 'handleWebhook']);

Route::post('/payments/verify', [PaystackController::class, 'verify']);

