<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendBlogToSubscribersJob;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\BlogResource;

class BlogController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:view_blogs')->only(['index']);
        $this->middleware('permission:create_blog')->only('store');
        $this->middleware('permission:edit_blog')->only('update');
        $this->middleware('permission:delete_blog')->only('destroy');
        $this->middleware('permission:toggle_blog_status')->only('toggleActive');
    }

    public function index()
    {
        $blogs = Blog::with('author')->get();
        return $this->success(BlogResource::collection($blogs), 'Blogs fetched successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'cover_photo' => 'required|image|max:4096',
            'category' => 'required|in:trending,guides,insights',
            'content' => 'required|string',
            'mark_as_hero' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        try {
            $data = $validator->validated();
            $data['created_by'] = Auth::id();
            if ($request->hasFile('cover_photo')) {
                $data['cover_photo'] = $request->file('cover_photo')->store('blogs', 'public');
            }
            $blog = Blog::create($data);
            if ($blog->is_active) {
                SendBlogToSubscribersJob::dispatch($blog);
            }

            // if mark as hero is true, then update the other blog's mark as hero to false
            if ($data['mark_as_hero']) {
                Blog::where('id', '!=', $blog->id)->update(['mark_as_hero' => false]);
            }

            return $this->success(new BlogResource($blog), 'Blog created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create blog: ' . $e->getMessage(), 500);
        }
    }

    public function show($blog)
    {
        $blog = Blog::with('author')->find($blog);
        if (!$blog) {
            return $this->error('Blog not found', 404);
        }

        return $this->success(new BlogResource($blog), 'Blog fetched successfully');
    }

    public function update(Request $request, $blog)
    {
        $blog = Blog::with('author')->find($blog);
        if (!$blog) {
            return $this->error('Blog not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'cover_photo' => 'sometimes|image|max:4096',
            'category' => 'sometimes|in:trending,guides,insights',
            'content' => 'sometimes|string',
            'mark_as_hero' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        try {
            $data = $validator->validated();
            if ($request->hasFile('cover_photo')) {
                // Delete old image
                if ($blog->cover_photo) {
                    Storage::disk('public')->delete($blog->cover_photo);
                }
                $data['cover_photo'] = $request->file('cover_photo')->store('blogs', 'public');
            }
            $blog->update($data);
            return $this->success(new BlogResource($blog), 'Blog updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update blog: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($blog)
    {
        $blog = Blog::with('author')->find($blog);
        if (!$blog) {
            return $this->error('Blog not found', 404);
        }

        try {
            if ($blog->cover_photo) {
                Storage::disk('public')->delete($blog->cover_photo);
            }
            $blog->delete();
            return $this->success(null, 'Blog deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete blog: ' . $e->getMessage(), 500);
        }
    }

    public function toggleActive($blog)
    {
        $blog = Blog::with('author')->find($blog);
        if (!$blog) {
            return $this->error('Blog not found', 404);
        }

        $blog->is_active = !$blog->is_active;
        $blog->save();
        return $this->success($blog, 'Blog status updated successfully');
    }

    public function publicIndex()
    {
        // if no hero is found, then get the latest blog
        $hero = Blog::where('is_active', true)->where('mark_as_hero', true)->latest()->first();
        if (!$hero) {
            $hero = Blog::where('is_active', true)->latest()->first();
        }

        $latest = Blog::where('is_active', true)->latest()->take(3)->get();
        $guides = Blog::where('is_active', true)->where('category', 'guides')->latest()->take(3)->get();
        $trending = Blog::where('is_active', true)->where('category', 'trending')->latest()->take(3)->get();
        return $this->success([
            'hero' => $hero ? new BlogResource($hero) : null,
            'latest' => BlogResource::collection($latest),
            'guides' => BlogResource::collection($guides),
            'trending' => BlogResource::collection($trending),
        ], 'Landing blogs fetched successfully');
    }

    public function activeBlogs()
    {
        $blogs = Blog::where('is_active', true)->latest()->get();
        return $this->success(BlogResource::collection($blogs), 'Active blogs fetched successfully');
    }
} 