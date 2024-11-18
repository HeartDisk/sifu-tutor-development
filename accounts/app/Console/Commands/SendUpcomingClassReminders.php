<?php

namespace App\Console\Commands;

use App\Libraries\PushNotificationLibrary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SendUpcomingClassReminders extends Command
{
    protected $signature = 'send:upcoming-class-reminders';
    protected $description = 'Send class reminders to tutors 5 minutes before the class starts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        
        
        $now = \Carbon\Carbon::now();

        $nextFiveMinutes = $now->copy()->addMinutes(5);
        
        $currentTime = $now->format('H:i:s');
        
        // dd($currentTime."===========".$nextFiveMinutes);
        
        $classes = DB::table('class_schedules')
            ->where('startTime', '>=', $currentTime)
            ->where('startTime', '<=', $nextFiveMinutes->format('H:i:s'))
            ->where('status', '!=', 'attended')
            ->get();

        foreach ($classes as $class) {
            
            
            $classStartTime = \Carbon\Carbon::parse($class->startTime);
            $notificationTime = $classStartTime->subMinutes(5);
            
                 $tutorDeviceTokens = DB::table('tutor_device_tokens')
                ->where('tutor_id', $class->tutorID)
                ->pluck('device_token');
                
                
                foreach ($tutorDeviceTokens as $token) {
                    $tutorDevices = DB::table('tutor_device_tokens')->distinct()->get(['device_token', 'tutor_id']);
                  
                    foreach ($tutorDevices as $rowDeviceToken) {
                        $push_notification_api = new PushNotificationLibrary();
                        $title = 'Upcoming Class';
                        $message = 'Your class will start in 5 minutes. Do not forget to clock-in';
                        $deviceToken = $rowDeviceToken->device_token;
                        $push_notification_api->sendPushNotification($deviceToken, $title, $message);
                    }
                }
        }

        $this->info('Class reminders sent successfully!');
    }
}
