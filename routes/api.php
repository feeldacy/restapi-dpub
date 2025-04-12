<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GroundController;
use App\Http\Controllers\StatusKepemilikanController;
use App\Http\Controllers\StatusTanahController;
use App\Http\Controllers\TipeTanahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');


Route::get('/tipe-tanah', [TipeTanahController::class, 'getAllTipeTanah'])->middleware('auth:sanctum');
Route::get('/status-tanah', [StatusTanahController::class, 'getAllStatusTanah'])->middleware('auth:sanctum');
Route::get('/status-kepemilikan', [StatusKepemilikanController::class, 'getAllStatusKepemilikan'])->middleware('auth:sanctum');

Route::post('/create-ground', [GroundController::class, 'store'])->middleware('auth:sanctum','permission:tambah-map');
Route::patch('/update-ground/{id}', [GroundController::class, 'update'])->middleware('auth:sanctum','permission:edit-map');
Route::delete('/delete-ground/{id}', [GroundController::class, 'destroy'])->middleware('auth:sanctum','permission:hapus-map');
Route::get('/get-ground', [GroundController::class, 'fetchAllData'])->middleware('auth:sanctum','permission:lihat-map');

