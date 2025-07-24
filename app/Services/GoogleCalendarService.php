<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\CreateConferenceRequest;
use Illuminate\Support\Facades\Storage;

class GoogleCalendarService
{
    protected $client;
    protected $calendarId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
        $this->client->addScope([Calendar::CALENDAR_EVENTS]);
        $this->calendarId = config('google-calendar.calendar_id');
        
        $this->loadExistingToken();
    }

    protected function loadExistingToken()
    {
        if (Storage::exists('google-calendar/oauth-token.json')) {
            $token = json_decode(Storage::get('google-calendar/oauth-token.json'), true);
            $this->client->setAccessToken($token);
            
            if ($this->client->isAccessTokenExpired()) {
                $this->refreshToken();
            }
        }
    }

    protected function refreshToken()
    {
        if ($this->client->getRefreshToken()) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken(
                $this->client->getRefreshToken()
            );
            $this->saveToken($newToken);
        }
    }

    protected function saveToken(array $token)
    {
        Storage::put('google-calendar/oauth-token.json', json_encode($token));
    }

    public function createEventWithMeet(array $eventData)
    {
        try {
            $service = new Calendar($this->client);

            $event = new Event([
                'summary' => $eventData['name'],
                'description' => $eventData['description'],
                'start' => ['dateTime' => $eventData['start']],
                'end' => ['dateTime' => $eventData['end']],
                'attendees' => [['email' => $eventData['attendee_email']]],
                'creator' => [
                    'displayName' => 'PropxPro Support',
                    'email' => 'support@propxpro.com'
                ],
                'organizer' => [
                    'displayName' => 'PropxPro Support',
                    'email' => 'info@propxpro.com'
                ],
            ]);

            // Add Google Meet conference
            $conference = new ConferenceData();
            $conference->setCreateRequest(new CreateConferenceRequest([
                'requestId' => uniqid(),
                'conferenceSolutionKey' => ['type' => 'hangoutsMeet']
            ]));
            $event->setConferenceData($conference);

            $createdEvent = $service->events->insert(
                $this->calendarId,
                $event,
                ['conferenceDataVersion' => 1]
            );

            return [
                'event_id' => $createdEvent->getId(),
                'meet_link' => $createdEvent->getHangoutLink(),
                'html_link' => $createdEvent->getHtmlLink()
            ];

        } catch (\Exception $e) {
            throw $e;
        }
    }
}