<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\SendUpdateScheduleClassReminderEmail;

class SendUpdateClassScheduleReminderEmail extends Command
{
    protected $signature = 'reminder:send-update-class-schedule';
    protected $description = 'Send reminder emails to tutors to update their class schedules';

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

        // Fetch class schedules for tomorrow
        $classSchedules = DB::table("class_schedules")->join("tutors", "class_schedules.tutorID", "=", "tutors.id")
            ->select("class_schedules.*", "tutors.email as tutor_email")
            ->where('class_schedules.remaining_classes', ">", 1)->get();


        // dd($classSchedules);

        foreach ($classSchedules as $classSchedule) {

            $email = $classSchedule->tutor_email;

            $emailContent = "Dear Tutor-Partner,\n\n";
            $emailContent .= "Just a reminder to update your attendance records before the 1st of the next month.\n\n";
            $emailContent .= "If they're not submitted by the deadline, there will be a 10% penalty, and your payment will be held until the following month (on hold).\n\n";
            $emailContent .= "Thank you!";

            // Send the email using Laravel Mailable
            Mail::to($email)->send(new SendUpdateScheduleClassReminderEmail($emailContent));

        }

        $this->info('Class reminders have been sent successfully!');
    }
}
