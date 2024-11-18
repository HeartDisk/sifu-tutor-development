<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Notifications\ClassReminderNotification;
use Illuminate\Support\Facades\Mail;
use App\Libraries\WhatsappApi;
use App\Libraries\SmsNiagaApi;

class SendClassReminders extends Command
{
    protected $signature = 'send:class-reminders';
    protected $description = 'Send reminders to parents about their children\'s classes scheduled for tomorrow';

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

        
        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow()->toDateString();


        // Fetch class schedules for tomorrow
        $classSchedules = DB::table("class_schedules")->whereDate('date', $tomorrow)->get();
        
        // dd($classSchedules);

        foreach ($classSchedules as $classSchedule) {
            // Get the student's name
            $studentId = $classSchedule->studentID;
            $student = DB::table("students")->where("id",$studentId)->first();
            $studentName = $student->full_name;
            $subject=DB::table("products")->where("id",$classSchedule->subjectID)->first();

            // Construct the message
           $message = "Class Reminder: Tomorrow. Dear Parent/Student, This is a quick reminder that {$studentName} has a {$subject->name} class tomorrow at {$classSchedule->startTime} - Sifututor";

            // Get the customer's email
            $customerId = $student->customer_id;
            $customer = DB::table("customers")->where("id",$customerId)->first();
            
            $deviceToken = $customer->device_token;
            $title = 'Class Reminders';

            $notificationdata = [
                'Sender' => 'Schedule'
            ];
        
            // Dispatch push notification job
            SendPushNotificationJob::dispatch($deviceToken, $title, $message, $notificationdata);
        
            // Store notification in the database
            DB::table('notifications')->insert([
                'page' => 'Schedule',
                'token' => $deviceToken,
                'title' => $title,
                'message' => $message,
                'type' => 'parent',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            if($customer->whatsapp!=null)
            {
                $whatsapp = $customer->whatsapp;
                $this->sendReminderWhatsapp($whatsapp, $message);
            }
            
            if($customer->phone!=null)
            {
                $phone = $customer->phone;
                $smsmessage = "Reminder – {$studentName} has a {$subject->name} class tomorrow at {$classSchedule->startTime}.";
                $this->sendMessage($phone, $smsmessage);
            }
            
        }

        $this->info('Class reminders have been sent successfully!');
    }
    
    private function sendMessage($phone, $smsmessage)
    {
        $sms_api = new SmsNiagaApi();
        $sms_api->sendSms($phone, $smsmessage);
    }
    
    private function sendReminderWhatsapp($whatsapp, $message)
    {
        
            $whatsapp_api = new WhatsappApi();
            $whatsapp_api->send_message($whatsapp, $message);
        
        // // Always set content-type when sending HTML email
        //  $headers = "MIME-Version: 1.0" . "\r\n";
        //  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // // More headers
        // $headers .= 'From: <info@sifututor.com>' . "\r\n";
        // $subject = 'Sifututor Class Reminder';
        // $headers = 'From: info@sifututor.com' . "\r\n" .
        //           'Reply-To: info@sifututor.com' . "\r\n" .
        //           'X-Mailer: PHP/' . phpversion();

        // mail($email, $subject, $messageContent, $headers);
    }
}
