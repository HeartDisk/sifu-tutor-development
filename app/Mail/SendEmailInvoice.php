<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendEmailInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceDetail;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @param  $invoiceDetail
     * @param  $pdfPath
     * @return void
     */
    public function __construct($invoiceDetail, $pdfPath)
    {
        $this->invoiceDetail = $invoiceDetail;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.student_payment_slip_invoice')
            ->subject('Student Invoice')
            ->attach($this->pdfPath, [
                'as' => 'invoice-' . $this->invoiceDetail->id . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
