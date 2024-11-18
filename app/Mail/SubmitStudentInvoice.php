<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmitStudentInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceDetail;
    public $paymentDate;

    /**
     * Create a new message instance.
     *
     * @param $invoiceDetail
     * @param $paymentDate
     */
    public function __construct($invoiceDetail, $paymentDate)
    {
        $this->invoiceDetail = $invoiceDetail;
        $this->paymentDate = $paymentDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Invoice Paid: " . $this->paymentDate . " - " . $this->invoiceDetail->reference)
            ->from('tutor@sifu.qurangeek.com', 'SifuTutor')
            ->view('emails.submit_student_invoice');
    }
}
