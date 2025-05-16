<?php

use App\Http\Controllers\Admin\DeleteAdminController;
use App\Http\Controllers\Admin\RegisterController as AdminRegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DeletedGroundController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroundController;
use App\Http\Controllers\StatusKepemilikanController;
use App\Http\Controllers\StatusTanahController;
use App\Http\Controllers\SuperAdmin\RegisterController as SuperAdminRegisterController;
use App\Http\Controllers\TipeTanahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $user = $request->user();

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'roles' => $user->getRoleNames()
    ]);
})->middleware('auth:sanctum');

Route::post('/register/guest', RegisterController::class);
Route::post('/register/superAdmin', SuperAdminRegisterController::class);

Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');
Route::get('/get/ground', [GroundController::class, 'getAllData'])->middleware('auth:sanctum');
Route::get('/get/ground/{id}', [GroundController::class, 'getSpecificData'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum', 'role:superAdmin'])->group(function(){
    Route::get('/get/admin', [UserController::class, 'index']);
    Route::delete('/delete/admin/{id}', DeleteAdminController::class);
    Route::post('/register/admin', AdminRegisterController::class);
});


Route::middleware(['auth:sanctum', 'role:admin|superAdmin'])->group(function () {
    Route::post('/create/ground', [GroundController::class, 'store']);
    Route::patch('/update/ground/{id}', [GroundController::class, 'update']);
    Route::delete('/delete/ground/{id}', [GroundController::class, 'destroy']);
    Route::post('/restore/deleted-ground/{id}', [DeletedGroundController::class, 'restore']);
    Route::get('/get/deleted-ground', [DeletedGroundController::class, 'getAllDeletedData']);
    Route::get('/get/deleted-ground/{id}', [DeletedGroundController::class, 'getSpecificDeletedData']);
    Route::post('/create/tipe-tanah', [TipeTanahController::class, 'store']);
    Route::patch('/update/tipe-tanah/{id}', [TipeTanahController::class, 'updateTipeTanah']);
    Route::get('/get/tipe-tanah', [TipeTanahController::class, 'getAllTipeTanah']);
    Route::get('/get/status-tanah', [StatusTanahController::class, 'getAllStatusTanah']);
    Route::get('/get/status-kepemilikan', [StatusKepemilikanController::class, 'getAllStatusKepemilikan']);
});





