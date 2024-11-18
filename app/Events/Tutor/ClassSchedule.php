<?php

namespace App\Events\Tutor;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClassSchedule implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $token;

   public function __construct($message,$token)
    {
        $this->message = $message;
        $this->device_token =$token;
    }

      public function broadcastOn()
    {
        return new Channel('device-'.$this->device_token);
    }
    
    
    public function broadcastAs()
    {
        return 'ClassSchedule';
    }
    
    
    public function broadcastWith()
    {
        // Format the data to match the desired response format
        return [
            'ResponseCode' => $this->message['ResponseCode'],
            'message' => $this->message['message'],
        ];
    }

}
