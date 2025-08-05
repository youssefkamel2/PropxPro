<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFileSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define maximum file sizes (in bytes)
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        $maxImageSize = 4 * 1024 * 1024; // 4MB for images
        
        // Check total request size first (prevents nginx 413 error)
        $contentLength = $request->server('CONTENT_LENGTH');
        if ($contentLength && $contentLength > $maxFileSize) {
            return response()->json([
                'success' => false,
                'message' => 'File size too large. Maximum allowed size is 10MB.',
                'data' => null
            ], 413);
        }

        // Check individual file sizes for file uploads
        if ($request->hasFile('video_url')) {
            $file = $request->file('video_url');
            if ($file->getSize() > $maxFileSize) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video file size too large. Maximum allowed size is 10MB.',
                    'data' => null
                ], 413);
            }
        }

        // Check cover photo size
        if ($request->hasFile('cover_photo')) {
            $file = $request->file('cover_photo');
            if ($file->getSize() > $maxImageSize) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cover photo size too large. Maximum allowed size is 4MB.',
                    'data' => null
                ], 413);
            }
        }

        // Check for any other uploaded files
        foreach ($request->allFiles() as $key => $file) {
            if (is_array($file)) {
                foreach ($file as $singleFile) {
                    if ($singleFile->getSize() > $maxFileSize) {
                        return response()->json([
                            'success' => false,
                            'message' => "File '{$key}' is too large. Maximum allowed size is 10MB.",
                            'data' => null
                        ], 413);
                    }
                }
            } else {
                if ($file->getSize() > $maxFileSize) {
                    return response()->json([
                        'success' => false,
                        'message' => "File '{$key}' is too large. Maximum allowed size is 10MB.",
                        'data' => null
                    ], 413);
                }
            }
        }

        return $next($request);
    }
}