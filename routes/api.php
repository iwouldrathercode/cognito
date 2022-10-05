<?php

use Iwouldrathercode\Cognito\Http\Controllers\LoginController;
use Iwouldrathercode\Cognito\Http\Controllers\RegisterController;
use Iwouldrathercode\Cognito\Http\Controllers\EmailVerificationController;
use Iwouldrathercode\Cognito\Http\Controllers\SelfServiceController;

Route::group([ 'prefix'=>'api' ],function () {

    Route::post('/register',[RegisterController::class, 'register'])->name('signup');
    
    Route::post('/login',[LoginController::class, 'login'])->name('signin');

    Route::post('/verify',[EmailVerificationController::class, 'verify'])->name('verify');

    Route::post('/forgot-password',[SelfServiceController::class, 'forgotPassword'])->name('forgot-password');

    Route::post('/confirm-forgot-password',[SelfServiceController::class, 'confirmForgotPassword'])->name('confirm-forgot-password');

});
