<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebinarEvent;
use App\Http\Resources\WebinarEventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ResponseTrait;

class WebinarEventController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:manage_webinars')->except(['publicIndex', 'publicShow']);
    }

    // Admin: List all events
    public function index()
    {
        $events = WebinarEvent::with('registrations')->orderBy('date', 'desc')->get();
        return $this->success(WebinarEventResource::collection($events), 'Events fetched successfully');
    }

    // Admin: Create event
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'cover_photo' => 'required|image|max:4096',
            'duration' => 'required|string',
            'presented_by' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $data = $validator->validated();
        $data['created_by'] = Auth::id();

        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $request->file('cover_photo')->store('webinars-events', 'public');
        }

        $event = WebinarEvent::create($data);
        return $this->success(new WebinarEventResource($event), 'Event created successfully', 201);
    }

    // Admin: Show event
    public function show($slug)
    {
        $event = WebinarEvent::where('slug', $slug)->first();
        if (!$event) {
            return $this->error('Event not found', 404);
        }
        return $this->success(new WebinarEventResource($event), 'Event fetched successfully');
    }

    // Admin: Update event
    public function update(Request $request, $slug)
    {
        $event = WebinarEvent::where('slug', $slug)->first();
        if (!$event) {
            return $this->error('Event not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'cover_photo' => 'sometimes|string',
            'duration' => 'sometimes|string',
            'presented_by' => 'sometimes|string',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }
        $event->update($validator->validated());
        return $this->success(new WebinarEventResource($event), 'Event updated successfully');
    }

    // Admin: Delete event
    public function destroy($slug)
    {
        $event = WebinarEvent::where('slug', $slug)->first();
        if (!$event) {
            return $this->error('Event not found', 404);
        }
        $event->delete();
        return $this->success(null, 'Event deleted successfully');
    }

    // Public: List events
    public function publicIndex()
    {
        $events = WebinarEvent::orderBy('date', 'asc')->get();
        return $this->success(WebinarEventResource::collection($events), 'Upcoming events fetched successfully');
    }

    // Public: Show event
    public function publicShow($slug)
    {
        $event = WebinarEvent::where('slug', $slug)->first();
        if (!$event) {
            return $this->error('Event not found', 404);
        }
        return $this->success(new WebinarEventResource($event), 'Event fetched successfully');
    }
}