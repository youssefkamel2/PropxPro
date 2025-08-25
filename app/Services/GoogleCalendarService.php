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
            try {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken(
                    $this->client->getRefreshToken()
                );
                
                if (isset($newToken['error'])) {
                    throw new \Exception('OAuth refresh failed: ' . $newToken['error'] . ' - ' . ($newToken['error_description'] ?? ''));
                }
                
                $this->saveToken($newToken);
            } catch (\Exception $e) {
                throw new \Exception('Failed to refresh OAuth token: ' . $e->getMessage());
            }
        } else {
            throw new \Exception('No refresh token available. OAuth re-authorization required.');
        }
    }

    protected function saveToken(array $token)
    {
        Storage::put('google-calendar/oauth-token.json', json_encode($token));
    }

    public function createEventWithMeet(array $eventData)
    {
        try {
            // Verify we have a valid access token
            if (!$this->client->getAccessToken()) {
                throw new \Exception('No valid OAuth access token available');
            }

            $service = new Calendar($this->client);

            $event = new Event([
                'summary' => $eventData['name'],
                'description' => $eventData['description'],
                'start' => ['dateTime' => $eventData['start']],
                'end' => ['dateTime' => $eventData['end']],
                'guestsCanInviteOthers' => false,
                'guestsCanModify' => false,
                'guestsCanSeeOtherGuests' => false,
                'attendees' => [
                    [
                        'email' => $eventData['attendee_email'],
                        'responseStatus' => 'declined' // This prevents Google from sending an invite
                    ]
                ],
                'creator' => [
                    'displayName' => 'PropxPro Support',
                    'email' => 'support@propxpro.com',
                    'self' => true
                ],
                'organizer' => [
                    'displayName' => 'PropxPro Support',
                    'email' => 'info@propxpro.com',
                    'self' => true
                ],
                'transparency' => 'opaque',
                'visibility' => 'private',
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => []
                ]
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
                [
                    'conferenceDataVersion' => 1,
                    'sendUpdates' => 'none', // Prevent Google from sending any emails
                    'supportsAttachments' => false,
                    'sendNotifications' => false,
                    'alwaysIncludeEmail' => false
                ]
            );

            // Ensure we have the full event with all fields
            $event = $service->events->get($this->calendarId, $createdEvent->getId());

            return [
                'event_id' => $event->id,
                'meet_link' => $event->hangoutLink ?? null,
                'html_link' => $event->htmlLink ?? null,
                'conference_data' => $event->conferenceData ?? null
            ];

        } catch (\Google\Service\Exception $e) {
            $error = json_decode($e->getMessage(), true);
            if (isset($error['error'])) {
                throw new \Exception('Google Calendar API error: ' . $error['error'] . ' - ' . ($error['error_description'] ?? $e->getMessage()));
            }
            throw new \Exception('Google Calendar API error: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw $e;
        }
    }
}