<?php

namespace App\Http\Controllers\BoltBite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AwsController extends Controller
{
    public function presign(Request $request)
    {
        if ($request->isMethod('GET')) {
            return response()->json([
                'message' => 'AWS Presign endpoint is working',
                'method' => 'Use POST to get presigned URL'
            ]);
        }
        
        $filename = $request->input('filename', 'upload');
        $path = 'uploads/' . uniqid() . '-' . basename($filename);
        
        return response()->json([
            'key' => $path,
            'url' => 'https://mock-upload-url.com/' . $path,
            'message' => 'Mock response - AWS not configured'
        ]);
    }
}
