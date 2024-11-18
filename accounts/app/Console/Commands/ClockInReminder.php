<?php

namespace App\Console\Commands;

use App\Libraries\PushNotificationLibrary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ClockInReminder extends Command
{
    
   protected $signature = 'send:class-passed-reminders';
    protected $description = 'Send reminders for classes that have already started';
    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        // dd("asd");
        
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');

        // Get the classes where the start time is less than or equal to the current time and status is not 'attended'
        $classes = DB::table('class_schedules')
            ->where('startTime', '<=', $currentTime)
            ->where('status', '!=', 'attended')
            ->get();
            
            // dd($classes);

        foreach ($classes as $class) {
            
            
            $tutorDeviceTokens = DB::table('tutor_device_tokens')
                ->where('tutor_id', $class->tutorID)
                ->pluck('device_token');
                // dd($tutorDeviceTokens);
                
                foreach ($tutorDeviceTokens as $token) {
                    $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
                  
                    foreach ($tutorDevices as $rowDeviceToken) {
                        $push_notification_api = new PushNotificationLibrary();
                        $title = 'Clock-In Required';
                        $message = 'Please Clock in Now!';
                        $deviceToken = $rowDeviceToken->device_token;
                        $push_notification_api->sendPushNotification($deviceToken, $title, $message);
                    }
                }
        }
    }
}
