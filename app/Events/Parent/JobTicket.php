<?php

namespace App\Events\Parent;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobTicket implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $token;

    public function __construct($message, $token)
    {
        $this->message = $message;
        $this->token = $token; // Correct assignment here
    }

    public function broadcastOn()
    {
        return new Channel('device-' . $this->token); // Use the corrected token property
    }

    public function broadcastAs()
    {
        return 'JobTicket';
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
