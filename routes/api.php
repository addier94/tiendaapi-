<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\MeController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Detail\DetailController;

// Public routes

// Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], function() {
    Route::get('me', [MeController::class, 'getMe']);
    Route::post('logout', [LoginController::class, 'logout']);
    Route::resource('article', ArticleController::class)->except([
        'create', 'edit'
    ]);
    Route::resource('detail', DetailController::class)->except([
        'create', 'edit'
    ]);
});

// Route for guests only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register');
    Route::post('verification/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verification/resend', [VerificationController::class, 'resend']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');
});
