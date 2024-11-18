<?php

namespace App\Events\Tutor;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class News  implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('News');
    }


    public function broadcastAs()
    {
        return 'News';
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
