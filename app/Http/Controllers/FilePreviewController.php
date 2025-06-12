<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vish4395\LaravelFileViewer\LaravelFileViewer;

class FilePreviewController extends Controller
{
    public function filePreview($fileName)
    {
        $remoteUrl = 'https://digitalmap-umbulharjo-api.madanateknologi.web.id/storage/ground_sertificate/' . $fileName;
    
        // Verify file exists (simple HTTP check)
        if (!@get_headers($remoteUrl)[0] === 'HTTP/1.1 200 OK') {
            return response()->json(['error' => 'File not found'], 404);
        }

        return LaravelFileViewer::show(
            $fileName,
            $remoteUrl, 
            $remoteUrl,
            null,
            [['label' => __('Label'), 'value' => "Value"]]
        );
    }
}