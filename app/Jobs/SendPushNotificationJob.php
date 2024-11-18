<?php

namespace App\Jobs;

use App\Libraries\PushNotificationLibrary;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deviceToken;
    protected $title;
    protected $message;
    protected $data;

    public function __construct($deviceToken, $title, $message, $data = [])
    {
        // Ensure $data is always an array
        $this->deviceToken = $deviceToken;
        $this->title = $title;
        $this->message = $message;
        $this->data = is_array($data) ? $data : []; // If $data is not an array, default to an empty array

        // Logging for debugging
        Log::info('SendPushNotificationJob Construct:', [
            'deviceToken' => $deviceToken,
            'title' => $title,
            'message' => $message,
            'data' => $this->data,
        ]);
    }

    public function handle()
    {
        Log::info('SendPushNotificationJob handle method called.');
        Log::info('Data being sent to PushNotificationLibrary:', [
            'deviceToken' => $this->deviceToken,
            'title' => $this->title,
            'message' => $this->message,
            'data_type' => gettype($this->data),
            'data' => $this->data,
        ]);
    
        // Ensure data is an array
        $this->data = is_array($this->data) ? $this->data : [];
    
        $push_notification_api = new PushNotificationLibrary();
        $response = $push_notification_api->sendPushNotification($this->deviceToken, $this->title, $this->message, $this->data);
    
        Log::info('Push notification sent with response: ' . $response);
    }
}
