<?php

namespace App\Helpers;

use Google\Client;

class GoogleHelper
{
    public static function getGoogleAccessToken()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/google-services.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        
        $client->useApplicationDefaultCredentials();
        
        // Fetch the access token
        $tokenArray = $client->fetchAccessTokenWithAssertion();
        
        // Return the access token
        return $tokenArray['access_token'];
    }
}
