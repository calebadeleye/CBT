<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\PaymentController;


Route::controller(GuestController::class)->group(function () {
    Route::get('/', 'home');
    Route::get('/login', 'login')->name('guest.showLogin');
    Route::get('/about', 'about')->name('guest.showAbout');
    Route::get('/leaderboard', 'leaderBoard')->name('guest.showLeaderBoard');
    Route::get('/contact', 'contact')->name('guest.showContact');
    Route::get('/forgot', 'forgotPassword')->name('guest.showForgot');
    Route::get('/myboard', 'myBoard')->name('guest.showUserBoard');
    Route::get('/myscores', 'myScores')->name('guest.showUserScores');
    Route::get('/terms', 'terms')->name('guest.showTerms');
    Route::get('/quiz', 'showQuiz')->name('guest.showQuiz');
    Route::get('/adminboard', 'showAdminBoard')->name('guest.showAdminBoard');
    Route::get('/admin/add', 'showAdminLogin')->name('guest.showAdminLogin');
    Route::get('/questions', 'showQuestions')->name('guest.showQuestions');
    Route::get('/addquestion', 'addQuestion')->name('guest.addQuestion');
});

Route::get('/verify/{id}', [SignupController::class, 'update']);
Route::get('/reset/{token}', [ResetController::class, 'show']);


