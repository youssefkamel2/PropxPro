<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpArticleImage;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HelpArticleImageController extends Controller
{
    use ResponseTrait;

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $file = $request->file('image');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Store file
        $path = $file->storeAs('public/help-article-images', $filename);
        
        // Create database record
        $image = HelpArticleImage::create([
            'path' => $path,
            'url' => Storage::url($path),
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'size' => $size,
        ]);

        return $this->success([
            'url' => config('app.url') . $image->url,
            'id' => $image->id,
        ], 'Image uploaded successfully');
    }

    public function destroy($id)
    {
        $image = HelpArticleImage::findOrFail($id);
        
        // Delete file
        Storage::delete($image->path);
        
        // Delete record
        $image->delete();

        return $this->success(null, 'Image deleted successfully');
    }
}