<?php

use App\Http\Controllers\Admin\DeleteAdminController;
use App\Http\Controllers\Admin\RegisterController as AdminRegisterController;
use App\Http\Controllers\Admin\EditAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DeletedGroundController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroundController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\StatusKepemilikanController;
use App\Http\Controllers\StatusTanahController;
use App\Http\Controllers\SubmitForgotPasswordController;
use App\Http\Controllers\SuperAdmin\RegisterController as SuperAdminRegisterController;
use App\Http\Controllers\TipeTanahController;
use App\Http\Controllers\Auth\SubmitForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyForgotPasswordController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\FilePreviewController;
use App\Http\Controllers\VerifyForgotPasswordController;
use App\Models\User;
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

Route::post('forgot-password-by-otp', SubmitForgotPasswordController::class);
Route::post('verify-forgot-password-otp', VerifyForgotPasswordController::class);
Route::post('reset-password-by-otp', ResetPasswordController::class);

Route::post('/register/guest', RegisterController::class);
Route::post('/register/superAdmin', SuperAdminRegisterController::class);

Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');
Route::get('/get/ground', [GroundController::class, 'getAllData'])->middleware('auth:sanctum');
Route::get('/get/ground/{id}', [GroundController::class, 'getSpecificData'])->middleware('auth:sanctum');

Route::post('/visit', [VisitorController::class, 'recordVisit'])->middleware('auth:sanctum');
Route::get('/get/visitors', [VisitorController::class, 'getCounts']);

Route::middleware(['auth:sanctum', 'role:superAdmin'])->group(function(){
    Route::get('/get/admin', [UserController::class, 'index']);
    Route::delete('/delete/admin/{id}', DeleteAdminController::class);
    Route::post('/register/admin', AdminRegisterController::class);
    Route::patch('/update/admin/{id}', EditAdminController::class);
});

Route::post('forgot-password-by-otp', SubmitForgotPasswordController::class);
Route::post('verify-forgot-password-otp', VerifyForgotPasswordController::class);
Route::post('reset-password-by-otp', ResetPasswordController::class);


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


Route::get('/auth/verify-email/{id}/{hash}', function ($id, $hash, Request $request) {
        // Find user by ID
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    // Verify if the hash is correct
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'Invalid verification link.'], 403);
    }

    // Mark email as verified
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect(env('FRONTEND_URL') . '/email-verified');
    // return response()->json(['message' => 'Email verified successfully!']);
})->middleware('signed')->name('verification.verify');


Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now(),
    ]);
});





Route::get('/auth/verify-email/{id}/{hash}', function ($id, $hash, Request $request) {
        // Find user by ID
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found.'], 404);
    }

    // Verify if the hash is correct
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'Invalid verification link.'], 403);
    }

    // Mark email as verified
    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect(env('FRONTEND_URL') . '/email-verified');
    // return response()->json(['message' => 'Email verified successfully!']);
})->middleware('signed')->name('verification.verify');


Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now(),
    ]);
});




Route::get('/file-preview/{fileName}', [FilePreviewController::class, 'filePreview']);

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now(),
    ]);
});




