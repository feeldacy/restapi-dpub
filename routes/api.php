<?php

use App\Http\Controllers\Admin\RegisterController as AdminRegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GroundController;
use App\Http\Controllers\StatusKepemilikanController;
use App\Http\Controllers\StatusTanahController;
use App\Http\Controllers\SuperAdmin\RegisterController as SuperAdminRegisterController;
use App\Http\Controllers\TipeTanahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'id' => $request->user()->id,
        'name' => $request->user()->name,
        'roles' => $request->user()->getRoleNames(), // dari spatie/laravel-permission
    ]);
});

Route::post('/register/guest', RegisterController::class);
Route::post('/register/admin', AdminRegisterController::class);
Route::post('/register/superAdmin', SuperAdminRegisterController::class);

Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');


Route::get('/status-tanah', [StatusTanahController::class, 'getAllStatusTanah'])->middleware('auth:sanctum');
Route::get('/status-kepemilikan', [StatusKepemilikanController::class, 'getAllStatusKepemilikan'])->middleware('auth:sanctum');



Route::middleware(['auth:sanctum', 'role:admin|superAdmin'])->group(function () {
    Route::post('/create-ground', [GroundController::class, 'store']);
    Route::patch('/update-ground/{id}', [GroundController::class, 'update']);
    Route::delete('/delete-ground/{id}', [GroundController::class, 'destroy']);
    Route::get('/get-ground', [GroundController::class, 'fetchAllData']);
    Route::post('/create/tipe-tanah', [TipeTanahController::class, 'store']);
    Route::patch('/update-tipe-tanah/{id}', [TipeTanahController::class, 'updateTipeTanah']);
    Route::get('/get/tipe-tanah', [TipeTanahController::class, 'getAllTipeTanah']);
});


