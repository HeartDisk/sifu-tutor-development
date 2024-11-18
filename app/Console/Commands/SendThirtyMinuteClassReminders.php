<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Libraries\WhatsappApi;
use App\Jobs\SendPushNotificationJob;

class SendThirtyMinuteClassReminders extends Command
{
    protected $signature = 'send:thirty-min-reminders';
    protected $description = 'Send reminders to parents about their children\'s classes scheduled to start in 30 minutes';

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
        // Get the current time plus 30 minutes
        $thirtyMinutesFromNow = Carbon::now()->addMinutes(30)->format('H:i');

        // Fetch class schedules that start in 30 minutes
        $classSchedules = DB::table("class_schedules")
            ->whereTime('startTime', '=', $thirtyMinutesFromNow)
            ->whereDate('date', Carbon::now()->toDateString())
            ->get();
            
        foreach ($classSchedules as $classSchedule) {
            // Get the student's name
            $studentId = $classSchedule->studentID;
            $student = DB::table("students")->where("id", $studentId)->first();
            $studentName = $student->full_name;
            $subject = DB::table("products")->where("id", $classSchedule->subjectID)->first();
            $tutor = DB::table("tutors")->where("id", $classSchedule->tutorID)->first();

            // Get the customer's email and device token
            $customerId = $student->customer_id;
            $customer = DB::table("customers")->where("id", $customerId)->first();
            
            
            $title = '30-Minute Class Reminder';
            // Construct the message
            $message = "Class with {$tutor->full_name} starts in 30 minutes.";

            // Notification data
            $notificationdata = [
                'Sender' => 'Schedule'
            ];
            
            $parentDevices = DB::table('parent_device_tokens')->where('parent_id', '=', $customer->id)->distinct()->get(['device_token', 'parent_id']);
            foreach ($parentDevices as $rowDeviceToken) {
                $deviceToken = $rowDeviceToken->device_token;
            
                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            }

            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $customer->token,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send WhatsApp reminder if the number exists
            if ($customer->whatsapp != null) {
                $whatsapp = $customer->whatsapp;
                $this->sendReminderWhatsapp($whatsapp, $message);
            }
        }

        $this->info('30-minute class reminders have been sent successfully!');
    }

    /**
     * Send WhatsApp reminder.
     */
    private function sendReminderWhatsapp($whatsapp, $message)
    {
        $whatsapp_api = new WhatsappApi();
        $whatsapp_api->send_message($whatsapp, $message);
    }
}
