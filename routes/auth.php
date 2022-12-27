<?php

use App\Http\Controllers\Authenticate\LoginController;
use App\Http\Controllers\Authentication\ForgotPasswordController;
use App\Http\Controllers\Authentication\ResetPasswordController;
use App\Http\Controllers\Authentication\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function() {
    Route::view('login','pages.auth.login')->name('login');
    Route::post('login', LoginController::class)->name('authenticate');
    
    Route::view('forgot-password', 'pages.auth.forgot-password')->name('password.request');
    Route::post('forgot-password', ForgotPasswordController::class)->name('password.email');
    
    Route::get('reset-password/{token}', [ResetPasswordController::class,'edit'])->name('password.reset');
    Route::put('reset-password', [ResetPasswordController::class,'update'])->name('password.update');
});


Route::middleware('auth')->group(function() {
    Route::view('email-verify', 'pages.auth.email-verify')->name('verification.notice');
   
    Route::get('email-verify/{id}/{hash}',VerifyEmailController::class)
        ->middleware('signed')
        ->name('verification.verify');
});