<?php

namespace App\Libraries;

class SmsNiagaApi {

    private $loginUrl;
    private $email;
    private $password;
    private $apiAccessToken;

    public function __construct() {
        // Load necessary libraries or models here
        $this->loginUrl = 'manage.smsniaga.com';
        $this->email = 'hello@sifututor.my';
        $this->password = '8bH$tX!wCh';
        $this->apiAccessToken = 'BfRxuo9ke5sI2Ckji2ocQOEUkAHCVLtUF9cluOtKM2BWj2Vebm5BTkgnIg2t';
    }

    public function getApiAccessToken() {
        // You can implement logic to refresh the token if needed
        return $this->apiAccessToken;
    }

    public function sendSms($phone_number, $message) 
    {
        // Get the API access token
        $apiAccessToken = 'BfRxuo9ke5sI2Ckji2ocQOEUkAHCVLtUF9cluOtKM2BWj2Vebm5BTkgnIg2t'; // Replace with your actual API access token
    
        // API Endpoint URL
        $api_url = 'https://manage.smsniaga.com/api/send';
    
        // Prepare data for the POST request
        $data = array(
            'body' => $message,
            'phones' => [$phone_number],
            'sender_id' => 'SIFU EDU & LEARNING',
            'preview' => 0,
        );
    
        // Initialize cURL session
        $ch = curl_init($api_url);
    
        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: Bearer ' . $apiAccessToken,
            'Content-Type: application/json',
        ));
    
        // Execute cURL session and get the response
        $response = curl_exec($ch);
    
        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        }
    
        // Close cURL session
        curl_close($ch);
    
        // Process the response
        if ($response === false) {
            echo 'Error sending SMS.';
        } else {
            // Check the response and handle accordingly
            $result = json_decode($response, true);

            // Check the response and handle accordingly
            if (isset($result['message']) && $result['message'] == 'Success' && isset($result['status_code']) && $result['status_code'] == 200) {
                //echo 'SMS sent successfully.';
            } else {
                //echo 'Error: Unexpected response format or API error.';
            }

        }
    }
}
