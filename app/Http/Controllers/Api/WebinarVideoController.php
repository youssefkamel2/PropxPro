<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebinarVideo;
use App\Http\Resources\WebinarVideoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;

class WebinarVideoController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:manage_webinars')->except(['publicIndex', 'publicShow']);
    }

    // Admin: List all videos
    public function index()
    {
        $videos = WebinarVideo::orderBy('created_at', 'desc')->get();
        return $this->success(WebinarVideoResource::collection($videos), 'Videos fetched successfully');
    }

    // Admin: Create video
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'video_url' => 'required|string',
            'cover_photo' => 'nullable|string',
            'type' => 'required|in:upload,youtube',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $data = $validator->validated();
        $data['created_by'] = Auth::id();
        $video = WebinarVideo::create($data);
        return $this->success(new WebinarVideoResource($video), 'Video created successfully', 201);
    }

    // Admin: Show video
    public function show($slug)
    {
        $video = WebinarVideo::where('slug', $slug)->first();
        if (!$video) {
            return $this->error('Video not found', 404);
        }
        return $this->success(new WebinarVideoResource($video), 'Video fetched successfully');
    }

    // Admin: Update video
    public function update(Request $request, $slug)
    {
        $video = WebinarVideo::where('slug', $slug)->first();
        if (!$video) {
            return $this->error('Video not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'video_url' => 'sometimes|string',
            'cover_photo' => 'nullable|string',
            'type' => 'sometimes|in:upload,youtube',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $video->update($validator->validated());
        return $this->success(new WebinarVideoResource($video), 'Video updated successfully');
    }

    // Admin: Delete video
    public function destroy($slug)
    {
        $video = WebinarVideo::where('slug', $slug)->first();
        if (!$video) {
            return $this->error('Video not found', 404);
        }
        $video->delete();
        return $this->success(null, 'Video deleted successfully');
    }

    // Public: List videos
    public function publicIndex()
    {
        $videos = WebinarVideo::orderBy('created_at', 'desc')->get();
        return $this->success(WebinarVideoResource::collection($videos), 'On-demand videos fetched successfully');
    }

    public function publicShow($slug)
    {
        $video = WebinarVideo::where('slug', $slug)->first();
        if (!$video) {
            return $this->error('Video not found', 404);
        }
        return $this->success(new WebinarVideoResource($video), 'Video fetched successfully');
    }
}