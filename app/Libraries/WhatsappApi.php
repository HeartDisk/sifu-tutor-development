<?php

namespace App\Libraries;

class WhatsappApi
{
    private $api_url;
    private $api_key;

    public function __construct()
    {
        $this->api_url = 'https://api.watext.com/hook/message';
        $this->api_key = 'e6f2cb62a2b54cfbb6a1b25fbfee6131';
    }

    public function send_message($phone_number, $message)
    {
        $dataAPI = [
            'apikey' => $this->api_key,
            'phone' => $phone_number,
            'message' => $message,
        ];

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataAPI));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            // Handle error: echo 'Error sending message.';
        } else {
            $result = json_decode($response, true);

            if (is_array($result)) {
                if (isset($result['message'])) {
                    // Handle error: echo 'Error: ' . $result['message'];
                } elseif (isset($result['status']) && $result['status'] == 'success') {
                    // Handle success: echo 'Message sent successfully.';
                } else {
                    // Handle error: echo 'Error: Unexpected response format.';
                }
            }
        }
    }
}
