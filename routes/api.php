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
    
    // ~~~~~ products routes ~~~~~
    Route::get('get-products',[App\Http\Controllers\ProductController::class,'getProducts']);
    Route::get('get-user-product',[App\Http\Controllers\ProductController::class,'getUserProducts']);
    Route::get('get-product-by-category',[App\Http\Controllers\ProductController::class,'getProductsByCategory']);
    Route::get('product-details',[App\Http\Controllers\ProductController::class,'getProductDetails']);
    Route::post('add-product',[App\Http\Controllers\ProductController::class,'addProduct']);
    Route::post('update-product',[App\Http\Controllers\ProductController::class,'updateProduct']);
    Route::delete('delete-product',[App\Http\Controllers\ProductController::class,'deleteProduct']);
    // ~~~~~ end of products routes ~~~~~
    
    // ~~~~~ categories routes ~~~~~
    Route::get('get-categories',[App\Http\Controllers\CategoriesController::class,'getCategories']);


});