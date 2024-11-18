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
        
        $now = Carbon::now();
        $nextFiveMinutes = $now->copy()->addMinutes(5);
        
        $currentTime = $now->format('H:i:s');
        $nextTime = $nextFiveMinutes->format('H:i:s');
        
        $classes = DB::table('class_schedules')
            ->where('startTime', '>=', $currentTime)
            ->where('startTime', '<=', $nextTime)
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
                    $deviceToken = $rowDeviceToken->device_token;
                    $title = 'Upcoming Class';
                    $message = 'Your class will start in 5 minutes. Do not forget to clock-in';

                        $notificationdata = [
                            'Sender' => 'Home'
                        ];
                    
                        // Dispatch push notification job
                        SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
                    
                        // Store notification in the database
                        DB::table('notifications')->insert([
                            'page' => 'Home',
                            'token' => $deviceToken,
                            'title' => $title,
                            'message' => $message,
                            'type' => 'tutor',
                            'status' => 'new',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    
                }
            }
        }

        $this->info('Class reminders sent successfully!');
    }
}
