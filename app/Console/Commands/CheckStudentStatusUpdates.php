<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendPushNotificationJob;

class CheckStudentStatusUpdates extends Command
{
    protected $signature = 'check:student-status-updates';
    protected $description = 'Check if any student\'s schedule status has not been updated and send reminders';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get all students
        $students = DB::table('students')->where('is_deleted', 0)->get();

        foreach ($students as $student) {
            // Check if the student has any schedule
            $hasSchedule = DB::table('class_schedules')
                ->where('studentID', $student->id)
                ->exists();

            if ($hasSchedule) {
                // Check if the schedule has any status change
                $scheduleStatusUpdated = DB::table('class_schedules')
                    ->where('studentID', $student->id)
                    ->whereNotNull('status') // Check if status is not null (status changed)
                    ->exists();

                // If no status change, send notification
                if (!$scheduleStatusUpdated) {
                    $this->sendReminderNotification($student);
                }
            } else {
                // If no schedule exists, notify to create a schedule for the student
                $this->sendNoScheduleNotification($student);
            }
        }

        $this->info('Checked all student status updates.');
    }

    /**
     * Send a reminder notification for students with no status updates
     */
    private function sendReminderNotification($student)
    {
        $title = 'Student Status Update Reminder';
        $message = "Please update {$student->full_name}'s schedule status. No status updates found.";

        // Send push notification
        $deviceTokens = DB::table('tutor_device_tokens')->where('tutor_id', $student->customer_id)->pluck('device_token');
        foreach ($deviceTokens as $deviceToken) {
            $notificationData = [
                'Sender' => 'StudentStatus',
                'message' => $message
            ];

            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationData);
        }

        // Log the notification in the database
        DB::table('notifications')->insert([
            'page' => 'StudentStatus',
            'token' => $student->uid,
            'title' => $title,
            'message' => $message,
            'type' => 'tutor',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Send a notification to create a schedule if none exists for the student
     */
    private function sendNoScheduleNotification($student)
    {
        $title = 'No Schedule Found';
        $message = "Please create a schedule for {$student->full_name}. No schedule found.";

        // Send push notification
        $deviceTokens = DB::table('tutor_device_tokens')->where('tutor_id', $student->customer_id)->pluck('device_token');
        foreach ($deviceTokens as $deviceToken) {
            $notificationData = [
                'Sender' => 'StudentStatus',
                'message' => $message
            ];

            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationData);
        }

        // Log the notification in the database
        DB::table('notifications')->insert([
            'page' => 'StudentStatus',
            'token' => $student->uid,
            'title' => $title,
            'message' => $message,
            'type' => 'tutor',
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
