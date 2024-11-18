<?php

namespace App\Libraries;

use App\Helpers\GoogleHelper;
use Illuminate\Support\Facades\Log;

class PushNotificationLibrary
{
    // Function to send a push notification using FCM HTTP v1 API
    public function sendPushNotification($deviceToken, $title, $message, $data = [])
    {
        Log::info('sendPushNotification Method Called:', [
            'deviceToken' => $deviceToken,
            'title' => $title,
            'message' => $message,
            'data_type' => gettype($data),
            'data_content' => $data
        ]);
    
        // Ensure $data is an array
        if (!is_array($data)) {
            $data = [];
            Log::warning('Data was not an array. Defaulting to an empty array.');
        }
    
        // FCM HTTP v1 endpoint for your project
        $projectId = 'sifututorone'; // Replace with your actual project ID
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
        
        // Fetch the access token
        $accessToken = $this->getAccessToken();
        Log::info('Access Token Retrieved:', ['access_token' => $accessToken]);
        
        // Data to be sent in the request
        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $message
                ],
                'data' => $data
            ]
        ];
        
        Log::info('Payload Prepared:', ['payload' => $payload]);
    
        // Convert data to JSON format
        $jsonData = json_encode($payload);
        
        // Headers for the request
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];
        
        // Initialize cURL session
        $ch = curl_init($url);
        
        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Execute cURL request
        $response = curl_exec($ch);
        
        // Check for errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            Log::error('cURL Error:', ['error' => $error]);
            return "cURL Error: $error";
        }
        
        // Close cURL session
        curl_close($ch);
        
        Log::info('Push Notification Sent. Response:', ['response' => $response]);
        
        // Return response
        return $response;
    }

    
    // Function to get the access token
    private function getAccessToken()
    {
        // Call your helper to get the Google access token
        return GoogleHelper::getGoogleAccessToken();
    }
}
