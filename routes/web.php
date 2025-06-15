<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });

// Route::get('/cek', function () {
//     return 'Laravel jalan';
// });

Route::get('/view-pdf/{filename}', [App\Http\Controllers\PdfController::class, 'show']);