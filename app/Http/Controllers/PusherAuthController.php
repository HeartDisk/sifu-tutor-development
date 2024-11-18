<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Response;

class PusherAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $channelName = $request->input('channel_name');
        $user = Auth::user(); // Or any other logic to get the user

        // Ensure the user is authenticated and authorized to access the channel
        if ($user && Broadcast::channel($channelName, function ($user) {
            // Return true if the user is authorized to access the channel
            return true;
        })) {
            return Response::json([
                'auth' => \Pusher\Pusher::auth($channelName, $request->input('socket_id'))
            ]);
        }

        return Response::json(['error' => 'Unauthorized'], 403);
    }
}
