<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class StaffPaymentSlip extends Mailable
{
    use Queueable, SerializesModels;

    public $emailBody;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @param string $emailBody
     * @param string $pdfPath
     */
    public function __construct($emailBody, $pdfPath)
    {
        $this->emailBody = $emailBody;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Staff Payment Slip Invoice')
            ->from('tutor@sifu.qurangeek.com', 'SifuTutor')
            ->view('emails.staff_payment_slip')
            ->attach($this->pdfPath, [
                'as' => 'Invoice.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
