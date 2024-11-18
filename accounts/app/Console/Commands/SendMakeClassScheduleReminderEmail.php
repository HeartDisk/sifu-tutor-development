<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


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
        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow()->toDateString();


        // Fetch class schedules for tomorrow
        $classSchedules = DB::table("class_schedules")->join("tutors","class_schedules.tutorID","=","tutors.id")
        ->select("class_schedules.*","tutors.email as tutor_email")
        ->where('class_schedules.remaining_classes',">", 1)->get();
       
        
        // dd($classSchedules);

        foreach ($classSchedules as $classSchedule) {
           
            
        $email=$classSchedule->tutor_email;
            
          $emailContent = "Dear Tutor-Partner,\n\n";
            $emailContent .= "Just a friendly reminder: please schedule all your students' classes for this month within the first week. This ensures your payments go smoothly and a seamless experience for your students.\n\n";
            $emailContent .= "Thank you!";

            
        $headers = "MIME-Version: 1.0" . "\r\n";
         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From: <info@sifututor.com>' . "\r\n";
        $subject = 'Create Class Schedule Reminder';
        $headers = 'From: info@sifututor.com' . "\r\n" .
                  'Reply-To: info@sifututor.com' . "\r\n" .
                  'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $emailContent, $headers);
            
        }

        $this->info('Class reminders have been sent successfully!');
    }
    
    
}
