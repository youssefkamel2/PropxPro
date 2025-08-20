<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client;
use Illuminate\Support\Facades\Storage;

class RefreshGoogleCalendarToken extends Command
{
    protected $signature = 'google-calendar:refresh-token';
    protected $description = 'Refresh Google Calendar OAuth token';

    public function handle()
    {
        try {
            $client = new Client();
            $client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
            
            if (!Storage::exists('google-calendar/oauth-token.json')) {
                $this->error('OAuth token file not found. Please run initial OAuth setup first.');
                return 1;
            }

            $token = json_decode(Storage::get('google-calendar/oauth-token.json'), true);
            $client->setAccessToken($token);

            if ($client->isAccessTokenExpired()) {
                $this->info('Access token is expired. Attempting to refresh...');
                
                if ($client->getRefreshToken()) {
                    $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    
                    if (isset($newToken['error'])) {
                        $this->error('Token refresh failed: ' . $newToken['error'] . ' - ' . ($newToken['error_description'] ?? ''));
                        $this->error('You may need to re-authorize the application.');
                        return 1;
                    }
                    
                    Storage::put('google-calendar/oauth-token.json', json_encode($newToken));
                    $this->info('Token refreshed successfully!');
                } else {
                    $this->error('No refresh token available. Re-authorization required.');
                    return 1;
                }
            } else {
                $this->info('Access token is still valid.');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
