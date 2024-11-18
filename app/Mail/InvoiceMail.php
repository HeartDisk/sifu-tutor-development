<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceDetail;
    public $base64Content;
    public $emailData;

    /**
     * Create a new message instance.
     *
     * @param $invoiceDetail
     * @param $base64Content
     * @param $emailData
     */
    public function __construct($invoiceDetail, $base64Content, $emailData)
    {
        $this->invoiceDetail = $invoiceDetail;
        $this->base64Content = $base64Content;
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invoice')
            ->from('tutor@sifu.qurangeek.com')
            ->view('emails.invoice')
            ->with($this->emailData)
            ->attachData(base64_decode($this->base64Content), "Invoice-{$this->invoiceDetail->id}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
