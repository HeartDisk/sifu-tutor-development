<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel; // Make sure this is imported
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestEvent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $message;
    public $token;

    public function __construct($message)
    {
        $this->message = $message;
        // $this->device_token =$token;
        $this->device_token ="wgsKUIkXvpUuN7eI9EUvlw1NCTKGe1cOKPyrzaBMysklbdFGOrrxdfgWZH83";
    }

      public function broadcastOn()
    {
        
        //  return new PrivateChannel('device-' . $this->device_token);
        return new Channel('device-'.$this->device_token);
        //  return new Channel(device-".AXKBTYj2BHo1yh89NdkVPaXWhfAvWFN9SWKpVFRL8V1zmzBDgd05rDTCJhHdAXKBTYj2BHo1yh89NdkVPaXWhfAvWFN9SWKpVFRL8V1zmzBDgd05rDTCJhHd");
    }
    
    
    
     // Customize the broadcast event name
    public function broadcastAs()
    {
        return 'TestEvent';
    }
}
