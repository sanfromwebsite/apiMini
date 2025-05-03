<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Auth 
Route::prefix('/auth')->group(function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('logout',[AuthController::class,'logout'])->middleware('is_login');

});


Route::middleware(['is_login','is_admin'])->group(function () {

    //positions
    Route::post('positions',[PositionController::class,'store']);
    Route::get('positions',[PositionController::class,'index']);
    Route::put('positions/{id}',[PositionController::class,'update']);
    Route::delete('positions/{id}',[PositionController::class,'destroy']);

    //staffs
    Route::post('staffs',[StaffController::class,'store']);
    Route::get('staffs',[StaffController::class,'index']);
    Route::delete('staffs/{id}',[StaffController::class,'destroy']);
    Route::post('staffs/{id}',[StaffController::class,'update']);

    //suppliers
    Route::post('suppliers',[SupplierController::class,'store']);
    Route::get('suppliers',[SupplierController::class,'index']);
    Route::put('suppliers/{id}',[SupplierController::class,'update']);
    Route::delete('suppliers/{id}',[SupplierController::class,'destroy']);

    //products
    Route::post('products/{id}',[ProductController::class,'update']);
    Route::delete('products/{id}',[ProductController::class,'destroy']);
    Route::post('products',[ProductController::class,'store']);

    //imports
    Route::post('imports',[ImportController::class,'store']);
    Route::get('imports',[ImportController::class,'index']);
    Route::post('imports/{id}',[ImportController::class,'update']);
    Route::delete('imports/{id}',[ImportController::class,'destroy']);
    Route::get('imports/{id}',[ImportController::class,'indexDetail']);
    Route::delete('imports/{id}',[ImportController::class,'destroy']);
    Route::put('imports/{id}',[ImportController::class,'update']);

});

Route::middleware(['is_login','is_customer'])->group(function(){
    Route::post('orders',[OrderController::class,'store']);
    Route::get('orders',[OrderController::class,'index']);
    Route::get('orders_customer',[OrderController::class,'customerOrder']);
});

//products 
Route::get('products',[ProductController::class,'index']);
//Order
Route::get('staffs',[StaffController::class,'index']);