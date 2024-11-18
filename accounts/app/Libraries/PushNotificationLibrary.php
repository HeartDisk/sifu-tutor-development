<?php

namespace App\Libraries;

class PushNotificationLibrary
{

    public function sendPushNotification($deviceToken, $title, $message) 
    {
        // FCM endpoint
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        // Data to be sent in the request
        $data = [
            'to' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $message
            ]
        ];
        
        // Convert data to JSON format
        $jsonData = json_encode($data);
        
        // Headers for the request
        $headers = [
            'Authorization: Bearer AAAAxgC0G8E:APA91bGGid53CVR8sd27Y-yvKQbjOOyvcIt6jHNdsH2Bt8JEJMKnNU5SUXZ5SzTH4oGMnKmTJ7Nw-YWcbmMdqsTO1DhO0Fe2g-EeurVOIZF-N-r2e4cgNg-Pp8ckhcbyBUIkbUzU9TBS',
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
        if($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return "cURL Error: $error";
        }
        
        // Close cURL session
        curl_close($ch);
        
        // Return response
        return $response;
    }
}
