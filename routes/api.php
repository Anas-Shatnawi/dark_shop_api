<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login',[App\Http\Controllers\UsersControllers::class,'login']);
Route::post('register',[App\Http\Controllers\UsersControllers::class,'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    //users routes
    Route::get('/user-details', [App\Http\Controllers\UsersControllers::class,'getUserDetails']);
    Route::post('/update-user', [App\Http\Controllers\UsersControllers::class,'updateUserDetails']);
    
    // stores api
    Route::get('/get-stores', [App\Http\Controllers\UsersControllers::class,'getStores']);

});