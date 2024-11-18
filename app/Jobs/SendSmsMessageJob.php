<?php

namespace App\Jobs;

use App\Libraries\SmsNiagaApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSmsMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone_number;
    protected $message;

    public function __construct($phone_number, $message)
    {
        $this->phone_number = $phone_number;
        $this->message = $message;
    }

    public function handle()
    {
        $sms_api = new SmsNiagaApi();
        $sms_api->sendSms($this->phone_number, $this->message);
    }
}
