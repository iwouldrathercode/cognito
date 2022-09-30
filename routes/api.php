<?php

use Iwouldrathercode\Cognito\Http\Controllers\LoginController;
use Iwouldrathercode\Cognito\Http\Controllers\RegisterController;
use Iwouldrathercode\Cognito\Http\Controllers\VerificationController;

Route::group([ 'prefix'=>'api' ],function () {

    Route::post('/register',[RegisterController::class, 'register'])->name('signup');
    Route::post('/login',[LoginController::class, 'login'])->name('signin');
    Route::post('/verify',[VerificationController::class, 'verify'])->name('verify');

});
