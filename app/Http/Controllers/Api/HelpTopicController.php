<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HelpTopic;
use App\Http\Resources\HelpTopicResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\ResponseTrait;

class HelpTopicController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:view_help_topics')->only(['index', 'show']);
        $this->middleware('permission:create_help_topic')->only(['store']);
        $this->middleware('permission:edit_help_topic')->only(['update']);
        $this->middleware('permission:delete_help_topic')->only(['destroy']);
        $this->middleware('permission:create_help_topic')->only(['uploadContentImage']);
    }

    public function index()
    {
        $topics = HelpTopic::with('subcategory', 'author')->orderBy('order')->get();
        return $this->success(HelpTopicResource::collection($topics), 'Help topics fetched successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subcategory_id' => 'required|exists:help_subcategories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:help_topics,slug',
            'content' => 'required|string',
            'headings' => 'nullable',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $data = $validator->validated();
        if (isset($data['headings'])) {
            if (is_string($data['headings'])) {
                $data['headings'] = json_decode($data['headings'], true);
            }
        }
        $data['created_by'] = Auth::id();
        $topic = HelpTopic::create($data);
        return $this->success(new HelpTopicResource($topic), 'Help topic created successfully', 201);
    }

    public function show($id)
    {
        $topic = HelpTopic::with('subcategory', 'author')->findOrFail($id);
        return $this->success(new HelpTopicResource($topic), 'Help topic fetched successfully');
    }

    public function update(Request $request, $id)
    {
        $topic = HelpTopic::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'subcategory_id' => 'sometimes|exists:help_subcategories,id',
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:help_topics,slug,' . $id,
            'content' => 'sometimes|string',
            'headings' => 'nullable',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $data = $validator->validated();
        if (isset($data['headings'])) {
            if (is_string($data['headings'])) {
                $data['headings'] = json_decode($data['headings'], true);
            }
        }
        $topic->update($data);
        return $this->success(new HelpTopicResource($topic), 'Help topic updated successfully');
    }

    public function destroy($id)
    {
        $topic = HelpTopic::findOrFail($id);
        $topic->delete();
        return $this->success(null, 'Topic deleted successfully');
    }

    public function uploadContentImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:4096',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $path = $request->file('image')->store('help-content', 'public');
        $url = asset('storage/' . $path);
        return $this->success(['url' => $url], 'Image uploaded successfully', 201);
    }
}