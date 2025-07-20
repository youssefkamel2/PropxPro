<?php

namespace App\Console\Commands;

use App\Models\RequestDemo;
use Google\Client;
use Google\Service\Calendar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CheckDemoStatuses extends Command
{
    protected $signature = 'demos:check-status';
    protected $description = 'Check and update demo request statuses';

    public function handle()
    {
        Log::info("Starting demo status check...");
        $demos = RequestDemo::where(function ($query) {
            $query->whereIn('status', ['pending', 'confirmed', 'awaiting_confirmation'])
                ->orWhereIn('meet_status', ['scheduled', 'awaiting_confirmation', 'pending']);
        })->get();

        foreach ($demos as $demo) {
            $this->checkDemoStatus($demo);
        }

        $this->info('Completed status check for ' . $demos->count() . ' demos');

        Log::info("Demo status check completed for {$demos->count()} demos.");

    }

    protected function checkDemoStatus($demo)
    {
        try {
            $client = $this->getGoogleClient();
            $service = new Calendar($client);

            $event = $service->events->get(
                config('google-calendar.calendar_id'),
                $demo->google_event_id
            );

            $statuses = $this->determineStatuses($demo, $event);

            $demo->update([
                'status' => $statuses['status'],
                'meet_status' => $statuses['meet_status'],
                'last_checked_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error("Demo status check failed for ID {$demo->id}: " . $e->getMessage());
            $demo->update([
                'meet_status' => 'failed',
                'last_checked_at' => now()
            ]);
        }
    }

    protected function getGoogleClient()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
        $client->addScope(Calendar::CALENDAR_READONLY);

        if (Storage::exists('google-calendar/oauth-token.json')) {
            $token = json_decode(Storage::get('google-calendar/oauth-token.json'), true);
            $client->setAccessToken($token);
        }

        return $client;
    }

    protected function determineStatuses($demo, $event)
    {
        $now = now();
        $status = $demo->status;
        $meetStatus = $demo->meet_status;

        // Handle cancelled events first
        if ($event->status === 'cancelled') {
            return [
                'status' => 'cancelled',
                'meet_status' => 'cancelled'
            ];
        }

        // Check attendee response if event exists
        $attendeeResponse = null;
        foreach ($event->getAttendees() ?: [] as $attendee) {
            if (strtolower($attendee->getEmail()) === strtolower($demo->email)) {
                $attendeeResponse = strtolower($attendee->getResponseStatus());
                break;
            }
        }

        // Determine status based on attendee response
        switch ($attendeeResponse) {
            case 'accepted':
                $status = 'confirmed';
                $meetStatus = 'confirmed';
                break;
            case 'declined':
                $status = 'declined';
                $meetStatus = 'declined';
                break;
            case 'tentative':
                $status = 'pending';
                $meetStatus = 'awaiting_confirmation';
                break;
            case null:
                // No response yet
                if ($demo->preferred_datetime->isFuture()) {
                    $status = 'pending';
                    $meetStatus = 'awaiting_confirmation';
                }
                break;
        }

        // Handle time-based statuses
        if ($demo->preferred_datetime < $now) {
            if ($status === 'confirmed') {
                $status = 'completed';
                $meetStatus = 'completed';
            } else if (in_array($status, ['pending', 'awaiting_confirmation'])) {
                $status = 'expired';
                $meetStatus = 'expired';
            }
        }

        // Special case for failed scheduling attempts
        if ($demo->status === 'failed' || $demo->meet_status === 'failed') {
            $status = 'failed';
            $meetStatus = 'failed';
        }

        return [
            'status' => $status,
            'meet_status' => $meetStatus
        ];
    }
}