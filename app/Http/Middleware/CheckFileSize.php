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
        
        // Check total request size first (this is the most important check)
        $contentLength = $request->server('CONTENT_LENGTH');
        
        if ($contentLength && $contentLength > $maxFileSize) {
            // For multipart form data, we need to determine which file is too large
            $contentType = $request->server('CONTENT_TYPE');
            
            if (str_contains($contentType, 'multipart/form-data')) {
                // Check if this is likely a video upload based on the route
                $route = $request->route();
                if ($route && str_contains($route->uri(), 'videos')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Video file size too large. Maximum allowed size is 10MB.',
                        'data' => null
                    ], 413);
                }
                
                // Check if this is likely an image upload
                if ($contentLength > $maxImageSize && $contentLength <= $maxFileSize) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Image file size too large. Maximum allowed size is 4MB.',
                        'data' => null
                    ], 413);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'File size too large. Maximum allowed size is 10MB.',
                'data' => null
            ], 413);
        }

        // If we get here, the request size is acceptable, continue to process
        return $next($request);
    }
}