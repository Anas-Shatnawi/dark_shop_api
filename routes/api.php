<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// ~~~~~ auth routes ~~~~~
Route::post('login',[App\Http\Controllers\UsersControllers::class,'login']);
Route::post('register',[App\Http\Controllers\UsersControllers::class,'register']);
// ~~~~~ end of auth routes ~~~~~


// ~~~~~ reset passwords routes ~~~~~
Route::post('send-reset-password-email',[App\Http\Controllers\ResetPasswordController::class,'sendResetPasswordEmail']);
Route::post('check-reset-password-code',[App\Http\Controllers\ResetPasswordController::class,'checkCode']);
Route::post('reset-password',[App\Http\Controllers\ResetPasswordController::class,'resetPassword']);
// ~~~~~ end of reset passwords routes ~~~~~


// ~~~~~ required token routes ~~~~~
Route::middleware(['auth:sanctum'])->group(function () {
    
    //~~~~~ users routes ~~~~~
    Route::get('/user-details', [App\Http\Controllers\UsersControllers::class,'getUserDetails']);
    Route::post('/update-user', [App\Http\Controllers\UsersControllers::class,'updateUserDetails']);
    //~~~~~ end of users routes ~~~~~
    
    // ~~~~~ stores routes ~~~~~
    Route::get('/get-stores', [App\Http\Controllers\UsersControllers::class,'getStores']);
    // ~~~~~ end of stores routes ~~~~~

});