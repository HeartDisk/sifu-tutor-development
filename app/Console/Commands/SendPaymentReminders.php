<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Libraries\SmsNiagaApi;

class SendPaymentReminders extends Command
{
    protected $signature = 'send:payment-reminders';
    protected $description = 'Send payment reminders to customers one day before their due date';

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
        // Get date for one day before today
        $dueTomorrow = Carbon::tomorrow()->toDateString();

        // Fetch invoices with dueDate equal to tomorrow
        $invoicesDueTomorrow = DB::table("invoices")->whereDate('dueDate', $dueTomorrow)->get();

        foreach ($invoicesDueTomorrow as $invoice) {
            // Get the customer and month of the invoice
            $studentID = $invoice->studentID;
            $student = DB::table("students")->where("id", $studentID)->first();

            $customerId = $student->customer_id;
            $customer = DB::table("customers")->where("id", $customerId)->first();

            $month = date('F', strtotime($invoice->paymentDate));

            // Construct the message
            $smsmessage = "Reminder – Your payment for {$month} is due tomorrow. Please pay via the SifuTutor app. Thank you!";

            // Send SMS if phone number exists
            if ($customer->phone != null) {
                $phone = $customer->phone;
                $this->sendMessage($phone, $smsmessage);
            }
        }

        $this->info('Payment reminders have been sent successfully!');
    }

    private function sendMessage($phone, $smsmessage)
    {
        $sms_api = new SmsNiagaApi();
        $sms_api->sendSms($phone, $smsmessage);
    }
}
