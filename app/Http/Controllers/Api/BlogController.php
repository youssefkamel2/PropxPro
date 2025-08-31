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

    /**
     * Process content to update dynamic dates to current year
     * 
     * @param string $content
     * @return string
     */
    private function processDynamicDates($content)
    {
        if (empty($content)) {
            return $content;
        }

        $currentYear = date('Y');
        $pattern = '/<span\s+class=[\'\"]dynamic-date[\'\"]>(\d{4})<\/span>/i';
        
        return preg_replace_callback($pattern, function($matches) use ($currentYear) {
            return str_replace($matches[1], $currentYear, $matches[0]);
        }, $content);
    }

    public function __construct()
    {
        $this->middleware('permission:view_blogs')->only(['index']);
        $this->middleware('permission:create_blog')->only('store');
        $this->middleware('permission:edit_blog')->only(['update', 'bulkUpdateStatus', 'bulkUpdateCategory', 'bulkMarkAsHero', 'bulkUpdate']);
        $this->middleware('permission:delete_blog')->only(['destroy', 'bulkDelete']);
        $this->middleware('permission:toggle_blog_status')->only(['toggleActive', 'bulkUpdateStatus']);
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
            'slug' => 'required|string|max:255|unique:blogs,slug',
            'cover_photo' => 'required|image|max:4096',
            'category' => 'required|in:trending,guides,insights',
            'content' => 'required|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
            'headings' => 'sometimes',
            'mark_as_hero' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        try {
            $data = $validator->validated();
            if (isset($data['headings'])) {
                if (is_string($data['headings'])) {
                    $data['headings'] = json_decode($data['headings'], true);
                }
            }
            $data['created_by'] = Auth::id();
            
            // Process dynamic dates in content
            if (!empty($data['content'])) {
                $data['content'] = $this->processDynamicDates($data['content']);
            }
            
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

        if (is_numeric($blog)) {
            $blog = Blog::with(['author', 'faqs'])->find($blog);
        } else {
            $blog = Blog::with(['author', 'faqs'])->where('slug', $blog)->first();
        }

        if (!$blog || !$blog->is_active) {
            return $this->error('Blog not found or inactive', 404);
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
            'slug' => 'sometimes|string|max:255|unique:blogs,slug,' . $blog->id,
            'cover_photo' => 'sometimes|image|max:4096',
            'category' => 'sometimes|in:trending,guides,insights',
            'content' => 'sometimes|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50',
            'headings' => 'sometimes',
            'mark_as_hero' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        try {
            $data = $validator->validated();
            if (isset($data['headings'])) {
                if (is_string($data['headings'])) {
                    $data['headings'] = json_decode($data['headings'], true);
                }
            }

            // Process dynamic dates in content
            if (!empty($data['content'])) {
                $data['content'] = $this->processDynamicDates($data['content']);
            }
            
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
        $hero = Blog::with(['author', 'faqs'])->where('is_active', true)->where('mark_as_hero', true)->latest()->first();
        if (!$hero) {
            $hero = Blog::with(['author', 'faqs'])->where('is_active', true)->latest()->first();
        }

        $latest = Blog::with(['author', 'faqs'])->where('is_active', true)->latest()->take(3)->get();
        $guides = Blog::with(['author', 'faqs'])->where('is_active', true)->where('category', 'guides')->latest()->take(3)->get();
        $trending = Blog::with(['author', 'faqs'])->where('is_active', true)->where('category', 'trending')->latest()->take(3)->get();
        return $this->success([
            'hero' => $hero ? new BlogResource($hero) : null,
            'latest' => BlogResource::collection($latest),
            'guides' => BlogResource::collection($guides),
            'trending' => BlogResource::collection($trending),
        ], 'Landing blogs fetched successfully');
    }

    public function activeBlogs()
    {
        $blogs = Blog::with(['author', 'faqs'])->where('is_active', true)->latest()->get();
        return $this->success(BlogResource::collection($blogs), 'Active blogs fetched successfully');
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:blogs,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $blogs = Blog::whereIn('id', $request->ids)->get();
            
            foreach ($blogs as $blog) {
                if ($blog->cover_photo) {
                    Storage::disk('public')->delete($blog->cover_photo);
                }
                $blog->delete();
            }

            return $this->success(null, count($request->ids) . ' blogs deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete blogs: ' . $e->getMessage(), 500);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:blogs,id',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $count = Blog::whereIn('id', $request->ids)->update(['is_active' => $request->status]);

            return $this->success(null, 'Status updated for ' . $count . ' blogs');
        } catch (\Exception $e) {
            return $this->error('Failed to update blog statuses: ' . $e->getMessage(), 500);
        }
    }

    public function bulkUpdateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:blogs,id',
            'category' => 'required|in:trending,guides,insights',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $count = Blog::whereIn('id', $request->ids)
                ->update(['category' => $request->category]);

            return $this->success(null, 'Category updated for ' . $count . ' blogs');
        } catch (\Exception $e) {
            return $this->error('Failed to update blog categories: ' . $e->getMessage(), 500);
        }
    }

    public function bulkMarkAsHero(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:blogs,id',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            // First reset all heroes
            Blog::where('mark_as_hero', true)->update(['mark_as_hero' => false]);
            
            // Set the new hero
            $blog = Blog::find($request->id);
            $blog->mark_as_hero = true;
            $blog->save();

            return $this->success(new BlogResource($blog), 'Blog marked as hero successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update hero status: ' . $e->getMessage(), 500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:blogs,id',
            'category' => 'sometimes|in:trending,guides,insights',
            'is_active' => 'sometimes|boolean',
            'mark_as_hero' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $updates = $request->only(['category', 'is_active', 'mark_as_hero']);
            
            // If marking as hero, first reset all heroes
            (isset($updates['mark_as_hero']) && $updates['mark_as_hero']) 
                ? Blog::where('mark_as_hero', true)->update(['mark_as_hero' => false])
                : null;

            $count = Blog::whereIn('id', $request->ids)->update($updates);

            return $this->success(null, 'Updated ' . $count . ' blogs');
        } catch (\Exception $e) {
            return $this->error('Failed to update blogs: ' . $e->getMessage(), 500);
        }
    }

    public function uploadContentImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:4096',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $path = $request->file('image')->store('blog-content', 'public');
        $url = asset('storage/' . $path);
        return $this->success(['url' => $url], 'Image uploaded successfully');
    }

    public function recentBlogs()
    {
        $blogs = Blog::where('is_active', true)
            ->select('id', 'slug', 'cover_photo', 'category' ,'title', 'created_at', 'updated_at')
            ->latest()
            ->take(5)
            ->get();

        // return the full cover photo url
        $blogs->map(function ($blog) {
            $blog->cover_photo = asset('storage/' . $blog->cover_photo);
            return $blog;
        });

        return $this->success($blogs, 'Recent blogs fetched successfully');
    }

    public function relatedBlogs($blogId)
    {
        $blog = Blog::find($blogId);
        if (!$blog) {
            return $this->error('Blog not found', 404);
        }

        $relatedBlogs = Blog::where('is_active', true)
            ->where('id', '!=', $blogId)
            ->where('category', $blog->category)
            ->select('id', 'slug', 'cover_photo', 'title', 'category' ,'created_at', 'updated_at')
            ->latest()
            ->take(3)
            ->get();

        $relatedBlogs->map(function ($blog) {
            $blog->cover_photo = asset('storage/' . $blog->cover_photo);
            return $blog;
        });

        return $this->success($relatedBlogs, 'Related blogs fetched successfully');
    }
}