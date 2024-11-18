<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Libraries\WhatsappApi;
use App\Jobs\SendPushNotificationJob;

class SendFifteenMinuteClassReminders extends Command
{
    protected $signature = 'send:fifteen-min-reminders';
    protected $description = 'Send reminders to parents about their children\'s classes scheduled to start in 15 minutes';

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
        // Get the current time plus 15 minutes
        $fifteenMinutesFromNow = Carbon::now()->addMinutes(15)->format('H:i');

        // Fetch class schedules that start in 15 minutes
        $classSchedules = DB::table("class_schedules")
            ->whereTime('startTime', '=', $fifteenMinutesFromNow)
            ->whereDate('date', Carbon::now()->toDateString())
            ->get();
            
        foreach ($classSchedules as $classSchedule) {
            // Get the student's name
            $studentId = $classSchedule->studentID;
            $student = DB::table("students")->where("id", $studentId)->first();
            $studentName = $student->full_name;
            $subject = DB::table("products")->where("id", $classSchedule->subjectID)->first();
            $tutor = DB::table("tutors")->where("id", $classSchedule->tutorID)->first();

            $title = 'Class Reminder (15 Minutes Prior)';
            // Construct the message
            $message = "Class with {$student->full_name} starts in 15 minutes. Be prepared!";

            // Notification data
            $notificationdata = [
                'Sender' => 'Schedule'
            ];

            $tutorDevices = DB::table('tutor_device_tokens')->where('tutor_id', '=', $tutor->id)->distinct()->get(['device_token', 'tutor_id']);
            foreach ($tutorDevices as $rowDeviceToken) {
                $deviceToken = $rowDeviceToken->device_token;

                // Dispatch push notification job
                SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
            }

            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $tutor->token,
                'title' => $title,
                'message' => $message,
                'type' => 'tutor',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send WhatsApp reminder if the number exists
            if ($tutor->whatsapp != null) {
                $whatsapp = $tutor->whatsapp;
                $this->sendReminderWhatsapp($whatsapp, $message);
            }
        }

        $this->info('15-minute class reminders have been sent successfully!');
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
