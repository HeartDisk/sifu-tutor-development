<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendPushNotificationJob;
use Carbon\Carbon;
use DB;

class SendPushNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send push notifications to users';

    public function __construct()
    {
        parent::__construct();
    }

    // public function handle()
    // {
    //     // Get current date and time
    //     $now = Carbon::now();

    //     // Fetch notifications that are not yet sent or are recurring
    //     $notifications = DB::table("self_push_notifications")
    //         ->where(function ($query) use ($now) {
    //             $query->where('sent', false)
    //                   ->orWhere(function ($query) use ($now) {
    //                       // Include recurring notifications
    //                       $query->where('type', '!=', 'one_time')
    //                             ->whereTime('time', $now->toTimeString());
    //                   });
    //         })
    //         ->get();
            

    //     foreach ($notifications as $notification) {
    //         // Determine if the notification should be sent
    //         if ($this->shouldSendNotification($notification, $now)) {
    //             // Fetch all device tokens to send the notification
    //             $device_tokens = DB::table("tutor_device_tokens")->get();

    //             foreach ($device_tokens as $token) {
    //                 // Dynamically set the screen based on the page field
    //                 $notificationdata = [
    //                     'sender' => [
    //                         [
    //                             'screen' => $notification->page // Use the page field dynamically
    //                         ]
    //                     ]
    //                 ];

    //                 // Dispatch the notification job
    //                 SendPushNotificationJob::dispatch($token, $notification->subject, $notification->message, $notificationdata);

    //                 // Add the notification to the `notifications` table
    //                 $this->storeNotification($notification, $token->device_token);
    //             }

    //             // Mark the notification as sent if it's a one-time notification
    //             if ($notification->type === 'one_time') {
    //                 DB::table('self_push_notifications')->where('id', $notification->id)->update(['sent' => 1]);
    //             }
    //         }
    //     }

    //     $this->info('Notifications dispatched and stored successfully.');
    // }
    
    public function handle()
    {
        // Get current date and time
        $now = Carbon::now();
    
        // Fetch notifications that are not yet sent or are recurring
        $notifications = DB::table("self_push_notifications")
            ->where(function ($query) use ($now) {
                $query->where('sent', false)
                      ->orWhere(function ($query) use ($now) {
                          // Include recurring notifications
                          $query->where('type', '!=', 'one_time')
                                ->whereTime('time', $now->toTimeString());
                      });
            })
            ->get();
    
        foreach ($notifications as $notification) {
            // Determine if the notification should be sent
            if ($this->shouldSendNotification($notification, $now)) {
                // Fetch all device tokens to send the notification
                $device_tokens = DB::table("tutor_device_tokens")->get();
                
                $sentCount = 0; // Initialize sent count
    
                foreach ($device_tokens as $token) {
                    // Fetch the corresponding tutor token based on a common field, such as tutor_id
                    $tutor_token = DB::table("tutors")
                        ->where('id', $token->tutor_id) // Assuming tutor_id exists in the device tokens table
                        ->value('token'); // Fetch the tutor's token
        
                    // If tutor token is not found, skip sending notification for this device token
                    if (!$tutor_token) {
                        continue;
                    }
                    
                    // Dynamically set the screen based on the page field
                    $notificationdata = [
                        'Sender' => $notification->page // Use the page field dynamically
                    ];
                    
                    // Dispatch the notification job
                    SendPushNotificationJob::dispatch($token->device_token, $notification->subject, $notification->message, $notificationdata);
    
                    // Add the notification to the `notifications` table using the tutor's token
                    $this->storeNotification($notification, $tutor_token);
    
                    $sentCount++; // Increment sent count
                }
    
                // Update the sent count in the notification table
                DB::table('self_push_notifications')->where('id', $notification->id)->update(['sent' => $sentCount]);
            }
        }
    
        $this->info('Notifications dispatched and stored successfully.');
    }


    /**
     * Determine if the notification should be sent based on its recurrence type.
     *
     * @param  object  $notification
     * @param  \Carbon\Carbon  $now
     * @return bool
     */
    // protected function shouldSendNotification($notification, $now)
    // {
    //     // Determine the recurrence type based on conditions
    //     $derivedType = $this->getDerivedType($notification);
    
    //     switch ($derivedType) {
    //         case 'one_time':
    //             // Send one-time notifications on the specified date and time (up to the minute)
    //             return $notification->date === $now->toDateString() 
    //                   && Carbon::parse($notification->time)->format('H:i') === $now->format('H:i');
    
    //         case 'daily_recurrence':
    //             // Send daily notifications every day at the specified time
    //             return $now->format('H:i') === Carbon::parse($notification->time)->format('H:i');
    
    //         case 'weekly_recurrence':
    //             // Send weekly notifications on the specified days and time (up to the minute)
    //             $days = json_decode($notification->days, true);
    //             return in_array($now->format('l'), $days) 
    //                   && $now->format('H:i') === Carbon::parse($notification->time)->format('H:i');
    
    //         case 'monthly_recurrence':
    //             // Send monthly notifications on the specified date each month and time (up to the minute)
    //             return $notification->monthly_date && $now->day == $notification->monthly_date
    //                   && $now->format('H:i') === Carbon::parse($notification->time)->format('H:i');
    
    //         default:
    //             return false;
    //     }
    // }
    
    protected function shouldSendNotification($notification, $now)
    {
        // Determine the recurrence type based on conditions
        $derivedType = $this->getDerivedType($notification);
        
        Log::info('Checking notification', [
            'notification_id' => $notification->id,
            'derived_type' => $derivedType,
            'current_date' => $now->toDateString(),
            'current_time' => $now->format('H:i'),
            'notification_date' => $notification->date,
            'notification_time' => Carbon::parse($notification->time)->format('H:i'),
        ]);
    
        switch ($derivedType) {
            case 'one_time':
                // Send one-time notifications on the specified date and time (up to the minute)
                $shouldSend = $notification->date === $now->toDateString() 
                           && Carbon::parse($notification->time)->format('H:i') === $now->format('H:i');
                Log::info('One-time notification check', [
                    'should_send' => $shouldSend,
                    'notification_id' => $notification->id
                ]);
                return $shouldSend;
    
            case 'daily_recurrence':
                // Send daily notifications every day at the specified time
                $shouldSend = $now->format('H:i') === Carbon::parse($notification->time)->format('H:i');
                Log::info('Daily recurrence notification check', [
                    'should_send' => $shouldSend,
                    'notification_id' => $notification->id
                ]);
                return $shouldSend;
    
            case 'weekly_recurrence':
                // Convert days to lowercase and check if current day is in the list
                $days = array_map('strtolower', json_decode($notification->days, true));
                $currentDay = strtolower($now->format('l'));
            
                $shouldSend = in_array($currentDay, $days) 
                            && $now->format('H:i') === Carbon::parse($notification->time)->format('H:i');
            
                Log::info('Weekly recurrence notification check details', [
                    'notification_id' => $notification->id,
                    'should_send' => $shouldSend,
                    'current_day' => $currentDay,
                    'notification_days' => $days,
                    'is_day_in_array' => in_array($currentDay, $days),
                    'current_time' => $now->format('H:i'),
                    'notification_time' => Carbon::parse($notification->time)->format('H:i'),
                    'time_match' => $now->format('H:i') === Carbon::parse($notification->time)->format('H:i')
                ]);
            
                return $shouldSend;
            case 'monthly_recurrence':
                // Send monthly notifications on the specified date each month and time (up to the minute)
                $shouldSend = $notification->monthly_date && $now->day == $notification->monthly_date
                           && $now->format('H:i') === Carbon::parse($notification->time)->format('H:i');
                Log::info('Monthly recurrence notification check', [
                    'should_send' => $shouldSend,
                    'notification_id' => $notification->id,
                    'current_day' => $now->day,
                    'notification_day' => $notification->monthly_date
                ]);
                return $shouldSend;
    
            default:
                Log::warning('Unknown notification type', [
                    'notification_id' => $notification->id,
                    'derived_type' => $derivedType
                ]);
                return false;
        }
    }
    
    /**
     * Determine the derived notification type based on its attributes.
     *
     * @param  object  $notification
     * @return string
     */
    protected function getDerivedType($notification)
    {
        if ($notification->type === 'one_time') {
            return 'one_time';
        }
    
        if (!empty($notification->days)) {
            return 'weekly_recurrence';
        }
    
        if ($notification->monthly_date) {
            return 'monthly_recurrence';
        }
    
        return 'daily_recurrence'; // Default to daily recurrence if no specific recurrence is found
    }


    /**
     * Store the notification in the `notifications` table.
     *
     * @param  object  $notification
     * @param  string  $token
     * @return void
     */
    protected function storeNotification($notification, $token)
    {
        DB::table('notifications')->insert([
            'page' => $notification->page,
            'token' => $token,
            'title' => $notification->subject,
            'message' => $notification->message,
            'type' => 'tutor', // Adjust the type based on your requirement
            'status' => 'new',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
