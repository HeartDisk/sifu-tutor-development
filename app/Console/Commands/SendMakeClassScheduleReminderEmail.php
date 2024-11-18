<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\ClassScheduleReminder;

class SendMakeClassScheduleReminderEmail extends Command
{
    protected $signature = 'reminder:send-make-class-schedule';
    protected $description = 'Send reminder emails to tutors who haven\'t scheduled any classes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $classSchedules = DB::table("class_schedules")->join("tutors", "class_schedules.tutorID", "=", "tutors.id")
            ->select("class_schedules.*", "tutors.email as tutor_email")
            ->where('class_schedules.remaining_classes', ">", 1)->get();

        foreach ($classSchedules as $classSchedule) {


            $email = $classSchedule->tutor_email;

            $emailContent = "Dear Tutor-Partner,\n\n";
            $emailContent .= "Just a friendly reminder: please schedule all your students' classes for this month within the first week. This ensures your payments go smoothly and a seamless experience for your students.\n\n";
            $emailContent .= "Thank you!";

            // Send the email using Laravel Mailable
            Mail::to($email)->send(new ClassScheduleReminder($emailContent));
        }

        $this->info('Class reminders have been sent successfully!');
    }


}
