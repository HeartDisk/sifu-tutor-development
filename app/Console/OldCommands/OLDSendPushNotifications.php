<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Jobs\SendPushNotificationJob;
use DB;

class SendPushNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send push notifications to users';

    protected $pushNotificationService;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
       
        $notifications = DB::table("self_push_notifications")->where('sent', false)->get(); // Assuming you have a 'sent' column to track status
        
        foreach ($notifications as $notification) {
            
            
              $notificationdata = array(
                    'sender' => array(
                        array(
                            'screen' => 'jobTicket'
                        )
                    )
                );
        
        $device_tokens = DB::table("tutor_device_tokens")->get();
        
        foreach($device_tokens as $token)
        
        {
           SendPushNotificationJob::dispatch($token, $notification->subject, $notification->message, $notificationdata);  
        }
        
       
        // Mark the notification as sent
            DB::table('self_push_notifications')->where('id', $notification->id)->update(['sent' => 1]);
        
            
        }

        $this->info('Notifications dispatched successfully.');
    }
}
