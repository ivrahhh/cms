<?php

use App\Http\Controllers\Authenticate\LoginController;
use App\Http\Controllers\Authentication\ForgotPasswordController;
use App\Http\Controllers\Authentication\ResetPasswordController;
use App\Http\Controllers\Authentication\VerifyEmailController;
use Illuminate\Support\Facades\Route;


Route::post('login', LoginController::class)->name('authenticate');

Route::post('forgot-password', ForgotPasswordController::class)->name('password.email');

Route::put('reset-password', [ResetPasswordController::class,'update'])->name('password.update');

Route::middleware('auth')->group(function() {
    Route::get('email-verify/{id}/{hash}',VerifyEmailController::class)
        ->middleware('signed')
        ->name('verification.verify');
});