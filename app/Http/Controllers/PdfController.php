<?php

namespace App\Http\Controllers;

use App\Models\detailTanah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    public function show($filename)
    {
        $safeFilename = basename($filename);

        // Path to PDF directory
        $path = storage_path('app/public/ground_sertificate/' . $safeFilename);
        // return $path;

        // Optional: check file extension
        if (!file_exists($path) || pathinfo($safeFilename, PATHINFO_EXTENSION) !== 'pdf') {
            abort(404);
        }

        return response()->stream(function () use ($path) {
            readfile($path);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }
}
