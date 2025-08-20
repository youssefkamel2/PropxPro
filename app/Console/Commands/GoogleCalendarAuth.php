<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Illuminate\Support\Facades\Storage;

class GoogleCalendarAuth extends Command
{
    protected $signature = 'google-calendar:auth';
    protected $description = 'Generate Google Calendar OAuth authorization URL';

    public function handle()
    {
        try {
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
            $client->addScope(\Google\Service\Calendar::CALENDAR_EVENTS);
            $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob'); // For manual authorization
            $client->setAccessType('offline');
            $client->setPrompt('consent');

            $authUrl = $client->createAuthUrl();
            
            $this->info('Visit the following URL to authorize the application:');
            $this->line($authUrl);
            $this->info('');
            $authCode = $this->ask('Enter the authorization code');

            if ($authCode) {
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                
                if (isset($accessToken['error'])) {
                    $this->error('Authorization failed: ' . $accessToken['error']);
                    return 1;
                }

                Storage::put('google-calendar/oauth-token.json', json_encode($accessToken));
                $this->info('Authorization successful! Token saved.');
                return 0;
            }

            return 1;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
