<?php

namespace App\Jobs;

use App\Libraries\WhatsappApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendWhatsAppMessageJob implements ShouldQueue
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
        $whatsapp_api = new WhatsappApi();
        $whatsapp_api->send_message($this->phone_number, $this->message);
    }
}
