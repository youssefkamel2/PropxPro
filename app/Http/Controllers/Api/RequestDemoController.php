<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestDemo;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestDemoConfirmation;
use Illuminate\Support\Facades\Log;
use App\Traits\ResponseTrait;
use Carbon\Carbon;

class RequestDemoController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:view_request_demos')->only(['index']);
    }

    public function index(Request $request)
    {
        try {

            // get all requests 

            $demos = RequestDemo::orderBy('created_at', 'desc')->get();
            

            return $this->success($demos, 'Demo requests retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Failed to retrieve demo requests: ' . $e->getMessage());
            return $this->error('Failed to retrieve demo requests', 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        $demo = $this->createDemoRequest($validated);

        try {
            $this->scheduleCalendarEvent($demo);

            return $this->success([
                'meet_link' => $demo->google_meet_link,
                'event_link' => $this->getCalendarEventLink($demo),
                'demo_id' => $demo->id
            ], 'Demo scheduled successfully');
        } catch (\Exception $e) {
            Log::error('Demo scheduling failed: ' . $e->getMessage());
            return $this->handleFailure($demo, $e);
        }
    }

    protected function validateRequest(Request $request): array
    {
        return $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'real_estate_experience' => 'nullable|string|max:255',
            'monthly_budget' => 'nullable|string|max:255',
            'preferred_datetime' => 'required|date|after:now',
        ]);
    }

    protected function createDemoRequest(array $data): RequestDemo
    {
        return RequestDemo::create(array_merge($data, [
            'status' => RequestDemo::STATUS_PENDING,
            'meet_status' => RequestDemo::MEET_STATUS_PENDING
        ]));
    }

    protected function scheduleCalendarEvent(RequestDemo $demo): void
    {
        $calendarService = new GoogleCalendarService();

        $eventDetails = [
            'name' => 'Demo Request - ' . $demo->full_name,
            'description' => $this->buildEventDescription($demo),
            'start' => $demo->preferred_datetime->toIso8601String(),
            'end' => $demo->preferred_datetime->addHour()->toIso8601String(),
            'attendee_email' => $demo->email,
        ];

        $event = $calendarService->createEventWithMeet($eventDetails);

        $demo->update([
            'google_event_id' => $event['event_id'],
            'google_meet_link' => $event['meet_link'],
            'meet_status' => RequestDemo::MEET_STATUS_AWAITING_CONFIRMATION,
            'status' => RequestDemo::STATUS_PENDING,
            'scheduled_at' => now(),
        ]);

        $this->sendConfirmationEmail($demo);
    }

    protected function buildEventDescription(RequestDemo $demo): string
    {
        return sprintf(
            "Demo Request Details:\n\n" .
            "Name: %s %s\n" .
            "Email: %s\n" .
            "Phone: %s\n" .
            "Real Estate Experience: %s\n" .
            "Monthly Budget: %s\n" .
            "Preferred Time: %s\n\n" .
            "This meeting was scheduled through the demo request form.",
            $demo->first_name,
            $demo->last_name,
            $demo->email,
            $demo->phone,
            $demo->real_estate_experience ?? 'Not specified',
            $demo->monthly_budget ?? 'Not specified',
            $demo->formatted_datetime
        );
    }

    protected function getCalendarEventLink(RequestDemo $demo): ?string
    {
        if (!$demo->google_event_id) {
            return null;
        }

        return sprintf(
            'https://calendar.google.com/calendar/event?eid=%s',
            urlencode($demo->google_event_id)
        );
    }

    protected function sendConfirmationEmail(RequestDemo $demo): void
    {
        try {
            Mail::to($demo->email)->send(new RequestDemoConfirmation($demo));
            $demo->update(['email_sent_at' => now()]);
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email: ' . $e->getMessage());
            $demo->update(['failure_reason' => 'Email failed: ' . $e->getMessage()]);
        }
    }

    protected function handleFailure(RequestDemo $demo, \Exception $e): \Illuminate\Http\JsonResponse
    {
        $demo->update([
            'status' => RequestDemo::STATUS_FAILED,
            'meet_status' => RequestDemo::MEET_STATUS_FAILED,
            'failure_reason' => $e->getMessage()
        ]);

        return $this->error(
            'Demo request received but scheduling failed. Our team will contact you.',
            200,
            ['demo_id' => $demo->id]
        );
    }
}