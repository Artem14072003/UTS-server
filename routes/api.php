<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\CalcController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\SparePartController;
use App\Http\Controllers\api\TruckController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [HomeController::class, 'index']);
Route::get('/catalog', [TruckController::class, 'index']);
Route::get('/catalog/{id}', [TruckController::class, 'show']);
Route::get('/spare-parts', [SparePartController::class, 'index']);
Route::get('/spare-parts/{id}', [SparePartController::class, 'show']);
Route::get('/calculators', [CalcController::class, 'index']);
Route::post('/mail-to', [HomeController::class, 'mailto']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin', [AdminController::class, 'login']);
    Route::get('/logout', [AdminController::class, 'logout']);
    Route::post('/catalog', [TruckController::class, 'store']);
    Route::patch('/admin/update', [AdminController::class, 'update']);
    Route::post('/spare-parts', [SparePartController::class, 'store']);
    Route::patch('/catalog/{id}', [TruckController::class, 'update']);
    Route::delete('/catalog/{id}', [TruckController::class, 'destroy']);
    Route::post('/spare-parts/{id}', [SparePartController::class, 'update']);
    Route::delete('/spare-parts/{id}', [SparePartController::class, 'destroy']);
    Route::post('/calculators', [CalcController::class, 'update']);
});
