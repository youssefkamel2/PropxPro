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
            'type' => 'required|in:upload,youtube',
            'cover_photo' => 'required|image|max:4096',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $data = $validator->validated();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $request->file('cover_photo')->store('webinars-videos-covers', 'public');
        }

        if ($request->type === 'youtube') {
            $request->validate([
                'video_url' => ['required', 'string', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/i']
            ]);
            $data['video_url'] = $request->video_url;
        } elseif ($request->type === 'upload') {
            $request->validate([
                'video_url' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:2048'
            ]);
            $data['video_url'] = $request->file('video_url')->store('webinars-videos-uploads', 'public');
        }

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
            'type' => 'sometimes|in:upload,youtube',
            'cover_photo' => 'nullable|string',
            // video_url validation will be handled below
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $data = $validator->validated();

        if ($request->has('type')) {
            if ($request->type === 'youtube') {
                $request->validate([
                    'video_url' => ['required', 'string', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/i']
                ]);
                $data['video_url'] = $request->video_url;
            } elseif ($request->type === 'upload') {
                $request->validate([
                    'video_url' => 'required|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime|max:2048'
                ]);
                $data['video_url'] = $request->file('video_url')->store('webinars-videos', 'public');
            }
        }

        $video->update($data);
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