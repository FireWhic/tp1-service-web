<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/equipment', [EquipmentController::class,'index']);
Route::get('/equipment/popularity', [EquipmentController::class,'showPopularity']);
Route::get('/equipment/{id}', [EquipmentController::class,'show']);


Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::patch('/users/{id}', [UserController::class, 'update']);

Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

Route::get('/rentals', [RentalController::class, 'averageRentalPrice']);